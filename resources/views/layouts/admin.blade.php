<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') — Rencana Setelah Lulus</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --sidebar-bg: #0d1b2a;
            --sidebar-hover: rgba(255, 255, 255, 0.06);
            --sidebar-active: #2563eb;
            --primary-blue: #2563eb;
            --kuliah-color: #2563eb;
            --bekerja-color: #f59e0b;
            --wirausaha-color: #8b5cf6;
            --bg-canvas: #f8fafc;
            --card-border: #e2e8f0;
            --text-dark: #0f172a;
            --text-muted: #64748b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-canvas);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
        }

        /* ===== SIDEBAR ===== */
        .admin-sidebar {
            width: 250px;
            background-color: var(--sidebar-bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar-brand {
            padding: 24px 20px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-brand .brand-icon {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.35rem;
            flex-shrink: 0;
        }

        .sidebar-brand .brand-title {
            color: #ffffff;
            font-size: 0.92rem;
            font-weight: 700;
            line-height: 1.25;
            letter-spacing: -0.01em;
        }

        .sidebar-brand .brand-subtitle {
            color: #64748b;
            font-size: 0.75rem;
            font-weight: 400;
            margin-top: 2px;
        }

        .sidebar-menu {
            padding: 20px 14px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            border-radius: 8px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .sidebar-item i {
            font-size: 1.15rem;
            width: 20px;
            text-align: center;
        }

        .sidebar-item:hover {
            color: #ffffff;
            background-color: var(--sidebar-hover);
        }

        .sidebar-item.active {
            background-color: var(--sidebar-active);
            color: #ffffff;
            font-weight: 600;
        }

        .sidebar-footer {
            padding: 20px 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-logout-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 500;
            padding: 8px 12px;
            border-radius: 8px;
            background: transparent;
            border: none;
            width: 100%;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .sidebar-logout-btn:hover {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.08);
        }

        /* ===== MAIN CONTENT WRAPPER ===== */
        .admin-main {
            margin-left: 250px;
            flex-grow: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            width: calc(100% - 250px);
            background-color: var(--bg-canvas);
        }

        /* ===== TOP NAVBAR ===== */
        .admin-topbar {
            height: 64px;
            background: #ffffff;
            border-bottom: 1px solid var(--card-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .menu-toggle-btn {
            background: none;
            border: none;
            font-size: 1.35rem;
            color: var(--text-dark);
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 6px;
            display: flex;
            align-items: center;
        }

        .menu-toggle-btn:hover {
            background: #f1f5f9;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #e0e7ff;
            color: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
        }

        .user-details {
            line-height: 1.25;
            text-align: left;
        }

        .user-details .user-name {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .user-details .user-role {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* ===== CONTENT BODY ===== */
        .admin-content-body {
            padding: 28px 32px 48px;
            flex-grow: 1;
        }

        /* ===== PAGE HEADER ===== */
        .page-title-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .page-title-row h1 {
            font-size: 1.6rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            letter-spacing: -0.02em;
        }

        .page-title-row p {
            font-size: 0.88rem;
            color: var(--text-muted);
            margin: 3px 0 0;
        }

        .date-badge {
            background: #ffffff;
            border: 1px solid var(--card-border);
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 0.82rem;
            color: #475569;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        /* ===== STAT CARDS ===== */
        .stat-card-custom {
            background: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 18px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            height: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
        }

        .stat-icon-circle {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: #ffffff;
            flex-shrink: 0;
        }

        .stat-icon-total { background-color: #2563eb; }
        .stat-icon-kuliah { background-color: #10b981; }
        .stat-icon-bekerja { background-color: #f59e0b; }
        .stat-icon-wirausaha { background-color: #8b5cf6; }

        .stat-info .stat-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 2px;
        }

        .stat-info .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            line-height: 1.15;
            color: #0f172a;
            letter-spacing: -0.02em;
        }

        .stat-info .stat-subtext {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* ===== CHART & TABLE CARDS ===== */
        .panel-card {
            background: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            padding: 24px;
            margin-bottom: 24px;
        }

        .panel-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .panel-header h2 {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }

        .panel-header p {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin: 3px 0 0;
        }

        /* ===== FILTER BAR ===== */
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)) auto;
            gap: 12px;
            align-items: flex-end;
            margin-bottom: 20px;
            background: #f8fafc;
            padding: 16px;
            border-radius: 8px;
            border: 1px solid #f1f5f9;
        }

        .filter-item label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
        }

        .filter-select {
            width: 100%;
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            font-size: 0.84rem;
            color: #1e293b;
            background-color: #ffffff;
            outline: none;
            transition: border-color 0.2s;
        }

        .filter-select:focus {
            border-color: #2563eb;
        }

        .btn-filter {
            background-color: #2563eb;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.84rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            height: 38px;
            transition: background-color 0.2s;
        }

        .btn-filter:hover {
            background-color: #1d4ed8;
            color: #fff;
        }

        .btn-export-outline {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #334155;
            padding: 7px 14px;
            border-radius: 6px;
            font-size: 0.82rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-export-outline:hover {
            background: #f8fafc;
            color: #0f172a;
            border-color: #94a3b8;
        }

        /* ===== TABLE STYLES ===== */
        .table-responsive-custom {
            overflow-x: auto;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.84rem;
        }

        .admin-table th {
            text-align: left;
            padding: 12px 14px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border-bottom: 1px solid var(--card-border);
            background: #f8fafc;
            white-space: nowrap;
        }

        .admin-table td {
            padding: 14px 14px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
            white-space: nowrap;
        }

        .admin-table tbody tr:hover {
            background-color: #f8fafc;
        }

        /* BADGES */
        .badge-pill-rencana {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-align: center;
            color: #ffffff;
        }

        .badge-pill-kuliah { background-color: #2563eb; }
        .badge-pill-bekerja { background-color: #f59e0b; }
        .badge-pill-berwirausaha { background-color: #8b5cf6; }

        /* BUTTONS */
        .btn-action-lihat {
            background-color: #2563eb;
            color: #ffffff;
            border: none;
            padding: 4px 14px;
            border-radius: 6px;
            font-size: 0.78rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.2s;
        }

        .btn-action-lihat:hover {
            background-color: #1d4ed8;
            color: #ffffff;
        }

        .btn-more-actions {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #64748b;
            width: 28px;
            height: 28px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-more-actions:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        /* PAGINATION */
        .pagination-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 20px;
            flex-wrap: wrap;
            gap: 12px;
            font-size: 0.82rem;
            color: var(--text-muted);
        }

        .pagination-custom {
            display: flex;
            gap: 4px;
            align-items: center;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .pagination-custom a,
        .pagination-custom span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            padding: 0 8px;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #334155;
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 500;
        }

        .pagination-custom a:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }

        .pagination-custom .active span {
            background: #2563eb;
            border-color: #2563eb;
            color: #ffffff;
            font-weight: 700;
        }

        .pagination-custom .disabled span {
            color: #cbd5e1;
            cursor: not-allowed;
            background: #f8fafc;
        }

        /* RESPONSIVE */
        @media (max-width: 992px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    {{-- SIDEBAR --}}
    <aside class="admin-sidebar" id="adminSidebar">
        <div>
            <div class="sidebar-brand">
                <div class="brand-icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div>
                    <div class="brand-title">Rencana Setelah Lulus</div>
                    <div class="brand-subtitle">Admin Panel</div>
                </div>
            </div>

            <nav class="sidebar-menu">
                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.siswa') }}"
                   class="sidebar-item {{ request()->routeIs('admin.siswa') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    <span>Data Siswa</span>
                </a>

                <a href="{{ route('admin.hasil-tes') }}"
                   class="sidebar-item {{ request()->routeIs('admin.hasil-tes*') ? 'active' : '' }}">
                    <i class="bi bi-lightning-charge-fill"></i>
                    <span>Hasil Tes Minat</span>
                </a>

                <a href="{{ route('admin.rencana') }}"
                   class="sidebar-item {{ request()->routeIs('admin.rencana*') ? 'active' : '' }}">
                    <i class="bi bi-compass-fill"></i>
                    <span>Rencana Siswa</span>
                </a>

                <a href="{{ route('admin.rekomendasi') }}"
                   class="sidebar-item {{ request()->routeIs('admin.rekomendasi*') ? 'active' : '' }}">
                    <i class="bi bi-stars"></i>
                    <span>Rekomendasi Karier</span>
                </a>

                <a href="{{ route('admin.pertanyaan-tes') }}"
                   class="sidebar-item {{ request()->routeIs('admin.pertanyaan-tes*') ? 'active' : '' }}">
                    <i class="bi bi-question-circle-fill"></i>
                    <span>Pertanyaan Tes</span>
                </a>

                <a href="{{ route('admin.kampus-prodi') }}"
                   class="sidebar-item {{ request()->routeIs('admin.kampus-prodi*') ? 'active' : '' }}">
                    <i class="bi bi-mortarboard-fill"></i>
                    <span>Data Kampus & Prodi</span>
                </a>

                <a href="{{ route('admin.laporan') }}"
                   class="sidebar-item {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph-fill"></i>
                    <span>Laporan & Export</span>
                </a>

                <a href="{{ route('admin.pengaturan') }}"
                   class="sidebar-item {{ request()->routeIs('admin.pengaturan*') ? 'active' : '' }}">
                    <i class="bi bi-gear-fill"></i>
                    <span>Pengaturan</span>
                </a>
            </nav>
        </div>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-logout-btn">
                    <i class="bi bi-box-arrow-left"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CANVAS --}}
    <div class="admin-main">
        {{-- TOPBAR --}}
        <header class="admin-topbar">
            <div class="topbar-left">
                <button class="menu-toggle-btn" id="sidebarToggle" title="Toggle Sidebar">
                    <i class="bi bi-list"></i>
                </button>
            </div>

            <div class="topbar-user">
                <div class="user-avatar-circle">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div class="user-details">
                    <div class="user-name">{{ auth()->user()->name ?? 'admin' }}</div>
                    <div class="user-role">Administrator</div>
                </div>
            </div>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="admin-content-body">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius:8px;font-size:0.88rem;">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert" style="border-radius:8px;font-size:0.88rem;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    {{-- Bootstrap 5 Bundle JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar Toggle for Mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const adminSidebar = document.getElementById('adminSidebar');
        if (sidebarToggle && adminSidebar) {
            sidebarToggle.addEventListener('click', function () {
                adminSidebar.classList.toggle('show');
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
