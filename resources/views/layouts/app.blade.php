<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'Newsletter System') — Admin Panel</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --accent: #6366f1;
            --accent-hover: #4f46e5;
            --header-height: 64px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            transition: transform .3s ease;
        }

        .sidebar-brand {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }

        .sidebar-brand .brand-logo {
            width: 36px; height: 36px;
            background: var(--accent);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-size: 18px;
            flex-shrink: 0;
        }

        .sidebar-brand .brand-name {
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            line-height: 1.2;
        }

        .sidebar-brand .brand-sub {
            color: #64748b;
            font-size: 11px;
            font-weight: 400;
        }

        .sidebar-nav { flex: 1; padding: 16px 12px; }

        .nav-section-title {
            color: #475569;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: 0 12px 8px;
            margin-top: 8px;
        }

        .sidebar-nav .nav-link {
            color: #94a3b8;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 13.5px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all .15s;
            margin-bottom: 2px;
        }

        .sidebar-nav .nav-link:hover,
        .sidebar-nav .nav-link.active {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .sidebar-nav .nav-link.active {
            background: var(--accent);
            color: #fff;
        }

        .sidebar-nav .nav-link i { font-size: 16px; }

        .sidebar-footer {
            padding: 16px 24px;
            border-top: 1px solid rgba(255,255,255,.07);
            color: #475569;
            font-size: 11.5px;
        }

        /* ── Main Content ── */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Header */
        .top-header {
            height: var(--header-height);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 28px;
            gap: 16px;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .top-header .page-title {
            font-size: 17px;
            font-weight: 600;
            color: #0f172a;
            margin: 0;
        }

        .top-header .breadcrumb {
            margin: 0;
            font-size: 12px;
        }

        .top-header .breadcrumb-item a { color: var(--accent); text-decoration: none; }

        .top-header .ms-auto { display: flex; align-items: center; gap: 12px; }

        .avatar-circle {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: var(--accent);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }

        /* Page Content */
        .page-content {
            flex: 1;
            padding: 28px;
        }

        /* Cards */
        .stat-card {
            background: #fff;
            border-radius: 14px;
            padding: 22px 24px;
            border: 1px solid #e2e8f0;
            transition: box-shadow .2s;
        }

        .stat-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.07); }

        .stat-card .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
        }

        .stat-card .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1;
        }

        .stat-card .stat-label {
            color: #64748b;
            font-size: 13px;
            font-weight: 500;
            margin-top: 4px;
        }

        /* Table Card */
        .table-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .table-card .table-header {
            padding: 20px 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .table-card .table-header h5 {
            font-size: 15px;
            font-weight: 600;
            margin: 0;
            color: #0f172a;
        }

        .table thead th {
            background: #f8fafc;
            color: #475569;
            font-size: 11.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .05em;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 16px;
        }

        .table tbody td {
            padding: 14px 16px;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
            font-size: 13.5px;
        }

        .table tbody tr:last-child td { border-bottom: none; }

        /* Badges */
        .badge-pill {
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 11.5px;
            font-weight: 600;
        }

        .badge-warning  { background: #fef3c7; color: #92400e; }
        .badge-info     { background: #dbeafe; color: #1e40af; }
        .badge-primary  { background: #ede9fe; color: #5b21b6; }
        .badge-success  { background: #dcfce7; color: #166534; }
        .badge-secondary{ background: #f1f5f9; color: #475569; }
        .badge-danger   { background: #fee2e2; color: #991b1b; }

        /* Progress Bar */
        .progress { border-radius: 100px; background: #f1f5f9; }
        .progress-bar { border-radius: 100px; background: var(--accent); }

        /* Forms */
        .form-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            padding: 28px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        .form-control, .form-select {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            color: #0f172a;
            transition: border-color .15s, box-shadow .15s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(99,102,241,.12);
        }

        .btn-primary {
            background: var(--accent);
            border-color: var(--accent);
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 8px;
            transition: background .15s, transform .1s;
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            border-color: var(--accent-hover);
            transform: translateY(-1px);
        }

        .btn-outline-secondary {
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 14px;
        }

        .action-btn {
            width: 32px; height: 32px;
            border-radius: 8px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all .15s;
        }

        .action-btn:hover { transform: translateY(-1px); }

        /* Alert */
        .alert { border-radius: 10px; font-size: 14px; border: none; }
        .alert-success { background: #dcfce7; color: #166534; }
        .alert-danger  { background: #fee2e2; color: #991b1b; }
        .alert-info    { background: #dbeafe; color: #1e40af; }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
        }
    </style>
</head>
<body>

<!-- ─── Sidebar ───────────────────────────── -->
<aside class="sidebar">
    <div class="sidebar-brand d-flex align-items-center gap-3">
        <div class="brand-logo"><i class="bi bi-envelope-paper-heart"></i></div>
        <div>
            <div class="brand-name">Newsletter</div>
            <div class="brand-sub">Admin Panel</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-title">Main Menu</div>

        <a href="{{ route('newsletters.index') }}"
           class="nav-link {{ request()->routeIs('newsletters.index') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>

        <a href="{{ route('newsletters.index') }}"
           class="nav-link {{ request()->routeIs('newsletters.*') && !request()->routeIs('newsletters.create') ? 'active' : '' }}">
            <i class="bi bi-envelope-paper"></i> All Newsletters
        </a>

        <a href="{{ route('newsletters.create') }}"
           class="nav-link {{ request()->routeIs('newsletters.create') ? 'active' : '' }}">
            <i class="bi bi-plus-circle"></i> Create Newsletter
        </a>

        <div class="nav-section-title mt-3">System</div>

    </nav>

    <div class="sidebar-footer">
        <div class="fw-semibold text-white-50" style="font-size:12px">Newsletter System v1.0</div>
        <div class="mt-1">Queue: database</div>
    </div>
</aside>

<!-- ─── Main Wrapper ──────────────────────── -->
<div class="main-wrapper">

    <!-- Top Header -->
    <header class="top-header">
        <div>
            <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('newsletters.index') }}">Home</a></li>
                    @yield('breadcrumb')
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('newsletters.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> New Newsletter
            </a>
            <div class="avatar-circle">A</div>
        </div>
    </header>

    <!-- Alerts -->
    <div class="page-content" style="padding-bottom: 0; padding-top: 16px;">
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center gap-2 mb-0">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-0">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ session('error') }}
            </div>
        @endif
    </div>

    <!-- Page Content -->
    <main class="page-content">
        @yield('content')
    </main>

</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
