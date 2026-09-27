@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-end mb-4">
    <div>
        <h3 class="text-dark fw-bold"><i class="bi bi-calendar2-check-fill me-2 text-primary"></i>Rekap Kehadiran</h3>
        <p class="text-muted m-0">Memantau statistik dan detail absensi masuk dan pulang karyawan.</p>
    </div>
    <!-- Tab Navigasi -->
    <div class="bg-white p-1 rounded-3 shadow-sm border" style="display: inline-flex;">
        <a href="{{ route('attendances.index') }}" class="btn btn-light text-muted px-4 py-2 fw-semibold rounded-2 border-0" style="transition:all 0.2s; background:transparent;">Harian</a>
        <a href="{{ route('attendances.monthly') }}" class="btn btn-primary px-4 py-2 fw-semibold rounded-2" style="transition:all 0.2s;">Bulanan</a>
    </div>
</div>

<div class="custom-card mb-4">
    <form id="filterForm" method="GET" action="{{ route('attendances.monthly') }}" class="row g-3 align-items-end">
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
            <a href="{{ route('attendances.monthly') }}" class="btn btn-outline-secondary btn-sm w-100 py-1">Reset</a>
        </div>
    </form>
</div>

<div class="custom-card">
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
                            $isToday = $dateStr === date('Y-m-d');
                        @endphp
                        <th class="{{ $isToday ? 'bg-primary bg-opacity-10' : '' }}" style="min-width: 35px; width: 35px; font-size: 0.75rem; {{ $isWeekend ? 'color: red;' : '' }}" title="{{ $d->translatedFormat('l, d F Y') }}">
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
                            $isToday = $dateStr === date('Y-m-d');
                            
                            $badgeClass = 'text-muted';
                            $dataStatus = strtolower($status);
                            if($status === 'H') { $badgeClass = 'text-success fw-bold'; $dataStatus = 'hadir'; }
                            if($status === 'T') { $badgeClass = 'text-warning fw-bold'; $dataStatus = 'terlambat'; }
                            if($status === 'I') { $badgeClass = 'text-primary fw-bold'; $dataStatus = 'izin'; }
                            if($status === 'C') { $badgeClass = 'text-primary fw-bold'; $dataStatus = 'cuti'; }
                            if($status === 'DL') { $badgeClass = 'text-primary fw-bold'; $dataStatus = 'dl'; }
                            if($status === 'S') { $badgeClass = 'text-info fw-bold'; $dataStatus = 'sakit'; }
                            if($status === 'A') { $badgeClass = 'text-danger fw-bold'; $dataStatus = 'alpha'; }
                            if($status === 'TAP') { $badgeClass = 'text-success fw-bold'; $dataStatus = 'hadir'; }
                            if($status === '-') { $badgeClass = $isWeekend ? 'text-danger opacity-50' : 'text-muted opacity-25'; $dataStatus = '-'; }
                        @endphp
                        <td class="{{ $badgeClass }} clickable-cell {{ $isToday ? 'bg-primary bg-opacity-10' : '' }}" data-emp-id="{{ $row['employee']->id }}" data-date="{{ $dateStr }}" data-status="{{ $dataStatus }}" style="cursor: pointer;" title="Klik untuk mengubah status">
                            {{ $status }}
                        </td>
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
    
    <div class="mt-4">
        {{ $grid['paginator']->links() }}
    </div>
    
    <!-- Keterangan Legend -->
    <div class="mt-4 pt-3" style="border-top: 1px solid #f1f5f9; font-size: 0.85rem;">
        <h6 class="text-muted fw-bold mb-3"><i class="bi bi-info-circle me-1"></i> Keterangan Status:</h6>
        <div class="row gx-3 gy-2">
            <div class="col-12 col-sm-6 col-md-4 col-lg-3"><div class="d-flex align-items-center"><span class="badge bg-success me-2" style="width: 28px;">H</span> Hadir</div></div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3"><div class="d-flex align-items-center"><span class="badge bg-warning text-dark me-2" style="width: 28px;">T</span> Terlambat</div></div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3"><div class="d-flex align-items-center"><span class="badge bg-primary me-2" style="width: 28px;">I</span> Izin</div></div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3"><div class="d-flex align-items-center"><span class="badge bg-info me-2" style="width: 28px;">S</span> Sakit</div></div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3"><div class="d-flex align-items-center"><span class="badge bg-danger me-2" style="width: 28px;">A</span> Alpa / Tanpa Keterangan</div></div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3"><div class="d-flex align-items-center"><span class="badge bg-secondary me-2" style="width: 28px;">C</span> Cuti</div></div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3"><div class="d-flex align-items-center"><span class="badge bg-dark me-2" style="width: 28px;">DL</span> Dinas Luar</div></div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3"><div class="d-flex align-items-center"><span class="badge bg-success opacity-75 me-2" style="width: 40px;">TAP</span> Tidak Absen Pulang</div></div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3"><div class="d-flex align-items-center"><span class="badge me-2 text-muted" style="width: 28px; background-color: #f8fafc; border: 1px solid #e2e8f0;">-</span> Kosong / Libur</div></div>
        </div>
    </div>
