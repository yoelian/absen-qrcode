@extends('layouts.app')

@section('content')
<div class="mb-4">
    <a href="{{ route('leaves.index') }}" class="text-decoration-none text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat Pengajuan
    </a>
    <h3 class="text-dark fw-bold mt-2"><i class="bi bi-file-earmark-plus-fill me-2 text-primary"></i>Input Pengajuan Baru</h3>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="custom-card">
            <form method="POST" action="{{ route('leaves.store') }}">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-5">
                        <label for="category_id" class="form-label text-dark fw-semibold">Filter Kategori (Opsional)</label>
                        <select id="category_id" class="form-select">
                            <option value="">-- Semua Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ strtoupper($cat->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-7">
                        <label for="employee_id" class="form-label text-dark fw-semibold">Pilih Pegawai / Siswa</label>
                        <select name="employee_id" id="employee_id" class="form-select @error('employee_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Pegawai / Siswa --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" data-category="{{ $emp->category_id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->name }} ({{ strtoupper($emp->category->name ?? '-') }})
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-dark fw-semibold">Jenis Pengajuan</label>
                    <div class="d-flex gap-2">
                        <label class="btn btn-outline-primary rounded-3 flex-fill text-start">
                            <input type="radio" name="type" value="izin" class="me-2" {{ old('type') == 'izin' ? 'checked' : '' }} required> 
                            <i class="bi bi-info-circle me-1"></i> Izin
                        </label>
                        <label class="btn btn-outline-info rounded-3 flex-fill text-start">
                            <input type="radio" name="type" value="sakit" class="me-2" {{ old('type') == 'sakit' ? 'checked' : '' }}> 
                            <i class="bi bi-heart-pulse me-1"></i> Sakit
                        </label>
                        <label class="btn btn-outline-warning text-dark rounded-3 flex-fill text-start">
                            <input type="radio" name="type" value="cuti" class="me-2" {{ old('type') == 'cuti' ? 'checked' : '' }}> 
                            <i class="bi bi-cup-hot me-1"></i> Cuti
                        </label>
                        <label class="btn btn-outline-dark rounded-3 flex-fill text-start">
                            <input type="radio" name="type" value="dl" class="me-2" {{ old('type') == 'dl' ? 'checked' : '' }}> 
                            <i class="bi bi-briefcase me-1"></i> Dinas Luar
                        </label>
                    </div>
                    @error('type')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="start_date" class="form-label text-dark fw-semibold">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" 
                               class="form-control @error('start_date') is-invalid @enderror" 
                               value="{{ old('start_date', date('Y-m-d')) }}" required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="end_date" class="form-label text-dark fw-semibold">Tanggal Selesai</label>
                        <input type="date" name="end_date" id="end_date" 
                               class="form-control @error('end_date') is-invalid @enderror" 
                               value="{{ old('end_date', date('Y-m-d')) }}" required>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted mt-1 d-block"><i class="bi bi-info-circle"></i> Samakan dengan Tanggal Mulai jika hanya 1 hari.</small>
                    </div>
                </div>

                <div class="mb-5">
                    <label for="reason" class="form-label text-dark fw-semibold">Alasan / Keterangan (Opsional)</label>
                    <textarea name="reason" id="reason" rows="3" 
                              class="form-control @error('reason') is-invalid @enderror"
                              placeholder="Contoh: Sakit demam berdarah, Izin acara keluarga, dll.">{{ old('reason') }}</textarea>
                    @error('reason')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary px-5 py-2 rounded-3 fw-semibold">
                        <i class="bi bi-check-circle-fill me-2"></i>Simpan Pengajuan
                    </button>
                    <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary px-4 py-2 rounded-3">Batal</a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="custom-card bg-light border-0">
            <h5 class="fw-bold mb-3"><i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Perhatian Penting</h5>
            <p class="small text-muted mb-2">
                Saat Anda menyimpan form ini, sistem akan secara otomatis mengisi status Izin/Sakit/Cuti ke dalam <strong>Rekap Kehadiran Bulanan</strong> sesuai dengan rentang tanggal yang dipilih.
            </p>
            <p class="small text-muted mb-0">
                Data kehadiran yang sudah ada (Hadir/Terlambat/Alpa) pada rentang tanggal tersebut akan <strong>ditimpa / di-overwrite</strong> dengan status pengajuan baru ini. Pastikan rentang tanggal sudah benar.
            </p>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');
    const employeeSelect = document.getElementById('employee_id');
    const allEmployeeOptions = Array.from(employeeSelect.options).filter(opt => opt.value !== "");

    categorySelect.addEventListener('change', function() {
        const selectedCategoryId = this.value;
        
        // Sembunyikan/tampilkan opsi pegawai
        allEmployeeOptions.forEach(opt => {
            if (selectedCategoryId === "" || opt.getAttribute('data-category') === selectedCategoryId) {
                opt.style.display = '';
            } else {
                opt.style.display = 'none';
            }
        });

        // Reset pilihan pegawai jika kategori berubah
        employeeSelect.value = "";
    });
});
</script>
@endsection
@endsection
