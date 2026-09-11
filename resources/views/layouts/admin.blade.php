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

        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            overflow-x: hidden;
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
            height: 100vh;
            height: 100dvh;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .sidebar-brand {
            padding: 20px 18px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            flex-shrink: 0;
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

        .sidebar-scrollable {
            flex: 1 1 auto;
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            overscroll-behavior: contain;
        }

        /* Sleek custom scrollbar for sidebar */
        .sidebar-scrollable::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar-scrollable::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-scrollable::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.16);
            border-radius: 4px;
        }
        .sidebar-scrollable::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.32);
        }

        .sidebar-menu {
            padding: 12px 10px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 9px 14px;
            border-radius: 8px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.86rem;
            font-weight: 500;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .sidebar-item i {
            font-size: 1.12rem;
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
            flex-shrink: 0;
            padding: 14px 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background-color: var(--sidebar-bg);
            z-index: 10;
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
            max-width: calc(100% - 250px);
            min-width: 0;
            background-color: var(--bg-canvas);
            box-sizing: border-box;
        }

        /* ===== TOP NAVBAR ENHANCED ===== */
        .admin-topbar {
            height: 68px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.85);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 99;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            box-shadow: 0 2px 8px -2px rgba(15, 23, 42, 0.04), 0 1px 2px -1px rgba(15, 23, 42, 0.02);
            gap: 16px;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
            flex-shrink: 1;
        }

        .menu-toggle-btn {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            font-size: 1.25rem;
            color: var(--text-dark);
            cursor: pointer;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .menu-toggle-btn:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: var(--primary-blue);
        }

        .topbar-page-info {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
            min-width: 0;
        }

        .topbar-page-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .topbar-school-tag {
            font-size: 0.72rem;
            color: #64748b;
            font-weight: 500;
            white-space: nowrap;
        }

        .topbar-center {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-grow: 1;
            max-width: 420px;
            justify-content: center;
        }

        .topbar-search-form {
            position: relative;
            width: 100%;
            max-width: 360px;
        }

        .topbar-search-input {
            width: 100%;
            height: 38px;
            padding: 0 48px 0 36px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            font-size: 0.84rem;
            color: #1e293b;
            transition: all 0.2s ease;
        }

        .topbar-search-input:focus {
            outline: none;
            background: #ffffff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
        }

        .topbar-search-form .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.88rem;
            pointer-events: none;
        }

        .topbar-search-form .search-shortcut {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.68rem;
            font-weight: 600;
            color: #94a3b8;
            background: #e2e8f0;
            padding: 2px 6px;
            border-radius: 4px;
            pointer-events: none;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .topbar-role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
            border: 1px solid transparent;
            white-space: nowrap;
        }

        .topbar-role-badge.admin-badge {
            background: #f0fdf4;
            color: #166534;
            border-color: #bbf7d0;
        }

        .topbar-role-badge.gurubk-badge {
            background: #eff6ff;
            color: #1e40af;
            border-color: #bfdbfe;
        }

        .topbar-quick-action-btn {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: #64748b;
            text-decoration: none;
            font-size: 1rem;
            transition: all 0.2s ease;
            position: relative;
        }

        .topbar-quick-action-btn:hover {
            background: #f1f5f9;
            color: #2563eb;
            border-color: #cbd5e1;
        }

        .topbar-divider {
            width: 1px;
            height: 28px;
            background: #e2e8f0;
        }

        .topbar-user-dropdown-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 5px 10px 5px 6px;
            border-radius: 10px;
            background: transparent;
            border: 1px solid transparent;
            cursor: pointer;
            text-align: left;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .topbar-user-dropdown-btn:hover,
        .topbar-user-dropdown-btn[aria-expanded="true"] {
            background: #f8fafc;
            border-color: #e2e8f0;
        }

        .topbar-user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.95rem;
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
            flex-shrink: 0;
        }

        .topbar-user-avatar.gurubk-avatar {
            background: linear-gradient(135deg, #10b981, #059669);
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
        }

        .topbar-user-meta {
            line-height: 1.25;
        }

        .topbar-user-name {
            font-size: 0.86rem;
            font-weight: 700;
            color: #0f172a;
            white-space: nowrap;
        }

        .topbar-user-role {
            font-size: 0.72rem;
            font-weight: 500;
            color: #64748b;
            white-space: nowrap;
        }

        .topbar-user-menu {
            min-width: 240px;
            padding: 6px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            margin-top: 8px !important;
        }

        .topbar-user-menu .dropdown-header {
            padding: 10px 12px;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 4px;
        }

        .topbar-user-menu .dropdown-item {
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.84rem;
            font-weight: 500;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.15s ease;
        }

        .topbar-user-menu .dropdown-item:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        .topbar-user-menu .dropdown-item.text-danger:hover {
            background: #fef2f2;
            color: #dc2626;
        }

        /* ===== CONTENT BODY ===== */
        .admin-content-body {
            padding: 28px 32px 48px;
            flex-grow: 1;
            width: 100%;
            max-width: 100%;
            min-width: 0;
            box-sizing: border-box;
        }

        /* ===== PAGE HEADER ===== */
        .page-title-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
            width: 100%;
            max-width: 100%;
            min-width: 0;
        }

        .page-title-row h1 {
            font-size: 1.6rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            letter-spacing: -0.02em;
            word-break: break-word;
        }

        .page-title-row p {
            font-size: 0.88rem;
            color: var(--text-muted);
            margin: 3px 0 0;
            word-break: break-word;
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
            white-space: nowrap;
            flex-shrink: 0;
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
            width: 100%;
            max-width: 100%;
            min-width: 0;
            box-sizing: border-box;
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

        .stat-info {
            min-width: 0;
            flex: 1 1 auto;
        }

        .stat-info .stat-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .stat-info .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            line-height: 1.15;
            color: #0f172a;
            letter-spacing: -0.02em;
            word-break: break-word;
        }

        .stat-info .stat-subtext {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ===== CHART & TABLE CARDS ===== */
        .panel-card {
            background: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            padding: 24px;
            margin-bottom: 24px;
            width: 100%;
            max-width: 100%;
            min-width: 0;
            box-sizing: border-box;
        }

        .panel-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
            width: 100%;
            max-width: 100%;
            min-width: 0;
        }

        .panel-header h2 {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            word-break: break-word;
        }

        .panel-header p {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin: 3px 0 0;
            word-break: break-word;
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
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
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
            white-space: nowrap;
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
            white-space: nowrap;
        }

        .btn-export-outline:hover {
            background: #f8fafc;
            color: #0f172a;
            border-color: #94a3b8;
        }

        /* ===== TABLE STYLES ===== */
        .table-responsive-custom {
            overflow-x: auto;
            width: 100%;
            max-width: 100%;
            min-width: 0;
            box-sizing: border-box;
            -webkit-overflow-scrolling: touch;
        }

        .admin-table {
            width: 100%;
            max-width: 100%;
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

        /* SIDEBAR BACKDROP FOR MOBILE */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.25s ease;
        }

        .sidebar-backdrop.show {
            display: block;
            opacity: 1;
        }

        /* ADMIN / GURU BK MOBILE BOTTOM NAVIGATION BAR */
        .admin-mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 64px;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            z-index: 998;
            padding: 0 6px calc(env(safe-area-inset-bottom, 0px) + 2px);
            justify-content: space-around;
            align-items: center;
            box-shadow: 0 -2px 10px rgba(15, 23, 42, 0.05);
        }

        .admin-mobile-bottom-nav .mobile-nav-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #64748b;
            font-size: 0.70rem;
            font-weight: 500;
            gap: 4px;
            padding: 6px 8px;
            border-radius: 8px;
            transition: all 0.15s ease;
            flex: 1;
            max-width: 80px;
            text-align: center;
            cursor: pointer;
            line-height: 1.1;
        }

        .admin-mobile-bottom-nav .mobile-nav-link i {
            font-size: 1.25rem;
            line-height: 1;
            color: #64748b;
            transition: transform 0.15s ease, color 0.15s ease;
        }

        .admin-mobile-bottom-nav .mobile-nav-link:hover {
            color: #2563eb;
        }

        .admin-mobile-bottom-nav .mobile-nav-link:hover i {
            color: #2563eb;
        }

        .admin-mobile-bottom-nav .mobile-nav-link.active {
            color: #2563eb;
            font-weight: 700;
        }

        .admin-mobile-bottom-nav .mobile-nav-link.active i {
            color: #2563eb;
            transform: scale(1.08);
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
                max-width: 100%;
                min-width: 0;
            }

            .admin-topbar {
                padding: 0 16px;
            }

            .admin-content-body {
                padding: 20px 16px 88px !important;
            }

            .admin-mobile-bottom-nav {
                display: flex;
            }
        }

        @media (max-width: 576px) {
            .filter-grid {
                grid-template-columns: 1fr;
            }

            .stat-card-custom {
                padding: 16px;
                gap: 14px;
            }

            .panel-card {
                padding: 16px;
            }

            .page-title-row h1 {
                font-size: 1.35rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    {{-- SIDEBAR --}}
    <aside class="admin-sidebar" id="adminSidebar">
        {{-- BRAND (PINNED TOP) --}}
        <div class="sidebar-brand d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <div class="brand-icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div>
                    <div class="brand-title">Rencana Setelah Lulus</div>
                    <div class="brand-subtitle">{{ auth()->user()->isGuruBk() ? 'Guru BK Panel' : 'Admin Panel' }}</div>
                </div>
            </div>
            <button type="button" class="btn text-white-50 p-1 d-lg-none" id="sidebarCloseBtn" title="Tutup Menu">
                <i class="bi bi-x-lg" style="font-size: 1.15rem;"></i>
            </button>
        </div>

        {{-- SCROLLABLE MENU (MIDDLE) --}}
        <div class="sidebar-scrollable">
            <nav class="sidebar-menu">
                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.siswa') }}"
                   class="sidebar-item {{ request()->routeIs('admin.siswa*') ? 'active' : '' }}">
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

                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.guru-bk') }}"
                   class="sidebar-item {{ request()->routeIs('admin.guru-bk*') ? 'active' : '' }}">
                    <i class="bi bi-person-video3"></i>
                    <span>Pengelolaan Guru BK</span>
                </a>

                <a href="{{ route('admin.pertanyaan-tes') }}"
                   class="sidebar-item {{ request()->routeIs('admin.pertanyaan-tes*') ? 'active' : '' }}">
                    <i class="bi bi-question-circle-fill"></i>
                    <span>Pertanyaan Tes</span>
                </a>
                @endif

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

                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.pengaturan') }}"
                   class="sidebar-item {{ request()->routeIs('admin.pengaturan*') ? 'active' : '' }}">
                    <i class="bi bi-gear-fill"></i>
                    <span>Pengaturan</span>
                </a>
                @endif
            </nav>
        </div>

        {{-- FOOTER WITH LOGOUT (PINNED BOTTOM) --}}
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

    {{-- SIDEBAR BACKDROP FOR MOBILE --}}
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    {{-- MAIN CANVAS --}}
    <div class="admin-main">
        {{-- TOPBAR ENHANCED --}}
        <header class="admin-topbar">
            {{-- Left Section: Toggle, Active Page Title & School Indicator --}}
            <div class="topbar-left">
                <button class="menu-toggle-btn" id="sidebarToggle" title="Toggle Sidebar">
                    <i class="bi bi-list"></i>
                </button>

                <div class="topbar-page-info">
                    <div class="topbar-page-title">
                        @if(request()->routeIs('admin.dashboard'))
                            <i class="bi bi-grid-1x2-fill text-primary" style="font-size: 0.95rem;"></i>
                            <span>Dashboard Utama</span>
                        @elseif(request()->routeIs('admin.siswa*'))
                            <i class="bi bi-people-fill text-primary" style="font-size: 0.95rem;"></i>
                            <span>Data Siswa</span>
                        @elseif(request()->routeIs('admin.hasil-tes*'))
                            <i class="bi bi-ui-checks text-primary" style="font-size: 0.95rem;"></i>
                            <span>Hasil Tes Minat RIASEC</span>
                        @elseif(request()->routeIs('admin.rencana*'))
                            <i class="bi bi-compass-fill text-primary" style="font-size: 0.95rem;"></i>
                            <span>Rencana Setelah Lulus</span>
                        @elseif(request()->routeIs('admin.rekomendasi*'))
                            <i class="bi bi-stars text-primary" style="font-size: 0.95rem;"></i>
                            <span>Rekomendasi Karier</span>
                        @elseif(request()->routeIs('admin.guru-bk*'))
                            <i class="bi bi-person-video3 text-primary" style="font-size: 0.95rem;"></i>
                            <span>Pengelolaan Guru BK</span>
                        @elseif(request()->routeIs('admin.pertanyaan-tes*'))
                            <i class="bi bi-question-circle-fill text-primary" style="font-size: 0.95rem;"></i>
                            <span>Bank Pertanyaan Tes</span>
                        @elseif(request()->routeIs('admin.kampus-prodi*'))
                            <i class="bi bi-mortarboard-fill text-primary" style="font-size: 0.95rem;"></i>
                            <span>Data Kampus & Prodi</span>
                        @elseif(request()->routeIs('admin.laporan*'))
                            <i class="bi bi-file-earmark-bar-graph-fill text-primary" style="font-size: 0.95rem;"></i>
                            <span>Laporan & Export</span>
                        @elseif(request()->routeIs('admin.pengaturan*'))
                            <i class="bi bi-gear-fill text-primary" style="font-size: 0.95rem;"></i>
                            <span>Pengaturan Aplikasi</span>
                        @else
                            <span>Panel Administrasi</span>
                        @endif
                    </div>
                    <div class="topbar-school-tag d-none d-sm-flex align-items-center gap-2">
                        <span>{{ \App\Models\Setting::get('nama_sekolah', 'SMK Negeri 4 Bandar Lampung') }}</span>
                        <span>•</span>
                        <span class="text-success d-inline-flex align-items-center gap-1">
                            <span style="width:6px;height:6px;border-radius:50%;background:#22c55e;display:inline-block;"></span>
                            TA {{ \App\Models\Setting::get('tahun_ajaran', '2026/2027') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Center Section: Quick Search for Student --}}
            <div class="topbar-center d-none d-lg-flex">
                <form action="{{ route('admin.siswa') }}" method="GET" class="topbar-search-form">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text"
                           name="q"
                           placeholder="Cari siswa atau NISN..."
                           value="{{ request('q') }}"
                           class="topbar-search-input">
                    <span class="search-shortcut">Cari</span>
                </form>
            </div>

            {{-- Right Section: Role Scope Pill, Quick Actions & User Dropdown --}}
            <div class="topbar-right">
                {{-- Role / Kelas Binaan Pill --}}
                @if(auth()->user()->isGuruBk())
                    <div class="topbar-role-badge gurubk-badge d-none d-md-flex" title="Kelas binaan yang Anda bimbing">
                        <i class="bi bi-mortarboard-fill"></i>
                        <span>
                            Binaan:
                            <strong>
                                @php
                                    $binaan = auth()->user()->kelas_binaan_array;
                                @endphp
                                {{ count($binaan) > 0 ? implode(', ', array_slice($binaan, 0, 2)) . (count($binaan) > 2 ? ' +' . (count($binaan) - 2) : '') : 'Semua Kelas' }}
                            </strong>
                        </span>
                    </div>
                @else
                    <div class="topbar-role-badge admin-badge d-none d-md-flex">
                        <i class="bi bi-shield-fill-check"></i>
                        <span>Super Admin</span>
                    </div>
                @endif

                {{-- Quick Nav Link: Kampus / Prodi Explorer --}}
                <a href="{{ route('admin.kampus-prodi') }}"
                   class="topbar-quick-action-btn d-none d-sm-flex"
                   title="Penjelajah Kampus & Prodi KIP Kuliah">
                    <i class="bi bi-buildings"></i>
                </a>

                {{-- Quick Nav Link: Export Laporan --}}
                <a href="{{ route('admin.laporan') }}"
                   class="topbar-quick-action-btn d-none d-sm-flex"
                   title="Laporan & Ekspor Data">
                    <i class="bi bi-file-earmark-arrow-down"></i>
                </a>

                <div class="topbar-divider d-none d-sm-block"></div>

                {{-- User Profile Dropdown --}}
                <div class="dropdown">
                    <button class="topbar-user-dropdown-btn"
                            type="button"
                            id="topbarUserDropdown"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                        <div class="topbar-user-avatar {{ auth()->user()->isGuruBk() ? 'gurubk-avatar' : '' }}">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="topbar-user-meta d-none d-md-block">
                            <div class="topbar-user-name">{{ auth()->user()->name ?? 'User' }}</div>
                            <div class="topbar-user-role">
                                {{ auth()->user()->isGuruBk() ? 'Guru BK' : 'Administrator' }}
                            </div>
                        </div>
                        <i class="bi bi-chevron-down text-muted d-none d-md-block" style="font-size: 0.75rem;"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end topbar-user-menu" aria-labelledby="topbarUserDropdown">
                        <li class="dropdown-header">
                            <div class="fw-bold text-dark" style="font-size: 0.88rem;">{{ auth()->user()->name }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ auth()->user()->email ?? '-' }}</div>
                            <div class="mt-1">
                                <span class="badge {{ auth()->user()->isGuruBk() ? 'bg-primary-subtle text-primary' : 'bg-success-subtle text-success' }} border" style="font-size: 0.7rem;">
                                    <i class="bi {{ auth()->user()->isGuruBk() ? 'bi-person-workspace' : 'bi-shield-check' }} me-1"></i>
                                    {{ auth()->user()->isGuruBk() ? 'Guru BK' : 'Administrator' }}
                                </span>
                            </div>
                        </li>

                        <li>
                            <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-grid-1x2 text-muted"></i>
                                <span>Dashboard Utama</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.siswa') }}">
                                <i class="bi bi-people text-muted"></i>
                                <span>Data Siswa</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.kampus-prodi') }}">
                                <i class="bi bi-mortarboard text-muted"></i>
                                <span>Data Kampus & Prodi</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.laporan') }}">
                                <i class="bi bi-file-earmark-bar-graph text-muted"></i>
                                <span>Laporan & Export</span>
                            </a>
                        </li>
                        @if(auth()->user()->isAdmin())
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.pengaturan') }}">
                                <i class="bi bi-gear text-muted"></i>
                                <span>Pengaturan Aplikasi</span>
                            </a>
                        </li>
                        @endif

                        <li><hr class="dropdown-divider my-1"></li>

                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger border-0 bg-transparent w-100 text-start">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Keluar / Logout</span>
                                </button>
                            </form>
                        </li>
                    </ul>
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

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius:8px;font-size:0.88rem;">
                    <i class="bi bi-exclamation-octagon-fill me-2"></i> {{ session('error') }}
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

    {{-- ADMIN / GURU BK MOBILE BOTTOM NAVIGATION BAR --}}
    <nav class="admin-mobile-bottom-nav d-lg-none" id="adminMobileBottomNav">
        <a href="{{ route('admin.dashboard') }}"
           class="mobile-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door{{ request()->routeIs('admin.dashboard') ? '-fill' : '' }}"></i>
            <span>Beranda</span>
        </a>

        <a href="{{ route('admin.siswa') }}"
           class="mobile-nav-link {{ request()->routeIs('admin.siswa*') ? 'active' : '' }}">
            <i class="bi bi-people{{ request()->routeIs('admin.siswa*') ? '-fill' : '' }}"></i>
            <span>Siswa</span>
        </a>

        <a href="{{ route('admin.hasil-tes') }}"
           class="mobile-nav-link {{ request()->routeIs('admin.hasil-tes*') ? 'active' : '' }}">
            <i class="bi bi-lightning-charge{{ request()->routeIs('admin.hasil-tes*') ? '-fill' : '' }}"></i>
            <span>Hasil Tes</span>
        </a>

        <a href="{{ route('admin.rencana') }}"
           class="mobile-nav-link {{ request()->routeIs('admin.rencana*') ? 'active' : '' }}">
            <i class="bi bi-compass{{ request()->routeIs('admin.rencana*') ? '-fill' : '' }}"></i>
            <span>Rencana</span>
        </a>

        <button type="button"
                class="mobile-nav-link border-0 bg-transparent {{ request()->routeIs('admin.kampus-prodi*', 'admin.laporan*', 'admin.guru-bk*', 'admin.pertanyaan-tes*', 'admin.pengaturan*', 'admin.rekomendasi*') ? 'active' : '' }}"
                id="mobileBottomMenuBtn"
                title="Buka Menu Lainnya">
            <i class="bi bi-grid{{ request()->routeIs('admin.kampus-prodi*', 'admin.laporan*', 'admin.guru-bk*', 'admin.pertanyaan-tes*', 'admin.pengaturan*', 'admin.rekomendasi*') ? '-fill' : '' }}"></i>
            <span>Menu</span>
        </button>
    </nav>

    {{-- Bootstrap 5 Bundle JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar Toggle & Backdrop for Mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const adminSidebar = document.getElementById('adminSidebar');
        const sidebarBackdrop = document.getElementById('sidebarBackdrop');

        function toggleAdminSidebar(forceClose = false) {
            if (!adminSidebar) return;
            if (forceClose) {
                adminSidebar.classList.remove('show');
                if (sidebarBackdrop) sidebarBackdrop.classList.remove('show');
            } else {
                adminSidebar.classList.toggle('show');
                if (sidebarBackdrop) {
                    sidebarBackdrop.classList.toggle('show', adminSidebar.classList.contains('show'));
                }
            }
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleAdminSidebar();
            });
        }

        const mobileBottomMenuBtn = document.getElementById('mobileBottomMenuBtn');
        if (mobileBottomMenuBtn) {
            mobileBottomMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleAdminSidebar();
            });
        }

        const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
        if (sidebarCloseBtn) {
            sidebarCloseBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleAdminSidebar(true);
            });
        }

        if (sidebarBackdrop) {
            sidebarBackdrop.addEventListener('click', function() {
                toggleAdminSidebar(true);
            });
        }

        // Close sidebar if window resized to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 992) {
                toggleAdminSidebar(true);
            }
        });

        // Quick Search Shortcut (Ctrl + K or /)
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                const searchInput = document.querySelector('.topbar-search-input');
                if (searchInput) {
                    e.preventDefault();
                    searchInput.focus();
                    searchInput.select();
                }
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
