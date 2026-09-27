<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'school_name' => 'required|string|max:255',
            'school_address' => 'required|string',
            'security_morning_in' => 'required|date_format:H:i',
            'security_morning_out' => 'required|date_format:H:i',
            'security_night_in' => 'required|date_format:H:i',
            'security_night_out' => 'required|date_format:H:i',
            'school_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'kop_pemerintah' => 'nullable|string|max:255',
            'kop_dinas' => 'nullable|string|max:255',
            'kop_kontak' => 'nullable|string|max:255',
            'headmaster_name' => 'nullable|string|max:255',
            'headmaster_nip' => 'nullable|string|max:255',
            'scan_cooldown_minutes' => 'required|integer|min:1|max:720',
            'timezone' => 'required|string|in:Asia/Jakarta,Asia/Makassar,Asia/Jayapura',
        ]);

        Setting::set('school_name', $request->school_name);
        Setting::set('school_address', $request->school_address);
        Setting::set('kop_pemerintah', $request->kop_pemerintah);
        Setting::set('kop_dinas', $request->kop_dinas);
        Setting::set('kop_kontak', $request->kop_kontak);
        Setting::set('security_morning_in', $request->security_morning_in);
        Setting::set('security_morning_out', $request->security_morning_out);
        Setting::set('security_night_in', $request->security_night_in);
        Setting::set('security_night_out', $request->security_night_out);
        Setting::set('headmaster_name', $request->headmaster_name);
        Setting::set('headmaster_nip', $request->headmaster_nip);
        Setting::set('scan_cooldown_minutes', $request->scan_cooldown_minutes);
        Setting::set('timezone', $request->timezone);

        if ($request->hasFile('school_logo')) {
            // Hapus logo lama jika ada
            $oldLogo = Setting::get('school_logo');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
            $path = $request->file('school_logo')->store('settings', 'public');
            Setting::set('school_logo', $path);
        }

        return redirect()->route('settings.index')->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }

    public function resetDatabase(Request $request)
    {
        try {
            $clearAttendances = $request->has('clear_attendances');
            $clearEmployees = $request->has('clear_employees');
            $clearMasters = $request->has('clear_masters');

            // Proteksi logika relasi:
            if ($clearMasters) {
                $clearEmployees = true;
                $clearAttendances = true;
            }
            if ($clearEmployees) {
                $clearAttendances = true;
            }

            if (!$clearAttendances && !$clearEmployees && !$clearMasters) {
                return redirect()->route('settings.backup')->with('error', 'Pilih setidaknya satu jenis data untuk dihapus.');
            }

            \Illuminate\Support\Facades\DB::beginTransaction();
            \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

            // 1. Hapus Absensi
            if ($clearAttendances) {
                \Illuminate\Support\Facades\DB::table('attendances')->truncate();
                \Illuminate\Support\Facades\DB::table('leaves')->truncate();
            }

            // 2. Hapus Pegawai/Siswa
            if ($clearEmployees) {
                $employees = \App\Models\Employee::whereNotNull('photo')->get();
                foreach ($employees as $emp) {
                    Storage::disk('public')->delete($emp->photo);
                }
                \Illuminate\Support\Facades\DB::table('employees')->truncate();
            }

            // 3. Hapus Kategori & Kelas
            if ($clearMasters) {
                \Illuminate\Support\Facades\DB::table('categories')->truncate();
                \Illuminate\Support\Facades\DB::table('positions')->truncate();
            }

            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('settings.backup')->with('success', 'Data terpilih berhasil dikosongkan!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            return redirect()->route('settings.backup')->with('error', 'Gagal mengosongkan data: ' . $e->getMessage());
        }
    }

    public function backup()
    {
        $backupPath = storage_path('app/backups');
        $backups = [];
        
        if (file_exists($backupPath)) {
            $files = \File::files($backupPath);
            foreach ($files as $file) {
                if ($file->getExtension() === 'json') {
                    $backups[] = [
                        'name' => $file->getFilename(),
                        'size' => round($file->getSize() / 1024, 2) . ' KB',
                        'date' => date('Y-m-d H:i:s', $file->getMTime())
                    ];
                }
            }
        }
        
        // Urutkan dari yang terbaru
        usort($backups, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        return view('settings.backup', compact('backups'));
    }

    public function downloadBackup()
    {
        $tables = ['users', 'categories', 'positions', 'employees', 'attendances', 'leaves', 'settings'];
        $data = [];
        
        foreach ($tables as $table) {
            $data[$table] = \Illuminate\Support\Facades\DB::table($table)->get();
        }

        $json = json_encode($data, JSON_PRETTY_PRINT);
        $filename = 'backup_absenpro_' . date('Y_m_d_His') . '.json';
        
        return response()->streamDownload(function () use ($json) {
            echo $json;
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function restoreBackup(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:json'
        ]);

        $file = $request->file('backup_file');
        
        try {
            $json = file_get_contents($file->getRealPath());
            $data = json_decode($json, true);
            
            if (!$data || !is_array($data)) {
                return redirect()->back()->with('error', 'File JSON tidak valid atau rusak.');
            }

            \Illuminate\Support\Facades\DB::beginTransaction();
            
            // Nonaktifkan foreign key sementara
            \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

            $tables = ['users', 'categories', 'positions', 'employees', 'attendances', 'leaves', 'settings'];
            
            foreach ($tables as $table) {
                if (isset($data[$table])) {
                    \Illuminate\Support\Facades\DB::table($table)->truncate();
                    
                    // Insert dalam bentuk chunk agar memori tidak penuh jika data banyak
                    $chunks = array_chunk($data[$table], 500);
                    foreach ($chunks as $chunk) {
                        \Illuminate\Support\Facades\DB::table($table)->insert($chunk);
                    }
                }
            }
            
            // Aktifkan foreign key kembali
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            
            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('settings.backup')->with('success', 'Data absensi dan pegawai berhasil dipulihkan dari backup JSON!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            return redirect()->back()->with('error', 'Gagal melakukan restore: ' . $e->getMessage());
        }
    }

    public function about()
    {
        return view('settings.about');
    }

    public function logs(Request $request)
    {
        $logsPath = storage_path('logs');
        $logFiles = [];

        if (file_exists($logsPath)) {
            $files = \File::files($logsPath);
            foreach ($files as $file) {
                if ($file->getExtension() === 'log') {
                    $logFiles[] = [
                        'name' => $file->getFilename(),
                        'size' => round($file->getSize() / 1024, 2) . ' KB',
                        'date' => date('Y-m-d H:i:s', $file->getMTime()),
                    ];
                }
            }
        }

        // Sort from newest
        usort($logFiles, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        $selectedFile = $request->get('file');
        $logContent = '';
        $currentFile = '';

        if (count($logFiles) > 0) {
            $currentFile = $selectedFile ?: $logFiles[0]['name'];
            $filePath = $logsPath . '/' . $currentFile;

            if (file_exists($filePath)) {
                $size = filesize($filePath);
                $fileHandle = fopen($filePath, 'r');
                // Read last 2MB max to prevent memory crash
                $maxBytes = 2 * 1024 * 1024;
                if ($size > $maxBytes) {
                    fseek($fileHandle, -$maxBytes, SEEK_END);
                }
                $content = fread($fileHandle, $maxBytes);
                fclose($fileHandle);

                $lines = explode("\n", $content);
                // Keep the last 500 lines for the log viewer
                $lines = array_slice($lines, -500);
                $logContent = implode("\n", $lines);
            }
        }

        return view('settings.logs', compact('logFiles', 'logContent', 'currentFile'));
    }

    public function clearLogs(Request $request)
    {
        $filename = $request->input('file');
        $filePath = storage_path('logs/' . $filename);

        if (file_exists($filePath) && str_ends_with($filename, '.log')) {
            file_put_contents($filePath, ''); // Empty the file safely
            return redirect()->route('settings.logs', ['file' => $filename])->with('success', 'Log berhasil dibersihkan!');
        }

        return redirect()->route('settings.logs')->with('error', 'File log tidak ditemukan.');
    }
}
