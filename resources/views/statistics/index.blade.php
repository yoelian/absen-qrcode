@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-end mb-4">
    <div>
        <h3 class="text-dark fw-bold"><i class="bi bi-pie-chart-fill me-2 text-primary"></i>Statistik Kehadiran</h3>
        <p class="text-muted m-0">Pemantauan tren dan analisis grafik kehadiran bulanan siswa dan staff.</p>
    </div>
</div>

<div class="custom-card mb-4">
    <form method="GET" action="{{ route('statistics.index') }}" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label text-dark fw-semibold">Bulan</label>
            <select name="month" class="form-select" onchange="this.form.submit()">
                @for($m=1; $m<=12; ++$m)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label text-dark fw-semibold">Tahun</label>
            <select name="year" class="form-select" onchange="this.form.submit()">
                @for($y=date('Y')-2; $y<=date('Y'); ++$y)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label text-dark fw-semibold">Kategori</label>
            <select name="category_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- Semua Kategori --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                        {{ strtoupper($cat->name) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <a href="{{ route('statistics.index') }}" class="btn btn-outline-secondary w-100 py-2">Reset Filter</a>
        </div>
    </form>
</div>

<!-- Kartu Persentase -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="custom-card p-4 text-center h-100 d-flex flex-column justify-content-center" style="border-bottom: 4px solid #10b981;">
            <h6 class="text-muted mb-2">Hadir Tepat Waktu</h6>
            <h2 class="fw-bold m-0 text-success">{{ $percentages['hadir'] }}%</h2>
            <small class="text-muted mt-1">{{ $totalHadir }} data</small>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="custom-card p-4 text-center h-100 d-flex flex-column justify-content-center" style="border-bottom: 4px solid #f59e0b;">
            <h6 class="text-muted mb-2">Terlambat</h6>
            <h2 class="fw-bold m-0 text-warning">{{ $percentages['terlambat'] }}%</h2>
            <small class="text-muted mt-1">{{ $totalTerlambat }} data</small>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="custom-card p-4 text-center h-100 d-flex flex-column justify-content-center" style="border-bottom: 4px solid #3b82f6;">
            <h6 class="text-muted mb-2">Izin & Sakit</h6>
            <h2 class="fw-bold m-0 text-primary">{{ $percentages['izin_sakit'] }}%</h2>
            <small class="text-muted mt-1">{{ $totalIzinSakit }} data</small>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="custom-card p-4 text-center h-100 d-flex flex-column justify-content-center" style="border-bottom: 4px solid #ef4444;">
            <h6 class="text-muted mb-2">Alpa / Belum Absen</h6>
            <h2 class="fw-bold m-0 text-danger">{{ $percentages['alpa'] }}%</h2>
            <small class="text-muted mt-1">{{ $totalAlpa }} data</small>
        </div>
    </div>
</div>

<div class="row">
    <!-- Line Chart: Tren Harian -->
    <div class="col-lg-8 mb-4">
        <div class="custom-card h-100">
            <h5 class="text-dark mb-4"><i class="bi bi-graph-up-arrow me-2 text-primary"></i>Tren Harian Bulan Ini</h5>
            <div style="position: relative; height: 350px;">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Doughnut Chart: Distribusi Total -->
    <div class="col-lg-4 mb-4">
        <div class="custom-card h-100">
            <h5 class="text-dark mb-4"><i class="bi bi-pie-chart me-2 text-primary"></i>Distribusi Kehadiran</h5>
            <div style="position: relative; height: 300px; display: flex; justify-content: center;">
                <canvas id="distributionChart"></canvas>
            </div>
            <div class="mt-4 text-center">
                <small class="text-muted d-block mb-1"><span class="d-inline-block rounded-circle me-1" style="width:10px;height:10px;background:#10b981;"></span> Hadir: {{ $totalHadir }}</small>
                <small class="text-muted d-block mb-1"><span class="d-inline-block rounded-circle me-1" style="width:10px;height:10px;background:#f59e0b;"></span> Terlambat: {{ $totalTerlambat }}</small>
                <small class="text-muted d-block mb-1"><span class="d-inline-block rounded-circle me-1" style="width:10px;height:10px;background:#3b82f6;"></span> Izin/Sakit: {{ $totalIzinSakit }}</small>
                <small class="text-muted d-block"><span class="d-inline-block rounded-circle me-1" style="width:10px;height:10px;background:#ef4444;"></span> Alpa: {{ $totalAlpa }}</small>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('assets/js/chart.min.js') }}"></script>
<script>
    // Konfigurasi Line Chart
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [
                {
                    label: 'Hadir',
                    data: {!! json_encode($dataHadir) !!},
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.3,
                    fill: false
                },
                {
                    label: 'Terlambat',
                    data: {!! json_encode($dataTerlambat) !!},
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    tension: 0.3,
                    fill: false
                },
                {
                    label: 'Izin/Sakit',
                    data: {!! json_encode($dataIzinSakit) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.3,
                    fill: false
                },
                {
                    label: 'Alpa',
                    data: {!! json_encode($dataAlpa) !!},
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.3,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', labels: { color: '#64748b', boxWidth: 12 } }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: { color: '#64748b', stepSize: 5 }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748b' }
                }
            }
        }
    });

    // Konfigurasi Doughnut Chart
    const distCtx = document.getElementById('distributionChart').getContext('2d');
    new Chart(distCtx, {
        type: 'doughnut',
        data: {
            labels: ['Hadir', 'Terlambat', 'Izin/Sakit', 'Alpa'],
            datasets: [{
                data: [{{ $totalHadir }}, {{ $totalTerlambat }}, {{ $totalIzinSakit }}, {{ $totalAlpa }}],
                backgroundColor: [
                    '#10b981',
                    '#f59e0b',
                    '#3b82f6',
                    '#ef4444'
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endsection
