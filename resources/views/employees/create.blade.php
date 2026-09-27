@extends('layouts.app')

@section('content')
<div class="mb-4">
    <a href="{{ route('employees.index') }}" class="text-decoration-none text-muted"><i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar</a>
    <h3 class="text-dark fw-bold mt-2"><i class="bi bi-person-plus-fill me-2 text-primary"></i>Tambah Siswa / Staff Baru</h3>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="custom-card">
            <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="code" class="form-label text-dark fw-semibold">Nomor Induk (NIS / NIP / Kode QR)</label>
                        <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="Contoh: 20261001" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="category_id" class="form-label text-dark fw-semibold">Kategori Pengguna</label>
                        <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="" disabled selected>-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ strtoupper($cat->name) }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="name" class="form-label text-dark fw-semibold">Nama Lengkap</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Masukkan nama lengkap sesuai identitas..." required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="position_id" class="form-label text-dark fw-semibold">
                        Kelas / Jabatan
                        <button type="button" class="btn btn-link ms-1 p-0 small text-primary text-decoration-none" data-bs-toggle="modal" data-bs-target="#positionModal">
                            <i class="bi bi-plus-circle"></i> Tambah baru
                        </button>
                    </label>
                    <select name="position_id" id="position_id" class="form-select @error('position_id') is-invalid @enderror">
                        <option value="">-- Pilih kategori dulu --</option>
                    </select>
                    <small class="text-muted">Pilih kategori terlebih dahulu untuk melihat daftar kelas/jabatan yang tersedia.</small>
                    @error('position_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

<script>
document.getElementById('category_id').addEventListener('change', function() {
    const catId = this.value;
    const posSelect = document.getElementById('position_id');
    posSelect.innerHTML = '<option value="">Memuat...</option>';
    posSelect.disabled = true;
    
    // Sinkronisasi kategori ke modal form
    const modalCatSelect = document.getElementById('modal_category_id');
    if (modalCatSelect) modalCatSelect.value = catId;

    if (!catId) {
        posSelect.innerHTML = '<option value="">-- Pilih kategori dulu --</option>';
        posSelect.disabled = false;
        return;
    }

    fetch(`/api/positions/by-category/${catId}`)
        .then(r => r.json())
        .then(data => {
            posSelect.innerHTML = '<option value="">-- Pilih Jabatan/Kelas (opsional) --</option>';
            data.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.text = p.name;
                if (opt.value == '{{ old('position_id') }}') opt.selected = true;
                posSelect.appendChild(opt);
            });
            if (data.length === 0) {
                posSelect.innerHTML = '<option value="">Belum ada jabatan/kelas untuk kategori ini</option>';
            }
            posSelect.disabled = false;
        })
        .catch(() => {
            posSelect.innerHTML = '<option value="">Gagal memuat data</option>';
            posSelect.disabled = false;
        });
});

// Auto-trigger jika ada nilai old
window.addEventListener('DOMContentLoaded', () => {
    const catId = document.getElementById('category_id').value;
    if (catId) document.getElementById('category_id').dispatchEvent(new Event('change'));
});
</script>

                <div class="mb-4">
                    <label for="photo" class="form-label text-dark fw-semibold">Foto Profil</label>
                    <input type="file" name="photo" id="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                    <small class="text-muted">Format JPG/PNG, ukuran maksimal 2MB.</small>
                    @error('photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-5 py-2 rounded-3">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Kelas -->
<div class="modal fade" id="positionModal" tabindex="-1" aria-labelledby="positionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-dark" id="positionModalLabel"><i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Kelas / Jabatan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modalAlert" class="alert alert-danger d-none rounded-3 py-2"></div>
                <form id="ajaxPositionForm">
                    @csrf
                    <div class="mb-3">
                        <label for="modal_category_id" class="form-label text-dark fw-semibold">Kategori Pengguna</label>
                        <select name="category_id" id="modal_category_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ strtoupper($cat->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="modal_name" class="form-label text-dark fw-semibold">Nama Kelas / Jabatan</label>
                        <input type="text" name="name" id="modal_name" class="form-control" placeholder="Contoh: Kelas X-A atau Wali Kelas" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="btnSavePosition" class="btn btn-primary">Simpan Kelas</button>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('btnSavePosition').addEventListener('click', function() {
    const btn = this;
    const form = document.getElementById('ajaxPositionForm');
    const alertDiv = document.getElementById('modalAlert');
    const formData = new FormData(form);
    
    btn.disabled = true;
    btn.innerHTML = 'Menyimpan...';
    alertDiv.classList.add('d-none');
    
    fetch('{{ route('positions.store') }}', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) {
            throw new Error(data.message || 'Terjadi kesalahan saat menyimpan data.');
        }
        return data;
    })
    .then(data => {
        // Berhasil disimpan
        const pos = data.position;
        const mainCat = document.getElementById('category_id');
        const mainPos = document.getElementById('position_id');
        
        // Tutup modal
        const modalEl = document.getElementById('positionModal');
        const modalObj = bootstrap.Modal.getInstance(modalEl);
        modalObj.hide();
        
        // Reset form modal
        document.getElementById('modal_name').value = '';
        
        // Jika kategori di form utama sama dengan kategori kelas yang baru dibuat, tambahkan ke dropdown
        if (mainCat.value == pos.category_id) {
            const opt = document.createElement('option');
            opt.value = pos.id;
            opt.text = pos.name;
            opt.selected = true;
            mainPos.appendChild(opt);
        } else {
            // Jika beda, ubah kategori utama ke kategori kelas baru dan biarkan auto-fetch berjalan
            mainCat.value = pos.category_id;
            mainCat.dispatchEvent(new Event('change'));
            
            // Tunggu sebentar sampai fetch selesai lalu select otomatis
            setTimeout(() => {
                mainPos.value = pos.id;
            }, 500);
        }
        
    })
    .catch(error => {
        alertDiv.textContent = error.message;
        alertDiv.classList.remove('d-none');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = 'Simpan Kelas';
    });
});
</script>
@endsection
