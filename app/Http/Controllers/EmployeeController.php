<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Category;
use App\Models\Position;
use App\Models\Attendance;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmployeeImport;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\File;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        // Simpan URL index (termasuk pagination dan filter) ke session untuk tombol 'Back' di halaman detail
        session()->put('employee_list_url', request()->fullUrl());

        $query = Employee::with(['category', 'position']);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        if ($request->filled('position')) {
            $query->where('position_id', $request->position);
        }

        $perPage = $request->get('per_page', 10);
        $employees = $query->paginate($perPage)->withQueryString();
        
        $categories = Category::all();
        
        $positionsQuery = Position::orderBy('name');
        if ($request->filled('category')) {
            $positionsQuery->where('category_id', $request->category);
        }
        $positions = $positionsQuery->get();
        
        return view('employees.index', compact('employees', 'categories', 'positions'));
    }

    public function printCards(Request $request)
    {
        $query = Employee::with(['category', 'position']);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        if ($request->filled('position')) {
            $query->where('position_id', $request->position);
        }

        // Ambil semua data sesuai filter tanpa pagination
        $employees = $query->get();
        
        $schoolName = Setting::get('school_name', 'SMAN 1 Bentian Besar');
        $schoolAddress = Setting::get('school_address');
        $schoolLogo = Setting::get('school_logo');
        $kopPemerintah = Setting::get('kop_pemerintah');
        $kopDinas = Setting::get('kop_dinas');

        return view('employees.print_cards', compact('employees', 'schoolName', 'schoolAddress', 'schoolLogo', 'kopPemerintah', 'kopDinas'));
    }

    public function downloadBarcodes(Request $request)
    {
        $query = Employee::with(['category', 'position']);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        if ($request->filled('position')) {
            $query->where('position_id', $request->position);
        }

        $employees = $query->get();
        $type = $request->get('type', 'barcode'); // 'barcode' or 'qrcode'

        if ($employees->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data untuk diunduh.');
        }

        $zip = new \ZipArchive();
        $tempDir = storage_path('app/temp/barcodes');
        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        $zipFileName = 'Download_' . ($type == 'qrcode' ? 'QRCode' : 'Barcode') . '_' . date('Ymd_His') . '.zip';
        $zipFilePath = storage_path('app/temp/' . $zipFileName);

        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            foreach ($employees as $employee) {
                // Bersihkan karakter yang tidak valid untuk nama file windows
                $safeName = preg_replace('/[^A-Za-z0-9\- ]/', '', $employee->name);
                $fileName = $employee->code . ' - ' . $safeName . '.png';
                $filePath = $tempDir . '/' . $fileName;

                if ($type == 'qrcode') {
                    $dns2d = new DNS2D();
                    $base64 = $dns2d->getBarcodePNG($employee->code, 'QRCODE', 10, 10, array(0,0,0));
                } else {
                    $dns1d = new DNS1D();
                    $base64 = $dns1d->getBarcodePNG($employee->code, 'C128', 2, 60, array(0,0,0), true);
                }

                $imageData = base64_decode($base64);
                File::put($filePath, $imageData);
                $zip->addFile($filePath, $fileName);
            }
            $zip->close();
        }

        // Hapus file PNG temporary setelah ZIP dibuat
        File::deleteDirectory($tempDir);

        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }

    public function create()
    {
        $categories = Category::all();
        return view('employees.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'        => 'required|string|unique:employees,code',
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'position_id' => 'nullable|exists:positions,id',
            'photo'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['code', 'name', 'category_id', 'position_id']);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('employees', 'public');
            $data['photo'] = $path;
        }

        Employee::create($data);

        return redirect()->route('employees.index')->with('success', 'Data karyawan/siswa berhasil ditambahkan.');
    }

    public function edit(Employee $employee)
    {
        $employee->load(['category', 'position']);
        $categories = Category::all();
        $positions = $employee->category_id
            ? Position::where('category_id', $employee->category_id)->orderBy('name')->get()
            : collect();
        return view('employees.edit', compact('employee', 'categories', 'positions'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'code'        => 'required|string|unique:employees,code,' . $employee->id,
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'position_id' => 'nullable|exists:positions,id',
            'photo'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active'   => 'required|boolean',
        ]);

        $data = $request->only(['code', 'name', 'category_id', 'position_id', 'is_active']);

        if ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $path = $request->file('photo')->store('employees', 'public');
            $data['photo'] = $path;
        }

        $employee->update($data);

        return redirect()->route('employees.index', $request->query())->with('success', 'Data karyawan/siswa berhasil diperbarui.');
    }

    public function show(Request $request, Employee $employee)
    {
        $employee->load(['category', 'position']);
        
        $period = $request->get('period', 'month'); // day, week, month, all
        $perPage = $request->get('per_page', 10); // Default 10
        
        $query = Attendance::where('employee_id', $employee->id)->orderBy('date', 'desc');
        
        if ($period == 'day') {
            $query->whereDate('date', Carbon::today());
        } elseif ($period == 'week') {
            $query->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($period == 'month') {
            $query->whereMonth('date', Carbon::today()->month)
                  ->whereYear('date', Carbon::today()->year);
        }
        
        $attendancesList = $query->paginate($perPage)->withQueryString();
        
        // Count summary based on the same filter (but unpaginated)
        $summaryQuery = Attendance::where('employee_id', $employee->id);
        if ($period == 'day') {
            $summaryQuery->whereDate('date', Carbon::today());
        } elseif ($period == 'week') {
            $summaryQuery->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($period == 'month') {
            $summaryQuery->whereMonth('date', Carbon::today()->month)
                         ->whereYear('date', Carbon::today()->year);
        }
        $allAttendances = $summaryQuery->get();

        $summary = [
            'hadir' => $allAttendances->whereIn('status', ['hadir', 'tepat waktu', 'terlambat', 'tap'])->count(),
            'tepat_waktu' => $allAttendances->whereIn('status', ['hadir', 'tepat waktu'])->count(),
            'terlambat' => $allAttendances->where('status', 'terlambat')->count(),
            'izin' => $allAttendances->where('status', 'izin')->count(),
            'sakit' => $allAttendances->where('status', 'sakit')->count(),
            'alpha' => $allAttendances->where('status', 'alpha')->count(),
            'cuti' => $allAttendances->where('status', 'cuti')->count(),
            'dl' => $allAttendances->where('status', 'dl')->count(),
            'tap' => $allAttendances->where('status', 'tap')->count(),
        ];

        return view('employees.show', compact('employee', 'summary', 'attendancesList', 'period'));
    }

    public function exportPdf(Request $request, Employee $employee)
    {
        $employee->load(['category', 'position']);
        
        $period = $request->get('period', 'month');
        
        $query = Attendance::where('employee_id', $employee->id)->orderBy('date', 'desc');
        
        if ($period == 'day') {
            $query->whereDate('date', Carbon::today());
        } elseif ($period == 'week') {
            $query->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($period == 'month') {
            $query->whereMonth('date', Carbon::today()->month)
                  ->whereYear('date', Carbon::today()->year);
        }
        
        $attendancesList = $query->get();
        
        $summary = [
            'hadir' => $attendancesList->whereIn('status', ['hadir', 'tepat waktu', 'terlambat', 'tap'])->count(),
            'tepat_waktu' => $attendancesList->whereIn('status', ['hadir', 'tepat waktu'])->count(),
            'terlambat' => $attendancesList->where('status', 'terlambat')->count(),
            'izin' => $attendancesList->where('status', 'izin')->count(),
            'sakit' => $attendancesList->where('status', 'sakit')->count(),
            'alpha' => $attendancesList->where('status', 'alpha')->count(),
            'cuti' => $attendancesList->where('status', 'cuti')->count(),
            'dl' => $attendancesList->where('status', 'dl')->count(),
            'tap' => $attendancesList->where('status', 'tap')->count(),
        ];

        $data = [
            'employee' => $employee,
            'attendancesList' => $attendancesList,
            'summary' => $summary,
            'period' => $period,
            'kopPemerintah' => Setting::get('kop_pemerintah'),
            'kopDinas' => Setting::get('kop_dinas'),
            'kopKontak' => Setting::get('kop_kontak'),
            'schoolName' => Setting::get('school_name', 'SMAN 1 Bentian Besar'),
            'schoolAddress' => Setting::get('school_address', 'Jl. Pendidikan, Bentian Besar'),
            'schoolLogo' => Setting::get('school_logo'),
            'headmasterName' => Setting::get('headmaster_name'),
            'headmasterNip' => Setting::get('headmaster_nip'),
        ];

        $pdf = Pdf::loadView('employees.exports.attendance_pdf', $data)
                  ->setPaper('a4', 'portrait');
                  
        return $pdf->download('Histori_Absen_' . str_replace(' ', '_', $employee->name) . '_' . date('Ymd') . '.pdf');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }
        $employee->delete();

        return redirect()->route('employees.index', request()->query())->with('success', 'Data karyawan/siswa berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:employees,id'
        ]);

        $employees = Employee::whereIn('id', $request->ids)->get();
        foreach ($employees as $employee) {
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $employee->delete();
        }

        return redirect()->route('employees.index')->with('success', count($request->ids) . ' data karyawan/siswa berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            $import = new EmployeeImport;
            Excel::import($import, $request->file('file'));
            
            $msg = "Import berhasil! {$import->importedCount} data ditambahkan.";
            if ($import->skippedCount > 0) {
                $msg .= " {$import->skippedCount} data dilewati (kode duplikat / kategori tidak ada).";
            }
            
            return redirect()->route('employees.index')->with('success', $msg);
        } catch (\Exception $e) {
            return redirect()->route('employees.index')->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $staticTemplatePath = resource_path('templates/Template_Import_Siswa_Staff.xlsx');

        if (File::exists($staticTemplatePath)) {
            return response()->download($staticTemplatePath, 'Template_Import_Siswa_Staff.xlsx', [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
            ]);
        }

        $spreadsheet = new Spreadsheet();
        
        // --- Sheet 1: Template Import ---
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Import');

        // Header
        $sheet->setCellValue('A1', 'NOMOR_INDUK');
        $sheet->setCellValue('B1', 'NAMA_LENGKAP');
        $sheet->setCellValue('C1', 'KODE_KATEGORI');
        $sheet->setCellValue('D1', 'JABATAN_KELAS');
        $sheet->setCellValue('E1', 'STATUS');

        // Style Header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF0284C7'] // Biru
            ],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(15);

        // Contoh Data
        $sheet->setCellValue('A2', '20261001');
        $sheet->setCellValue('B2', 'Ahmad Budi');
        $sheet->setCellValue('C2', 'SIS');
        $sheet->setCellValue('D2', 'X-IPA-1');
        $sheet->setCellValue('E2', 'Aktif');

        $sheet->setCellValue('A3', 'G001');
        $sheet->setCellValue('B3', 'Budi Santoso, S.Pd');
        $sheet->setCellValue('C3', 'GUR');
        $sheet->setCellValue('D3', 'Guru Matematika');
        $sheet->setCellValue('E3', 'Aktif');

        // --- Sheet 2: Panduan & Referensi ---
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Panduan Kategori');

        $sheet2->setCellValue('A1', 'KODE_KATEGORI');
        $sheet2->setCellValue('B1', 'NAMA KATEGORI');
        $sheet2->setCellValue('C1', 'REFERENSI JABATAN / KELAS YANG ADA');
        
        $sheet2->getStyle('A1:C1')->applyFromArray($headerStyle);
        $sheet2->getColumnDimension('A')->setWidth(18);
        $sheet2->getColumnDimension('B')->setWidth(20);
        $sheet2->getColumnDimension('C')->setWidth(70);

        $categories = Category::with('positions')->get();
        $row = 2;
        foreach ($categories as $cat) {
            $sheet2->setCellValue("A{$row}", $cat->code);
            $sheet2->setCellValue("B{$row}", $cat->name);
            
            $posNames = $cat->positions->pluck('name')->toArray();
            $posString = empty($posNames) ? '-' : implode(', ', $posNames);
            
            $sheet2->setCellValue("C{$row}", $posString);
            $row++;
        }

        // Pesan info di bawah tabel panduan
        $row += 2;
        $sheet2->setCellValue("A{$row}", "CATATAN PENTING:");
        $sheet2->getStyle("A{$row}")->getFont()->setBold(true);
        $sheet2->setCellValue("A" . ($row + 1), "- Pastikan mengisi KODE_KATEGORI sesuai kolom A.");
        $sheet2->setCellValue("A" . ($row + 2), "- Jika JABATAN_KELAS yang Anda ketik belum ada, sistem akan otomatis membuatnya.");

        // Kembali fokus ke Sheet 1
        $spreadsheet->setActiveSheetIndex(0);

        $useXlsx = class_exists(\XMLWriter::class);
        $fileName = $useXlsx
            ? 'Template_Import_Siswa_Staff.xlsx'
            : 'Template_Import_Siswa_Staff.csv';
        $tempPath = storage_path('app/temp');

        if (!File::exists($tempPath)) {
            File::makeDirectory($tempPath, 0755, true);
        }

        $tempFile = tempnam($tempPath, 'template_') . ($useXlsx ? '.xlsx' : '.csv');

        $writer = $useXlsx
            ? new Xlsx($spreadsheet)
            : new Csv($spreadsheet);
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName, [
            'Content-Type' => $useXlsx
                ? 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                : 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
        ])->deleteFileAfterSend(true);
    }
}
