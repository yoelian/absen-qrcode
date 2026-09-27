@extends('layouts.app')

@section('content')
<div class="mb-4">
    <a href="{{ route('positions.index') }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Jabatan/Kelas
    </a>
    <h3 class="text-dark fw-bold mt-2"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit: {{ $position->name }}</h3>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="custom-card">
            <form method="POST" action="{{ route('positions.update', $position->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="category_id" class="form-label text-dark fw-semibold">Kategori Pengguna</label>
                    <select name="category_id" id="category_id"
                            class="form-select @error('category_id') is-invalid @enderror" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $position->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ strtoupper($cat->name) }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="name" class="form-label text-dark fw-semibold">Nama Jabatan / Kelas</label>
                    <input type="text" name="name" id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $position->name) }}"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-warning px-5 py-2 rounded-3 fw-semibold">
                        <i class="bi bi-check-circle-fill me-2"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('positions.index') }}" class="btn btn-outline-secondary px-4 py-2 rounded-3">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistik -->
    <div class="col-lg-5">
        <div class="custom-card">
            <h5 class="text-dark fw-semibold mb-3"><i class="bi bi-bar-chart-fill me-2 text-primary"></i>Info Jabatan/Kelas</h5>
            <div class="p-4 rounded-3 mb-4 d-flex align-items-center gap-4" style="background:#f8fafc;border:1px solid #e2e8f0;">
                <div class="text-center">
                    <h2 class="fw-bold text-dark m-0">{{ $position->employees_count }}</h2>
                    <small class="text-muted">Anggota</small>
                </div>
                <div>
                    @if($position->category)
                        <span class="badge bg-{{ $position->category->badge_color ?? 'secondary' }} text-white px-3 py-2 mb-2 d-block">
                            {{ strtoupper($position->category->name) }}
                        </span>
                    @endif
                    <p class="fw-semibold text-dark m-0">{{ $position->name }}</p>
                </div>
            </div>

            @if($position->employees_count > 0)
                <div class="alert alert-warning rounded-3 border-0 small" style="background:#fef3c7;color:#92400e;">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    Ada <strong>{{ $position->employees_count }} anggota</strong> yang menggunakan jabatan/kelas ini.
                    Anda bisa mengubah nama, tapi tidak bisa menghapus selama masih digunakan.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
