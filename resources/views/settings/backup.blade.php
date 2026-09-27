@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h3 class="text-dark fw-bold"><i class="bi bi-hdd-network-fill me-2 text-primary"></i>Backup Database</h3>
    <p class="text-muted m-0">Unduh atau kelola file salinan database aplikasi Anda.</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-6">
        <div class="custom-card mb-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-download text-primary me-2"></i>Backup Manual</h5>
            <p class="text-muted mb-4">
                Klik tombol di bawah ini untuk mengunduh seluruh data aplikasi (database) ke dalam komputer / flashdisk Anda saat ini.
                Simpan file ini di tempat yang aman sebagai cadangan jika komputer utama rusak.
            </p>
            <form action="{{ route('settings.downloadBackup') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary py-3 px-4 fw-bold rounded-3 w-100">
                    <i class="bi bi-cloud-arrow-down-fill me-2"></i> Unduh Database Sekarang
                </button>
            </form>
        </div>

        <div class="custom-card border-danger border-opacity-50 mt-4 mb-4">
            <h5 class="fw-bold mb-3 text-danger"><i class="bi bi-arrow-clockwise me-2"></i>Restore Database</h5>
            <p class="text-muted mb-3 small">
                Unggah file backup (<code>.json</code>) untuk mengembalikan data. <strong>Peringatan:</strong> Seluruh data saat ini akan terhapus dan digantikan oleh file yang Anda unggah.
            </p>
            <form action="{{ route('settings.restoreBackup') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('PERINGATAN!\n\nSeluruh data absensi dan pegawai saat ini akan dihapus dan digantikan oleh file backup yang Anda pilih.\n\nApakah Anda yakin ingin melanjutkan?');">
                @csrf
                <div class="mb-3">
                    <input class="form-control" type="file" name="backup_file" accept=".json" required>
                </div>
                <button type="submit" class="btn btn-danger py-2 px-4 fw-bold rounded-3 w-100">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Restore Sekarang
                </button>
            </form>
        </div>

        <div class="custom-card border-danger border border-2 border-opacity-25 bg-danger bg-opacity-10 mt-4">
            <h5 class="text-danger fw-bold border-bottom border-danger border-opacity-25 pb-2 mb-3">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>Zona Bahaya
            </h5>
            <p class="text-dark small mb-4">Fitur ini akan menghapus <strong>seluruh</strong> data berikut secara permanen:</p>
            <ul class="text-dark small mb-4">
                <li>Data Kehadiran / Absensi</li>
                <li>Data Izin / Sakit</li>
                <li>Data Siswa / Staff beserta Fotonya</li>
                <li>Data Kategori (Status)</li>
                <li>Data Kelas (Jabatan)</li>
            </ul>
            <p class="text-danger small fw-bold mb-4">Aksi ini TIDAK DAPAT DIBATALKAN. Gunakan hanya jika Anda ingin menghapus data dummy / uji coba.</p>
            
            <button type="button" class="btn btn-danger w-100 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#resetModal">
                <i class="bi bi-trash3-fill me-2"></i>Kosongkan Data Dummy
            </button>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="custom-card bg-light border-0 h-100">
            <h5 class="fw-bold mb-3"><i class="bi bi-clock-history text-info me-2"></i>Riwayat Auto-Backup</h5>
            <p class="text-muted small mb-3">
                Sistem secara otomatis akan membuat 1 file salinan setiap kali ada aktivitas login pertama di hari tersebut.
            </p>
            
            <div class="list-group list-group-flush rounded-3 border">
                @forelse($backups as $backup)
                    <div class="list-group-item bg-white d-flex justify-content-between align-items-center p-3">
                        <div>
                            <h6 class="mb-1 fw-bold text-dark"><i class="bi bi-file-earmark-code text-secondary me-2"></i>{{ $backup['name'] }}</h6>
                            <small class="text-muted">{{ $backup['date'] }} &bull; {{ $backup['size'] }}</small>
                        </div>
                    </div>
                @empty
                    <div class="list-group-item text-center p-4 text-muted bg-white">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        Belum ada riwayat auto-backup.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Reset -->
<div class="modal fade" id="resetModal" tabindex="-1" aria-labelledby="resetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-danger border-2">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title fw-bold" id="resetModalLabel"><i class="bi bi-exclamation-triangle-fill me-2"></i>Peringatan Keras!</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('settings.reset-database') }}">
                @csrf
                <div class="modal-body">
                    <p class="mb-3">Silakan pilih bagian data mana saja yang ingin Anda hapus. <strong>Tindakan ini tidak dapat dibatalkan!</strong></p>
                    
                    <div class="form-check mb-3 p-3 bg-light rounded border">
                        <input class="form-check-input ms-1" type="checkbox" name="clear_attendances" id="cbAttendances" value="1" checked>
                        <label class="form-check-label ms-2 fw-bold" for="cbAttendances">
                            Hapus Data Kehadiran & Izin
                        </label>
                        <div class="text-muted small ms-2 mt-1">Hanya menghapus riwayat absen. Data Siswa tetap aman.</div>
                    </div>

                    <div class="form-check mb-3 p-3 bg-light rounded border">
                        <input class="form-check-input ms-1" type="checkbox" name="clear_employees" id="cbEmployees" value="1">
                        <label class="form-check-label ms-2 fw-bold" for="cbEmployees">
                            Hapus Data Siswa & Staff (beserta fotonya)
                        </label>
                        <div class="text-danger small ms-2 mt-1">Otomatis menghapus Data Kehadiran juga.</div>
                    </div>

                    <div class="form-check mb-3 p-3 bg-light rounded border">
                        <input class="form-check-input ms-1" type="checkbox" name="clear_masters" id="cbMasters" value="1">
                        <label class="form-check-label ms-2 fw-bold" for="cbMasters">
                            Hapus Data Kategori & Kelas
                        </label>
                        <div class="text-danger small ms-2 mt-1">Otomatis menghapus Data Siswa dan Kehadiran juga.</div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger fw-bold"><i class="bi bi-trash3-fill me-2"></i>Ya, Hapus Data Terpilih!</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cbAttendances = document.getElementById('cbAttendances');
        const cbEmployees = document.getElementById('cbEmployees');
        const cbMasters = document.getElementById('cbMasters');

        function updateCheckboxes() {
            // Jika Masters dicentang, paksa Employees dan Attendances dicentang dan dikunci
            if (cbMasters.checked) {
                cbEmployees.checked = true;
                cbEmployees.disabled = true;
                
                cbAttendances.checked = true;
                cbAttendances.disabled = true;
            } 
            // Jika Employees dicentang, paksa Attendances dicentang dan dikunci
            else if (cbEmployees.checked) {
                cbEmployees.disabled = false;
                
                cbAttendances.checked = true;
                cbAttendances.disabled = true;
            } 
            // Bebaskan jika tidak ada
            else {
                cbEmployees.disabled = false;
                cbAttendances.disabled = false;
            }
        }

        cbMasters.addEventListener('change', updateCheckboxes);
        cbEmployees.addEventListener('change', updateCheckboxes);
        
        // Sebelum submit form, pastikan checkbox yang di-disable tetap ikut terkirim dengan mengaktifkannya kembali sesaat sebelum submit
        const form = cbMasters.closest('form');
        form.addEventListener('submit', function() {
            cbAttendances.disabled = false;
            cbEmployees.disabled = false;
        });
    });
</script>
@endsection
