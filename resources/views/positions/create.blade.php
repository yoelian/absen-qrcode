@extends('layouts.app')

@section('content')
<div class="mb-4">
    <a href="{{ route('positions.index') }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Jabatan/Kelas
    </a>
    <h3 class="text-dark fw-bold mt-2"><i class="bi bi-briefcase-fill me-2 text-primary"></i>Tambah Jabatan / Kelas Baru</h3>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="custom-card">
            <form method="POST" action="{{ route('positions.store') }}">
                @csrf

                <div class="mb-4">
                    <label for="category_id" class="form-label text-dark fw-semibold">Kategori Pengguna</label>
                    <select name="category_id" id="category_id"
                            class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="" disabled selected>-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ strtoupper($cat->name) }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Pilih kategori terlebih dahulu untuk menentukan apakah ini jabatan staff atau kelas siswa.</small>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="name" class="form-label text-dark fw-semibold">Nama Jabatan / Kelas</label>
                    <input type="text" name="name" id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           placeholder="Contoh: X-IPA-1, Guru Matematika, Kepala TU, Security Pagi..."
                           required>
                    <small class="text-muted">Gunakan nama yang jelas dan spesifik agar mudah diidentifikasi.</small>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary px-5 py-2 rounded-3 fw-semibold">
                        <i class="bi bi-check-circle-fill me-2"></i>Simpan
                    </button>
                    <a href="{{ route('positions.index') }}" class="btn btn-outline-secondary px-4 py-2 rounded-3">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Panel Info -->
    <div class="col-lg-5">
        <div class="custom-card">
            <h5 class="text-dark fw-semibold mb-3"><i class="bi bi-lightbulb-fill me-2 text-warning"></i>Contoh Pengisian</h5>

            <div class="mb-4">
                <p class="fw-semibold text-dark mb-2"><span class="badge bg-primary me-2">SISWA</span>Nama Kelas:</p>
                <div class="d-flex flex-wrap gap-2">
                    @foreach(['X-IPA-1','X-IPA-2','X-IPS-1','XI-IPA-1','XII-IPS-2'] as $ex)
                        <span class="badge bg-light text-dark border px-3 py-2" style="font-size:.8rem;">{{ $ex }}</span>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <p class="fw-semibold text-dark mb-2"><span class="badge bg-success me-2">GURU</span>Bidang Studi:</p>
                <div class="d-flex flex-wrap gap-2">
                    @foreach(['Matematika','Bahasa Indonesia','Fisika','Kimia','Bahasa Inggris'] as $ex)
                        <span class="badge bg-light text-dark border px-3 py-2" style="font-size:.8rem;">Guru {{ $ex }}</span>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <p class="fw-semibold text-dark mb-2"><span class="badge bg-info me-2">TU</span>Jabatan:</p>
                <div class="d-flex flex-wrap gap-2">
                    @foreach(['Kepala TU','Staff Administrasi','Bendahara','Staff Kesiswaan'] as $ex)
                        <span class="badge bg-light text-dark border px-3 py-2" style="font-size:.8rem;">{{ $ex }}</span>
                    @endforeach
                </div>
            </div>

            <div class="mb-2">
                <p class="fw-semibold text-dark mb-2"><span class="badge bg-warning me-2">SECURITY</span>Posisi:</p>
                <div class="d-flex flex-wrap gap-2">
                    @foreach(['Security Pagi','Security Malam'] as $ex)
                        <span class="badge bg-light text-dark border px-3 py-2" style="font-size:.8rem;">{{ $ex }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
