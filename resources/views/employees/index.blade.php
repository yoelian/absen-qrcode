@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark fw-bold"><i class="bi bi-people-fill me-2 text-primary"></i>Daftar Siswa & Staff</h3>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-success px-3 py-2 rounded-3" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-file-earmark-excel me-1"></i>Import Excel
        </button>
        <a href="{{ route('employees.create') }}" class="btn btn-primary px-4 py-2 rounded-3"><i class="bi bi-plus-lg me-1"></i>Tambah Baru</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 mb-4" role="alert"
        style="background:linear-gradient(135deg,#d1fae5,#a7f3d0);color:#065f46;box-shadow:0 4px 15px rgba(16,185,129,.15);">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 mb-4" role="alert"
        style="background:linear-gradient(135deg,#fee2e2,#fecaca);color:#991b1b;box-shadow:0 4px 15px rgba(239,68,68,.15);">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="custom-card">
    <!-- Filter dan Search -->
    <form method="GET" action="{{ route('employees.index') }}" class="row g-3 mb-4">
        <!-- Hidden submit to capture Enter key -->
        <button type="submit" class="d-none" aria-hidden="true"></button>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-secondary" style="border-color: #cbd5e1;"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama..." value="{{ request('search') }}" style="border-color: #cbd5e1;">
            </div>
        </div>
        <div class="col-md-3">
            <select name="category" class="form-select" onchange="document.querySelector('select[name=position]').value=''; this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ strtoupper($cat->name) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="position" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Kelas/Jabatan</option>
                @foreach($positions as $pos)
                    <option value="{{ $pos->id }}" {{ request('position') == $pos->id ? 'selected' : '' }}>{{ strtoupper($pos->name) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center px-3" title="Reset Filter">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
            </a>
            <div class="dropdown flex-fill">
                <button class="btn btn-info text-white w-100 h-100 shadow-sm dropdown-toggle d-flex align-items-center justify-content-center" type="button" id="printDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-printer-fill me-1"></i> Aksi Massal
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="printDropdown">
                    <li><button type="submit" formaction="{{ route('employees.print-cards') }}" class="dropdown-item py-2"><i class="bi bi-printer me-2 text-primary"></i> Cetak Kartu (A4)</button></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><button type="submit" name="type" value="barcode" formaction="{{ route('employees.download-barcodes') }}" class="dropdown-item py-2"><i class="bi bi-upc-scan me-2 text-success"></i> Download Barcode (ZIP)</button></li>
                    <li><button type="submit" name="type" value="qrcode" formaction="{{ route('employees.download-barcodes') }}" class="dropdown-item py-2"><i class="bi bi-qr-code me-2 text-success"></i> Download QR Code (ZIP)</button></li>
                </ul>
            </div>
        </div>
    </form>

    <!-- Hidden Form for Bulk Delete -->
    <form id="bulkDeleteForm" action="{{ route('employees.bulk-delete') }}" method="POST" class="d-none">
        @csrf
    </form>

    <div class="mb-3 d-none" id="bulkActionContainer">
        <button type="button" id="btnBulkDelete" class="btn btn-danger rounded-3">
            <i class="bi bi-trash me-1"></i>Hapus Terpilih (<span id="selectedCount">0</span>)
        </button>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle border-secondary border-opacity-10">
            <thead class="text-muted" style="border-bottom: 2px solid rgba(255, 255, 255, 0.05);">
                    <tr>
                        <th scope="col" style="width: 40px;">
                            <input class="form-check-input" type="checkbox" id="selectAll">
                        </th>
                        <th scope="col" style="width: 80px;">Foto</th>
                    <th scope="col">Nomor Induk</th>
                    <th scope="col">Nama Lengkap</th>
                    <th scope="col">Kategori</th>
                    <th scope="col">Kelas / Jabatan</th>
                    <th scope="col">Status</th>
                    <th scope="col" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $employee)
                    <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                        <td>
                            <input class="form-check-input row-checkbox" type="checkbox" name="ids[]" value="{{ $employee->id }}">
                        </td>
                        <td>
                            @if($employee->photo)
                                <img src="{{ route('avatar.serve', $employee->photo) }}" class="rounded-3" style="width: 50px; height: 50px; object-fit: cover; border: 1px solid rgba(255, 255, 255, 0.1);">
                            @else
                                <img src="{{ asset('images/default-avatar.png') }}" class="rounded-3" style="width: 50px; height: 50px; object-fit: cover;">
                            @endif
                        </td>
                        <td class="fw-bold">{{ $employee->code }}</td>
                        <td>{{ $employee->name }}</td>
                        <td>
                            <span class="badge badge-category bg-{{ $employee->category->badge_color ?? 'primary' }} text-white">
                                {{ strtoupper($employee->category->name ?? '') }}
                            </span>
                        </td>
                        <td class="text-muted">{{ $employee->position->name ?? '-' }}</td>
                        <td>
                            @if($employee->is_active)
                                <span class="badge bg-success text-white" style="font-size: 0.75rem;">AKTIF</span>
                            @else
                                <span class="badge bg-danger text-white" style="font-size: 0.75rem;">NONAKTIF</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-2">
                                <a href="{{ route('employees.show', $employee->id) }}?{{ http_build_query(request()->query()) }}" class="btn btn-outline-info btn-sm rounded-2" title="Detail / Cetak Barcode"><i class="bi bi-eye"></i> Show</a>
                                <a href="{{ route('employees.edit', $employee->id) }}?{{ http_build_query(request()->query()) }}" class="btn btn-outline-warning btn-sm rounded-2"><i class="bi bi-pencil"></i> Edit</a>
                                <form action="{{ route('employees.destroy', $employee->id) }}?{{ http_build_query(request()->query()) }}" method="POST" onsubmit="confirmDelete(this, 'Apakah Anda yakin ingin menghapus data siswa/staff ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-2"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">Data siswa atau staff tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $employees->links() }}
    </div>
</div>

<!-- Modal Import Excel -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="{{ route('employees.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="modal-title fw-bold text-dark" id="importModalLabel"><i class="bi bi-file-earmark-excel-fill text-success me-2"></i>Import Data Karyawan/Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 py-4">
                    <div class="alert alert-info border-0 rounded-3 small" style="background:#eff6ff;color:#1e40af;">
                        <i class="bi bi-info-circle-fill me-2"></i>Silakan unduh template Excel terlebih dahulu, isi data sesuai format, lalu unggah kembali file tersebut.
                    </div>
                    <div class="mb-4 text-center">
                        <a href="{{ route('employees.template') }}" class="btn btn-outline-primary btn-sm rounded-3">
                            <i class="bi bi-download me-1"></i>Unduh Template Excel
                        </a>
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label fw-semibold text-dark">File Excel (.xlsx, .xls, .csv)</label>
                        <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-3 px-4"><i class="bi bi-upload me-1"></i>Mulai Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAllCheckbox = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const bulkActionContainer = document.getElementById('bulkActionContainer');
    const selectedCountSpan = document.getElementById('selectedCount');

    function updateBulkAction() {
        const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
        selectedCountSpan.textContent = checkedCount;
        
        if (checkedCount > 0) {
            bulkActionContainer.classList.remove('d-none');
        } else {
            bulkActionContainer.classList.add('d-none');
        }

        selectAllCheckbox.checked = checkedCount === rowCheckboxes.length && rowCheckboxes.length > 0;
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkAction();
        });
    }

    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkAction);
    });

    const btnBulkDelete = document.getElementById('btnBulkDelete');
    const bulkDeleteForm = document.getElementById('bulkDeleteForm');

    if (btnBulkDelete) {
        btnBulkDelete.addEventListener('click', function (event) {
            // Hapus input tersembunyi sebelumnya jika ada
            bulkDeleteForm.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
            
            // Tambahkan input tersembunyi untuk setiap checkbox yang dicentang
            document.querySelectorAll('.row-checkbox:checked').forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = checkbox.value;
                bulkDeleteForm.appendChild(input);
            });
            
            // Panggil modal konfirmasi kustom bawaan app layout
            confirmDelete(bulkDeleteForm, 'Apakah Anda yakin ingin menghapus ' + selectedCountSpan.textContent + ' data siswa/staff yang dipilih?');
        });
    }
});
</script>
@endsection
