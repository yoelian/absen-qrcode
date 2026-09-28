@extends('layouts.app')

@section('content')
<!-- Hero Welcome Banner -->
<div class="row mb-4">
    <div class="col-12">
        <div class="p-4 rounded-4 position-relative overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0284c7 100%); color: #ffffff; box-shadow: 0 15px 30px -10px rgba(2, 132, 199, 0.25);">
            <!-- Background Decorative Glows -->
            <div style="position: absolute; right: -20px; top: -20px; width: 180px; height: 180px; background: rgba(56, 189, 248, 0.2); filter: blur(50px); border-radius: 50%;"></div>
            <div style="position: absolute; right: 15%; bottom: -30px; width: 140px; height: 140px; background: rgba(129, 140, 248, 0.2); filter: blur(40px); border-radius: 50%;"></div>
            
            <div class="position-relative z-1 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-2" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(8px); font-size: 0.78rem; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase;">
                        <i class="bi bi-shield-check text-info"></i> {{ auth()->user()->role ?? 'Admin' }} Panel
                    </div>
                    <h3 class="fw-bold mb-1">Selamat Datang, {{ auth()->user()->name ?? 'Administrator' }}! 👋</h3>
                    <p class="mb-0 text-slate-300" style="color: #cbd5e1; font-size: 0.92rem;">
                        <i class="bi bi-calendar3 me-1 text-info"></i> {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }} — Pantau absensi dan aktivitas harian secara real-time.
                    </p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('scan') }}" class="btn btn-light fw-bold px-3 py-2 rounded-pill shadow-sm d-flex align-items-center gap-2" style="color: #0f172a;">
                        <i class="bi bi-qr-code-scan text-primary"></i>
                        <span>Buka Scanner</span>
                    </a>
                    <a href="{{ route('leaves.create') }}" class="btn btn-outline-light fw-semibold px-3 py-2 rounded-pill d-flex align-items-center gap-2" style="border-color: rgba(255, 255, 255, 0.3);">
                        <i class="bi bi-file-earmark-plus"></i>
                        <span>Input Izin / Cuti</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Modern Cards -->
