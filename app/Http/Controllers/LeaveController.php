<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function index()
    {
        $leaves = Leave::with(['employee.category'])->latest()->paginate(20);
        return view('leaves.index', compact('leaves'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        $employees = Employee::where('is_active', true)->with('category')->orderBy('name')->get();
        return view('leaves.create', compact('employees', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|in:izin,sakit,cuti,dl',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string'
        ]);

        $employee = Employee::with('category')->findOrFail($request->employee_id);

        // Simpan riwayat pengajuan
        Leave::create($request->all());

        // Lakukan looping dari start_date sampai end_date
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        
        $isSecurity = ($employee->category && $employee->category->code === 'SEC');

        // Loop setiap hari
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dateStr = $date->toDateString();
            $isWeekend = $date->isWeekend();

            // Sesuai konfirmasi user: Izin/Sakit/Cuti tetap diisi meskipun hari libur (Sabtu/Minggu)
            // Jadi kita update/buat record attendance tanpa me-skip weekend
            
            $attendance = Attendance::where('employee_id', $employee->id)->where('date', $dateStr)->first();

            if ($attendance) {
                $attendance->update([
                    'status' => $request->type,
                    'check_in' => null,
                    'check_out' => null
                ]);
            } else {
                Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => $dateStr,
                    'status' => $request->type,
                    'check_in' => null,
                    'check_out' => null
                ]);
            }
        }

        return redirect()->route('leaves.index')->with('success', 'Pengajuan ' . ucfirst($request->type) . ' berhasil disimpan dan rekap absensi telah diperbarui.');
    }

    public function destroy(Leave $leaf) // $leaf is singular of leaves
    {
        // 1. Bersihkan kalender absensi di rentang tanggal pengajuan
        $startDate = Carbon::parse($leaf->start_date);
        $endDate = Carbon::parse($leaf->end_date);
        
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dateStr = $date->toDateString();
            
            // Hapus data kehadiran JIKA statusnya sesuai dengan tipe izin/sakit/cuti/dl ini
            // (Mencegah terhapusnya absen 'hadir' jika sudah diganti manual oleh admin)
            Attendance::where('employee_id', $leaf->employee_id)
                ->where('date', $dateStr)
                ->where('status', $leaf->type)
                ->delete();
        }

        // 2. Hapus riwayat pengajuan itu sendiri
        $leaf->delete();
        
        return redirect()->route('leaves.index')->with('success', 'Riwayat pengajuan berhasil dihapus dan data kalender telah dibersihkan otomatis.');
    }
}
