<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>AbsenPro — SMAN 1 Bentian Besar</title>

    <!-- Google Fonts: Outfit & Inter -->
    <link href="{{ asset('assets/css/fonts.css') }}" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-icons.min.css') }}">

    <!-- Custom Premium Design styling -->
    <style>
        html {
            font-size: 13.5px; /* Skala ulang UI (efek zoom out ~85%) yang aman untuk Electron tanpa merusak klik input */
        }
        :root {
            --primary-bg: #f8fafc; /* Biru sangat muda yang bersih */
            --sidebar-bg: #0f172a; /* Slate gelap untuk sidebar kontras */
            --card-bg: #ffffff; /* Card putih bersih */
            --accent-color: #0284c7; /* Biru langit cerah */
            --text-main: #0f172a; /* Teks gelap utama */
            --text-muted: #64748b; /* Teks sekunder */
            --btn-primary: #0284c7;
        }

        body {
            font-family: 'Outfit', 'Inter', sans-serif;
            background-color: var(--primary-bg);
            color: var(--text-main);
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 240px;
            background-color: var(--sidebar-bg);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 10px 0 30px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        /* Custom Scrollbar untuk Sidebar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .sidebar-brand {
            padding: 24px;
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            background: linear-gradient(90deg, #3b82f6, #60a5fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-menu {
            list-style: none;
            padding: 20px 12px;
            margin: 0;
        }

        .sidebar-item {
            margin-bottom: 8px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .sidebar-link i {
            margin-right: 12px;
            font-size: 1.2rem;
            transition: all 0.2s ease;
        }

        .sidebar-link:hover, .sidebar-item.active .sidebar-link {
            background-color: rgba(2, 132, 199, 0.15);
            color: #38bdf8; /* Biru terang cerah */
        }

        /* Sidebar Collapsed State */
        /* Sidebar Collapsed State (Auto-expand on hover) */
        body.sidebar-collapsed .sidebar {
            width: 80px;
        }
        body.sidebar-collapsed .sidebar:hover {
            width: 240px;
            box-shadow: 15px 0 40px rgba(0, 0, 0, 0.3);
        }
        body.sidebar-collapsed .sidebar:not(:hover) .sidebar-brand span, 
        body.sidebar-collapsed .sidebar:not(:hover) .sidebar-link span,
        body.sidebar-collapsed .sidebar:not(:hover) .sidebar-link .bi-chevron-down {
            display: none;
        }
        body.sidebar-collapsed .main-content {
            margin-left: 80px;
            transition: all 0.3s ease;
        }
        body.sidebar-collapsed .sidebar:not(:hover) .sidebar-link {
            justify-content: center;
            padding: 12px 0;
        }
        body.sidebar-collapsed .sidebar:not(:hover) .sidebar-link i {
            margin-right: 0;
            font-size: 1.4rem;
        }
        body.sidebar-collapsed .sidebar:not(:hover) .sidebar-brand {
            padding: 24px 0;
            justify-content: center;
        }
        body.sidebar-collapsed .sidebar:not(:hover) .sidebar-brand i {
            margin-right: 0 !important;
        }
        body.sidebar-collapsed .sidebar:not(:hover) #settingsSubmenu {
            display: none !important;
        }

        /* Main Content Wrapper */
        .main-content {
            margin-left: 240px;
            padding: 40px;
            min-height: 100vh;
        }

        /* Card Customization */
        .custom-card {
            background-color: var(--card-bg);
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            color: var(--text-main);
            padding: 24px;
            margin-bottom: 24px;
        }

        /* Form Customization */
        .form-control, .form-select {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            color: var(--text-main);
            border-radius: 8px;
            padding: 12px;
        }

        .form-control:focus, .form-select:focus {
            background-color: #ffffff;
            border-color: var(--accent-color);
            color: var(--text-main);
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
        }

        .table {
            color: var(--text-main);
        }
        
        .table-hover tbody tr:hover {
            color: var(--text-main);
            background-color: rgba(0, 0, 0, 0.02);
        }

        .navbar-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding-bottom: 16px;
        }
        
        .badge-siswa { background-color: #10b981; }
        .badge-guru { background-color: #3b82f6; }
        .badge-tu { background-color: #8b5cf6; }
        .badge-security { background-color: #f59e0b; }
        .badge-cs { background-color: #6366f1; }
        /* ===== PAGINATION STYLING ===== */
        .pagination {
            gap: 4px;
            align-items: center;
            margin-bottom: 0;
        }
        .page-link {
            border-radius: 8px !important;
            border: 1px solid #e2e8f0;
            color: #475569;
            font-size: 0.875rem;
            padding: 6px 12px;
            min-width: 36px;
            text-align: center;
            transition: all 0.2s;
            background: #fff;
            line-height: 1.5;
        }
        .page-link:hover {
            background: #f0f9ff;
            border-color: #93c5fd;
            color: #0284c7;
        }
        .page-item.active .page-link {
            background: #0284c7;
            border-color: #0284c7;
            color: #fff;
            font-weight: 600;
        }
        .page-item.disabled .page-link {
            background: #f8fafc;
            color: #cbd5e1;
            border-color: #e2e8f0;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar Layout -->
    <div class="sidebar">
        <div class="sidebar-brand d-flex align-items-center">
            <i class="bi bi-qr-code-scan me-2" style="font-size: 1.5rem; -webkit-text-fill-color: initial; color: #3b82f6;"></i>
            <span>AbsenPro</span>
        </div>
        <ul class="sidebar-menu">
            <li class="sidebar-item {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="sidebar-link">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sidebar-item {{ Request::routeIs('scan') ? 'active' : '' }}">
                <a href="{{ route('scan') }}" class="sidebar-link">
                    <i class="bi bi-camera-fill"></i>
                    <span>Layar Scan Absen</span>
                </a>
            </li>
            <li class="sidebar-item {{ Request::routeIs('employees.*') ? 'active' : '' }}">
                <a href="{{ route('employees.index') }}" class="sidebar-link">
                    <i class="bi bi-people-fill"></i>
                    <span>Siswa & Staff</span>
                </a>
            </li>
            <li class="sidebar-item {{ Request::routeIs('categories.*') ? 'active' : '' }}">
                <a href="{{ route('categories.index') }}" class="sidebar-link">
                    <i class="bi bi-tags-fill"></i>
                    <span>Master Kategori</span>
                </a>
            </li>
            <li class="sidebar-item {{ Request::routeIs('positions.*') ? 'active' : '' }}">
                <a href="{{ route('positions.index') }}" class="sidebar-link">
                    <i class="bi bi-briefcase-fill"></i>
                    <span>Jabatan & Kelas</span>
                </a>
            </li>
            <li class="sidebar-item {{ Request::routeIs('attendances.*') ? 'active' : '' }}">
                <a href="{{ route('attendances.index') }}" class="sidebar-link">
                    <i class="bi bi-calendar2-check-fill"></i>
                    <span>Rekap Kehadiran</span>
                </a>
            </li>
            <li class="sidebar-item {{ Request::routeIs('leaves.*') ? 'active' : '' }}">
                <a href="{{ route('leaves.index') }}" class="sidebar-link">
                    <i class="bi bi-file-earmark-medical-fill"></i>
                    <span>Input Izin/Cuti</span>
                </a>
            </li>
            <li class="sidebar-item {{ Request::routeIs('reports.*') ? 'active' : '' }}">
                <a href="{{ route('reports.index') }}" class="sidebar-link">
                    <i class="bi bi-file-earmark-bar-graph-fill"></i>
                    <span>Laporan Absensi</span>
                </a>
            </li>
            <li class="sidebar-item {{ Request::routeIs('statistics.*') ? 'active' : '' }}">
                <a href="{{ route('statistics.index') }}" class="sidebar-link">
                    <i class="bi bi-pie-chart-fill"></i>
                    <span>Statistik Kehadiran</span>
                </a>
            </li>
            @php
                $isSettingsActive = Request::routeIs('users.*') || Request::routeIs('settings.*');
            @endphp
            <li class="sidebar-item {{ $isSettingsActive ? 'active' : '' }}">
                <a href="#settingsSubmenu" data-bs-toggle="collapse" class="sidebar-link {{ $isSettingsActive ? '' : 'collapsed' }}" aria-expanded="{{ $isSettingsActive ? 'true' : 'false' }}">
                    <i class="bi bi-gear-fill"></i>
                    <span>Pengaturan</span>
                    <i class="bi bi-chevron-down ms-auto" style="font-size: 0.8rem; margin-right: 0;"></i>
                </a>
                <ul class="collapse list-unstyled {{ $isSettingsActive ? 'show' : '' }}" id="settingsSubmenu" style="background: rgba(0,0,0,0.2); border-radius: 8px; margin-top: 5px;">
                    <li>
                        <a href="{{ route('settings.index') }}" class="sidebar-link" style="padding-left: 45px; font-size: 0.9rem; {{ Request::routeIs('settings.index') ? 'color: #38bdf8;' : '' }}">
                            <i class="bi bi-sliders" style="font-size: 1rem;"></i> Sistem & Jam
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users.index') }}" class="sidebar-link" style="padding-left: 45px; font-size: 0.9rem; {{ Request::routeIs('users.*') ? 'color: #38bdf8;' : '' }}">
                            <i class="bi bi-people-fill" style="font-size: 1rem;"></i> Manajemen User
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('settings.backup') }}" class="sidebar-link" style="padding-left: 45px; font-size: 0.9rem; {{ Request::routeIs('settings.backup') ? 'color: #38bdf8;' : '' }}">
                            <i class="bi bi-hdd-network-fill" style="font-size: 1rem;"></i> Manajemen Database
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('settings.logs') }}" class="sidebar-link" style="padding-left: 45px; font-size: 0.9rem; {{ Request::routeIs('settings.logs') ? 'color: #38bdf8;' : '' }}">
                            <i class="bi bi-terminal-fill" style="font-size: 1rem;"></i> Log System / Debug
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('settings.about') }}" class="sidebar-link" style="padding-left: 45px; font-size: 0.9rem; {{ Request::routeIs('settings.about') ? 'color: #38bdf8;' : '' }}">
                            <i class="bi bi-info-circle-fill" style="font-size: 1rem;"></i> Tentang Aplikasi
                        </a>
                    </li>
                </ul>
            </li>
            <li class="sidebar-item mt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-link w-100 text-start bg-transparent border-0" style="outline: none;">
                        <i class="bi bi-box-arrow-left text-danger"></i>
                        <span class="text-danger">Keluar</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content Layout -->
    <div class="main-content">
        <!-- Top Nav Info Bar -->
        <div class="navbar-top">
            <div class="d-flex align-items-center">
                <button id="sidebarToggle" class="btn btn-light border-0 me-3 shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;" title="Minimize Sidebar">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <div>
                    <h4 class="m-0 font-weight-bold text-dark">{{ App\Models\Setting::get('school_name', 'SMAN 1 Bentian Besar') }}</h4>
                    <p class="text-muted m-0" style="font-size: 0.9rem;">Sistem Manajemen Absensi Sekolah Offline</p>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <span class="me-3 text-muted"><i class="bi bi-person-circle me-1"></i> {{ Auth::user() ? Auth::user()->name : 'Operator' }}</span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="background-color: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.2); color: #10b981;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Global Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-body p-4 text-center">
                    <div class="text-danger mb-3">
                        <i class="bi bi-exclamation-circle" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Konfirmasi Hapus</h5>
                    <p class="text-muted small mb-4" id="confirmDeleteMessage">Apakah Anda yakin ingin menghapus data ini?</p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger rounded-3 px-3" id="btnConfirmDelete">Ya, Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Generic Action Confirmation Modal -->
    <div class="modal fade" id="actionConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-body p-4 text-center">
                    <div class="mb-3" id="actionConfirmIconWrapper">
                        <i class="bi bi-question-circle text-primary" id="actionConfirmIcon" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2" id="actionConfirmTitle">Konfirmasi</h5>
                    <p class="text-muted small mb-4" id="actionConfirmMessage">Apakah Anda yakin?</p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary rounded-3 px-4" id="btnConfirmAction">Ya, Lanjutkan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        function confirmDelete(form, message) {
            event.preventDefault();
            document.getElementById('confirmDeleteMessage').innerText = message || 'Apakah Anda yakin ingin menghapus data ini?';
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            
            const btnConfirm = document.getElementById('btnConfirmDelete');
            const newBtn = btnConfirm.cloneNode(true);
            btnConfirm.parentNode.replaceChild(newBtn, btnConfirm);
            
            newBtn.addEventListener('click', function() {
                newBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menghapus...';
                newBtn.disabled = true;
                form.submit();
            });
            
            deleteModal.show();
        }

        function confirmAction(form, title, message, btnText, btnClass, iconClass, iconColor) {
            event.preventDefault();
            document.getElementById('actionConfirmTitle').innerText = title || 'Konfirmasi';
            document.getElementById('actionConfirmMessage').innerHTML = message || 'Apakah Anda yakin?';
            
            const icon = document.getElementById('actionConfirmIcon');
            icon.className = 'bi ' + (iconClass || 'bi-question-circle') + ' text-' + (iconColor || 'primary');
            
            const actionModal = new bootstrap.Modal(document.getElementById('actionConfirmModal'));
            
            const btnConfirm = document.getElementById('btnConfirmAction');
            const newBtn = btnConfirm.cloneNode(true);
            btnConfirm.parentNode.replaceChild(newBtn, btnConfirm);
            
            newBtn.className = 'btn rounded-3 px-4 btn-' + (btnClass || 'primary');
            newBtn.innerText = btnText || 'Ya, Lanjutkan';
            
            newBtn.addEventListener('click', function() {
                newBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
                newBtn.disabled = true;
                form.submit();
            });
            
            actionModal.show();
        }

        // Auto-dismiss Alerts setelah 4 detik
        document.addEventListener("DOMContentLoaded", function() {
            var alertList = document.querySelectorAll('.alert:not(.alert-info):not(#resultAlert)'); // Kecualikan alert info & scanner result alert
            alertList.forEach(function (alertNode) {
                setTimeout(function() {
                    if(document.body.contains(alertNode)) {
                        var bsAlert = new bootstrap.Alert(alertNode);
                        bsAlert.close();
                    }
                }, 4000);
            });
        });

        // Sidebar Toggle Logic
        document.addEventListener("DOMContentLoaded", function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            if (sidebarToggle) {
                // Restore state
                if (localStorage.getItem('sidebar-collapsed') === 'true') {
                    document.body.classList.add('sidebar-collapsed');
                }
                
                sidebarToggle.addEventListener('click', function() {
                    document.body.classList.toggle('sidebar-collapsed');
                    localStorage.setItem('sidebar-collapsed', document.body.classList.contains('sidebar-collapsed'));
                });
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
