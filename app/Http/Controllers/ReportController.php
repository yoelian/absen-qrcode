<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Setting;
use App\Models\Employee;
use App\Exports\GridAttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelWriterType;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $categories = \App\Models\Category::orderBy('name')->get();
        
        $positionsQuery = \App\Models\Position::orderBy('name');
        if ($request->filled('category_id')) {
            $positionsQuery->where('category_id', $request->category_id);
        }
        $positions = $positionsQuery->get();
        
        $tipeLaporan = $request->get('tipe_laporan', 'grid');
        $startDate = $request->get('start_date', Carbon::today()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::today()->toDateString());
        $categoryId = $request->get('category_id');
        $positionId = $request->get('position_id');
        $perPage = $request->get('per_page', 10);

        if ($tipeLaporan === 'detail') {
            $attendancesQuery = Attendance::with(['employee.category', 'employee.position'])
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date')
                ->orderBy(Employee::select('name')->whereColumn('employees.id', 'attendances.employee_id'));
                
            if ($categoryId) {
                $attendancesQuery->whereHas('employee', function($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                });
            }
            if ($positionId) {
                $attendancesQuery->whereHas('employee', function($q) use ($positionId) {
                    $q->where('position_id', $positionId);
                });
            }
            
            $search = $request->get('search');
            if ($search) {
                $attendancesQuery->whereHas('employee', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
                });
            }
            
            $attendances = $attendancesQuery->paginate($perPage)->withQueryString();
            
            return view('reports.index', [
                'categories' => $categories,
                'positions' => $positions,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'categoryId' => $categoryId,
                'positionId' => $positionId,
                'tipeLaporan' => 'detail',
                'attendances' => $attendances
            ]);
        }

        $data = $this->generateGridData($request);
        
        return view('reports.index', [
            'categories' => $categories,
            'positions' => $positions,
            'startDate' => $data['startDate'],
            'endDate' => $data['endDate'],
            'categoryId' => $data['categoryId'],
            'positionId' => $data['positionId'],
            'tipeLaporan' => 'grid',
            'grid' => $data['grid'],
            'daysDiff' => $data['daysDiff'],
            'dates' => $data['dates']
        ]);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:pdf,excel',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $categoryId = $request->category_id;
        $positionId = $request->position_id;
        
        $catName = 'Semua';
        if ($categoryId) {
            $catModel = \App\Models\Category::find($categoryId);
            if ($catModel) $catName = $catModel->name;
        }

        $posName = 'Semua';
        if ($positionId) {
            $posModel = \App\Models\Position::find($positionId);
            if ($posModel) $posName = $posModel->name;
        }

        $tipeLaporan = $request->get('tipe_laporan', 'grid');

        if ($tipeLaporan === 'detail') {
            $attendancesQuery = Attendance::with(['employee.category', 'employee.position'])
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date')
                ->orderBy(Employee::select('name')->whereColumn('employees.id', 'attendances.employee_id'));
                
            if ($categoryId) {
                $attendancesQuery->whereHas('employee', function($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                });
            }
            if ($positionId) {
                $attendancesQuery->whereHas('employee', function($q) use ($positionId) {
                    $q->where('position_id', $positionId);
                });
            }
            
            $search = $request->get('search');
            if ($search) {
                $attendancesQuery->whereHas('employee', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
                });
            }

            $attendances = $attendancesQuery->get();

            if ($request->format === 'excel') {
                $supportsXlsx = class_exists(\XMLWriter::class);
                $extension = $supportsXlsx ? 'xlsx' : 'csv';
                $writerType = $supportsXlsx ? ExcelWriterType::XLSX : ExcelWriterType::CSV;

                $filename = 'Detail_Absensi_' . str_replace(' ', '_', $catName) . '_' . $startDate . '_ke_' . $endDate . '.' . $extension;
                return Excel::download(new \App\Exports\DetailAttendanceExport($attendances, $startDate, $endDate, $posName), $filename, $writerType);
            }

            // Cetak PDF Detail
            $kopPemerintah = Setting::get('kop_pemerintah');
            $kopDinas = Setting::get('kop_dinas');
            $kopKontak = Setting::get('kop_kontak');
            $schoolName = Setting::get('school_name', 'SMAN 1 Bentian Besar');
            $schoolAddress = Setting::get('school_address', 'Jl. Pendidikan, Bentian Besar');
            $schoolLogo = Setting::get('school_logo');
            $headmasterName = Setting::get('headmaster_name');
            $headmasterNip = Setting::get('headmaster_nip');

            $pdf = Pdf::loadView('reports.pdf_detail', compact('attendances', 'startDate', 'endDate', 'catName', 'posName', 'schoolName', 'schoolAddress', 'schoolLogo', 'headmasterName', 'headmasterNip', 'kopPemerintah', 'kopDinas', 'kopKontak'))
                ->setPaper('a4', 'portrait');

            return $pdf->download('Laporan_Harian_Detail_' . $startDate . '_' . $endDate . '.pdf');
        }

        // Paksa grid data export mode (tanpa pagination)
        $data = $this->generateGridData($request, true);

        if ($request->format === 'excel') {
            $supportsXlsx = class_exists(\XMLWriter::class);
            $extension = $supportsXlsx ? 'xlsx' : 'csv';
            $writerType = $supportsXlsx ? ExcelWriterType::XLSX : ExcelWriterType::CSV;

            $filename = 'Grid_Absensi_' . str_replace(' ', '_', $catName) . '_' . $startDate . '_ke_' . $endDate . '.' . $extension;
            return Excel::download(new GridAttendanceExport($data['grid'], $startDate, $endDate, $data['dates'], $posName), $filename, $writerType);
        }

        // Cetak PDF Grid
        $kopPemerintah = Setting::get('kop_pemerintah');
        $kopDinas = Setting::get('kop_dinas');
        $kopKontak = Setting::get('kop_kontak');
        $schoolName = Setting::get('school_name', 'SMAN 1 Bentian Besar');
        $schoolAddress = Setting::get('school_address', 'Jl. Pendidikan, Bentian Besar');
        $schoolLogo = Setting::get('school_logo');
        $headmasterName = Setting::get('headmaster_name');
        $headmasterNip = Setting::get('headmaster_nip');

        $grid = $data['grid'];
        $dates = $data['dates'];
        $daysDiff = $data['daysDiff'];

        // Jika jumlah hari lebih dari 15, gunakan kertas Legal atau Folio (F4) landscape agar muat. Jika <=15, A4 cukup.
        $paperSize = $daysDiff > 15 ? 'legal' : 'a4';

        $pdf = Pdf::loadView('reports.pdf', compact('grid', 'dates', 'startDate', 'endDate', 'catName', 'posName', 'schoolName', 'schoolAddress', 'schoolLogo', 'headmasterName', 'headmasterNip', 'kopPemerintah', 'kopDinas', 'kopKontak'))
            ->setPaper($paperSize, 'landscape');

        return $pdf->download('Grid_Absensi_' . $startDate . '_' . $endDate . '.pdf');
    }

    public function generateGridData($request, $isExport = false)
    {
        $startDate = $request->get('start_date', Carbon::today()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::today()->toDateString());
        
        $categoryId = $request->get('category_id');
        if (!$categoryId) $categoryId = $request->get('category'); // Fallback param nama lama

        $positionId = $request->get('position_id');
        if (!$positionId) $positionId = $request->get('position'); // Fallback param nama lama

        $perPage = $request->get('per_page', 10);

        $employeesQuery = Employee::with(['category', 'position'])->where('is_active', true)->orderBy('category_id')->orderBy('name');
        if ($categoryId) {
            $employeesQuery->where('category_id', $categoryId);
        }
        if ($positionId) {
            $employeesQuery->where('position_id', $positionId);
        }
        
        $search = $request->get('search');
        if ($search) {
            $employeesQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }
        
        if ($isExport) {
            $employees = $employeesQuery->get();
        } else {
            $employees = $employeesQuery->paginate($perPage)->withQueryString();
        }

        $attendancesQuery = Attendance::whereBetween('date', [$startDate, $endDate]);
        
        if ($categoryId) {
            $attendancesQuery->whereHas('employee', function($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }
        $attendances = $attendancesQuery->get()->groupBy('employee_id');

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $daysDiff = $start->diffInDays($end) + 1;
        
        $dates = [];
        for ($i = 0; $i < $daysDiff; $i++) {
            $dates[] = $start->copy()->addDays($i)->format('Y-m-d');
        }
        
        $grid = [];
        foreach ($employees as $emp) {
            $empAttendances = $attendances->get($emp->id, collect())->keyBy('date');
            $rowData = [
                'employee' => $emp,
                'days' => [],
                'summary' => ['H' => 0, 'T' => 0, 'I' => 0, 'S' => 0, 'A' => 0, 'C' => 0, 'DL' => 0, 'TAP' => 0]
            ];
            
            foreach ($dates as $dateStr) {
                if ($empAttendances->has($dateStr)) {
                    $att = $empAttendances->get($dateStr);
                    if ($att->status === 'terlambat') {
                        $rowData['days'][$dateStr] = 'T';
                        $rowData['summary']['T']++;
                    } elseif ($att->status === 'hadir') {
                        $rowData['days'][$dateStr] = 'H';
                        $rowData['summary']['H']++;
                    } elseif ($att->status === 'tap') {
                        $rowData['days'][$dateStr] = 'TAP';
                        $rowData['summary']['TAP']++;
                    } else {
                        $statusKey = strtoupper(substr($att->status, 0, 1));
                        if ($att->status === 'dl') $statusKey = 'DL';
                        if ($att->status === 'alpha') $statusKey = 'A';
                        
                        $rowData['days'][$dateStr] = $statusKey;
                        if (isset($rowData['summary'][$statusKey])) {
                            $rowData['summary'][$statusKey]++;
                        }
                    }
                } else {
                    $rowData['days'][$dateStr] = '-';
                }
            }
            $grid[] = $rowData;
        }

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'categoryId' => $categoryId,
            'positionId' => $positionId,
            'grid' => $isExport ? $grid : ['items' => $grid, 'paginator' => $employees],
            'daysDiff' => $daysDiff,
            'dates' => $dates
        ];
    }
}
