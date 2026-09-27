@extends('layouts.app')

@section('content')
<div class="row">
    <!-- Statistik Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="custom-card h-100 d-flex align-items-center justify-content-between">
            <div>
                <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.8rem;">Siswa & Staff Aktif</p>
                <h3 class="m-0 text-dark fw-bold">{{ $totalEmployees }}</h3>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                <i class="bi bi-people-fill" style="font-size: 1.5rem;"></i>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="custom-card h-100 d-flex align-items-center justify-content-between">
            <div>
                <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.8rem;">Hadir Tepat Waktu</p>
                <h3 class="m-0 text-success fw-bold">{{ $totalPresent }}</h3>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(16, 185, 129, 0.1); color: #10b981;">
                <i class="bi bi-patch-check-fill" style="font-size: 1.5rem;"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="custom-card h-100 d-flex align-items-center justify-content-between">
            <div>
                <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.8rem;">Terlambat</p>
                <h3 class="m-0 text-warning fw-bold">{{ $totalLate }}</h3>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.5rem;"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ route('attendances.index', ['status_filter' => 'belum_absen']) }}" class="text-decoration-none d-block h-100">
            <div class="custom-card h-100 d-flex align-items-center justify-content-between" style="cursor: pointer;">
                <div>
                    <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.8rem;">Belum Absen / Alpa</p>
                    <h3 class="m-0 text-danger fw-bold">{{ $totalAbsent }}</h3>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(239, 68, 68, 0.1); color: #ef4444;">
                    <i class="bi bi-x-octagon-fill" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row">
    <!-- Chart Absensi Mingguan -->
    <div class="col-lg-8 mb-4">
        <div class="custom-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="text-dark mb-0"><i class="bi bi-graph-up me-2 text-primary"></i>Tren Kehadiran (7 Hari Terakhir)</h5>
                
                <!-- Tombol Tutup Absen -->
                <form action="{{ route('attendances.close-today') }}" method="POST" onsubmit="confirmAction(this, 'Tutup Absensi Hari Ini?', 'Semua siswa/staff yang BELUM absen akan otomatis tercatat ALPA hari ini. Aksi ini tidak dapat dibatalkan secara massal.', 'Ya, Tutup Absen', 'danger', 'bi-x-octagon', 'danger')">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm px-3 fw-semibold rounded-pill">
                        <i class="bi bi-x-octagon-fill me-1"></i> Tutup Absen Hari Ini
                    </button>
                </form>
            </div>
            
            <div style="position: relative; height:320px;">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Aktivitas Absensi Hari Ini -->
    <div class="col-lg-4 mb-4">
        <div class="custom-card">
            <h5 class="text-dark mb-4"><i class="bi bi-clock-history me-2 text-primary"></i>Aktivitas Hari Ini</h5>
            
            <div class="d-flex flex-column gap-3 overflow-y-auto" style="max-height: 320px;">
                @forelse($recentAttendances as $attendance)
                    <div class="d-flex align-items-center justify-content-between p-2 border-bottom border-secondary border-opacity-10">
                        <div class="d-flex align-items-center">
                            @if($attendance->employee->photo)
                                <img src="{{ route('avatar.serve', $attendance->employee->photo) }}" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; color: white;">
                                    <i class="bi bi-person"></i>
                                </div>
                            @endif
                            <div>
                                <h6 class="m-0 text-dark font-weight-bold" style="font-size: 0.9rem;">{{ $attendance->employee->name }}</h6>
                                <small class="text-muted">{{ strtoupper($attendance->employee->category->name ?? '-') }}</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge {{ $attendance->status == 'terlambat' ? 'bg-warning' : 'bg-success' }} mb-1" style="font-size: 0.75rem;">
                                {{ strtoupper($attendance->status) }}
                            </span>
                            <br>
                            <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ Carbon\Carbon::parse($attendance->check_in)->format('H:i') }}</small>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center my-5">Belum ada aktivitas absensi hari ini.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/chart.min.js') }}"></script>
<script>
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [
                {
                    label: 'Tepat Waktu',
                    data: {!! json_encode($chartDataHadir) !!},
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Terlambat',
                    data: {!! json_encode($chartDataTerlambat) !!},
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: { color: '#94a3b8' }
                }
            },
            scales: {
                y: {
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: { color: '#94a3b8', stepSize: 1 }
                },
                x: {
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: { color: '#94a3b8' }
                }
            }
        }
    });
</script>
@endsection
