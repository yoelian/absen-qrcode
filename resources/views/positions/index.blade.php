@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="text-dark fw-bold m-0"><i class="bi bi-briefcase-fill me-2 text-primary"></i>Master Data Jabatan & Kelas</h3>
        <p class="text-muted m-0 mt-1">Kelola jabatan staff dan nama kelas siswa berdasarkan kategori.</p>
    </div>
    <a href="{{ route('positions.create') }}" class="btn btn-primary px-4 py-2 rounded-3">
        <i class="bi bi-plus-lg me-1"></i>Tambah Jabatan/Kelas
    </a>
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
    <!-- Filter -->
    <form method="GET" action="{{ route('positions.index') }}" class="row g-3 mb-4 align-items-end">
        <div class="col-md-5">
            <label class="form-label text-dark fw-semibold">Filter Kategori</label>
            <select name="category" class="form-select" onchange="this.form.submit()">
                <option value="">-- Semua Kategori --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ strtoupper($cat->name) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <a href="{{ route('positions.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
        </div>
    </form>

    <!-- Tabel -->
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="text-muted" style="font-size:.85rem;">
                <tr>
                    <th style="width:50px;">#</th>
                    <th>Kategori</th>
                    <th>Nama Jabatan / Kelas</th>
                    <th>Jumlah Anggota</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($positions as $position)
                    <tr>
                        <td class="text-muted">{{ $positions->firstItem() + $loop->index }}</td>
                        <td>
                            @if($position->category)
                                <span class="badge bg-{{ $position->category->badge_color ?? 'secondary' }} text-white px-3 py-2" style="font-size:.8rem;">
                                    {{ strtoupper($position->category->name) }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="fw-semibold text-dark">{{ $position->name }}</td>
                        <td>
                            <span class="fw-bold text-dark">{{ $position->employees_count }}</span>
                            <span class="text-muted ms-1">orang</span>
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-2">
                                <a href="{{ route('positions.edit', $position->id) }}"
                                   class="btn btn-outline-warning btn-sm rounded-2">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('positions.destroy', $position->id) }}" method="POST"
                                      onsubmit="confirmDelete(this, 'Hapus jabatan/kelas ini? Pastikan tidak ada anggota yang terkait.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-2"
                                            {{ $position->employees_count > 0 ? 'disabled title=Masih ada anggota' : '' }}>
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-briefcase" style="font-size:3rem;opacity:.3;"></i>
                                <p class="mt-3 mb-1 fw-semibold">Belum ada jabatan/kelas</p>
                                <small>Klik tombol "Tambah Jabatan/Kelas" untuk memulai.</small>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($positions->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4 pt-3" style="border-top:1px solid #f1f5f9;">
            <small class="text-muted">
                Menampilkan {{ $positions->firstItem() }}–{{ $positions->lastItem() }} dari {{ $positions->total() }} data
            </small>
            {{ $positions->links() }}
        </div>
    @endif
</div>
@endsection
