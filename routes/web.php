<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\UserController;

// 1. Halaman Absensi Utama (Halaman Scan) - Terbuka secara publik/operator lokal
Route::get('/', [AttendanceController::class, 'scanPage'])->name('scan');
Route::post('/attendance/scan', [AttendanceController::class, 'scan'])->name('attendance.scan');

// 2. Proteksi Halaman Administrasi (Hanya Admin / Operator terautentikasi)
Route::middleware(['auth'])->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Karyawan / Siswa
    Route::post('/employees/import', [EmployeeController::class, 'import'])->name('employees.import');
    Route::get('/employees/template', [EmployeeController::class, 'downloadTemplate'])->name('employees.template');
    Route::get('/employees/{employee}/export-pdf', [EmployeeController::class, 'exportPdf'])->name('employees.export.pdf');
    Route::post('/employees/bulk-delete', [EmployeeController::class, 'bulkDestroy'])->name('employees.bulk-delete');
    Route::get('/employees/print-cards', [EmployeeController::class, 'printCards'])->name('employees.print-cards');
    Route::get('/employees/download-barcodes', [EmployeeController::class, 'downloadBarcodes'])->name('employees.download-barcodes');
    Route::resource('employees', EmployeeController::class);

    // Master Data Kategori
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Master Data Jabatan & Kelas
    Route::resource('positions', PositionController::class)->except(['show']);
    Route::get('/api/positions/by-category/{category}', [PositionController::class, 'byCategory'])->name('positions.by-category');

    // Rekap Absensi Harian & Modul Monitoring
    Route::post('/attendances/close-today', [AttendanceController::class, 'closeToday'])->name('attendances.close-today');

    Route::post('/attendances/update-status', [AttendanceController::class, 'updateStatus'])->name('attendances.updateStatus');
    Route::get('/attendances/monthly', [AttendanceController::class, 'monthly'])->name('attendances.monthly');
    Route::resource('attendances', AttendanceController::class)->except(['create', 'store', 'show', 'edit']);

    // Modul Pengajuan Izin / Cuti
    Route::resource('leaves', LeaveController::class)->except(['show', 'edit', 'update']);

    // Manajemen User
    Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);

    // Laporan (PDF & Excel)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
    
    // Statistik & Grafik
    Route::get('/statistics', [StatisticController::class, 'index'])->name('statistics.index');

    // Pengaturan Sistem
    Route::get('/settings/backup', [SettingController::class, 'backup'])->name('settings.backup');
    Route::post('/settings/backup/download', [SettingController::class, 'downloadBackup'])->name('settings.downloadBackup');
    Route::post('/settings/backup/restore', [SettingController::class, 'restoreBackup'])->name('settings.restoreBackup');

    Route::get('/settings/about', [SettingController::class, 'about'])->name('settings.about');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');
    Route::get('/settings/logs', [SettingController::class, 'logs'])->name('settings.logs');
    Route::post('/settings/logs/clear', [SettingController::class, 'clearLogs'])->name('settings.clearLogs');
    Route::post('/settings/reset-database', [SettingController::class, 'resetDatabase'])->name('settings.reset-database');
});

require __DIR__.'/auth.php';

// Route fallback khusus NativePHP Desktop untuk melayani file Storage (karena symlink tidak berjalan di Windows AppData)
Route::get('avatar/{path}', function ($path) {
    $cleanPath = str_replace(['..', "\0"], '', (string)$path);
    $cleanPath = ltrim($cleanPath, '/\\');
    
    if (empty($cleanPath) || !\Illuminate\Support\Facades\Storage::disk('public')->exists($cleanPath)) {
        return redirect(asset('images/default-avatar.png'));
    }
    
    // Bypass BinaryFileResponse untuk menghindari 403 di beberapa environment Windows
    $file = \Illuminate\Support\Facades\Storage::disk('public')->get($cleanPath);
    $mime = \Illuminate\Support\Facades\Storage::disk('public')->mimeType($cleanPath) ?: 'application/octet-stream';
    return response($file, 200)->header('Content-Type', $mime);
})->where('path', '.*')->name('avatar.serve');
