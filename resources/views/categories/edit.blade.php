@extends('layouts.app')

@section('content')
<div class="mb-4">
    <a href="{{ route('categories.index') }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Kategori
    </a>
    <h3 class="text-dark fw-bold mt-2"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Kategori: {{ $category->name }}</h3>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="custom-card">
            <form method="POST" action="{{ route('categories.update', $category->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="form-label text-dark fw-semibold">Nama Kategori</label>
                    <input type="text" name="name" id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $category->name) }}"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="code" class="form-label text-dark fw-semibold">Kode Singkat</label>
                    <input type="text" name="code" id="code"
                           class="form-control @error('code') is-invalid @enderror"
                           value="{{ old('code', $category->code) }}"
                           maxlength="10"
                           style="text-transform: uppercase;"
                           required>
                    <small class="text-muted">Kode <strong>SEC</strong> digunakan untuk deteksi shift otomatis Security.</small>
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="target_in_time" class="form-label text-dark fw-semibold">Jam Masuk (Batas Terlambat)</label>
                        <input type="time" name="target_in_time" id="target_in_time"
                               class="form-control @error('target_in_time') is-invalid @enderror"
                               value="{{ old('target_in_time', $category->target_in_time ? \Carbon\Carbon::parse($category->target_in_time)->format('H:i') : '') }}">
                        @error('target_in_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="target_out_time" class="form-label text-dark fw-semibold">Jam Pulang</label>
                        <input type="time" name="target_out_time" id="target_out_time"
                               class="form-control @error('target_out_time') is-invalid @enderror"
                               value="{{ old('target_out_time', $category->target_out_time ? \Carbon\Carbon::parse($category->target_out_time)->format('H:i') : '') }}">
                        @error('target_out_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mt-1">
                        <small class="text-muted"><i class="bi bi-info-circle text-primary"></i> <strong>Kosongkan</strong> kedua jam di atas jika kategori ini memiliki jam kerja Fleksibel/Bebas (contoh: CS) agar tidak terhitung terlambat.</small>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="form-label text-dark fw-semibold">Warna Badge</label>
                    <div class="row g-3">
                        @php
                            $colors = [
                                'primary'   => ['label' => 'Biru (Primary)',   'hex' => '#3b82f6'],
                                'success'   => ['label' => 'Hijau (Success)',  'hex' => '#22c55e'],
                                'warning'   => ['label' => 'Kuning (Warning)', 'hex' => '#f59e0b'],
                                'danger'    => ['label' => 'Merah (Danger)',   'hex' => '#ef4444'],
                                'info'      => ['label' => 'Biru Muda (Info)', 'hex' => '#06b6d4'],
                                'secondary' => ['label' => 'Abu (Secondary)',  'hex' => '#6b7280'],
                                'dark'      => ['label' => 'Hitam (Dark)',     'hex' => '#1e293b'],
                                'indigo'    => ['label' => 'Indigo',           'hex' => '#6366f1'],
                            ];
                        @endphp
                        @foreach($colors as $val => $color)
                            <div class="col-md-3">
                                <label class="d-block cursor-pointer">
                                    <input type="radio" name="badge_color" value="{{ $val }}"
                                           class="d-none badge-radio"
                                           id="color_{{ $val }}"
                                           {{ old('badge_color', $category->badge_color) === $val ? 'checked' : '' }}>
                                    <div class="badge-option rounded-3 p-3 text-center border"
                                         style="border-color: {{ $color['hex'] }} !important; transition: all 0.2s; cursor: pointer;"
                                         data-color="{{ $val }}">
                                        <span class="badge bg-{{ $val }} text-white d-block mb-2 py-2">
                                            {{ strtoupper($val) }}
                                        </span>
                                        <small class="text-muted" style="font-size: 0.75rem;">{{ $color['label'] }}</small>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('badge_color')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-warning px-5 py-2 rounded-3 fw-semibold">
                        <i class="bi bi-check-circle-fill me-2"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary px-4 py-2 rounded-3">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Panel -->
    <div class="col-lg-5">
        <div class="custom-card">
            <h5 class="text-dark fw-semibold mb-3"><i class="bi bi-bar-chart-fill me-2 text-primary"></i>Statistik Kategori</h5>
            <div class="d-flex align-items-center gap-4 p-4 rounded-3 mb-4" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                <div class="text-center">
                    <h2 class="fw-bold text-dark m-0">{{ $category->employees_count }}</h2>
                    <small class="text-muted">Total Anggota</small>
                </div>
                <div>
                    <span class="badge bg-{{ $category->badge_color }} text-white px-3 py-2 mb-2 d-block">
                        {{ strtoupper($category->name) }}
                    </span>
                    <small class="text-muted">Kode: <strong>{{ $category->code }}</strong></small>
                </div>
            </div>

            @if($category->employees_count > 0)
                <div class="alert alert-warning rounded-3 border-0 small" style="background: #fef3c7; color: #92400e;">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    Kategori ini memiliki <strong>{{ $category->employees_count }} anggota</strong>. 
                    Anda masih bisa mengubah nama, kode, dan warna — tapi tidak bisa menghapus kategori ini selama masih ada anggota.
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.badge-radio:checked + .badge-option {
    box-shadow: 0 0 0 3px rgba(59,130,246,0.4);
    background: #f0f9ff;
}
.badge-option:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
</style>

<script>
    document.getElementById('code').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });

    document.querySelectorAll('.badge-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.badge-option').forEach(el => {
                el.style.boxShadow = '';
                el.style.background = '';
            });
            if (this.checked) {
                this.nextElementSibling.style.boxShadow = '0 0 0 3px rgba(59,130,246,0.4)';
                this.nextElementSibling.style.background = '#f0f9ff';
            }
        });
        if (radio.checked) {
            radio.nextElementSibling.style.boxShadow = '0 0 0 3px rgba(59,130,246,0.4)';
            radio.nextElementSibling.style.background = '#f0f9ff';
        }
    });
</script>
@endsection
