<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>AbsenPro — {{ App\Models\Setting::get('school_name', 'SMAN 1 Bentian Besar') }}</title>

    <!-- Google Fonts: Outfit & Inter -->
    <link href="{{ asset('assets/css/fonts.css') }}" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-icons.min.css') }}">

    <!-- Custom Modern 2026 UI Design Styling -->
    <style>
        html {
            font-size: 14px;
        }
        :root {
            --primary-bg: #f8fafc;
            --sidebar-bg: #0f172a;
            --sidebar-surface: #1e293b;
            --card-bg: #ffffff;
            --accent-color: #0284c7;
            --accent-gradient: linear-gradient(135deg, #0284c7 0%, #2563eb 100%);
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-subtle: rgba(226, 232, 240, 0.8);
            --card-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.04), 0 8px 10px -6px rgba(15, 23, 42, 0.02);
            --card-shadow-hover: 0 20px 35px -10px rgba(15, 23, 42, 0.08), 0 1px 3px 0 rgba(15, 23, 42, 0.05);
        }

        body {
            font-family: 'Outfit', 'Inter', sans-serif;
            background-color: var(--primary-bg);
            color: var(--text-main);
            overflow-x: hidden;
        }

        /* ===== Modern Sidebar Styling ===== */
        .sidebar {
            width: 250px;
            background-color: var(--sidebar-bg);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            border-right: 1px solid rgba(255, 255, 255, 0.06);
            box-shadow: 10px 0 30px rgba(0, 0, 0, 0.15);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .sidebar::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
        }

        .sidebar-brand {
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .brand-icon-box {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: var(--accent-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.35rem;
            box-shadow: 0 8px 16px -4px rgba(2, 132, 199, 0.4);
            flex-shrink: 0;
        }

        .brand-text-title {
            font-weight: 800;
            font-size: 1.25rem;
            letter-spacing: -0.3px;
            background: linear-gradient(90deg, #38bdf8, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1.1;
        }

        .brand-text-sub {
            font-size: 0.72rem;
            color: #64748b;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .sidebar-section-title {
            padding: 18px 20px 6px 20px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #475569;
        }

        .sidebar-menu {
            list-style: none;
            padding: 12px 14px;
            margin: 0;
            flex-grow: 1;
        }

        .sidebar-item {
            margin-bottom: 4px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 10px 14px;
            color: #94a3b8;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 500;
            font-size: 0.92rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .sidebar-link i {
            margin-right: 12px;
            font-size: 1.15rem;
            color: #64748b;
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            color: #f8fafc;
            background-color: rgba(255, 255, 255, 0.05);
        }

        .sidebar-link:hover i {
            color: #38bdf8;
            transform: scale(1.1);
        }

        .sidebar-item.active .sidebar-link {
            background: linear-gradient(90deg, rgba(2, 132, 199, 0.2) 0%, rgba(2, 132, 199, 0.05) 100%);
            color: #38bdf8;
            font-weight: 600;
            border-left: 3px solid #38bdf8;
        }

        .sidebar-item.active .sidebar-link i {
            color: #38bdf8;
        }

        .sidebar-user-footer {
            padding: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            background: rgba(15, 23, 42, 0.8);
        }

        /* Sidebar Collapsed State */
        body.sidebar-collapsed .sidebar {
            width: 80px;
        }
        body.sidebar-collapsed .sidebar:hover {
            width: 250px;
            box-shadow: 20px 0 50px rgba(0, 0, 0, 0.35);
        }
        body.sidebar-collapsed .sidebar:not(:hover) .sidebar-brand .brand-text,
        body.sidebar-collapsed .sidebar:not(:hover) .sidebar-link span,
        body.sidebar-collapsed .sidebar:not(:hover) .sidebar-link .bi-chevron-down,
        body.sidebar-collapsed .sidebar:not(:hover) .sidebar-section-title,
        body.sidebar-collapsed .sidebar:not(:hover) .user-footer-details {
            display: none !important;
        }
        body.sidebar-collapsed .main-content {
            margin-left: 80px;
        }
        body.sidebar-collapsed .sidebar:not(:hover) .sidebar-link {
            justify-content: center;
            padding: 12px 0;
        }
        body.sidebar-collapsed .sidebar:not(:hover) .sidebar-link i {
            margin-right: 0;
            font-size: 1.35rem;
        }

        /* ===== Main Content Wrapper ===== */
        .main-content {
            margin-left: 250px;
            padding: 28px 36px;
            min-height: 100vh;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ===== Modern Navbar Top ===== */
        .navbar-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            padding: 14px 20px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-subtle);
            border-radius: 18px;
            box-shadow: var(--card-shadow);
        }

        .btn-toggle-sidebar {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            border: 1px solid var(--border-subtle);
            background: #ffffff;
            color: #475569;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .btn-toggle-sidebar:hover {
            background: #f1f5f9;
            color: #0284c7;
        }

        .live-pulse-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-radius: 20px;
            color: #059669;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulseGreen 2s infinite;
        }

        @keyframes pulseGreen {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* ===== Modern Custom Cards ===== */
        .custom-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-subtle);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            color: var(--text-main);
            padding: 24px;
            margin-bottom: 24px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .custom-card:hover {
            box-shadow: var(--card-shadow-hover);
            border-color: rgba(2, 132, 199, 0.2);
        }

        .stat-card-modern {
            padding: 22px;
            border-radius: 20px;
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            box-shadow: var(--card-shadow);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .stat-card-modern:hover {
            transform: translateY(-3px);
            box-shadow: var(--card-shadow-hover);
        }

        .stat-card-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--accent-gradient);
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .stat-card-modern:hover::before {
            opacity: 1;
        }

        .stat-icon-wrapper {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            flex-shrink: 0;
        }

        /* Form Controls */
        .form-control, .form-select {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            color: var(--text-main);
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
        }

        .btn-primary {
            background: var(--accent-gradient);
            border: none;
            border-radius: 12px;
            padding: 10px 18px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(2, 132, 199, 0.35);
        }

        .btn-secondary {
            border-radius: 12px;
            padding: 10px 18px;
            font-weight: 600;
        }

        /* Table Styling */
        .table-responsive {
            border-radius: 16px;
            border: 1px solid var(--border-subtle);
            overflow: hidden;
        }

        .table thead th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border-bottom: 1px solid #e2e8f0;
            padding: 14px 16px;
        }

        .table tbody td {
            padding: 14px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        /* Badges */
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
            letter-spacing: 0.2px;
        }

        .pagination {
            gap: 6px;
            align-items: center;
            margin-bottom: 0;
        }
        .page-link {
            border-radius: 10px !important;
            border: 1px solid #e2e8f0;
            color: #475569;
            font-size: 0.875rem;
            padding: 8px 14px;
            font-weight: 600;
            transition: all 0.2s ease;
            background: #fff;
        }
        .page-link:hover {
            background: #f0f9ff;
            border-color: #93c5fd;
            color: #0284c7;
        }
        .page-item.active .page-link {
            background: var(--accent-gradient);
            border-color: #0284c7;
            color: #fff;
            box-shadow: 0 4px 10px rgba(2, 132, 199, 0.3);
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar Layout -->
    <aside class="sidebar">
        <!-- Brand Header -->
        <div class="sidebar-brand">
            <div class="brand-icon-box">
                <i class="bi bi-qr-code-scan"></i>
            </div>
            <div class="brand-text">
                <div class="brand-text-title">AbsenPro</div>
                <div class="brand-text-sub">Presensi Digital v2.0</div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <ul class="sidebar-menu">
            <li class="sidebar-section-title">Menu Utama</li>

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

            <li class="sidebar-section-title">Data & Presensi</li>

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
                    <span>Input Izin / Cuti</span>
                </a>
            </li>

            <li class="sidebar-section-title">Laporan & Pengaturan</li>

            <li class="sidebar-item {{ Request::routeIs('reports.*') ? 'active' : '' }}">
                <a href="{{ route('reports.index') }}" class="sidebar-link">
                    <i class="bi bi-file-earmark-bar-graph-fill"></i>
                    <span>Laporan Cetak / Excel</span>
                </a>
            </li>
            <li class="sidebar-item {{ Request::routeIs('statistics.*') ? 'active' : '' }}">
                <a href="{{ route('statistics.index') }}" class="sidebar-link">
                    <i class="bi bi-pie-chart-fill"></i>
                    <span>Statistik Grafik</span>
                </a>
            </li>

            @php
                $isSettingsActive = Request::routeIs('users.*') || Request::routeIs('settings.*');
            @endphp
            <li class="sidebar-item {{ $isSettingsActive ? 'active' : '' }}">
                <a href="#settingsSubmenu" data-bs-toggle="collapse" class="sidebar-link {{ $isSettingsActive ? '' : 'collapsed' }}" aria-expanded="{{ $isSettingsActive ? 'true' : 'false' }}">
                    <i class="bi bi-gear-fill"></i>
                    <span>Pengaturan Sistem</span>
                    <i class="bi bi-chevron-down ms-auto" style="font-size: 0.8rem; margin-right: 0;"></i>
                </a>
                <ul class="collapse list-unstyled {{ $isSettingsActive ? 'show' : '' }}" id="settingsSubmenu" style="background: rgba(0,0,0,0.25); border-radius: 12px; margin-top: 4px; padding: 4px;">
                    <li>
                        <a href="{{ route('settings.index') }}" class="sidebar-link" style="padding-left: 40px; font-size: 0.88rem; {{ Request::routeIs('settings.index') ? 'color: #38bdf8;' : '' }}">
                            <i class="bi bi-sliders" style="font-size: 0.95rem;"></i> Jam & Profil
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users.index') }}" class="sidebar-link" style="padding-left: 40px; font-size: 0.88rem; {{ Request::routeIs('users.*') ? 'color: #38bdf8;' : '' }}">
                            <i class="bi bi-person-badge-fill" style="font-size: 0.95rem;"></i> Manajemen User
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('settings.backup') }}" class="sidebar-link" style="padding-left: 40px; font-size: 0.88rem; {{ Request::routeIs('settings.backup') ? 'color: #38bdf8;' : '' }}">
                            <i class="bi bi-database-fill-gear" style="font-size: 0.95rem;"></i> Backup & Reset
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('settings.logs') }}" class="sidebar-link" style="padding-left: 40px; font-size: 0.88rem; {{ Request::routeIs('settings.logs') ? 'color: #38bdf8;' : '' }}">
                            <i class="bi bi-terminal-fill" style="font-size: 0.95rem;"></i> Log System
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('settings.about') }}" class="sidebar-link" style="padding-left: 40px; font-size: 0.88rem; {{ Request::routeIs('settings.about') ? 'color: #38bdf8;' : '' }}">
                            <i class="bi bi-info-circle-fill" style="font-size: 0.95rem;"></i> Tentang AbsenPro
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

        <!-- Sidebar User Footer -->
        <div class="sidebar-user-footer">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2 user-footer-details">
                    <div class="rounded-circle bg-primary bg-opacity-25 text-info d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div style="line-height: 1.2;">
                        <div class="text-light fw-semibold" style="font-size: 0.85rem;">{{ Auth::user() ? Str::limit(Auth::user()->name, 14) : 'Operator' }}</div>
                        <small class="text-muted" style="font-size: 0.75rem;">Online</small>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger border-0 rounded-circle" title="Keluar dari Aplikasi">
                        <i class="bi bi-box-arrow-right fs-6"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Layout -->
    <main class="main-content">
        <!-- Top Nav Info Bar -->
        <header class="navbar-top">
            <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggle" class="btn-toggle-sidebar" title="Perkecil/Perbesar Sidebar">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div>
                    <h5 class="m-0 fw-bold text-dark">{{ App\Models\Setting::get('school_name', 'SMAN 1 Bentian Besar') }}</h5>
                    <p class="text-muted m-0 small">Sistem Informasi & Presensi Digital Offline</p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('scan') }}" class="btn btn-sm btn-outline-primary d-none d-md-flex align-items-center gap-2 rounded-pill px-3 py-2 fw-semibold">
                    <span class="live-pulse-badge p-0 px-1 border-0"><span class="pulse-dot"></span></span>
                    <span>Buka Layar Scan</span>
                </a>

                <div class="d-flex align-items-center gap-2 border-start ps-3">
                    <div class="text-end d-none d-sm-block">
                        <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ Auth::user() ? Auth::user()->name : 'Operator' }}</div>
                        <span class="badge bg-primary bg-opacity-10 text-primary py-1 px-2" style="font-size: 0.7rem;">{{ Auth::user() && Auth::user()->roles->count() > 0 ? strtoupper(Auth::user()->roles->first()->name) : 'ADMIN' }}</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 shadow-sm p-3 mb-4 d-flex align-items-center" role="alert" style="background-color: #ecfdf5; border-left: 4px solid #10b981 !important; color: #065f46;">
                <i class="bi bi-check-circle-fill fs-5 me-2 text-success"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 rounded-4 shadow-sm p-3 mb-4 d-flex align-items-center" role="alert" style="background-color: #fef2f2; border-left: 4px solid #ef4444 !important; color: #991b1b;">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-2 text-danger"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Global Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-body p-4 text-center">
                    <div class="text-danger mb-3">
                        <i class="bi bi-trash3-fill" style="font-size: 2.8rem;"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Konfirmasi Hapus</h5>
                    <p class="text-muted small mb-4" id="confirmDeleteMessage">Apakah Anda yakin ingin menghapus data ini?</p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger rounded-pill px-4" id="btnConfirmDelete">Ya, Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Generic Action Confirmation Modal -->
    <div class="modal fade" id="actionConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-body p-4 text-center">
                    <div class="mb-3" id="actionConfirmIconWrapper">
                        <i class="bi bi-question-circle text-primary" id="actionConfirmIcon" style="font-size: 2.8rem;"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2" id="actionConfirmTitle">Konfirmasi</h5>
                    <p class="text-muted small mb-4" id="actionConfirmMessage">Apakah Anda yakin?</p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary rounded-pill px-4" id="btnConfirmAction">Ya, Lanjutkan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        // Sidebar Toggle with localStorage persistence
        const sidebarToggle = document.getElementById('sidebarToggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                document.body.classList.toggle('sidebar-collapsed');
                localStorage.setItem('sidebar_collapsed', document.body.classList.contains('sidebar-collapsed') ? '1' : '0');
            });

            if (localStorage.getItem('sidebar_collapsed') === '1') {
                document.body.classList.add('sidebar-collapsed');
            }
        }

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
            btnConfirm.className = 'btn rounded-pill px-4 btn-' + (btnClass || 'primary');
            btnConfirm.innerText = btnText || 'Ya, Lanjutkan';
            
            const newBtn = btnConfirm.cloneNode(true);
            btnConfirm.parentNode.replaceChild(newBtn, btnConfirm);
            
            newBtn.addEventListener('click', function() {
                newBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
                newBtn.disabled = true;
                form.submit();
            });
            
            actionModal.show();
        }
    </script>
    @yield('scripts')
</body>
</html>
