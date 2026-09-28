<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Setting;
use Carbon\Carbon;

class AttendanceService
{
    /**
     * Memproses scan Barcode/QR Code untuk check-in atau check-out.
     *
     * @param string $code Kode NIS/NIP/kartu siswa/karyawan
     * @return array Response detail status absensi
     */
    public function processScan(string $code): array
    {
        // 1. Cari data Employee berdasarkan kode beserta relasi kategorinya
        $employee = Employee::with('category')->where('code', $code)->where('is_active', true)->first();

        if (!$employee) {
            return [
                'success' => false,
                'message' => "Kode '$code' tidak terdaftar atau tidak aktif!"
            ];
        }

        $now = Carbon::now();
        $todayDate = $now->toDateString();
        $currentTime = $now->toTimeString();

        // 2. LOGIKA ABSENSI SECURITY (SHIFT OTOMATIS)
        if ($employee->category && strtoupper($employee->category->code) === 'SEC') {
            return $this->processSecurityAttendance($employee, $now);
        }

        // 3. LOGIKA ABSENSI REGULER (Siswa, Guru, TU, CS)
        return $this->processRegulerAttendance($employee, $todayDate, $currentTime);
    }

    /**
     * Memproses absensi reguler (Siswa/Guru/TU/CS) berbasis harian satu tanggal.
     */
    private function processRegulerAttendance(Employee $employee, string $date, string $time): array
    {
        // Cek apakah sudah absen hari ini
        $attendance = Attendance::where('employee_id', $employee->id)
            ->where('date', $date)
            ->first();

        // Ambil dari jam masuk kategori. Jika kosong (null), maka jadwal fleksibel
        $targetIn = $employee->category->target_in_time;
        // $targetOut = $employee->category->target_out_time; // (Opsional bisa dipakai nanti jika perlu validasi pulang cepat)

        if (!$attendance) {
            // Check-in baru
            // Status terlambat dihitung jika targetIn diset DAN waktu scan melebihi targetIn
            $isLate = false;
            $lateMinutes = 0;
            if ($targetIn) {
                $targetCarbon = Carbon::parse($targetIn)->startOfMinute();
                $timeCarbon = Carbon::parse($time)->startOfMinute();
                if ($timeCarbon->greaterThan($targetCarbon)) {
                    $isLate = true;
                    $lateMinutes = intval(abs($timeCarbon->diffInMinutes($targetCarbon)));
                }
            }
            
            $status = $isLate ? 'terlambat' : 'hadir';

            $attendance = Attendance::create([
                'employee_id' => $employee->id,
                'date' => $date,
                'check_in' => $time,
                'status' => $status,
                'detected_shift' => 'reguler',
            ]);

            $lateText = '';
            $msg = "ABSEN MASUK: {$employee->name} (" . strtoupper($employee->category->name ?? '') . ") berhasil check-in pada pukul " . Carbon::parse($time)->format('H:i');
            if ($isLate && $lateMinutes > 0) {
                $lateText = $this->formatLateMinutes($lateMinutes);
                $msg .= " (Terlambat " . $lateText . ")";
            }

            return [
                'success' => true,
                'action' => 'check_in',
                'employee' => $employee,
                'time' => Carbon::parse($time)->format('H:i'),
                'status' => $status,
                'late_text' => $lateText,
                'message' => $msg
            ];
        }

        // Jika record absensi sudah ada
        if ($attendance) {
            // Jika statusnya bukan hadir/terlambat (misal: Sakit, Izin, Alpha) dan belum ada check-in
            if (in_array($attendance->status, ['izin', 'sakit', 'cuti', 'dl', 'alpha'])) {
                return [
                    'success' => false,
                    'employee' => $employee,
                    'message' => "{$employee->name} sudah tercatat dengan status: " . strtoupper($attendance->status) . " hari ini!"
                ];
            }

            // Jika sudah ada check-in, tapi belum check-out, maka lakukan check-out
            if ($attendance->check_in && !$attendance->check_out) {
                // Berikan cooldown agar tidak langsung check-out secara tidak sengaja
                $cooldownMinutes = (int) Setting::get('scan_cooldown_minutes', '1');
                $attDateStr = Carbon::parse($attendance->date)->format('Y-m-d');
                $attTimeStr = Carbon::parse($attendance->check_in)->format('H:i:s');
                $checkInDateTime = Carbon::parse("$attDateStr $attTimeStr");
                $currentDateTime = Carbon::parse("$date $time");

                if (abs($currentDateTime->diffInSeconds($checkInDateTime)) < ($cooldownMinutes * 60)) {
                    return [
                        'success' => false,
                        'employee' => $employee,
                        'message' => "Siswa {$employee->name} sudah absen masuk. Tunggu {$cooldownMinutes} menit lagi untuk absen pulang!"
                    ];
                }

                $attendance->update([
                    'check_out' => $time,
                ]);

                return [
                    'success' => true,
                    'action' => 'check_out',
                    'employee' => $employee,
                    'time' => Carbon::parse($time)->format('H:i'),
                    'status' => $attendance->status,
                    'message' => "ABSEN PULANG: {$employee->name} (" . strtoupper($employee->category->name ?? '') . ") berhasil check-out pada pukul " . Carbon::parse($time)->format('H:i')
                ];
            }

            // Jika sudah punya check-in dan check-out
            return [
                'success' => false,
                'employee' => $employee,
                'message' => "{$employee->name} sudah melakukan Check-In dan Check-Out hari ini!"
            ];
        }
    }

