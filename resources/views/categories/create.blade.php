@extends('layouts.app')

@section('content')
<div class="mb-4">
    <a href="{{ route('categories.index') }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Kategori
    </a>
    <h3 class="text-dark fw-bold mt-2"><i class="bi bi-tag-fill me-2 text-primary"></i>Tambah Kategori Baru</h3>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="custom-card">
            <form method="POST" action="{{ route('categories.store') }}">
                @csrf

                <div class="mb-4">
                    <label for="name" class="form-label text-dark fw-semibold">Nama Kategori</label>
                    <input type="text" name="name" id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           placeholder="Contoh: Siswa, Guru, TU, Security, CS..."
                           required>
                    <small class="text-muted">Nama lengkap yang akan tampil di laporan dan tabel data.</small>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="code" class="form-label text-dark fw-semibold">Kode Singkat</label>
                    <input type="text" name="code" id="code"
                           class="form-control @error('code') is-invalid @enderror"
                           value="{{ old('code') }}"
                           placeholder="Contoh: SIS, GUR, TU, SEC, CS"
                           maxlength="10"
                           style="text-transform: uppercase;"
                           required>
                    <small class="text-muted">Kode unik maksimal 10 karakter, akan dikonversi ke huruf kapital. Digunakan untuk deteksi otomatis shift Security (gunakan kode <strong>SEC</strong>).</small>
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="target_in_time" class="form-label text-dark fw-semibold">Jam Masuk (Batas Terlambat)</label>
                        <input type="time" name="target_in_time" id="target_in_time"
                               class="form-control @error('target_in_time') is-invalid @enderror"
                               value="{{ old('target_in_time') }}">
                        @error('target_in_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="target_out_time" class="form-label text-dark fw-semibold">Jam Pulang</label>
                        <input type="time" name="target_out_time" id="target_out_time"
                               class="form-control @error('target_out_time') is-invalid @enderror"
                               value="{{ old('target_out_time') }}">
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
                                           {{ old('badge_color', 'primary') === $val ? 'checked' : '' }}>
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
                    <button type="submit" class="btn btn-primary px-5 py-2 rounded-3 fw-semibold">
                        <i class="bi bi-check-circle-fill me-2"></i>Simpan Kategori
                    </button>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary px-4 py-2 rounded-3">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Panel Info Kanan -->
    <div class="col-lg-5">
        <div class="custom-card h-100">
            <h5 class="text-dark fw-semibold mb-3"><i class="bi bi-info-circle-fill me-2 text-primary"></i>Petunjuk</h5>
            <ul class="list-unstyled text-muted small lh-lg">
                <li><i class="bi bi-dot text-primary"></i><strong>Nama Kategori</strong> akan tampil di daftar karyawan, rekap kehadiran, dan laporan PDF/Excel.</li>
                <li class="mt-2"><i class="bi bi-dot text-warning"></i><strong>Kode SEC</strong> wajib digunakan untuk kategori Security agar sistem dapat mendeteksi shift otomatis (pagi/malam).</li>
                <li class="mt-2"><i class="bi bi-dot text-success"></i><strong>Warna Badge</strong> adalah warna label yang muncul di tabel dan tampilan absensi.</li>
                <li class="mt-2"><i class="bi bi-dot text-danger"></i>Kategori yang sudah memiliki <strong>anggota terdaftar tidak dapat dihapus</strong> sampai semua anggotanya dipindahkan atau dihapus.</li>
            </ul>

            <hr class="border-secondary border-opacity-25 my-4">

            <h6 class="text-dark fw-semibold mb-3">Kategori Default Rekomendasi</h6>
            <div class="d-flex flex-wrap gap-2">
                <span class="badge bg-primary text-white px-3 py-2">SISWA</span>
                <span class="badge bg-success text-white px-3 py-2">GURU</span>
                <span class="badge bg-info text-white px-3 py-2">TU</span>
                <span class="badge bg-warning text-white px-3 py-2">SECURITY</span>
                <span class="badge bg-secondary text-white px-3 py-2">CS</span>
            </div>
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
    // Auto uppercase kode
    document.getElementById('code').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });

    // Highlight selected badge color
    document.querySelectorAll('.badge-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.badge-option').forEach(el => el.style.boxShadow = '');
            if (this.checked) {
                this.nextElementSibling.style.boxShadow = '0 0 0 3px rgba(59,130,246,0.4)';
                this.nextElementSibling.style.background = '#f0f9ff';
            }
        });
        // Init state
        if (radio.checked) {
            radio.nextElementSibling.style.boxShadow = '0 0 0 3px rgba(59,130,246,0.4)';
            radio.nextElementSibling.style.background = '#f0f9ff';
        }
    });
</script>
@endsection
