@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-end mb-4">
    <div>
        <h3 class="text-dark fw-bold"><i class="bi bi-calendar2-check-fill me-2 text-primary"></i>Rekap Kehadiran</h3>
        <p class="text-muted m-0">Memantau statistik dan detail absensi masuk dan pulang karyawan.</p>
    </div>
    <!-- Tab Navigasi -->
    <div class="bg-white p-1 rounded-3 shadow-sm border" style="display: inline-flex;">
        <a href="{{ route('attendances.index') }}" class="btn btn-primary px-4 py-2 fw-semibold rounded-2" style="transition:all 0.2s;">Harian</a>
        <a href="{{ route('attendances.monthly') }}" class="btn btn-light text-muted px-4 py-2 fw-semibold rounded-2 border-0" style="transition:all 0.2s; background:transparent;">Bulanan</a>
    </div>
</div>

<!-- Statistik Ringkasan -->
<div class="row g-2 mb-4">
    <div class="col-md-3 col-sm-4 col-6">
        <div class="card bg-success bg-opacity-10 border-success border-opacity-25 h-100">
            <div class="card-body p-3 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-success m-0 fw-bold">Hadir / Tepat Waktu</h6>
                </div>
                <h3 class="text-success m-0 fw-bold">{{ $stats['hadir'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-4 col-6">
        <div class="card bg-warning bg-opacity-10 border-warning border-opacity-25 h-100">
            <div class="card-body p-3 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-warning text-darken m-0 fw-bold" style="color: #d97706;">Terlambat</h6>
                </div>
                <h3 class="m-0 fw-bold" style="color: #d97706;">{{ $stats['terlambat'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-4 col-6">
        <div class="card bg-primary bg-opacity-10 border-primary border-opacity-25 h-100">
            <div class="card-body p-3 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-primary m-0 fw-bold">Izin</h6>
                </div>
                <h3 class="text-primary m-0 fw-bold">{{ $stats['izin'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-4 col-6">
        <div class="card bg-info bg-opacity-10 border-info border-opacity-25 h-100">
            <div class="card-body p-3 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-info text-darken m-0 fw-bold" style="color: #0369a1;">Sakit</h6>
                </div>
                <h3 class="m-0 fw-bold" style="color: #0369a1;">{{ $stats['sakit'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-4 col-6">
        <div class="card bg-secondary bg-opacity-10 border-secondary border-opacity-25 h-100">
            <div class="card-body p-3 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-secondary m-0 fw-bold">Cuti</h6>
                </div>
                <h3 class="text-secondary m-0 fw-bold">{{ $stats['cuti'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-4 col-6">
        <div class="card bg-dark bg-opacity-10 border-dark border-opacity-25 h-100">
            <div class="card-body p-3 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-dark m-0 fw-bold">Dinas Luar</h6>
                </div>
                <h3 class="text-dark m-0 fw-bold">{{ $stats['dl'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-4 col-6">
        <div class="card bg-danger bg-opacity-10 border-danger border-opacity-25 h-100">
            <div class="card-body p-3 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-danger m-0 fw-bold">Alpa</h6>
                </div>
                <h3 class="text-danger m-0 fw-bold">{{ $stats['alpha'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-4 col-6">
        <div class="card bg-light border-secondary border-opacity-25 h-100">
            <div class="card-body p-3 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted m-0 fw-bold">Belum Pulang</h6>
                </div>
                <h3 class="text-muted m-0 fw-bold">{{ $stats['belum_pulang'] }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="custom-card">
    <form method="GET" action="{{ route('attendances.index') }}" class="row g-3 mb-4 align-items-end">
        <!-- Hidden submit to capture Enter key -->
        <button type="submit" class="d-none" aria-hidden="true"></button>
        <div class="col-md-2">
            <label class="form-label text-dark fw-semibold">Tanggal</label>
            <input type="date" name="date" class="form-control" value="{{ $date }}" onchange="this.form.submit()">
        </div>
        <div class="col-md-2">
            <label class="form-label text-dark fw-semibold">Kategori</label>
            <select name="category" class="form-select" onchange="this.form.submit()">
                <option value="">-- Semua --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ strtoupper($cat->name) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label text-dark fw-semibold">Jabatan/Kelas</label>
            <select name="position" class="form-select" onchange="this.form.submit()">
                <option value="">-- Semua --</option>
                @foreach($positions as $pos)
                    <option value="{{ $pos->id }}" {{ request('position') == $pos->id ? 'selected' : '' }}>
                        {{ $pos->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label text-dark fw-semibold">Status Absen</label>
            <select name="status_filter" class="form-select" onchange="this.form.submit()">
                <option value="">Sudah Absen</option>
                <option value="belum_absen" {{ request('status_filter') == 'belum_absen' ? 'selected' : '' }}>Belum Absen</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label text-dark fw-semibold">Cari Nama</label>
            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Ketik nama..." onchange="this.form.submit()">
        </div>
        <div class="col-md-2">
            <label class="form-label text-dark fw-semibold">Urutkan</label>
            <select name="sort" class="form-select" onchange="this.form.submit()">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Waktu Scan</option>
                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama</option>
            </select>
        </div>
        <div class="col-md-2">
            <a href="{{ route('attendances.index', ['date' => Carbon\Carbon::today()->toDateString()]) }}" class="btn btn-outline-secondary w-100 py-2">Hari Ini</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="text-muted" style="border-bottom: 2px solid #e2e8f0; font-size: 0.85rem;">
                <tr>
                    <th scope="col" style="width: 60px;">Foto</th>
                    <th scope="col">Nomor Induk</th>
                    <th scope="col">Nama Lengkap</th>
                    <th scope="col">Kategori</th>
                    <th scope="col" class="text-center">Jam Masuk</th>
                    <th class="text-center">Jam Keluar</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Ket.</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $attendance)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td>
                            @if($attendance->employee->photo)
                                <img src="{{ route('avatar.serve', $attendance->employee->photo) }}" class="rounded-circle shadow-sm" style="width: 42px; height: 42px; object-fit: cover; border: 2px solid #fff;">
                            @else
                                <img src="{{ asset('images/default-avatar.png') }}" class="rounded-circle shadow-sm" style="width: 42px; height: 42px; border: 2px solid #fff;">
                            @endif
                        </td>
                        <td class="fw-bold text-dark">{{ $attendance->employee->code }}</td>
                        <td class="fw-semibold text-dark">{{ $attendance->employee->name }}</td>
                        <td>
                            <span class="badge bg-{{ $attendance->employee->category->badge_color ?? 'primary' }} text-white px-3 py-2" style="font-size: 0.75rem;">
                                {{ strtoupper($attendance->employee->category->name ?? '-') }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($attendance->check_in)
                                <span class="fw-bold text-success">{{ Carbon\Carbon::parse($attendance->check_in)->format('H:i') }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($attendance->check_out)
                                <span class="fw-bold text-primary">{{ Carbon\Carbon::parse($attendance->check_out)->format('H:i') }}</span>
                            @else
                                <span class="badge bg-light text-muted border">Belum Keluar</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($attendance->status === 'terlambat')
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="bi bi-clock-history me-1"></i> Terlambat</span>
                            @elseif($attendance->status === 'izin')
                                <span class="badge bg-primary text-white px-3 py-2 rounded-pill"><i class="bi bi-info-circle me-1"></i> Izin</span>
                            @elseif($attendance->status === 'sakit')
                                <span class="badge bg-info text-white px-3 py-2 rounded-pill"><i class="bi bi-heart-pulse me-1"></i> Sakit</span>
                            @elseif($attendance->status === 'cuti')
                                <span class="badge bg-secondary text-white px-3 py-2 rounded-pill"><i class="bi bi-cup-hot me-1"></i> Cuti</span>
                            @elseif($attendance->status === 'alpha')
                                <span class="badge bg-danger text-white px-3 py-2 rounded-pill"><i class="bi bi-x-circle me-1"></i> Alpa</span>
                            @elseif($attendance->status === 'tap')
                                <span class="badge bg-danger text-white px-3 py-2 rounded-pill"><i class="bi bi-exclamation-triangle-fill me-1"></i> Tanpa Absen Pulang</span>
                            @elseif($attendance->status === 'belum_absen')
                                <span class="badge bg-secondary text-white px-3 py-2 rounded-pill"><i class="bi bi-dash-circle me-1"></i> Belum Absen</span>
                            @else
                                <span class="badge bg-success text-white px-3 py-2 rounded-pill"><i class="bi bi-check-circle me-1"></i> Tepat Waktu</span>
                            @endif
                        </td>
                        <td class="text-center text-muted fw-semibold">
                            @if($attendance->detected_shift && $attendance->detected_shift !== 'reguler')
                                <span class="badge bg-secondary text-white px-2 py-1" style="font-size: 0.7rem;">Shift {{ ucfirst($attendance->detected_shift) }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            @if($attendance->status !== 'belum_absen')
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-3 btn-edit-attendance" 
                                        data-id="{{ $attendance->id }}"
                                        data-name="{{ $attendance->employee->name }}"
                                        data-date="{{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('d F Y') }}"
                                        data-checkin="{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '' }}"
                                        data-checkout="{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '' }}"
                                        data-status="{{ $attendance->status }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-calendar-x" style="font-size: 3rem; opacity: 0.3;"></i>
                                <p class="mt-3 mb-1 fw-semibold">Belum ada data absensi</p>
                                <small>Tidak ada riwayat kehadiran untuk filter tanggal dan kategori ini.</small>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($attendances->hasPages())
        <div class="mt-4 pt-3 d-flex justify-content-between align-items-center" style="border-top:1px solid #f1f5f9;">
            <small class="text-muted">Menampilkan {{ $attendances->firstItem() }} - {{ $attendances->lastItem() }} dari total {{ $attendances->total() }} data</small>
            {{ $attendances->links() }}
        </div>
    @endif
</div>

<!-- Modal Edit Absensi -->
<div class="modal fade" id="editAttendanceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="editAttendanceForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Data Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info rounded-3 py-2 mb-3">
                        <strong id="editEmpName"></strong><br>
                        <small id="editEmpDate"></small>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold">Jam Masuk</label>
                            <input type="time" name="check_in" id="editCheckIn" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold">Jam Pulang</label>
                            <input type="time" name="check_out" id="editCheckOut" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-dark fw-semibold">Status Kehadiran</label>
                            <select name="status" id="editStatus" class="form-select" required>
                                <option value="hadir">Hadir Tepat Waktu</option>
                                <option value="terlambat">Terlambat</option>
                                <option value="izin">Izin</option>
                                <option value="sakit">Sakit</option>
                                <option value="cuti">Cuti</option>
                                <option value="dl">Dinas Luar (DL)</option>
                                <option value="alpha">Alpa</option>
                                <option value="tap">Tanpa Absen Pulang (TAP)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.btn-edit-attendance');
    const editModal = new bootstrap.Modal(document.getElementById('editAttendanceModal'));
    const editForm = document.getElementById('editAttendanceForm');

    editButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const date = this.getAttribute('data-date');
            const checkIn = this.getAttribute('data-checkin');
            const checkOut = this.getAttribute('data-checkout');
            const status = this.getAttribute('data-status');

            document.getElementById('editEmpName').textContent = name;
            document.getElementById('editEmpDate').textContent = 'Tanggal: ' + date;
            
            document.getElementById('editCheckIn').value = checkIn;
            document.getElementById('editCheckOut').value = checkOut;
            document.getElementById('editStatus').value = status;

            editForm.action = `/attendances/${id}`;

            editModal.show();
        });
    });
});
</script>
@endsection
