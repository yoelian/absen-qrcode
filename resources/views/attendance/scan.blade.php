@extends('layouts.app')

@section('styles')
<style>
    /* Mode feedback status - Menggunakan warna lembut & modern glow */
    .scan-wrapper.status-success { 
        background: radial-gradient(circle at top right, rgba(16, 185, 129, 0.15), transparent 70%);
        border-color: rgba(16, 185, 129, 0.4); 
    }
    .scan-wrapper.status-checkout { 
        background: radial-gradient(circle at top right, rgba(2, 132, 199, 0.15), transparent 70%);
        border-color: rgba(2, 132, 199, 0.4); 
    }
    .scan-wrapper.status-error { 
        background: radial-gradient(circle at top right, rgba(239, 68, 68, 0.15), transparent 70%);
        border-color: rgba(239, 68, 68, 0.4); 
    }

    .scan-wrapper {
        border-radius: 24px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
        background-color: transparent;
    }
    
    /* Kiosk Mode (Fullscreen) Styles */
    body.kiosk-mode .sidebar {
        display: none !important;
    }
    body.kiosk-mode .main-content {
        margin-left: 0 !important;
        padding: 1.5rem !important;
    }

    .scan-card {
        background-color: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 24px;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.04), 0 8px 10px -6px rgba(15, 23, 42, 0.02);
        padding: 32px;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .header-logo {
        max-height: 56px;
        object-fit: contain;
    }

    .clock-widget {
        font-size: 2.8rem;
        font-weight: 800;
        letter-spacing: -1px;
        font-variant-numeric: tabular-nums;
        background: linear-gradient(135deg, #0f172a 0%, #0284c7 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        line-height: 1;
    }

    .date-widget {
        font-size: 0.95rem;
        color: #64748b;
        font-weight: 600;
    }

    .profile-img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 24px;
        border: 4px solid #ffffff;
        box-shadow: 0 12px 25px -5px rgba(15, 23, 42, 0.15);
    }

    /* Focus Trap input */
    .focus-trap {
        position: absolute;
        opacity: 0;
        pointer-events: none;
        width: 1px;
        height: 1px;
    }

    /* Scanner Camera box */
    #webcam-reader {
        width: 100%;
        border-radius: 18px;
        overflow: hidden;
        border: 1px solid rgba(226, 232, 240, 0.8);
    }

    .badge-category {
        padding: 6px 14px;
        border-radius: 30px;
        font-weight: 700;
        font-size: 0.8rem;
    }

    /* Pulse Scanner Graphic */
    .scanner-target-box {
        border: 2px dashed rgba(2, 132, 199, 0.4);
        border-radius: 20px;
        padding: 24px;
        background: rgba(2, 132, 199, 0.02);
        transition: all 0.3s ease;
    }
    .scanner-target-box:hover {
        border-color: #0284c7;
        background: rgba(2, 132, 199, 0.04);
    }
</style>
@endsection

