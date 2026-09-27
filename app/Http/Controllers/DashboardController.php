<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();

        // 1. Data Card Kehadiran Hari Ini
        $totalEmployees = Employee::where('is_active', true)->count();
        $totalPresent = Attendance::where('date', $today)->whereIn('status', ['hadir', 'tepat waktu'])->count();
        $totalLate = Attendance::where('date', $today)->where('status', 'terlambat')->count();
        
        $totalActiveAttendances = Attendance::where('date', $today)->where('status', '!=', 'alpha')->count();
        $totalAbsent = max(0, $totalEmployees - $totalActiveAttendances);

        // 2. Data Grafik Mingguan Kehadiran (7 Hari Terakhir)
        $chartLabels = [];
        $chartDataHadir = [];
        $chartDataTerlambat = [];

        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $chartLabels[] = $day->translatedFormat('d M');
            $chartDataHadir[] = Attendance::where('date', $day->toDateString())->whereIn('status', ['hadir', 'tepat waktu'])->count();
            $chartDataTerlambat[] = Attendance::where('date', $day->toDateString())->where('status', 'terlambat')->count();
        }

        // 3. Aktivitas Log Absensi Terbaru Hari Ini
        $recentAttendances = Attendance::with('employee')
            ->where('date', $today)
            ->orderBy('updated_at', 'desc')
            ->take(8)
            ->get();

        return view('dashboard', compact(
            'totalEmployees',
            'totalPresent',
            'totalLate',
            'totalAbsent',
            'chartLabels',
            'chartDataHadir',
            'chartDataTerlambat',
            'recentAttendances'
        ));
    }
}
