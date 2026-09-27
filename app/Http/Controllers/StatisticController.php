<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;

class StatisticController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', Carbon::today()->month);
        $year = $request->get('year', Carbon::today()->year);

        // Validasi input
        if (!is_numeric($month) || $month < 1 || $month > 12) $month = Carbon::today()->month;
        if (!is_numeric($year) || $year < 2020 || $year > 2099) $year = Carbon::today()->year;

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        
        // Batasi hari sampai hari ini jika bulan yang dipilih adalah bulan ini
        $daysInMonth = $endDate->day;
        $currentDay = ($month == Carbon::today()->month && $year == Carbon::today()->year) 
            ? Carbon::today()->day 
            : $daysInMonth;

        $categoryId = $request->get('category_id');

        $attendancesQuery = Attendance::whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()]);
        
        if ($categoryId) {
            $attendancesQuery->whereHas('employee', function($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }
        $attendances = $attendancesQuery->get();

        $chartLabels = [];
        $dataHadir = [];
        $dataTerlambat = [];
        $dataIzinSakit = [];
        $dataAlpa = [];

        $totalHadir = 0;
        $totalTerlambat = 0;
        $totalIzinSakit = 0;
        $totalAlpa = 0;

        $employeesQuery = Employee::where('is_active', true);
        if ($categoryId) {
            $employeesQuery->where('category_id', $categoryId);
        }
        $totalEmployees = $employeesQuery->count();
        
        $categories = \App\Models\Category::orderBy('name')->get();

        // Loop per hari dalam bulan tersebut
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $dateString = Carbon::createFromDate($year, $month, $i)->toDateString();
            $chartLabels[] = $i;

            if ($i <= $currentDay) {
                // Ambil data untuk tanggal ini
                $dayData = $attendances->where('date', $dateString);
                
                $h = $dayData->whereIn('status', ['hadir', 'tepat waktu'])->count();
                $t = $dayData->where('status', 'terlambat')->count();
                $is = $dayData->whereIn('status', ['izin', 'sakit'])->count();
                
                // Alpa: total karyawan dikurangi semua absensi kecuali alpa, ditambah yang explicitly di set alpa
                // Aturan yang sama dengan dashboard
                $activeCount = $dayData->where('status', '!=', 'alpha')->count();
                $a = max(0, $totalEmployees - $activeCount);

                $dataHadir[] = $h;
                $dataTerlambat[] = $t;
                $dataIzinSakit[] = $is;
                $dataAlpa[] = $a;

                $totalHadir += $h;
                $totalTerlambat += $t;
                $totalIzinSakit += $is;
                $totalAlpa += $a;
            } else {
                // Jika masa depan, kosongkan (0)
                $dataHadir[] = 0;
                $dataTerlambat[] = 0;
                $dataIzinSakit[] = 0;
                $dataAlpa[] = 0;
            }
        }

        $totalAll = $totalHadir + $totalTerlambat + $totalIzinSakit + $totalAlpa;
        
        $percentages = [
            'hadir' => $totalAll > 0 ? round(($totalHadir / $totalAll) * 100, 1) : 0,
            'terlambat' => $totalAll > 0 ? round(($totalTerlambat / $totalAll) * 100, 1) : 0,
            'izin_sakit' => $totalAll > 0 ? round(($totalIzinSakit / $totalAll) * 100, 1) : 0,
            'alpa' => $totalAll > 0 ? round(($totalAlpa / $totalAll) * 100, 1) : 0,
        ];

        return view('statistics.index', compact(
            'month', 
            'year', 
            'categories',
            'categoryId',
            'chartLabels', 
            'dataHadir', 
            'dataTerlambat', 
            'dataIzinSakit', 
            'dataAlpa',
            'totalHadir',
            'totalTerlambat',
            'totalIzinSakit',
            'totalAlpa',
            'percentages'
        ));
    }
}