    /**
     * Memproses absensi dinamis khusus Security berdasarkan waktu check-in.
     */
    private function processSecurityAttendance(Employee $employee, Carbon $now): array
    {
        $timeStr = $now->toTimeString();
        $dateStr = $now->toDateString();

        // Rentang waktu deteksi shift:
        // Pagi: Check-in jam 05:00 s.d. 12:00
        // Malam: Check-in jam 17:00 s.d. 23:59 atau 00:00 s.d 04:59

        $hour = $now->hour;
        $detectedShift = 'pagi';
        if ($hour >= 17 || $hour < 5) {
            $detectedShift = 'malam';
        }

        // Cek data check-in aktif yang BELUM check-out
        // Untuk shift malam, check-out dilakukan keesokan harinya (cross-day)
        // Kita cari data kehadiran terakhir yang belum memiliki check-out dalam 24 jam terakhir
        $activeAttendance = Attendance::where('employee_id', $employee->id)
            ->whereNull('check_out')
            ->orderBy('id', 'desc')
            ->first();

        if ($activeAttendance) {
            $checkInDateTime = Carbon::parse($activeAttendance->date . ' ' . $activeAttendance->check_in);
            if (abs($now->diffInHours($checkInDateTime)) > 24) {
                $activeAttendance = null; // Shift sudah kadaluarsa (lebih dari 24 jam)
            }
        }

        if (!$activeAttendance) {
            // Logika Check-In Baru
            $targetInTime = ($detectedShift === 'pagi') 
                ? Setting::get('security_morning_in', '07:00') 
                : Setting::get('security_night_in', '19:00');

            // Hitung keterlambatan (tidak ada toleransi, di atas jam target masuk dianggap terlambat)
            // Khusus shift malam yang check-in jam 19:00, bandingkan jam saat ini dengan 19:00
            $targetCarbon = Carbon::parse($now->toDateString() . ' ' . $targetInTime)->startOfMinute();
            $nowMinute = $now->copy()->startOfMinute();
            
            // Jika masuk shift malam lewat tengah malam (keterlambatan parah), sesuaikan target ke hari sebelumnya jika perlu.
            // Namun secara umum:
            $isLate = false;
            $lateMinutes = 0;
            if ($detectedShift === 'pagi') {
                if ($nowMinute->greaterThan($targetCarbon)) {
                    $isLate = true;
                    $lateMinutes = intval(abs($nowMinute->diffInMinutes($targetCarbon)));
                }
            } else {
                // Untuk malam, jika check-in di antara jam 19:00 - 23:59
                if ($hour >= 17) {
                    if ($nowMinute->greaterThan($targetCarbon)) {
                        $isLate = true;
                        $lateMinutes = intval(abs($nowMinute->diffInMinutes($targetCarbon)));
                    }
                } else {
                    // Check-in lewat tengah malam (00:00 - 04:59) pasti terlambat
                    $isLate = true;
                    $actualTarget = $targetCarbon->copy()->subDay();
                    $lateMinutes = intval(abs($nowMinute->diffInMinutes($actualTarget)));
                }
            }

            $status = $isLate ? 'terlambat' : 'hadir';

            $newAttendance = Attendance::create([
                'employee_id' => $employee->id,
                'date' => $dateStr, // Tanggal log mulai shift
                'check_in' => $timeStr,
                'status' => $status,
                'detected_shift' => $detectedShift,
            ]);

            $lateText = '';
            $shiftDisplay = ucfirst($detectedShift);
            $msg = "ABSEN MASUK (Shift $shiftDisplay): {$employee->name} berhasil check-in pada pukul {$timeStr}.";
            if ($isLate && $lateMinutes > 0) {
                $lateText = $this->formatLateMinutes($lateMinutes);
                $msg .= " (Terlambat " . $lateText . ")";
            }

            return [
                'success' => true,
                'action' => 'check_in',
                'employee' => $employee,
                'time' => $timeStr,
                'status' => $status,
                'late_text' => $lateText,
                'message' => $msg
            ];
        }

        // Logika Check-Out (Jika ada data check-in aktif yang belum check-out)
        // Berikan cooldown agar tidak ter-check-out secara tidak sengaja saat scan pertama kali
        $cooldownMinutes = (int) Setting::get('scan_cooldown_minutes', '1');
        $attDateStr = Carbon::parse($activeAttendance->date)->format('Y-m-d');
        $attTimeStr = Carbon::parse($activeAttendance->check_in)->format('H:i:s');
        $checkInDateTime = Carbon::parse("$attDateStr $attTimeStr");

        if (abs($now->diffInSeconds($checkInDateTime)) < ($cooldownMinutes * 60)) {
            return [
                'success' => false,
                'employee' => $employee,
                'message' => "{$employee->name} sudah absen masuk. Tunggu {$cooldownMinutes} menit lagi untuk absen pulang!"
            ];
        }

        $activeAttendance->update([
            'check_out' => $timeStr,
        ]);

        return [
            'success' => true,
            'action' => 'check_out',
            'employee' => $employee,
            'time' => $now->format('H:i'),
            'status' => $activeAttendance->status,
            'message' => "ABSEN PULANG SECURITY (SHIFT " . strtoupper($activeAttendance->detected_shift) . "): {$employee->name} berhasil check-out pukul " . $now->format('H:i')
        ];
    }

    /**
     * Format menit menjadi format yang lebih mudah dibaca (misal: 1 jam 30 menit)
     */
    private function formatLateMinutes(int $minutes): string
    {
        if ($minutes < 60) {
            return "$minutes menit";
        }
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        if ($remainingMinutes === 0) {
            return "$hours jam";
        }
        return "$hours jam $remainingMinutes menit";
    }
}
