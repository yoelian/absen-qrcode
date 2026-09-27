@extends('layouts.app')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h3 class="text-dark fw-bold"><i class="bi bi-file-earmark-bar-graph-fill me-2 text-primary"></i>Laporan Kehadiran Grid</h3>
        <p class="text-muted m-0">Tabel dinamis absensi harian, mingguan, hingga bulanan.</p>
    </div>
    
    <!-- Tombol Cetak dari Form -->
    <div class="d-flex gap-2">
        <button type="submit" form="filterForm" name="format" value="pdf" class="btn btn-danger fw-semibold px-4 rounded-3 shadow-sm d-flex align-items-center" formaction="{{ route('reports.generate') }}">
            <i class="bi bi-file-earmark-pdf-fill me-2"></i>Cetak PDF
        </button>
        <button type="submit" form="filterForm" name="format" value="excel" class="btn btn-success fw-semibold px-4 rounded-3 shadow-sm d-flex align-items-center" formaction="{{ route('reports.generate') }}">
            <i class="bi bi-file-earmark-excel-fill me-2"></i>Cetak Excel
        </button>
    </div>
</div>

<div class="custom-card mb-4">
    <form id="filterForm" method="GET" action="{{ route('reports.index') }}" class="row g-3 align-items-end">
        <!-- Hidden submit to capture Enter key -->
        <button type="submit" class="d-none" aria-hidden="true"></button>
        <div class="col-md-2">
            <label class="form-label text-dark fw-semibold" style="font-size: 0.85rem;">Cari Nama</label>
            <input type="text" name="search" class="form-control form-control-sm" value="{{ request('search') }}" placeholder="Ketik nama..." onchange="this.form.submit()">
        </div>
        <div class="col-md-2">
            <label class="form-label text-dark fw-semibold" style="font-size: 0.85rem;">Mulai Tanggal</label>
            <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate }}" onchange="this.form.submit()">
        </div>
        <div class="col-md-2">
            <label class="form-label text-dark fw-semibold" style="font-size: 0.85rem;">Sampai Tanggal</label>
            <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}" onchange="this.form.submit()">
        </div>
        <div class="col-md-2">
            <label class="form-label text-dark fw-semibold" style="font-size: 0.85rem;">Kategori</label>
            <select name="category_id" class="form-select form-select-sm" onchange="document.querySelector('select[name=position_id]').value=''; this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                        {{ strtoupper($cat->name) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label text-dark fw-semibold" style="font-size: 0.85rem;">Kelas / Jabatan</label>
            <select name="position_id" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                @foreach($positions as $pos)
                    <option value="{{ $pos->id }}" {{ $positionId == $pos->id ? 'selected' : '' }}>
                        {{ strtoupper($pos->name) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label text-dark fw-semibold" style="font-size: 0.85rem;">Tampilkan</label>
            <select name="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 Baris</option>
                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Baris</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Baris</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Baris</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label text-dark fw-semibold" style="font-size: 0.85rem;">Tipe Laporan</label>
            <select name="tipe_laporan" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="grid" {{ request('tipe_laporan') == 'grid' || !request()->has('tipe_laporan') ? 'selected' : '' }}>Grid Rekapitulasi</option>
                <option value="detail" {{ request('tipe_laporan') == 'detail' ? 'selected' : '' }}>Detail Harian (Jam)</option>
            </select>
        </div>
        <div class="col-md-2">
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm w-100 py-1">Reset</a>
        </div>
    </form>
</div>

<div class="custom-card">
    @if($tipeLaporan === 'grid')
        <div class="table-responsive" style="overflow-x: auto;">
            <table class="table table-bordered table-hover align-middle mb-0 text-center" style="min-width: {{ 300 + ($daysDiff * 35) }}px; font-size: 0.85rem;">
                <thead class="table-light">
                    <tr>
                        <th rowspan="2" class="align-middle text-start" style="width: 250px; min-width: 200px; position: sticky; left: 0; background-color: #f8f9fa; z-index: 2;">Nama & Induk</th>
                        <th colspan="{{ $daysDiff }}" class="text-center">Tanggal ({{ Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ Carbon\Carbon::parse($endDate)->format('d M Y') }})</th>
                        <th colspan="8" class="text-center bg-light">Rekapitulasi</th>
                    </tr>
                    <tr>
                        @foreach($dates as $dateStr)
                            @php 
                                $d = Carbon\Carbon::parse($dateStr);
                                $isWeekend = $d->isWeekend();
                            @endphp
                            <th style="min-width: 35px; width: 35px; font-size: 0.75rem; {{ $isWeekend ? 'color: red;' : '' }}" title="{{ $d->translatedFormat('l, d F Y') }}">
                                {{ $d->format('d') }}<br>
                                <span style="font-size: 0.65rem; font-weight: normal;">{{ substr($d->translatedFormat('l'), 0, 3) }}</span>
                            </th>
                        @endforeach
                        <!-- Rekap Headers -->
                        <th style="width: 35px;" title="Hadir" class="bg-light">H</th>
                        <th style="width: 35px;" title="Terlambat (Dihitung Hadir)" class="bg-light">T</th>
                        <th style="width: 35px;" title="Izin" class="bg-light">I</th>
                        <th style="width: 35px;" title="Sakit" class="bg-light">S</th>
                        <th style="width: 35px;" title="Alpa" class="bg-light">A</th>
                        <th style="width: 35px;" title="Cuti" class="bg-light">C</th>
                        <th style="width: 35px;" title="Dinas Luar" class="bg-light">DL</th>
                        <th style="width: 35px;" title="Tidak Absen Pulang (Dihitung Hadir)" class="bg-light">TAP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grid['items'] as $row)
                    <tr>
                        <td class="text-start" style="position: sticky; left: 0; background-color: #fff; z-index: 1; border-right: 2px solid #dee2e6;">
                            <div class="fw-bold text-dark">{{ $row['employee']->name }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">
                                {{ $row['employee']->code }} &bull; {{ $row['employee']->position->name ?? '-' }}
                            </div>
                        </td>
                        @foreach($dates as $dateStr)
                            @php 
                                $status = $row['days'][$dateStr]; 
                                $isWeekend = Carbon\Carbon::parse($dateStr)->isWeekend();
                                
                                $badgeClass = 'text-muted';
                                if($status === 'H') $badgeClass = 'text-success fw-bold';
                                if($status === 'T') $badgeClass = 'text-warning fw-bold';
                                if($status === 'I' || $status === 'C' || $status === 'DL') $badgeClass = 'text-primary fw-bold';
                                if($status === 'S') $badgeClass = 'text-info fw-bold';
                                if($status === 'A') $badgeClass = 'text-danger fw-bold';
                                if($status === 'TAP') $badgeClass = 'text-success fw-bold';
                                if($status === '-') $badgeClass = $isWeekend ? 'text-danger opacity-50' : 'text-muted opacity-25';
                            @endphp
                            <td class="{{ $badgeClass }}">{{ $status }}</td>
                        @endforeach
                        
                        <td class="bg-light fw-bold text-success">{{ $row['summary']['H'] }}</td>
                        <td class="bg-light text-warning">{{ $row['summary']['T'] > 0 ? $row['summary']['T'] : '-' }}</td>
                        <td class="bg-light text-primary">{{ $row['summary']['I'] > 0 ? $row['summary']['I'] : '-' }}</td>
                        <td class="bg-light text-info">{{ $row['summary']['S'] > 0 ? $row['summary']['S'] : '-' }}</td>
                        <td class="bg-light text-danger">{{ $row['summary']['A'] > 0 ? $row['summary']['A'] : '-' }}</td>
                        <td class="bg-light text-primary">{{ $row['summary']['C'] > 0 ? $row['summary']['C'] : '-' }}</td>
                        <td class="bg-light text-primary">{{ $row['summary']['DL'] > 0 ? $row['summary']['DL'] : '-' }}</td>
                        <td class="bg-light text-success">{{ $row['summary']['TAP'] > 0 ? $row['summary']['TAP'] : '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $daysDiff + 9 }}" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                            Belum ada data untuk kriteria tersebut.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light text-muted" style="border-bottom: 2px solid #e2e8f0; font-size: 0.85rem;">
                    <tr>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Nomor Induk</th>
                        <th scope="col">Nama Lengkap</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Kelas / Jabatan</th>
                        <th scope="col" class="text-center">Jam Masuk</th>
                        <th scope="col" class="text-center">Jam Keluar</th>
                        <th scope="col" class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}</td>
                            <td class="fw-bold text-dark">{{ $attendance->employee->code }}</td>
                            <td class="fw-semibold text-dark">{{ $attendance->employee->name }}</td>
                            <td>{{ strtoupper($attendance->employee->category->name ?? '-') }}</td>
                            <td>{{ $attendance->employee->position->name ?? '-' }}</td>
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
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($attendance->status === 'hadir')
                                    <span class="badge bg-success">HADIR</span>
                                @elseif($attendance->status === 'terlambat')
                                    <span class="badge bg-warning text-dark">TERLAMBAT</span>
                                @else
                                    <span class="badge bg-secondary">{{ strtoupper($attendance->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                Belum ada data untuk kriteria tersebut.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
    
    <div class="mt-4">
        @if($tipeLaporan === 'grid')
            {{ $grid['paginator']->links() }}
        @else
            {{ $attendances->links() }}
        @endif
    </div>
</div>
@endsection