@section('content')
<div id="scanWrapper" class="scan-wrapper p-2">
    <!-- Focus Input untuk hardware scanner (Keyboard Wedge) -->
    <input type="text" id="scannerInput" class="focus-trap" autofocus autocomplete="off">

    <div class="container-fluid px-0">
        
        <!-- Header Profil Sekolah -->
        <div class="d-flex align-items-center justify-content-between mb-4 bg-white p-4 rounded-4 shadow-sm border" style="border-color: rgba(226, 232, 240, 0.8) !important;">
            <div class="d-flex align-items-center gap-3">
                @if($schoolLogo)
                    <img src="{{ route('avatar.serve', $schoolLogo) }}" class="header-logo rounded-3 shadow-sm">
                @else
                    <div class="rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 52px; height: 52px; background: linear-gradient(135deg, #0284c7 0%, #2563eb 100%); color: white;">
                        <i class="bi bi-qr-code" style="font-size: 1.6rem;"></i>
                    </div>
                @endif
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h4 class="m-0 fw-bold text-dark">{{ $schoolName }}</h4>
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25" style="font-size: 0.72rem;">
                            <i class="bi bi-broadcast me-1"></i>Live Kiosk
                        </span>
                    </div>
                    <p class="m-0 text-muted" style="font-size: 0.88rem;">Pindai Kartu Presensi Digital Siswa, Guru & Staff</p>
                </div>
            </div>
            <div class="d-none d-md-flex align-items-center justify-content-end gap-3">
                <div class="text-end">
                    <div id="realtime-clock" class="clock-widget">00:00:00</div>
                    <div id="realtime-date" class="date-widget">Hari, 00 Bulan 2026</div>
                </div>
                <button id="fullscreenBtn" class="btn btn-outline-secondary shadow-sm rounded-3 d-flex align-items-center justify-content-center" title="Masuk Mode Layar Penuh (Kiosk)" style="width: 44px; height: 44px;">
                    <i class="bi bi-arrows-fullscreen fs-5"></i>
                </button>
            </div>
        </div>

        <div class="row g-4">
            <!-- Sisi Kiri: Webcam scanner & Status absensi -->
            <div class="col-lg-6">
                <div class="scan-card h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="scanner-target-box text-center mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 64px; height: 64px; background: rgba(2, 132, 199, 0.1); color: #0284c7;">
                                <i class="bi bi-upc-scan" style="font-size: 2rem;"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-1">Arahkan Barcode / QR Code</h5>
                            <p class="text-muted small mb-0">Dekatkan barcode/QR pada ID Card ke barcode scanner hardware Anda. Sistem membaca otomatis.</p>
                        </div>
                        
                        <!-- Toggle Webcam Scanner -->
                        <div>
                            <button id="toggleWebcamBtn" class="btn btn-outline-primary w-100 py-2 fw-bold rounded-3">
                                <i class="bi bi-camera-fill me-2"></i>Aktifkan Kamera / Webcam
                            </button>
                        </div>
                        
                        <div id="webcam-container" class="mt-3 d-none">
                            <div id="webcam-reader"></div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top border-secondary border-opacity-10 text-center">
                        <span class="text-muted small"><i class="bi bi-info-circle me-1 text-primary"></i>Mendukung Barcode Scanner USB / Wireless & Kamera bawaan.</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="scan-card h-100 d-flex flex-column align-items-center justify-content-center text-center py-4" id="responseCard">
                    
                    <!-- Area Idle State -->
                    <div id="idleState" class="w-100">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 shadow-sm" style="width: 90px; height: 90px; background: #f8fafc; border: 2px dashed #cbd5e1;">
                            <i class="bi bi-person-badge text-muted" style="font-size: 2.5rem;"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">SIAP MEMINDAI</h4>
                        <p class="text-muted small px-3 mb-0">Silakan dekatkan barcode kartu Anda ke sensor scanner.</p>
                    </div>

                    <!-- Area Active State (Result) -->
                    <div id="activeState" class="d-none w-100">
                        <img id="resultPhoto" src="{{ asset('images/default-avatar.png') }}" class="profile-img mb-3">
                        <h3 class="fw-bold text-dark mb-2" id="resultName" style="text-transform: uppercase;">-</h3>
                        <div class="mb-3 d-flex justify-content-center gap-2">
                            <span class="badge badge-category bg-primary" id="resultCategory">SISWA</span>
                            <span class="badge badge-category bg-secondary" id="resultPosition">-</span>
                        </div>
                        
                        <div class="alert mt-2 px-4 py-3 border-0 rounded-4 shadow-sm" id="resultAlert" style="display: block !important; width: 100%; min-height: 80px;">
                            <h5 class="alert-heading fw-bold m-0" id="resultAlertTitle">CHECK-IN SUKSES</h5>
                            <p class="m-0 mt-1 small" id="resultAlertMsg">Berhasil melakukan check-in</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Tabel Riwayat Absen Terbaru -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="custom-card shadow-sm border-0 bg-white">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Scan Terbaru Hari Ini</h5>
                    <div class="table-responsive" style="max-height: 320px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0" id="recentScansTable">
                            <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                                <tr>
                                    <th>Waktu</th>
                                    <th>Nama Siswa / Staff</th>
                                    <th>Kategori / Kelas</th>
                                    <th>Tipe</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentAttendances as $att)
                                    @php
                                        if ($att->check_out) {
                                            $scanType = 'PULANG';
                                            $typeClass = 'bg-info text-dark';
                                        } elseif ($att->check_in) {
                                            $scanType = 'MASUK';
                                            $typeClass = 'bg-primary';
                                        } else {
                                            $scanType = '-';
                                            $typeClass = 'bg-secondary';
                                        }
                                        
                                        $statusClass = 'bg-success';
                                        $statusText = 'HADIR';
                                        
                                        if ($att->status == 'terlambat') {
                                            $statusClass = 'bg-warning text-dark';
                                            $statusText = 'TERLAMBAT';
                                            
                                            if ($att->check_in && $att->employee->category->target_in_time) {
                                                $targetIn = \Carbon\Carbon::parse($att->employee->category->target_in_time)->startOfMinute();
                                                $timeCarbon = \Carbon\Carbon::parse($att->check_in)->startOfMinute();
                                                if ($timeCarbon->greaterThan($targetIn)) {
                                                    $mins = intval(abs($timeCarbon->diffInMinutes($targetIn)));
                                                    if ($mins > 0) {
                                                        if ($mins < 60) $statusText .= " ($mins mnt)";
                                                        else {
                                                            $h = floor($mins / 60);
                                                            $m = $mins % 60;
                                                            $statusText .= $m == 0 ? " ($h jam)" : " ($h j $m m)";
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td><span class="fw-bold">{{ \Carbon\Carbon::parse($att->updated_at)->format('H:i') }}</span></td>
                                        <td class="fw-medium">{{ $att->employee->name }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ strtoupper($att->employee->category->name ?? '') }}</span>
                                            <span class="badge bg-secondary">{{ $att->employee->position->name ?? '-' }}</span>
                                        </td>
                                        <td><span class="badge {{ $typeClass }}">{{ $scanType }}</span></td>
                                        <td><span class="badge {{ $statusClass }}">{{ $statusText }}</span></td>
                                    </tr>
                                @empty
                                    <tr id="emptyScanRow">
                                        <td colspan="5" class="text-center text-muted py-4">Belum ada data scan absensi hari ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<!-- Web Audio API untuk BEEP feedback -->
<script>


    function playBeep(type) {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            
            osc.connect(gain);
            gain.connect(ctx.destination);
            
            if (type === 'success') {
                osc.frequency.setValueAtTime(1000, ctx.currentTime);
                gain.gain.setValueAtTime(0.1, ctx.currentTime);
                osc.start();
                osc.stop(ctx.currentTime + 0.15);
            } else if (type == 'checkout') {
                osc.frequency.setValueAtTime(800, ctx.currentTime);
                gain.gain.setValueAtTime(0.1, ctx.currentTime);
                osc.start();
                osc.stop(ctx.currentTime + 0.3);
            } else {
                osc.frequency.setValueAtTime(300, ctx.currentTime);
                gain.gain.setValueAtTime(0.2, ctx.currentTime);
                osc.start();
                osc.stop(ctx.currentTime + 0.5);
            }
        } catch (e) {
            console.error("Audio beep gagal dijalankan", e);
        }
    }
</script>

<!-- html5-qrcode dari CDN untuk kamera webcam backup -->
<script src="{{ asset('assets/js/html5-qrcode.min.js') }}" type="text/javascript"></script>

<script>
    // 1. Setup Jam & Tanggal Realtime
    function updateTime() {
        const now = new Date();
        const timeStr = now.toTimeString().split(' ')[0];
        const clockElem = document.getElementById('realtime-clock');
        if(clockElem) clockElem.textContent = timeStr;
        
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const dateElem = document.getElementById('realtime-date');
        if(dateElem) dateElem.textContent = now.toLocaleDateString('id-ID', options);
    }
    setInterval(updateTime, 1000);
    updateTime();

    // 2. LOGIKA HARDWARE SCANNER (Focus Trap)
    const scannerInput = document.getElementById('scannerInput');
    
    function keepFocus() {
        if(scannerInput) scannerInput.focus();
    }
    // Jika kita klik area manapun di wrapper, paksa fokus kembali ke input
    const wrapper = document.getElementById('scanWrapper');
    if(wrapper) {
        wrapper.addEventListener('click', () => {
            setTimeout(keepFocus, 100);
        });
    }
    keepFocus();

    let buffer = "";
    let lastKeyTime = Date.now();
    
    if(scannerInput) {
        scannerInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                
                // Ambil kode dari value input (karena USB scanner bertindak sebagai keyboard biasa)
                // Fallback ke buffer jika value kosong karena alasan tertentu
                let code = scannerInput.value.trim();
                if (!code) code = buffer.trim();
                
                if (code.length >= 3) {
                    processScanCode(code);
                }
                
                // Reset setelah scan
                buffer = "";
                scannerInput.value = "";
            } else if (e.key.length === 1) {
                // USB scanner biasanya mengetik dengan cepat
                buffer += e.key;
            }
        });
        
        // Sebagai cadangan, jika kehilangan fokus tapi ada input yang masuk secara global
        document.addEventListener('keydown', function(e) {
            if(e.target !== scannerInput && e.key.length === 1 && !e.ctrlKey && !e.altKey && !e.metaKey) {
                keepFocus();
            }
        });
    }

    let resetTimeout;
    let isProcessing = false;
    let lastScannedCode = "";
    let lastScanTime = 0;
    const SAME_CODE_COOLDOWN = 6000; // Abaikan barcode yang sama jika di-scan ulang dalam 6 detik
    
    function processScanCode(code) {
        const currentTime = Date.now();
        
        // 1. Blokir barcode yang SAMA jika di-scan berulang kali dalam jeda cooldown
        if (code === lastScannedCode && (currentTime - lastScanTime) < SAME_CODE_COOLDOWN) {
            return;
        }

        // 2. Cegah request bertumpuk jika server sedang lambat merespon
        if (isProcessing) return;
        
        isProcessing = true;
        lastScannedCode = code;
        lastScanTime = currentTime;

        if (typeof startProgressBar === 'function') startProgressBar();

        fetch('{{ route("attendance.scan") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ code: code })
        })
        .then(res => res.json())
        .then(data => {
            if (typeof finishProgressBar === 'function') finishProgressBar();
            if (data.success) {
                showScanResult(data);
            } else {
                showScanError(data);
            }
            isProcessing = false; // Buka kunci untuk barcode LAIN
        })
        .catch(err => {
            if (typeof finishProgressBar === 'function') finishProgressBar();
            showScanError({ message: "Gagal menghubungi server. Hubungi admin." });
            isProcessing = false; // Buka kunci untuk barcode LAIN
        });
    }

    function showScanResult(data) {
        const idleState = document.getElementById('idleState');
        const activeState = document.getElementById('activeState');
        const resultPhoto = document.getElementById('resultPhoto');
        const resultName = document.getElementById('resultName');
        const resultCategory = document.getElementById('resultCategory');
        const resultPosition = document.getElementById('resultPosition');
        const alertBox = document.getElementById('resultAlert');
        const alertTitle = document.getElementById('resultAlertTitle');
        const alertMsg = document.getElementById('resultAlertMsg');
        let scanWrapper = document.getElementById('scanWrapper');

        if(idleState) idleState.classList.add('d-none');
        if(activeState) activeState.classList.remove('d-none');
        
        if(resultPhoto) resultPhoto.src = data.photo;
        if(resultName) resultName.textContent = data.name;
        if(resultCategory) resultCategory.textContent = data.category;
        if(resultPosition) resultPosition.textContent = data.position;
        
        if (!scanWrapper) scanWrapper = document.body;
        
        scanWrapper.className = "scan-wrapper p-3";
        
        if (alertBox) {
            alertBox.className = "alert mt-3 px-4 py-3 border-0 rounded-4";
            alertBox.style.display = "block";
        } else {
            console.warn("Element resultAlert tidak ditemukan di DOM!");
        }

        if (data.action === 'check_in') {
            scanWrapper.classList.add('status-success');
            if (alertBox) alertBox.classList.add(data.status === 'terlambat' ? 'bg-warning' : 'bg-success', 'text-white');
            if (alertTitle) alertTitle.textContent = data.status === 'terlambat' ? 'CHECK-IN TERLAMBAT' : 'CHECK-IN BERHASIL';
        } else {
            scanWrapper.classList.add('status-checkout');
            if (alertBox) alertBox.classList.add('bg-primary', 'text-white');
            if (alertTitle) alertTitle.textContent = 'CHECK-OUT PULANG';
        }
        
        if (alertMsg) {
            alertMsg.textContent = data.message;
        }
        
        // Update Tabel Riwayat Terbaru
        const tbody = document.querySelector('#recentScansTable tbody');
        if (tbody) {
            const emptyRow = document.getElementById('emptyScanRow');
            if (emptyRow) emptyRow.remove();
            
            const tr = document.createElement('tr');
            
            let scanType = data.action === 'check_in' ? 'MASUK' : 'PULANG';
            let typeClass = data.action === 'check_in' ? 'bg-primary' : 'bg-info text-dark';
            let typeBadge = `<span class="badge ${typeClass}">${scanType}</span>`;
            
            let statusBadge = '<span class="badge bg-success">HADIR</span>';
            if (data.status === 'terlambat') {
                let text = 'TERLAMBAT';
                if (data.late_text) text += ' (' + data.late_text + ')';
                statusBadge = '<span class="badge bg-warning text-dark">' + text + '</span>';
            } else if (data.status === 'hadir') {
                statusBadge = '<span class="badge bg-success">HADIR</span>';
            }
            
            tr.innerHTML = `
                <td><span class="fw-bold">${data.time}</span></td>
                <td class="fw-medium">${data.name}</td>
                <td>
                    <span class="badge bg-primary">${data.category}</span>
                    <span class="badge bg-secondary">${data.position}</span>
                </td>
                <td>${typeBadge}</td>
                <td>${statusBadge}</td>
            `;
            
            // Insert at top
            tbody.insertBefore(tr, tbody.firstChild);
            
            // Keep only max 20 rows
            if (tbody.children.length > 20) {
                tbody.removeChild(tbody.lastChild);
            }
        }
        
        playBeep(data.action === 'check_in' && data.status === 'terlambat' ? 'error' : (data.action === 'check_in' ? 'success' : 'checkout'));
        
        if (resetTimeout) clearTimeout(resetTimeout);
        resetTimeout = setTimeout(resetToIdle, 6000);
    }

    function showScanError(data) {
        let message = typeof data === 'string' ? data : (data.message || "Gagal memproses absensi.");

        let idleState = document.getElementById('idleState');
        let activeState = document.getElementById('activeState');
        if(idleState) idleState.classList.add('d-none');
        if(activeState) activeState.classList.remove('d-none');

        let resultPhoto = document.getElementById('resultPhoto');
        if(resultPhoto) resultPhoto.src = data.photo || "{{ asset('images/default-avatar.png') }}";
        
        let resultName = document.getElementById('resultName');
        if(resultName) resultName.textContent = data.name || "TIDAK DITEMUKAN";
        
        let resultCategory = document.getElementById('resultCategory');
        if(resultCategory) resultCategory.textContent = data.category || "SISTEM";
        
        let resultPosition = document.getElementById('resultPosition');
        if(resultPosition) resultPosition.textContent = data.position || "-";

        const alertBox = document.getElementById('resultAlert');
        const alertTitle = document.getElementById('resultAlertTitle');
        const alertMsg = document.getElementById('resultAlertMsg');
        let scanWrapper = document.getElementById('scanWrapper');
        if (!scanWrapper) scanWrapper = document.body;

        scanWrapper.className = "scan-wrapper p-3 status-error";
        
        if (alertBox) {
            alertBox.className = "alert mt-3 px-4 py-3 border-0 rounded-4 bg-danger text-white";
        } else {
            console.warn("Element resultAlert tidak ditemukan di DOM!");
        }
        
        if (alertTitle) alertTitle.textContent = "PEMINDAIAN DITOLAK";
        if (alertMsg) alertMsg.textContent = message;
        
        playBeep('error');

        if (resetTimeout) clearTimeout(resetTimeout);
        resetTimeout = setTimeout(resetToIdle, 6000);
    }

    function resetToIdle() {
        let scanWrapper = document.getElementById('scanWrapper');
        if (!scanWrapper) scanWrapper = document.body;
        
        scanWrapper.className = "scan-wrapper p-3";
        
        let idleState = document.getElementById('idleState');
        let activeState = document.getElementById('activeState');
        if(idleState) idleState.classList.remove('d-none');
        if(activeState) activeState.classList.add('d-none');
        
        keepFocus();
    }

    let html5QrScanner = null;
    const toggleWebcamBtn = document.getElementById('toggleWebcamBtn');
    const webcamContainer = document.getElementById('webcam-container');

    if(toggleWebcamBtn) {
        toggleWebcamBtn.addEventListener('click', function() {
            if (webcamContainer.classList.contains('d-none')) {
                webcamContainer.classList.remove('d-none');
                toggleWebcamBtn.innerHTML = '<i class="bi bi-camera-video-off-fill me-2"></i>Matikan Kamera';
                
                html5QrScanner = new Html5QrcodeScanner("webcam-reader", { fps: 10, qrbox: 250 });
                html5QrScanner.render(onScanSuccess, onScanError);
            } else {
                webcamContainer.classList.add('d-none');
                toggleWebcamBtn.innerHTML = '<i class="bi bi-camera-fill me-2"></i>Aktifkan Kamera Laptop / Webcam';
                if (html5QrScanner) {
                    html5QrScanner.clear();
                }
            }
        });
    }

    function onScanSuccess(decodedText, decodedResult) {
        processScanCode(decodedText);
    }

    function onScanError(errorMessage) {}
    
    // Fullscreen Logic
    const fullscreenBtn = document.getElementById('fullscreenBtn');
    if(fullscreenBtn) {
        fullscreenBtn.addEventListener('click', () => {
            if (!document.fullscreenElement) {
                const elem = document.documentElement;
                if (elem.requestFullscreen) {
                    elem.requestFullscreen();
                } else if (elem.mozRequestFullScreen) { /* Firefox */
                    elem.mozRequestFullScreen();
                } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
                    elem.webkitRequestFullscreen();
                } else if (elem.msRequestFullscreen) { /* IE/Edge */
                    elem.msRequestFullscreen();
                }
                document.body.classList.add('kiosk-mode');
                fullscreenBtn.innerHTML = '<i class="bi bi-fullscreen-exit fs-4"></i>';
                fullscreenBtn.setAttribute('title', 'Keluar Mode Layar Penuh');
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
                document.body.classList.remove('kiosk-mode');
                fullscreenBtn.innerHTML = '<i class="bi bi-arrows-fullscreen fs-4"></i>';
                fullscreenBtn.setAttribute('title', 'Masuk Mode Layar Penuh');
            }
        });
        
        // Listen to fullscreen changes to update button state properly if user exits with ESC key
        document.addEventListener('fullscreenchange', () => {
            if (!document.fullscreenElement) {
                document.body.classList.remove('kiosk-mode');
                fullscreenBtn.innerHTML = '<i class="bi bi-arrows-fullscreen fs-4"></i>';
                fullscreenBtn.setAttribute('title', 'Masuk Mode Layar Penuh');
            }
        });
    }
</script>
@endsection
