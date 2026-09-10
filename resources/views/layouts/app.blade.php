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
            --brand-primary: #2563eb;
            --brand-primary-hover: #1d4ed8;
            --brand-primary-light: #eff6ff;
            --brand-dark: #0f172a;
            --brand-navy: #0b1329;
            --kuliah-color: #2563eb;
            --bekerja-color: #d97706;
            --wirausaha-color: #7c3aed;
            --surface-ground: #f8fafc;
            --surface-card: #ffffff;
            --border-subtle: #e2e8f0;
            --border-focus: #93c5fd;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
            --radius-full: 9999px;
            --shadow-subtle: 0 1px 3px rgba(15, 23, 42, 0.04), 0 1px 2px rgba(15, 23, 42, 0.02);
            --shadow-card: 0 4px 16px -2px rgba(15, 23, 42, 0.05), 0 2px 6px -1px rgba(15, 23, 42, 0.03);
            --shadow-elevated: 0 12px 32px -4px rgba(15, 23, 42, 0.08), 0 4px 12px -2px rgba(15, 23, 42, 0.04);
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
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
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
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.25rem;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
            flex-shrink: 0;
        }

        .brand-school-name {
            font-size: 0.88rem;
            font-weight: 800;
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

        .student-profile-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            padding: 5px 12px 5px 6px;
            border-radius: var(--radius-full);
            box-shadow: var(--shadow-subtle);
            text-decoration: none;
            color: var(--text-primary);
        }

        .student-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            color: #4338ca;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.82rem;
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
            color: #2563eb;
        }

        /* ===== MAIN CONTAINER ===== */
        .student-main-content {
            padding: 24px 0 96px; /* extra bottom padding on mobile for bottom bar */
        }

        @media (min-width: 768px) {
            .student-main-content {
                padding: 32px 0 48px;
            }
        }

        /* ===== PRO CARDS ===== */
        .card-pro {
            background: var(--surface-card);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-card);
            overflow: hidden;
            transition: all 0.25s ease;
        }

        .card-pro-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--border-subtle);
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-pro-body {
            padding: 22px;
        }

        /* ===== BUTTONS & TACTILE TOUCH ===== */
        .btn-brand-primary {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            border: none;
            font-weight: 700;
            font-size: 0.92rem;
            padding: 12px 24px;
            border-radius: var(--radius-md);
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
            min-height: 46px; /* mobile tap target rule */
        }

        .btn-brand-primary:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.3);
        }

        .btn-brand-primary:active {
            transform: scale(0.98);
        }

        .btn-brand-outline {
            background: #ffffff;
            color: var(--text-secondary);
            border: 1px solid var(--border-subtle);
            font-weight: 600;
            font-size: 0.88rem;
            padding: 11px 20px;
            border-radius: var(--radius-md);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            min-height: 46px;
        }

        .btn-brand-outline:hover {
            background: #f8fafc;
            color: var(--brand-dark);
            border-color: #cbd5e1;
        }

        .btn-brand-outline:active {
            transform: scale(0.98);
        }

        /* ===== MOBILE BOTTOM NAVIGATION BAR ===== */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 68px;
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-top: 1px solid var(--border-subtle);
            z-index: 1050;
            padding: 0 12px calc(env(safe-area-inset-bottom, 0px));
            justify-content: space-around;
            align-items: center;
            box-shadow: 0 -4px 16px rgba(0, 0, 0, 0.04);
        }

        .mobile-nav-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #94a3b8;
            font-size: 0.68rem;
            font-weight: 600;
            gap: 4px;
            padding: 6px 14px;
            border-radius: var(--radius-md);
            transition: all 0.2s;
        }

        .mobile-nav-link i {
            font-size: 1.25rem;
            line-height: 1;
        }

        .mobile-nav-link.active {
            color: var(--brand-primary);
        }

        .mobile-nav-link:active {
            transform: scale(0.92);
        }

        @media (max-width: 768px) {
            .mobile-bottom-nav {
                display: flex;
            }
        }

        /* ===== BADGES ===== */
        .badge-pill-soft {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: var(--radius-full);
            font-size: 0.78rem;
            font-weight: 700;
        }

        .badge-kuliah-soft { background: #dbeafe; color: #1d4ed8; }
        .badge-bekerja-soft { background: #fef3c7; color: #b45309; }
        .badge-wirausaha-soft { background: #ede9fe; color: #6d28d9; }
        .badge-verified-soft { background: #dcfce7; color: #15803d; }

        /* ===== FLOATING FORM INPUTS ===== */
        .form-control-pro {
            width: 100%;
            height: 48px;
            padding: 10px 16px;
            border-radius: var(--radius-md);
            border: 1.5px solid var(--border-subtle);
            font-size: 0.92rem;
            font-weight: 500;
            color: var(--brand-dark);
            background: #ffffff;
            transition: all 0.2s ease;
            outline: none;
        }

        .form-control-pro:focus {
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .form-control-pro::placeholder {
            color: #94a3b8;
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
                    <a href="{{ route('siswa.dashboard') }}" class="btn-brand-outline py-2 px-3 border-0 {{ request()->routeIs('siswa.dashboard') ? 'text-primary fw-bold bg-light' : '' }}">
                        <i class="bi bi-house-door"></i> Beranda
                    </a>
                    <a href="{{ route('tes.index') }}" class="btn-brand-outline py-2 px-3 border-0 {{ request()->routeIs('tes.*') ? 'text-primary fw-bold bg-light' : '' }}">
                        <i class="bi bi-lightning-charge"></i> Tes Minat
                    </a>
                    <a href="{{ route('tes.rekomendasi') }}" class="btn-brand-outline py-2 px-3 border-0 {{ request()->routeIs('tes.rekomendasi') ? 'text-primary fw-bold bg-light' : '' }}">
                        <i class="bi bi-stars"></i> Rekomendasi
                    </a>
                    <a href="{{ route('siswa.rencana') }}" class="btn-brand-outline py-2 px-3 border-0 {{ request()->routeIs('siswa.rencana*') ? 'text-primary fw-bold bg-light' : '' }}">
                        <i class="bi bi-compass"></i> Rencana
                    </a>
                    <a href="{{ route('siswa.pilihan-saya') }}" class="btn-brand-outline py-2 px-3 border-0 {{ request()->routeIs('siswa.pilihan-saya') ? 'text-primary fw-bold bg-light' : '' }}">
                        <i class="bi bi-person-badge"></i> Pilihan Saya
                    </a>
                </nav>
                @endif

                {{-- User Profile & Logout on Desktop --}}
                <div class="d-flex align-items-center gap-2 gap-sm-3">
                    <div class="student-profile-chip">
                        <div class="student-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="student-chip-info">
                            <div class="student-chip-name">{{ auth()->user()->name }}</div>
                            <div class="student-chip-class">
                                {{ auth()->user()->kelas ?? (auth()->user()->isAdmin() ? 'Administrator' : 'Siswa') }}
                            </div>
                        </div>
                    </div>

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

        <a href="{{ route('tes.index') }}"
           class="mobile-nav-link {{ request()->routeIs('tes.*') ? 'active' : '' }}">
            <i class="bi bi-lightning-charge{{ request()->routeIs('tes.*') ? '-fill' : '' }}"></i>
            <span>Tes Minat</span>
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
