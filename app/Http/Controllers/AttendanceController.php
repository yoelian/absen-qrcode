<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AttendanceService;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Setting;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelWriterType;
use App\Exports\MonthlyAttendanceExport;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Halaman scan absensi real-time.
     */
    public function scanPage()
    {
        $schoolName = Setting::get('school_name', 'SMAN 1 Bentian Besar');
        $schoolLogo = Setting::get('school_logo');
        
        // Ambil 20 riwayat absensi terbaru hari ini untuk ditampilkan di layar scan
        $recentAttendances = Attendance::with('employee')
            ->whereDate('created_at', Carbon::today())
            ->where(function($q) {
                $q->whereNotNull('check_in')->orWhereNotNull('check_out');
            })
            ->orderBy('updated_at', 'desc')
            ->take(20)
            ->get();

        return view('attendance.scan', compact('schoolName', 'schoolLogo', 'recentAttendances'));
    }

    /**
     * API Endpoint untuk memproses request scan dari hardware scanner maupun webcam.
     */
    public function scan(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $result = $this->attendanceService->processScan($request->code);

        if ($result['success']) {
            // Dapatkan path foto/detail tambahan untuk response view
            $employee = $result['employee'];
            
            // Format photo url
            $photoUrl = $employee->photo 
                ? route('avatar.serve', $employee->photo) 
                : asset('images/default-avatar.png');

            return response()->json([
                'success' => true,
                'action' => $result['action'],
                'name' => $employee->name,
                'category' => strtoupper($employee->category->name ?? ''),
                'position' => $employee->position->name ?? '-',
                'photo' => $photoUrl,
                'time' => $result['time'],
                'status' => $result['status'],
                'late_text' => $result['late_text'] ?? '',
                'message' => $result['message']
            ]);
        }

        $errorData = [
            'success' => false,
            'message' => $result['message']
        ];

        if (isset($result['employee'])) {
            $emp = $result['employee'];
            $errorData['name'] = $emp->name;
            $errorData['category'] = strtoupper($emp->category->name ?? '');
            $errorData['position'] = $emp->position->name ?? '-';
            $errorData['photo'] = $emp->photo ? route('avatar.serve', $emp->photo) : asset('images/default-avatar.png');
        }

        return response()->json($errorData, 400);
    }

    /**
     * Daftar absensi hari ini (rekap harian).
     */
    public function index(Request $request)
    {
        $date = $request->get('date', Carbon::today()->toDateString());
        $categoryId = $request->get('category');
        
        $baseQuery = Attendance::with(['employee.category', 'employee.position'])
            ->where('date', $date)
            ->when($categoryId, function($query, $category) {
                return $query->whereHas('employee', function($q) use ($category) {
                    $q->where('category_id', $category);
                });
            });

        $positionId = $request->get('position');
        $baseQuery->when($positionId, function($query, $position) {
            return $query->whereHas('employee', function($q) use ($position) {
                $q->where('position_id', $position);
            });
        });

        $search = $request->get('search');
        $baseQuery->when($search, function($query, $search) {
            return $query->whereHas('employee', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        });

        // Hitung statistik lengkap
        $stats = [
            'hadir' => (clone $baseQuery)->whereIn('status', ['hadir', 'tepat waktu'])->count(),
            'terlambat' => (clone $baseQuery)->where('status', 'terlambat')->count(),
            'izin' => (clone $baseQuery)->where('status', 'izin')->count(),
            'sakit' => (clone $baseQuery)->where('status', 'sakit')->count(),
            'cuti' => (clone $baseQuery)->where('status', 'cuti')->count(),
            'dl' => (clone $baseQuery)->where('status', 'dl')->count(),
            'alpha' => (clone $baseQuery)->where('status', 'alpha')->count(),
            'belum_pulang' => (clone $baseQuery)->whereIn('status', ['hadir', 'tepat waktu', 'terlambat'])->whereNull('check_out')->count(),
        ];

        $sort = $request->get('sort', 'latest');
        $statusFilter = $request->get('status_filter');

        if ($statusFilter === 'belum_absen') {
            $attendedEmployeeIds = Attendance::where('date', $date)->pluck('employee_id')->toArray();
            $employees = Employee::with(['category', 'position'])
                ->where('is_active', true)
                ->whereNotIn('id', $attendedEmployeeIds)
                ->when($categoryId, function($q, $category) {
                    return $q->where('category_id', $category);
                })
                ->when($positionId, function($q, $position) {
                    return $q->where('position_id', $position);
                })
                ->when($search, function($q, $search) {
                    return $q->where(function($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                              ->orWhere('code', 'like', "%{$search}%");
                    });
                });
                
            if ($sort === 'name') {
                $employees->orderBy('name');
            } else {
                $employees->latest('updated_at'); // Fallback if no specific column
            }

            $attendances = $employees->paginate(10)->withQueryString();

            // Transform into mock Attendance objects so the view works seamlessly
            $attendances->getCollection()->transform(function($emp) use ($date) {
                $att = new Attendance([
                    'employee_id' => $emp->id,
                    'date' => $date,
                    'status' => 'belum_absen',
                    'check_in' => null,
                    'check_out' => null,
                ]);
                $att->setRelation('employee', $emp);
                return $att;
            });
        } else {
            if ($sort === 'name') {
                $baseQuery->orderBy(Employee::select('name')->whereColumn('employees.id', 'attendances.employee_id')->take(1));
            } else {
                $baseQuery->latest('updated_at');
            }
            $attendances = $baseQuery->paginate(10)->withQueryString();
        }
        $categories = \App\Models\Category::all();
        
        $positionsQuery = \App\Models\Position::orderBy('name');
        if ($categoryId) {
            $positionsQuery->where('category_id', $categoryId);
        }
        $positions = $positionsQuery->get();

        return view('attendance.index', compact('attendances', 'date', 'stats', 'categories', 'positions'));
    }

    /**
     * Daftar absensi bulanan (grid Excel-like).
     */
    public function monthly(Request $request)
    {
        $reportController = new \App\Http\Controllers\ReportController();
        $data = $reportController->generateGridData($request, false);
        
        $categories = \App\Models\Category::orderBy('name')->get();
        $positionsQuery = \App\Models\Position::orderBy('name');
        if ($request->filled('category_id')) {
            $positionsQuery->where('category_id', $request->category_id);
        }
        $positions = $positionsQuery->get();

        return view('attendance.monthly', [
            'categories' => $categories,
            'positions' => $positions,
            'startDate' => $data['startDate'],
            'endDate' => $data['endDate'],
            'categoryId' => $data['categoryId'],
            'positionId' => $data['positionId'],
            'grid' => $data['grid'],
            'daysDiff' => $data['daysDiff'],
            'dates' => $data['dates']
        ]);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'status' => 'required|in:hadir,terlambat,izin,sakit,cuti,dl,alpha,tap,-'
        ]);

        $empId = $request->employee_id;
        $date = $request->date;
        $status = $request->status;

        // Jika statusnya '-', artinya dihapus dari database (kosong)
        if ($status === '-') {
            Attendance::where('employee_id', $empId)->where('date', $date)->delete();
            return response()->json(['success' => true, 'message' => 'Status dikosongkan.']);
        }

        // Cek jika record sudah ada
        $attendance = Attendance::where('employee_id', $empId)->where('date', $date)->first();

        // Jika I/S/C/DL/A, tidak perlu jam check_in dan check_out
        $isNoTime = in_array($status, ['izin', 'sakit', 'cuti', 'dl', 'alpha']);

        if ($attendance) {
            $attendance->status = $status;
            if ($isNoTime) {
                $attendance->check_in = null;
                $attendance->check_out = null;
            } else {
                if (!$attendance->check_in) $attendance->check_in = '07:00:00';
                if (!$attendance->check_out) $attendance->check_out = '15:00:00'; // Lengkapi manual
            }
            $attendance->save();
        } else {
            // Jika record belum ada, buat baru
            $attendance = Attendance::create([
                'employee_id' => $empId,
                'date' => $date,
                'status' => $status,
                'check_in' => $isNoTime ? null : '07:00:00', 
                'check_out' => $isNoTime ? null : '15:00:00', // Jam dummy
            ]);
        }

        return response()->json([
            'success' => true, 
            'message' => 'Status berhasil diubah menjadi ' . strtoupper($status),
            'attendance' => $attendance
        ]);
    }

    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:hadir,terlambat,izin,sakit,cuti,dl,alpha,tap'
        ]);

        $attendance->update([
            'check_in' => $request->check_in ? Carbon::parse($request->check_in)->format('H:i:s') : null,
            'check_out' => $request->check_out ? Carbon::parse($request->check_out)->format('H:i:s') : null,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Data absensi berhasil diperbarui.');
    }

    public function closeToday(Request $request)
    {
        $today = Carbon::today()->format('Y-m-d');

        // Dapatkan ID pegawai yang sudah absen (hadir, sakit, izin, cuti, dl) hari ini
        $attendedEmployeeIds = Attendance::whereDate('date', $today)->pluck('employee_id')->toArray();

        // Ambil pegawai yang belum ada record hari ini dan statusnya masih aktif
        $missingEmployees = Employee::where('is_active', true)->whereNotIn('id', $attendedEmployeeIds)->get();

        $inserts = [];
        $now = Carbon::now();

        foreach ($missingEmployees as $emp) {
            $inserts[] = [
                'employee_id' => $emp->id,
                'date' => $today,
                'status' => 'alpha',
                'check_in' => null,
                'check_out' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (count($inserts) > 0) {
            Attendance::insert($inserts);
        }

        // Tandai yang belum absen pulang sebagai TAP
        $tapCount = Attendance::whereDate('date', $today)
            ->whereIn('status', ['hadir', 'terlambat'])
            ->whereNull('check_out')
            ->update(['status' => 'tap']);

        if (count($inserts) > 0 || $tapCount > 0) {
            return redirect()->back()->with('success', count($inserts) . ' siswa dicatat ALPA, dan ' . $tapCount . ' siswa dicatat TAP (Tanpa Absen Pulang).');
        }

        return redirect()->back()->with('success', 'Semua siswa/staff sudah memiliki data kehadiran hari ini. Tidak ada yang dicatat Alpa.');
    }
}
