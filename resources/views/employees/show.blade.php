@extends('layouts.app')

@section('content')
<div class="mb-4">
    <a href="{{ session('employee_list_url', route('employees.index')) }}" class="text-decoration-none text-muted"><i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar</a>
    <h3 class="text-dark fw-bold mt-2"><i class="bi bi-person-badge-fill me-2 text-info"></i>Profil & Cetak Barcode</h3>
</div>

<div class="row">
    <!-- Profil Detail -->
    <div class="col-lg-7">
        <div class="custom-card h-100 d-flex flex-column justify-content-between">
            <div class="row align-items-start">
                <div class="col-md-4 text-center mb-3 mb-md-0">
                    @if($employee->photo)
                        <img src="{{ route('avatar.serve', $employee->photo) }}" class="img-fluid rounded-4 border border-secondary border-opacity-50" style="max-height: 180px; object-fit: cover;">
                    @else
                        <img src="{{ asset('images/default-avatar.png') }}" class="img-fluid rounded-4" style="max-height: 180px; object-fit: cover;">
                    @endif
                </div>
                <div class="col-md-8">
                    <span class="badge badge-category bg-{{ $employee->category->badge_color ?? 'primary' }} text-white mb-2">
                        {{ strtoupper($employee->category->name ?? '') }}
                    </span>
                    <h2 class="text-dark fw-bold m-0 mb-1">{{ $employee->name }}</h2>
                    <h5 class="text-muted m-0 mb-3">{{ $employee->position->name ?? '-' }}</h5>

                    <table class="table table-borderless text-dark m-0">
                        <tr>
                            <td class="text-muted ps-0" style="width: 140px;">Nomor Induk</td>
                            <td>: <span class="fw-bold">{{ $employee->code }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">Status Keaktifan</td>
                            <td>: 
                                @if($employee->is_active)
                                    <span class="badge bg-success">AKTIF</span>
                                @else
                                    <span class="badge bg-danger">NONAKTIF</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">Tanggal Terdaftar</td>
                            <td>: {{ $employee->created_at->translatedFormat('d F Y') }}</td>
                        </tr>
                    </table>
                    
                    @php
                        $totalDays = $summary['hadir'] + $summary['izin'] + $summary['sakit'] + $summary['alpha'] + $summary['cuti'] + $summary['dl'];
                        $pHadir = $totalDays > 0 ? ($summary['hadir'] / $totalDays) * 100 : 0;
                        $pSakit = $totalDays > 0 ? ($summary['sakit'] / $totalDays) * 100 : 0;
                        $pAlpa = $totalDays > 0 ? ($summary['alpha'] / $totalDays) * 100 : 0;
                        $pLainnya = $totalDays > 0 ? (($summary['izin'] + $summary['cuti'] + $summary['dl']) / $totalDays) * 100 : 0;
                    @endphp
                    <div class="bg-light p-2 rounded-3 border shadow-sm mt-3 me-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted fw-bold" style="font-size: 0.75rem;"><i class="bi bi-bar-chart-fill me-1 text-primary"></i>Grafik Kehadiran ({{ ucfirst($period) }})</span>
                            <span class="text-dark fw-bold" style="font-size: 0.75rem;">{{ $totalDays }} Hari</span>
                        </div>
                        <div style="height: 180px; position: relative;" class="d-flex justify-content-center my-3">
                            <canvas id="attendanceDoughnutChart"></canvas>
                        </div>
                        @if($totalDays > 0)
                        <div class="d-flex justify-content-between mt-2 px-2" style="font-size: 0.75rem;">
                            <span class="text-success fw-bold"><i class="bi bi-circle-fill me-1"></i> {{ round($pHadir) }}% Hadir</span>
                            @if(round($pAlpa) > 0)
                                <span class="text-danger fw-bold"><i class="bi bi-circle-fill me-1"></i> {{ round($pAlpa) }}% Alpa</span>
                            @else
                                <span class="text-muted"><i class="bi bi-hand-thumbs-up-fill text-warning me-1"></i> Performa Bagus</span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="pt-4 border-top border-secondary border-opacity-25 mt-4 d-flex align-items-center justify-content-between">
                <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning px-4 py-2 rounded-3 text-nowrap"><i class="bi bi-pencil me-1"></i>Edit Data</a>
            </div>
        </div>
    </div>

    <!-- Cetak Kartu Identitas & QR Code -->
    <div class="col-lg-5">
        <div class="custom-card text-center" id="printableCardArea">
            <h5 class="text-dark mb-4"><i class="bi bi-card-image me-2 text-primary"></i>Preview Kartu Identitas</h5>
            
            <!-- Desain ID Card Premium -->
            <div class="card-identity mx-auto p-4 rounded-4 text-start mb-4" style="width: 320px; background: linear-gradient(135deg, #1e293b, #0f172a); border: 2px solid #3b82f6; position: relative; box-shadow: 0 15px 30px rgba(0,0,0,0.5);">
                
                <!-- Kop Sekolah -->
                <div class="d-flex align-items-center mb-3 pb-2 border-bottom border-secondary border-opacity-50">
                    <i class="bi bi-bank me-2 text-primary" style="font-size: 1.3rem;"></i>
                    <div>
                        <h6 class="m-0 text-white fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">SMAN 1 BENTIAN BESAR</h6>
                        <small class="text-white-50" style="font-size: 0.6rem;">Kartu Absensi AbsenPro</small>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <!-- Foto -->
                    @if($employee->photo)
                        <img src="{{ route('avatar.serve', $employee->photo) }}" style="width: 70px; height: 90px; object-fit: cover; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2);">
                    @else
                        <img src="{{ asset('images/default-avatar.png') }}" style="width: 70px; height: 90px; object-fit: cover; border-radius: 6px;">
                    @endif

                    <!-- Informasi -->
                    <div style="flex: 1; min-width: 0;">
                        <h6 class="m-0 text-white fw-bold text-truncate" style="font-size: 0.9rem;" title="{{ $employee->name }}">{{ $employee->name }}</h6>
                        <small class="text-info d-block fw-semibold text-truncate" style="font-size: 0.7rem; margin-bottom: 4px;">{{ strtoupper($employee->category->name ?? '') }}</small>
                        <small class="text-white-50 d-block text-truncate" style="font-size: 0.65rem;">ID: {{ $employee->code }}</small>
                        <small class="text-white-50 d-block text-truncate" style="font-size: 0.65rem;">Posisi: {{ $employee->position->name ?? '-' }}</small>
                    </div>
                </div>

                <!-- Barcode & QR Area -->
                <div class="mt-4 text-center p-2 bg-white rounded-3">
                    <!-- 1D Barcode Code128 -->
                    <div class="mb-2">
                        {!! DNS1D::getBarcodeHTML($employee->code, 'C128', 1.8, 38, 'black', true) !!}
                    </div>
                    <!-- QR Code -->
                    <div class="d-inline-block">
                        {!! DNS2D::getBarcodeHTML($employee->code, 'QRCODE', 3.5, 3.5, 'black') !!}
                    </div>
                </div>
            </div>

            <!-- Tombol Cetak / Simpan Gambar -->
            <button onclick="window.print()" class="btn btn-outline-light w-100 py-2 mb-3"><i class="bi bi-printer me-2"></i>Cetak Kartu Absensi</button>
            
            <div class="d-flex gap-2">
                <a download="Barcode_{{ $employee->code }}.png" href="data:image/png;base64,{{ DNS1D::getBarcodePNG($employee->code, 'C128', 2, 60, array(0,0,0), true) }}" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="bi bi-upc-scan me-1"></i> Download Barcode
                </a>
                <a download="QRCode_{{ $employee->code }}.png" href="data:image/png;base64,{{ DNS2D::getBarcodePNG($employee->code, 'QRCODE', 10, 10) }}" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="bi bi-qr-code me-1"></i> Download QR Code
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="text-dark fw-bold m-0"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat & Rekap Absensi</h4>
            <form method="GET" action="{{ route('employees.show', $employee->id) }}" class="d-flex gap-2">
                <select name="period" class="form-select" onchange="this.form.submit()" style="min-width: 150px;">
                    <option value="day" {{ $period == 'day' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="all" {{ $period == 'all' ? 'selected' : '' }}>Semua Waktu</option>
                </select>
                <select name="per_page" class="form-select" onchange="this.form.submit()" style="min-width: 100px;">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 Baris</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Baris</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Baris</option>
                </select>
                <a href="{{ route('employees.export.pdf', ['employee' => $employee->id, 'period' => $period]) }}" class="btn btn-danger text-white ms-2" title="Download PDF">
                    <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                </a>
            </form>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="custom-card text-center py-3" style="border-bottom: 4px solid #10b981;">
                    <h6 class="text-muted mb-2">Hadir & Tepat</h6>
                    <h3 class="fw-bold text-dark m-0">
                        {{ $summary['hadir'] }}
                    </h3>
                    @if($summary['tap'] > 0)
                        <div class="mt-1"><span class="badge bg-warning text-dark" style="font-size: 0.65rem;">{{ $summary['tap'] }} Lupa Pulang (TAP)</span></div>
                    @endif
                </div>
            </div>
            <div class="col-md-3">
                <div class="custom-card text-center py-3" style="border-bottom: 4px solid #3b82f6;">
                    <h6 class="text-muted mb-2">Izin / Dinas</h6>
                    <h3 class="fw-bold text-dark m-0">{{ $summary['izin'] + $summary['dl'] }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="custom-card text-center py-3" style="border-bottom: 4px solid #8b5cf6;">
                    <h6 class="text-muted mb-2">Sakit / Cuti</h6>
                    <h3 class="fw-bold text-dark m-0">{{ $summary['sakit'] + $summary['cuti'] }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="custom-card text-center py-3" style="border-bottom: 4px solid #ef4444;">
                    <h6 class="text-muted mb-2">Alpa</h6>
                    <h3 class="fw-bold text-dark m-0">{{ $summary['alpha'] }}</h3>
                </div>
            </div>
        </div>

        <div class="custom-card p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3">Tanggal</th>
                            <th class="py-3">Jam Masuk</th>
                            <th class="py-3">Jam Pulang</th>
                            <th class="py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendancesList as $att)
                            <tr>
                                <td class="ps-4 py-3 fw-semibold text-dark">{{ \Carbon\Carbon::parse($att->date)->translatedFormat('l, d M Y') }}</td>
                                <td class="py-3">
                                    @if($att->check_in)
                                        <span class="badge bg-light text-dark border px-2 py-1"><i class="bi bi-box-arrow-in-right me-1 text-success"></i>{{ \Carbon\Carbon::parse($att->check_in)->format('H:i') }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="py-3">
                                    @if($att->check_out)
                                        <span class="badge bg-light text-dark border px-2 py-1"><i class="bi bi-box-arrow-left me-1 text-danger"></i>{{ \Carbon\Carbon::parse($att->check_out)->format('H:i') }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="py-3">
                                    @php
                                        $badgeClass = 'secondary';
                                        if($att->status == 'hadir' || $att->status == 'tepat waktu') $badgeClass = 'success';
                                        if($att->status == 'terlambat') $badgeClass = 'warning text-dark';
                                        if($att->status == 'izin' || $att->status == 'dl') $badgeClass = 'primary';
                                        if($att->status == 'sakit' || $att->status == 'cuti') $badgeClass = 'info text-dark';
                                        if($att->status == 'alpha' || $att->status == 'tap') $badgeClass = 'danger';
                                    @endphp
                                    <span class="badge bg-{{ $badgeClass }} px-2 py-1">{{ strtoupper($att->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>
                                    Tidak ada data kehadiran untuk periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($attendancesList->hasPages())
        <div class="mt-4">
            {{ $attendancesList->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #printableCardArea, #printableCardArea * {
            visibility: visible;
        }
        #printableCardArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .btn {
            display: none !important;
        }
    }
</style>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/chart.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('attendanceDoughnutChart');
    if(ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Izin/Cuti/DL', 'Sakit', 'Alpa'],
                datasets: [{
                    data: [
                        {{ $summary['hadir'] }},
                        {{ $summary['izin'] + $summary['cuti'] + $summary['dl'] }},
                        {{ $summary['sakit'] }},
                        {{ $summary['alpha'] }}
                    ],
                    backgroundColor: [
                        '#10b981', // success
                        '#3b82f6', // primary
                        '#0ea5e9', // info
                        '#ef4444'  // danger
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
                    legend: {
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            font: { size: 11 }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endsection