<div class="row g-3 mb-4">
    <!-- Card 1: Siswa & Staff Aktif -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-modern h-100">
            <div>
                <p class="text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Siswa & Staff Aktif</p>
                <h3 class="m-0 text-dark fw-extrabold" style="font-weight: 800; letter-spacing: -0.5px;">{{ $totalEmployees }}</h3>
                <small class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-person-check me-1 text-primary"></i>Terdaftar di sistem</small>
            </div>
            <div class="stat-icon-wrapper" style="background: rgba(2, 132, 199, 0.1); color: #0284c7;">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>
    </div>
    
    <!-- Card 2: Hadir Tepat Waktu -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-modern h-100">
            <div>
                <p class="text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Hadir Tepat Waktu</p>
                <h3 class="m-0 text-success fw-extrabold" style="font-weight: 800; letter-spacing: -0.5px;">{{ $totalPresent }}</h3>
                <small class="text-success" style="font-size: 0.75rem;"><i class="bi bi-check-circle-fill me-1"></i>Sesuai jadwal masuk</small>
            </div>
            <div class="stat-icon-wrapper" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                <i class="bi bi-patch-check-fill"></i>
            </div>
        </div>
    </div>

    <!-- Card 3: Terlambat -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-modern h-100">
            <div>
                <p class="text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Terlambat</p>
                <h3 class="m-0 text-warning fw-extrabold" style="font-weight: 800; letter-spacing: -0.5px;">{{ $totalLate }}</h3>
                <small class="text-warning" style="font-size: 0.75rem;"><i class="bi bi-clock-history me-1"></i>Melewati batas toleransi</small>
            </div>
            <div class="stat-icon-wrapper" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
        </div>
    </div>

    <!-- Card 4: Belum Absen / Alpa -->
    <div class="col-xl-3 col-md-6">
        <a href="{{ route('attendances.index', ['status_filter' => 'belum_absen']) }}" class="text-decoration-none d-block h-100">
            <div class="stat-card-modern h-100" style="cursor: pointer; border-color: rgba(239, 68, 68, 0.2);">
                <div>
                    <p class="text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Belum Absen / Alpa</p>
                    <h3 class="m-0 text-danger fw-extrabold" style="font-weight: 800; letter-spacing: -0.5px;">{{ $totalAbsent }}</h3>
                    <small class="text-danger" style="font-size: 0.75rem;"><i class="bi bi-arrow-right-short"></i>Klik untuk filter data</small>
                </div>
                <div class="stat-icon-wrapper" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                    <i class="bi bi-x-octagon-fill"></i>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Chart Absensi Mingguan -->
    <div class="col-lg-8">
        <div class="custom-card h-100 mb-0">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h5 class="text-dark fw-bold mb-1">
                        <i class="bi bi-graph-up-arrow me-2 text-primary"></i>Tren Kehadiran (7 Hari Terakhir)
                    </h5>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">Statistik perbandingan hadir tepat waktu vs terlambat</p>
                </div>
                
                <!-- Tombol Tutup Absen -->
                <form action="{{ route('attendances.close-today') }}" method="POST" onsubmit="confirmAction(this, 'Tutup Absensi Hari Ini?', 'Semua siswa/staff yang BELUM absen akan otomatis tercatat ALPA hari ini. Aksi ini tidak dapat dibatalkan secara massal.', 'Ya, Tutup Absen', 'danger', 'bi-x-octagon', 'danger')">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm px-3 py-2 fw-semibold rounded-pill d-flex align-items-center gap-1 shadow-sm">
                        <i class="bi bi-x-octagon-fill"></i> Tutup Absen Hari Ini
                    </button>
                </form>
            </div>
            
            <div style="position: relative; height: 320px;">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Aktivitas Absensi Hari Ini -->
    <div class="col-lg-4">
        <div class="custom-card h-100 mb-0 d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="text-dark fw-bold mb-0">
                    <i class="bi bi-clock-history me-2 text-primary"></i>Aktivitas Hari Ini
                </h5>
                <a href="{{ route('attendances.index') }}" class="btn btn-sm btn-link text-decoration-none fw-semibold p-0" style="font-size: 0.82rem; color: #0284c7;">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            
            <div class="flex-grow-1 overflow-y-auto pe-1" style="max-height: 330px;">
                @forelse($recentAttendances as $attendance)
                    <div class="d-flex align-items-center justify-content-between p-2 mb-2 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0; transition: background 0.2s ease;">
                        <div class="d-flex align-items-center gap-3">
                            @if($attendance->employee->photo)
                                <img src="{{ route('avatar.serve', $attendance->employee->photo) }}" class="rounded-circle shadow-sm" style="width: 42px; height: 42px; object-fit: cover; border: 2px solid #e2e8f0;">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 42px; height: 42px; background: linear-gradient(135deg, #64748b 0%, #475569 100%); font-size: 1rem;">
                                    {{ strtoupper(substr($attendance->employee->name ?? 'A', 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <h6 class="m-0 text-dark fw-bold text-truncate" style="font-size: 0.88rem; max-width: 140px;">{{ $attendance->employee->name }}</h6>
                                <span class="badge bg-light text-secondary border px-2 py-0" style="font-size: 0.68rem; font-weight: 600;">
                                    {{ strtoupper($attendance->employee->category->name ?? '-') }}
                                </span>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge {{ $attendance->status == 'terlambat' ? 'bg-warning text-dark' : 'bg-success' }} mb-1 shadow-sm" style="font-size: 0.7rem;">
                                {{ strtoupper($attendance->status) }}
                            </span>
                            <br>
                            <small class="text-muted fw-semibold" style="font-size: 0.75rem;">
                                <i class="bi bi-clock me-1 text-primary"></i>{{ Carbon\Carbon::parse($attendance->check_in)->format('H:i') }}
                            </small>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" style="width: 56px; height: 56px; background: #f1f5f9; color: #94a3b8; font-size: 1.5rem;">
                            <i class="bi bi-inbox"></i>
                        </div>
                        <p class="text-muted small mb-0">Belum ada aktivitas absensi hari ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/chart.min.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const canvas = document.getElementById('attendanceChart');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        
        // Create Gradients for area fill
        const gradientHadir = ctx.createLinearGradient(0, 0, 0, 300);
        gradientHadir.addColorStop(0, 'rgba(16, 185, 129, 0.25)');
        gradientHadir.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

        const gradientTerlambat = ctx.createLinearGradient(0, 0, 0, 300);
        gradientTerlambat.addColorStop(0, 'rgba(245, 158, 11, 0.25)');
        gradientTerlambat.addColorStop(1, 'rgba(245, 158, 11, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [
                    {
                        label: 'Hadir Tepat Waktu',
                        data: {!! json_encode($chartDataHadir) !!},
                        borderColor: '#10b981',
                        backgroundColor: gradientHadir,
                        borderWidth: 3,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.35,
                        fill: true
                    },
                    {
                        label: 'Terlambat',
                        data: {!! json_encode($chartDataTerlambat) !!},
                        borderColor: '#f59e0b',
                        backgroundColor: gradientTerlambat,
                        borderWidth: 3,
                        pointBackgroundColor: '#f59e0b',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.35,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            padding: 16,
                            color: '#475569',
                            font: { family: "'Outfit', 'Inter', sans-serif", size: 12, weight: '600' }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleColor: '#f8fafc',
                        bodyColor: '#cbd5e1',
                        padding: 12,
                        cornerRadius: 10,
                        titleFont: { weight: 'bold' }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(226, 232, 240, 0.6)' },
                        ticks: { color: '#64748b', font: { family: "'Outfit', sans-serif" }, stepSize: 1 }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#64748b', font: { family: "'Outfit', sans-serif" } }
                    }
                }
            }
        });
    });
</script>
@endsection

