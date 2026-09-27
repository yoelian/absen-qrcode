@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="text-dark fw-bold m-0"><i class="bi bi-tags-fill me-2 text-primary"></i>Master Data Kategori</h3>
        <p class="text-muted m-0 mt-1">Kelola kategori pengguna: Siswa, Guru, TU, Security, CS, dll.</p>
    </div>
    <a href="{{ route('categories.create') }}" class="btn btn-primary px-4 py-2 rounded-3">
        <i class="bi bi-plus-lg me-1"></i>Tambah Kategori
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 mb-4" role="alert"
        style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; box-shadow: 0 4px 15px rgba(16,185,129,0.15);">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 mb-4" role="alert"
        style="background: linear-gradient(135deg, #fee2e2, #fecaca); color: #991b1b; box-shadow: 0 4px 15px rgba(239,68,68,0.15);">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="custom-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="text-muted">
                <tr>
                    <th scope="col" style="width: 50px;">#</th>
                    <th scope="col">Nama Kategori</th>
                    <th scope="col">Kode</th>
                    <th scope="col">Warna Badge</th>
                    <th scope="col">Preview Badge</th>
                    <th scope="col">Jam Kerja</th>
                    <th scope="col">Jumlah Anggota</th>
                    <th scope="col" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration }}</td>
                        <td class="fw-bold text-dark">{{ $category->name }}</td>
                        <td>
                            <code class="px-2 py-1 rounded-2" style="background: #f1f5f9; color: #475569; font-size: 0.85rem;">
                                {{ $category->code }}
                            </code>
                        </td>
                        <td class="text-muted">{{ $category->badge_color }}</td>
                        <td>
                            <span class="badge bg-{{ $category->badge_color }} text-white px-3 py-2" style="font-size: 0.8rem;">
                                {{ strtoupper($category->name) }}
                            </span>
                        </td>
                        <td>
                            @if($category->target_in_time)
                                <div class="fw-semibold text-success"><i class="bi bi-box-arrow-in-right"></i> {{ \Carbon\Carbon::parse($category->target_in_time)->format('H:i') }}</div>
                                <div class="text-muted small"><i class="bi bi-box-arrow-left"></i> {{ $category->target_out_time ? \Carbon\Carbon::parse($category->target_out_time)->format('H:i') : '-' }}</div>
                            @else
                                <span class="badge bg-light text-secondary border"><i class="bi bi-infinity"></i> Fleksibel</span>
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold text-dark">{{ $category->employees_count }}</span>
                            <span class="text-muted ms-1">orang</span>
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-2">
                                <a href="{{ route('categories.edit', $category->id) }}" 
                                   class="btn btn-outline-warning btn-sm rounded-2">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" 
                                      onsubmit="confirmDelete(this, 'Hapus kategori ini? Hanya bisa dihapus jika tidak memiliki anggota.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-2">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-tags" style="font-size: 3rem; opacity: 0.3;"></i>
                                <p class="mt-3 mb-1 fw-semibold">Belum ada kategori</p>
                                <small>Tambah kategori pertama untuk memulai.</small>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($categories->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $categories->links() }}
        </div>
    @endif
</div>

@endsection
