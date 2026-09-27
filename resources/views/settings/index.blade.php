@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h3 class="text-dark fw-bold"><i class="bi bi-gear-fill me-2 text-primary"></i>Pengaturan Sistem</h3>
    <p class="text-muted m-0">Sesuaikan profil sekolah, parameter jam reguler, dan jam shift untuk Security.</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="custom-card">
            <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
                @csrf

                <!-- Profil Sekolah -->
                <h5 class="text-dark border-bottom border-secondary border-opacity-25 pb-2 mb-4">
                    <i class="bi bi-bank me-2 text-primary"></i>Profil Sekolah
                </h5>

                <div class="mb-4">
                    <label class="form-label text-dark fw-semibold">Teks Baris 1: Pemerintah Daerah / Yayasan (Opsional)</label>
                    <input type="text" name="kop_pemerintah" class="form-control @error('kop_pemerintah') is-invalid @enderror" value="{{ old('kop_pemerintah', App\Models\Setting::get('kop_pemerintah')) }}" placeholder="Contoh: PEMERINTAH PROVINSI KALIMANTAN TIMUR">
                    @error('kop_pemerintah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label text-dark fw-semibold">Teks Baris 2: Dinas / Cabang Dinas (Opsional)</label>
                    <input type="text" name="kop_dinas" class="form-control @error('kop_dinas') is-invalid @enderror" value="{{ old('kop_dinas', App\Models\Setting::get('kop_dinas')) }}" placeholder="Contoh: DINAS PENDIDIKAN DAN KEBUDAYAAN">
                    @error('kop_dinas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label text-dark fw-semibold">Teks Baris 3: Nama Sekolah / Instansi (Wajib)</label>
                    <input type="text" name="school_name" class="form-control @error('school_name') is-invalid @enderror" value="{{ old('school_name', App\Models\Setting::get('school_name')) }}" required>
                    @error('school_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label text-dark fw-semibold">Teks Baris 4: Alamat Sekolah (Wajib)</label>
                    <textarea name="school_address" class="form-control @error('school_address') is-invalid @enderror" rows="2" required>{{ old('school_address', App\Models\Setting::get('school_address')) }}</textarea>
                    @error('school_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label text-dark fw-semibold">Teks Baris 5: Kontak / Website / Email (Opsional)</label>
                    <input type="text" name="kop_kontak" class="form-control @error('kop_kontak') is-invalid @enderror" value="{{ old('kop_kontak', App\Models\Setting::get('kop_kontak')) }}" placeholder="Contoh: Telp. (0542) 12345 | Email: absenpro@sekolah.id">
                    @error('kop_kontak')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-dark fw-semibold">Nama Kepala Sekolah / Pimpinan</label>
                        <input type="text" name="headmaster_name" class="form-control @error('headmaster_name') is-invalid @enderror" value="{{ old('headmaster_name', App\Models\Setting::get('headmaster_name')) }}">
                        @error('headmaster_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-dark fw-semibold">NIP / NIK Kepala Sekolah</label>
                        <input type="text" name="headmaster_nip" class="form-control @error('headmaster_nip') is-invalid @enderror" value="{{ old('headmaster_nip', App\Models\Setting::get('headmaster_nip')) }}">
                        @error('headmaster_nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-8">
                        <label class="form-label text-dark fw-semibold">Logo Sekolah Baru</label>
                        <input type="file" name="school_logo" class="form-control @error('school_logo') is-invalid @enderror" accept="image/*">
                        @error('school_logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 text-center">
                        <label class="form-label text-muted fw-semibold d-block">Logo Saat Ini</label>
                        @if(App\Models\Setting::get('school_logo'))
                            <img src="{{ route('avatar.serve', App\Models\Setting::get('school_logo')) }}" class="rounded border" style="height: 60px; max-width: 100%; object-fit: contain;">
                        @else
                            <span class="text-muted small">Belum ada logo</span>
                        @endif
                    </div>
                </div>

                <!-- Jeda Waktu Absensi -->
                <h5 class="text-dark border-bottom border-secondary border-opacity-25 pb-2 mb-4 mt-5">
                    <i class="bi bi-stopwatch-fill me-2 text-info"></i>Pengaturan Mesin Absen
                </h5>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-dark fw-semibold">Jeda Waktu (Cooldown) Scan Ganda (dalam menit)</label>
                        <input type="number" name="scan_cooldown_minutes" class="form-control @error('scan_cooldown_minutes') is-invalid @enderror" value="{{ old('scan_cooldown_minutes', App\Models\Setting::get('scan_cooldown_minutes', '1')) }}" min="1" required>
                        <div class="form-text">Mencegah siswa absen masuk lalu langsung terabsen pulang jika mereka men-scan kartunya berulang kali. (Default: 1 menit)</div>
                        @error('scan_cooldown_minutes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-dark fw-semibold">Zona Waktu (Timezone) Absensi</label>
                        @php
                            $currentTz = old('timezone', App\Models\Setting::get('timezone', 'Asia/Makassar'));
                        @endphp
                        <select name="timezone" class="form-select @error('timezone') is-invalid @enderror" required>
                            <option value="Asia/Jakarta" {{ $currentTz == 'Asia/Jakarta' ? 'selected' : '' }}>(WIB) Waktu Indonesia Barat</option>
                            <option value="Asia/Makassar" {{ $currentTz == 'Asia/Makassar' ? 'selected' : '' }}>(WITA) Waktu Indonesia Tengah</option>
                            <option value="Asia/Jayapura" {{ $currentTz == 'Asia/Jayapura' ? 'selected' : '' }}>(WIT) Waktu Indonesia Timur</option>
                        </select>
                        <div class="form-text">Sesuaikan dengan lokasi sekolah agar jam scan 100% akurat.</div>
                        @error('timezone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Jam Shift Security -->
                <h5 class="text-dark border-bottom border-secondary border-opacity-25 pb-2 mb-4 mt-5">
                    <i class="bi bi-shield-lock-fill me-2 text-warning"></i>Parameter Jam Shift Security (Deteksi Otomatis)
                </h5>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-dark fw-semibold">Shift Pagi - Jam Masuk</label>
                        <input type="time" name="security_morning_in" class="form-control" value="{{ App\Models\Setting::get('security_morning_in', '07:00') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-dark fw-semibold">Shift Pagi - Jam Pulang</label>
                        <input type="time" name="security_morning_out" class="form-control" value="{{ App\Models\Setting::get('security_morning_out', '19:00') }}" required>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-dark fw-semibold">Shift Malam - Jam Masuk</label>
                        <input type="time" name="security_night_in" class="form-control" value="{{ App\Models\Setting::get('security_night_in', '19:00') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-dark fw-semibold">Shift Malam - Jam Pulang</label>
                        <input type="time" name="security_night_out" class="form-control" value="{{ App\Models\Setting::get('security_night_out', '07:00') }}" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-bold mt-4">
                    <i class="bi bi-check-circle-fill me-2"></i>Simpan Seluruh Pengaturan
                </button>
    </div>
</div>
@endsection
