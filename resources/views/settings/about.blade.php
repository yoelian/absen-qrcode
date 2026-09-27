@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h3 class="text-dark fw-bold"><i class="bi bi-info-circle-fill me-2 text-primary"></i>Tentang Aplikasi</h3>
    <p class="text-muted m-0">Informasi sistem dan kredit pengembang.</p>
</div>

<div class="row justify-content-center mt-5">
    <div class="col-lg-6">
        <div class="custom-card text-center p-5 border-0 bg-white" style="box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
            
            <div class="mb-4">
                <div class="d-inline-block bg-primary text-white rounded-circle p-4 mb-3 shadow">
                    <i class="bi bi-qr-code-scan" style="font-size: 3rem;"></i>
                </div>
                <h2 class="fw-bold text-dark mb-0">AbsenPro</h2>
                <span class="badge bg-light text-primary border px-3 py-2 mt-2 rounded-pill">Versi 1.2.7 (Offline Edition)</span>
            </div>

            <p class="text-muted mb-5">
                Sistem Informasi Kehadiran dengan teknologi pemindaian QR Code dan Barcode yang dirancang khusus untuk kecepatan, keandalan, dan kemudahan rekapitulasi secara *offline*.
            </p>

            <hr class="text-muted opacity-25">

            <div class="mt-4 pt-2">
                <p class="text-muted small text-uppercase fw-bold letter-spacing-1 mb-2">Dikembangkan dengan ❤️ oleh</p>
                <h4 class="fw-bold text-primary mb-1">Yobel Liandri</h4>
                <h6 class="text-muted mb-3">Tim Support: Junianto</h6>
                <p class="text-muted small">&copy; {{ date('Y') }} Hak Cipta Dilindungi.</p>
            </div>
            
        </div>
    </div>
</div>
@endsection
