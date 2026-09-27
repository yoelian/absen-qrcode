<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\File;

class AutoBackupMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya lakukan cek backup pada request GET yang tidak memakan resource berat
        // Dan hanya jika user sudah login (Admin)
        if (auth()->check() && $request->isMethod('GET')) {
            try {
                $this->performAutoBackup();
            } catch (
                \Throwable $e
            ) {
                // Abaikan kegagalan backup agar request utama tetap jalan.
            }
        }

        return $next($request);
    }

    private function performAutoBackup()
    {
        $backupDir = storage_path('app/backups');
        
        // Buat folder jika belum ada
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $today = date('Y-m-d');
        $expectedBackupName = 'backup_absenpro_' . $today . '.json';
        $backupPath = $backupDir . '/' . $expectedBackupName;

        // Jika backup untuk hari ini belum ada, lakukan generate JSON
        if (!File::exists($backupPath)) {
            $tables = ['users', 'categories', 'positions', 'employees', 'attendances', 'leaves', 'settings'];
            $data = [];
            
            foreach ($tables as $table) {
                $data[$table] = \Illuminate\Support\Facades\DB::table($table)->get();
            }

            $json = json_encode($data, JSON_PRETTY_PRINT);
            File::put($backupPath, $json);
            
            // Rotasi Backup: Hapus backup yang lebih lama dari 7 hari
            $this->cleanupOldBackups($backupDir);
        }
    }

    private function cleanupOldBackups($backupDir)
    {
        $files = File::files($backupDir);
        $now = time();
        $daysToKeep = 7; // Simpan backup selama 7 hari terakhir
        
        foreach ($files as $file) {
            if ($file->getExtension() === 'json') {
                $fileAgeInDays = ($now - $file->getMTime()) / (60 * 60 * 24);
                if ($fileAgeInDays > $daysToKeep) {
                    File::delete($file->getPathname());
                }
            }
        }
    }
}
