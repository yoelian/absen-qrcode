@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="text-dark fw-bold m-0"><i class="bi bi-file-earmark-medical-fill me-2 text-primary"></i>Pengajuan Izin & Cuti</h3>
        <p class="text-muted m-0 mt-1">Kelola riwayat pengajuan Izin, Sakit, dan Cuti pegawai.</p>
    </div>
    <a href="{{ route('leaves.create') }}" class="btn btn-primary px-4 py-2 rounded-3">
        <i class="bi bi-plus-lg me-1"></i>Input Pengajuan Baru
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 mb-4" role="alert"
        style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46;">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="custom-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="text-muted">
                <tr>
                    <th>#</th>
                    <th>Nama Pegawai</th>
                    <th>Kategori</th>
                    <th>Jenis</th>
                    <th>Rentang Tanggal</th>
                    <th>Alasan / Keterangan</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leaves as $leave)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration + $leaves->firstItem() - 1 }}</td>
                        <td class="fw-bold text-dark">{{ $leave->employee->name }}</td>
                        <td>
                            <span class="badge bg-{{ $leave->employee->category->badge_color ?? 'primary' }}">
                                {{ strtoupper($leave->employee->category->name ?? '-') }}
                            </span>
                        </td>
                        <td>
                            @if($leave->type === 'izin')
                                <span class="badge bg-primary text-white"><i class="bi bi-info-circle me-1"></i> IZIN</span>
                            @elseif($leave->type === 'sakit')
                                <span class="badge bg-info text-white"><i class="bi bi-heart-pulse me-1"></i> SAKIT</span>
                            @elseif($leave->type === 'dl')
                                <span class="badge" style="background-color: #00695c; color: white;"><i class="bi bi-briefcase me-1"></i> DINAS LUAR</span>
                            @elseif($leave->type === 'cuti')
                                <span class="badge" style="background-color: #9a3412;"><i class="bi bi-cup-hot me-1"></i> CUTI</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">
                                {{ \Carbon\Carbon::parse($leave->start_date)->translatedFormat('d M Y') }}
                                @if($leave->start_date !== $leave->end_date)
                                    <span class="text-muted mx-1">s/d</span> 
                                    {{ \Carbon\Carbon::parse($leave->end_date)->translatedFormat('d M Y') }}
                                @endif
                            </div>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }} Hari
                            </small>
                        </td>
                        <td class="text-muted" style="max-width: 250px;">
                            {{ $leave->reason ?: '-' }}
                        </td>
                        <td class="text-end">
                            <form action="{{ route('leaves.destroy', $leave->id) }}" method="POST" onsubmit="return confirm('Hapus riwayat pengajuan ini? Menghapus riwayat tidak akan mereset absensi di grid secara otomatis.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-2">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-folder2-open" style="font-size: 3rem; opacity: 0.3;"></i>
                                <p class="mt-3 mb-1 fw-semibold">Belum ada riwayat pengajuan</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-3">
        {{ $leaves->links() }}
    </div>
</div>
@endsection
