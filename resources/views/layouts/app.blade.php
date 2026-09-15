<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Rencana Setelah Lulus') — {{ \App\Models\Setting::get('nama_sekolah', 'SMK Negeri 1') }}</title>

    {{-- Google Fonts: Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --brand-primary: #3157A4;
            --brand-primary-hover: #28498B;
            --brand-primary-dark: #243F78;
            --brand-primary-light: #EFF4FB;
            --brand-dark: #1F2937;
            --brand-navy: #1F355F;
            --kuliah-color: #3157A4;
            --bekerja-color: #B7791F;
            --wirausaha-color: #5925DC;
            --surface-ground: #F7F8FA;
            --surface-card: #ffffff;
            --border-subtle: #E4E7EC;
            --border-focus: #B2CCFF;
            --text-primary: #1F2937;
            --text-secondary: #667085;
            --text-muted: #98A2B3;
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-xl: 16px;
            --radius-full: 9999px;
            --shadow-subtle: 0 1px 2px rgba(16, 24, 40, 0.04);
            --shadow-card: 0 1px 2px rgba(16, 24, 40, 0.04);
            --shadow-elevated: 0 4px 6px -1px rgba(16, 24, 40, 0.06), 0 2px 4px -2px rgba(16, 24, 40, 0.04);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--surface-ground);
            color: var(--text-primary);
            min-height: 100vh;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ===== TOP NAVBAR ===== */
        .student-topbar {
            background: #ffffff;
            border-bottom: 1px solid var(--border-subtle);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 12px 0;
            transition: all 0.2s ease;
        }

        .brand-logo-badge {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-md);
            background: #1F355F;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .brand-school-name {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--brand-dark);
            line-height: 1.2;
            letter-spacing: -0.01em;
        }

        .brand-app-subtext {
            font-size: 0.72rem;
            font-weight: 500;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Desktop Nav Link Style (Point 8 & 9) */
        .nav-app-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 0.88rem;
            font-weight: 500;
            color: #667085;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .nav-app-link i {
            color: #667085;
            font-size: 1rem;
        }
        .nav-app-link:hover {
            color: #1F2937;
            background: #F7F8FA;
        }
        .nav-app-link.active {
            color: #3157A4;
            font-weight: 600;
            background: #F0F4FA;
        }
        .nav-app-link.active i {
            color: #3157A4;
        }

        .student-profile-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            padding: 4px 12px 4px 6px;
            border-radius: 6px;
            box-shadow: var(--shadow-subtle);
            text-decoration: none;
            color: var(--text-primary);
        }

        .student-avatar {
            width: 30px;
            height: 30px;
            border-radius: 4px;
            background: #EFF4FB;
            color: #3157A4;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.82rem;
            border: 1px solid #D0D5DD;
        }

        .student-chip-info {
            text-align: left;
            line-height: 1.15;
        }

        .student-chip-name {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--brand-dark);
            max-width: 140px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .student-chip-class {
            font-size: 0.68rem;
            font-weight: 600;
            color: #3157A4;
        }

        /* ===== MAIN CONTAINER ===== */
        .student-main-content {
            padding: 24px 0 96px;
        }

        @media (min-width: 768px) {
            .student-main-content {
                padding: 32px 0 48px;
            }
        }

        /* ===== PRO CARDS (Point 2 & 5) ===== */
        .card-pro {
            background: var(--surface-card);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-card);
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .card-pro-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-subtle);
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-pro-body {
            padding: 20px;
        }

        /* ===== BUTTONS & TACTILE TOUCH (Point 3) ===== */
        .btn-brand-primary {
            background: #3157A4;
            color: #ffffff;
            border: 1px solid #3157A4;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 10px 20px;
            border-radius: 8px;
            box-shadow: 0 1px 2px rgba(16, 24, 40, 0.05);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.15s ease;
            text-decoration: none;
            min-height: 42px;
        }

        .btn-brand-primary:hover {
            background: #28498B;
            border-color: #28498B;
            color: #ffffff;
        }

        .btn-brand-primary:active {
            background: #243F78;
            border-color: #243F78;
        }

        .btn-brand-outline {
            background: #ffffff;
            color: #667085;
            border: 1px solid var(--border-subtle);
            font-weight: 600;
            font-size: 0.88rem;
            padding: 9px 18px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
            text-decoration: none;
            min-height: 42px;
        }

        .btn-brand-outline:hover {
            background: #F7F8FA;
            color: #3157A4;
            border-color: #D0D5DD;
        }

        .btn-brand-outline:active {
            background: #F0F4FA;
        }

        /* ===== MOBILE BOTTOM NAVIGATION BAR ===== */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 64px;
            background: #ffffff;
            border-top: 1px solid var(--border-subtle);
            z-index: 1050;
            padding: 0 12px calc(env(safe-area-inset-bottom, 0px));
            justify-content: space-around;
            align-items: center;
            box-shadow: 0 -2px 8px rgba(16, 24, 40, 0.04);
        }

        .mobile-nav-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #667085;
            font-size: 0.68rem;
            font-weight: 500;
            gap: 4px;
            padding: 6px 14px;
            border-radius: 6px;
            transition: all 0.15s ease;
        }

        .mobile-nav-link i {
            font-size: 1.2rem;
            line-height: 1;
            color: #667085;
        }

        .mobile-nav-link.active {
            color: #3157A4;
            font-weight: 600;
        }

        .mobile-nav-link.active i {
            color: #3157A4;
        }

        @media (max-width: 768px) {
            .mobile-bottom-nav {
                display: flex;
            }
        }

        /* ===== BADGES (Point 4) ===== */
        .badge-pill-soft {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.78rem;
            font-weight: 600;
            border: 1px solid transparent;
        }

        .badge-kuliah-soft { background: #EFF4FB; color: #3157A4; border-color: #D0D5DD; }
        .badge-bekerja-soft { background: #FFF7E6; color: #8A6116; border-color: #F1D99B; }
        .badge-wirausaha-soft { background: #F4F3FF; color: #5925DC; border-color: #D9D6FE; }
        .badge-verified-soft { background: #F0FDF4; color: #276749; border-color: #B7E4C7; }

        /* ===== BOOTSTRAP OVERRIDES FOR INSTITUTIONAL PALETTE ===== */
        .bg-primary { background-color: #3157A4 !important; }
        .text-primary { color: #3157A4 !important; }
        .border-primary { border-color: #3157A4 !important; }

        .btn-primary {
            background-color: #3157A4 !important;
            border-color: #3157A4 !important;
            color: #ffffff !important;
            box-shadow: 0 1px 2px rgba(16, 24, 40, 0.05) !important;
            font-weight: 600 !important;
            border-radius: 8px !important;
        }
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: #28498B !important;
            border-color: #28498B !important;
            color: #ffffff !important;
        }

        .btn-outline-primary {
            color: #3157A4 !important;
            border-color: #E4E7EC !important;
            background-color: #ffffff !important;
            font-weight: 600 !important;
            border-radius: 8px !important;
        }
        .btn-outline-primary:hover, .btn-outline-primary:focus, .btn-outline-primary:active {
            background-color: #F0F4FA !important;
            color: #28498B !important;
            border-color: #3157A4 !important;
        }

        .btn-warning {
            background-color: #FFF7E6 !important;
            border-color: #F1D99B !important;
            color: #8A6116 !important;
            font-weight: 600 !important;
            border-radius: 8px !important;
        }
        .btn-warning:hover {
            background-color: #FEF0C7 !important;
            border-color: #F1D99B !important;
            color: #7A520C !important;
        }

        .bg-success-subtle { background-color: #F0FDF4 !important; color: #276749 !important; border: 1px solid #B7E4C7 !important; }
        .bg-warning-subtle { background-color: #FFF7E6 !important; color: #8A6116 !important; border: 1px solid #F1D99B !important; }
        .bg-primary-subtle { background-color: #EFF4FB !important; color: #3157A4 !important; border: 1px solid #D0D5DD !important; }
        .bg-secondary-subtle { background-color: #F2F4F7 !important; color: #344054 !important; border: 1px solid #E4E7EC !important; }

        .progress-bar.bg-primary {
            background-color: #3157A4 !important;
        }

        /* ===== FLOATING FORM INPUTS ===== */
        .form-control-pro {
            width: 100%;
            height: 46px;
            padding: 10px 16px;
            border-radius: 8px;
            border: 1px solid var(--border-subtle);
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--brand-dark);
            background: #ffffff;
            transition: all 0.2s ease;
            outline: none;
        }

        .form-control-pro:focus {
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 3px rgba(49, 87, 164, 0.12);
        }

        .form-control-pro::placeholder {
            color: #98A2B3;
            font-weight: 400;
        }

        /* ===== GLOBAL PRINT STYLING ===== */
        @media print {
            .student-topbar,
            .mobile-bottom-nav,
            .btn,
            button,
            .alert,
            .d-print-none {
                display: none !important;
            }

            body {
                background: #ffffff !important;
                color: #000000 !important;
                font-size: 10pt !important;
            }

            .student-main-content {
                padding: 0 !important;
                margin: 0 !important;
            }

            .container {
                max-width: 100% !important;
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    @auth
    {{-- TOPBAR --}}
    <header class="student-topbar">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                {{-- Brand --}}
                <div class="d-flex align-items-center gap-2 gap-sm-3">
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('siswa.dashboard') }}"
                       class="text-decoration-none d-flex align-items-center gap-2 gap-sm-3">
                        <div class="brand-logo-badge">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <div>
                            <div class="brand-school-name">{{ \App\Models\Setting::get('nama_sekolah', 'SMK Negeri 1') }}</div>
                            <div class="brand-app-subtext">
                                <span>{{ \App\Models\Setting::get('nama_aplikasi', 'Rencana Lulus') }}</span>
                                <span class="d-none d-sm-inline">• TA {{ \App\Models\Setting::get('tahun_ajaran', '2024/2025') }}</span>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Desktop Navigation Links --}}
                @if(auth()->user()->isSiswa())
                <nav class="d-none d-lg-flex align-items-center gap-1">
                    <a href="{{ route('siswa.dashboard') }}" class="nav-app-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-house-door"></i> Beranda
                    </a>
                    <a href="{{ route('siswa.profil') }}" class="nav-app-link {{ request()->routeIs('siswa.profil*') ? 'active' : '' }}">
                        <i class="bi bi-person-vcard"></i> Biodata Diri
                        @if(!auth()->user()->is_biodata_confirmed)
                            <span class="badge bg-warning text-dark ms-1" style="font-size: 0.65rem; font-weight: 700; padding: 2px 6px;">Wajib</span>
                        @endif
                    </a>
                    <a href="{{ route('tes.index') }}" class="nav-app-link {{ request()->routeIs('tes.*') ? 'active' : '' }}">
                        <i class="bi bi-lightning-charge"></i> Tes Minat
                    </a>
                    <a href="{{ route('tes.rekomendasi') }}" class="nav-app-link {{ request()->routeIs('tes.rekomendasi') ? 'active' : '' }}">
                        <i class="bi bi-stars"></i> Rekomendasi
                    </a>
                    <a href="{{ route('siswa.rencana') }}" class="nav-app-link {{ request()->routeIs('siswa.rencana*') ? 'active' : '' }}">
                        <i class="bi bi-compass"></i> Rencana
                    </a>
                    <a href="{{ route('siswa.pilihan-saya') }}" class="nav-app-link {{ request()->routeIs('siswa.pilihan-saya') ? 'active' : '' }}">
                        <i class="bi bi-person-badge"></i> Pilihan Saya
                    </a>
                </nav>
                @endif

                {{-- User Profile & Logout on Desktop --}}
                <div class="d-flex align-items-center gap-2 gap-sm-3">
                    @if(auth()->user()->isSiswa())
                        <a href="{{ route('siswa.profil') }}" class="student-profile-chip text-decoration-none" title="Lihat & Perbarui Biodata Diri">
                            <div class="student-avatar">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="student-chip-info">
                                <div class="student-chip-name">{{ auth()->user()->name }}</div>
                                <div class="student-chip-class">
                                    {{ auth()->user()->kelas ?? 'Siswa' }}
                                </div>
                            </div>
                        </a>
                    @else
                        <div class="student-profile-chip">
                            <div class="student-avatar">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="student-chip-info">
                                <div class="student-chip-name">{{ auth()->user()->name }}</div>
                                <div class="student-chip-class">
                                    {{ auth()->user()->isAdmin() ? 'Administrator' : 'Guru BK' }}
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" class="d-none d-md-block">
                        @csrf
                        <button type="submit" class="btn-brand-outline px-3" style="min-height:38px; padding:6px 12px; font-size:0.8rem;" title="Keluar">
                            <i class="bi bi-box-arrow-right"></i>
                            <span class="d-none d-lg-inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    {{-- MOBILE BOTTOM BAR --}}
    @if(auth()->user()->isSiswa())
    <nav class="mobile-bottom-nav">
        <a href="{{ route('siswa.dashboard') }}"
           class="mobile-nav-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door{{ request()->routeIs('siswa.dashboard') ? '-fill' : '' }}"></i>
            <span>Beranda</span>
        </a>

        <a href="{{ route('siswa.profil') }}"
           class="mobile-nav-link {{ request()->routeIs('siswa.profil*') ? 'active' : '' }} position-relative">
            <i class="bi bi-person-vcard{{ request()->routeIs('siswa.profil*') ? '-fill' : '' }}"></i>
            <span>Biodata</span>
            @if(!auth()->user()->is_biodata_confirmed)
                <span class="position-absolute top-1 start-50 translate-middle p-1 bg-danger border border-light rounded-circle" style="margin-left: 12px; margin-top: 6px;"></span>
            @endif
        </a>

        <a href="{{ route('tes.index') }}"
           class="mobile-nav-link {{ request()->routeIs('tes.*') ? 'active' : '' }}">
            <i class="bi bi-lightning-charge{{ request()->routeIs('tes.*') ? '-fill' : '' }}"></i>
            <span>Tes</span>
        </a>

        <a href="{{ route('siswa.rencana') }}"
           class="mobile-nav-link {{ request()->routeIs('siswa.rencana*') ? 'active' : '' }}">
            <i class="bi bi-compass{{ request()->routeIs('siswa.rencana*') ? '-fill' : '' }}"></i>
            <span>Rencana</span>
        </a>

        <a href="{{ route('siswa.pilihan-saya') }}"
           class="mobile-nav-link {{ request()->routeIs('siswa.pilihan-saya') ? 'active' : '' }}">
            <i class="bi bi-person-badge{{ request()->routeIs('siswa.pilihan-saya') ? '-fill' : '' }}"></i>
            <span>Pilihan</span>
        </a>

        <form action="{{ route('logout') }}" method="POST" id="mobileLogoutForm" class="m-0">
            @csrf
            <button type="submit" class="mobile-nav-link border-0 bg-transparent" onclick="return confirm('Apakah kamu yakin ingin keluar dari akun?');">
                <i class="bi bi-box-arrow-right"></i>
                <span>Keluar</span>
            </button>
        </form>
    </nav>
    @endif
    @endauth

    {{-- MAIN CONTENT --}}
    <main class="student-main-content">
        <div class="container">
            {{-- Flash Alert Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="border-radius:var(--radius-md); font-size:0.88rem; background:#ecfdf5; color:#065f46; border-left:4px solid #10b981 !important;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="border-radius:var(--radius-md); font-size:0.88rem; background:#fffbeb; color:#92400e; border-left:4px solid #f59e0b !important;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill text-warning fs-5"></i>
                        <div>{{ session('warning') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="border-radius:var(--radius-md); font-size:0.88rem; background:#eff6ff; color:#1e40af; border-left:4px solid #3b82f6 !important;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle-fill text-primary fs-5"></i>
                        <div>{{ session('info') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    {{-- Bootstrap 5 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
