<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;

class DummyAttendanceSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil 50 pegawai aktif secara acak
        $employees = Employee::where('is_active', true)->inRandomOrder()->take(50)->get();

        if ($employees->isEmpty()) {
            return;
        }

        $statuses = ['hadir', 'hadir', 'hadir', 'terlambat', 'izin', 'sakit', 'alpha'];

        // Buat data untuk 7 hari terakhir (termasuk hari ini)
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::today()->subDays($i)->toDateString();

            foreach ($employees as $employee) {
                // Acak status (hadir lebih sering)
                $status = $statuses[array_rand($statuses)];
                
                // Set jam masuk/pulang hanya jika hadir atau terlambat
                $checkIn = null;
                $checkOut = null;
                
                if (in_array($status, ['hadir', 'terlambat'])) {
                    if ($status == 'hadir') {
                        $checkIn = '06:' . str_pad(rand(30, 59), 2, '0', STR_PAD_LEFT) . ':00';
                    } else {
                        $checkIn = '07:' . str_pad(rand(15, 59), 2, '0', STR_PAD_LEFT) . ':00';
                    }
                    $checkOut = '15:' . str_pad(rand(0, 30), 2, '0', STR_PAD_LEFT) . ':00';
                }

                // Hindari duplikat jika dijalankan berkali-kali
                Attendance::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'date' => $date
                    ],
                    [
                        'status' => $status,
                        'check_in' => $checkIn,
                        'check_out' => $checkOut,
                    ]
                );
            }
        }
    }
}