</div>

<!-- Modal Edit Status -->
<div class="modal fade" id="editStatusModal" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-light">
        <h6 class="modal-title fw-bold text-dark"><i class="bi bi-pencil-square me-2 text-primary"></i>Ubah Status</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="edit_emp_id">
        <input type="hidden" id="edit_date">
        <div class="mb-3" id="statusModalInfo" style="font-size: 0.85rem;"></div>
        <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:0.85rem;">Status Kehadiran</label>
            <div class="d-grid gap-2">
                <label class="btn btn-outline-success text-start rounded-3"><input type="radio" name="editStatus" value="hadir" class="me-2"> (H) Hadir</label>
                <label class="btn btn-outline-warning text-dark text-start rounded-3"><input type="radio" name="editStatus" value="terlambat" class="me-2"> (T) Terlambat</label>
                <label class="btn btn-outline-primary text-start rounded-3"><input type="radio" name="editStatus" value="izin" class="me-2"> (I) Izin</label>
                <label class="btn btn-outline-info text-start rounded-3"><input type="radio" name="editStatus" value="sakit" class="me-2"> (S) Sakit</label>
                <label class="btn btn-outline-danger text-start rounded-3"><input type="radio" name="editStatus" value="alpha" class="me-2"> (A) Alpa / Tanpa Keterangan</label>
                <label class="btn btn-outline-secondary text-start rounded-3"><input type="radio" name="editStatus" value="cuti" class="me-2"> (C) Cuti</label>
                <label class="btn btn-outline-dark text-start rounded-3"><input type="radio" name="editStatus" value="dl" class="me-2"> (DL) Dinas Luar</label>
                <label class="btn btn-light border text-start rounded-3"><input type="radio" name="editStatus" value="-" class="me-2"> (-) Kosongkan / Libur</label>
            </div>
        </div>
      </div>
      <div class="modal-footer bg-light p-2">
        <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary btn-sm px-3 fw-semibold" onclick="saveStatus()" id="btnSaveStatus">Simpan</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editModal = new bootstrap.Modal(document.getElementById('editStatusModal'));
    
    document.querySelectorAll('.clickable-cell').forEach(cell => {
        cell.addEventListener('click', function() {
            const empId = this.dataset.empId;
            const date = this.dataset.date;
            const currentStatus = this.dataset.status;
            const empName = this.closest('tr').querySelector('.text-dark').innerText;
            
            document.getElementById('edit_emp_id').value = empId;
            document.getElementById('edit_date').value = date;
            document.getElementById('statusModalInfo').innerHTML = `<strong>${empName}</strong><br>Tanggal: ${date}`;
            
            const radio = document.querySelector(`input[name="editStatus"][value="${currentStatus}"]`);
            if(radio) radio.checked = true;
            
            editModal.show();
        });
    });

    window.saveStatus = function() {
        const btn = document.getElementById('btnSaveStatus');
        const empId = document.getElementById('edit_emp_id').value;
        const date = document.getElementById('edit_date').value;
        const statusRadio = document.querySelector('input[name="editStatus"]:checked');
        
        if (!statusRadio) {
            alert('Pilih status terlebih dahulu!');
            return;
        }
        
        const status = statusRadio.value;
        
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Menyimpan...';
        
        fetch('{{ route('attendances.updateStatus') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                employee_id: empId,
                date: date,
                status: status
            })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Gagal mengubah status');
                btn.disabled = false;
                btn.innerHTML = 'Simpan';
            }
        })
        .catch(err => {
            alert('Terjadi kesalahan jaringan.');
            btn.disabled = false;
            btn.innerHTML = 'Simpan';
        });
    }
});
</script>
@endsection
