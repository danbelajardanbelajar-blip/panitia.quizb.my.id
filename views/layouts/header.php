<?php
// Mencegah error jika fungsi belum ada
if (!function_exists('getCurrentUser')) {
    function getCurrentUser() { return ['name' => 'User', 'email' => '', 'avatar' => null]; }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Sistem Administrasi Panitia') ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="assets/images/favicon.svg">
    <link rel="apple-touch-icon" href="assets/images/favicon.svg">
    
    <!-- Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Design System CSS -->
    <style>
        :root {
            /* Design System Colors - SaaS Modern */
            --primary: #4f46e5;      /* Indigo 600 */
            --primary-hover: #4338ca; /* Indigo 700 */
            --secondary: #64748b;    /* Slate 500 */
            --accent: #0ea5e9;       /* Sky 500 */
            --background: #f8fafc;   /* Slate 50 */
            --surface: #ffffff;      /* White */
            --text-main: #0f172a;    /* Slate 900 */
            --text-muted: #64748b;   /* Slate 500 */
            --border: #e2e8f0;       /* Slate 200 */
            
            /* Status Colors */
            --success: #10b981;      /* Emerald 500 */
            --success-bg: #d1fae5;
            --warning: #f59e0b;      /* Amber 500 */
            --warning-bg: #fef3c7;
            --danger: #ef4444;       /* Red 500 */
            --danger-bg: #fee2e2;
            --info: #3b82f6;         /* Blue 500 */
            --info-bg: #dbeafe;

            /* Shadows */
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            
            /* Dimensions */
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--background);
            color: var(--text-main);
            -webkit-font-smoothing: antialiased;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--surface);
            border-right: 1px solid var(--border);
            z-index: 1000;
            overflow-y: auto;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 24px;
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--text-main);
            text-decoration: none;
            border-bottom: 1px solid var(--border);
        }
        .sidebar-brand .logo-icon {
            background: var(--primary);
            color: white;
            padding: 6px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .sidebar-menu {
            padding: 20px 16px;
            list-style: none;
            margin: 0;
        }
        .sidebar-menu .nav-item {
            margin-bottom: 4px;
            padding: 0;
        }
        .sidebar-menu .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            color: var(--text-muted);
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.2s;
        }
        .sidebar-menu .nav-link i[data-lucide] {
            width: 20px;
            height: 20px;
            stroke-width: 2.5;
        }
        .sidebar-menu .nav-link:hover {
            background-color: var(--background);
            color: var(--primary);
        }
        .sidebar-menu .nav-link.active {
            background-color: var(--primary);
            color: white;
            box-shadow: var(--shadow-sm);
        }
        .sidebar-menu .nav-link.text-danger:hover {
            background-color: var(--danger-bg);
            color: var(--danger) !important;
        }

        /* ----- MAIN CONTENT & TOPBAR ----- */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .topbar {
            height: 70px;
            background-color: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 990;
        }
        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .sidebar-toggle {
            display: none;
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-main);
            padding: 8px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .sidebar-toggle:hover {
            background-color: var(--background);
        }
        .page-header-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
            color: var(--text-main);
        }
        .user-profile-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            background: transparent;
            border: 1px solid var(--border);
            padding: 6px 12px 6px 6px;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            color: var(--text-main);
            font-weight: 500;
            font-size: 0.9rem;
        }
        .user-profile-btn:hover {
            background-color: var(--background);
            border-color: var(--secondary);
        }
        .user-profile-btn img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* ----- GLOBAL COMPONENTS ----- */
        .content-wrapper {
            padding: 32px;
            flex: 1;
        }
        .card-modern {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 24px;
            overflow: hidden;
        }
        .card-header-modern {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            background: transparent;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-body-modern {
            padding: 24px;
        }
        
        /* Typography */
        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            letter-spacing: -0.02em;
        }
        
        /* Buttons */
        .btn-modern {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
            border: none;
            text-decoration: none;
            cursor: pointer;
        }
        .btn-modern i[data-lucide] {
            width: 18px;
            height: 18px;
            stroke-width: 2.5;
        }
        .btn-primary-modern {
            background-color: var(--primary);
            color: white;
            box-shadow: 0 2px 4px rgba(79, 70, 229, 0.2);
        }
        .btn-primary-modern:hover {
            background-color: var(--primary-hover);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(79, 70, 229, 0.3);
        }
        .btn-secondary-modern {
            background-color: var(--surface);
            color: var(--text-main);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
        }
        .btn-secondary-modern:hover {
            background-color: var(--background);
            border-color: var(--secondary);
            color: var(--text-main);
        }
        .btn-danger-modern {
            background-color: var(--danger);
            color: white;
        }
        .btn-danger-modern:hover {
            background-color: #dc2626;
            color: white;
        }
        .btn-ghost-modern {
            background-color: transparent;
            color: var(--text-muted);
            padding: 8px;
            border-radius: 8px;
        }
        .btn-ghost-modern:hover {
            background-color: var(--background);
            color: var(--primary);
        }
        .btn-ghost-danger:hover {
            background-color: var(--danger-bg);
            color: var(--danger);
        }
        
        /* Badges */
        .badge-modern {
            padding: 6px 12px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .badge-modern i[data-lucide] {
            width: 12px;
            height: 12px;
            stroke-width: 3;
        }
        .badge-success { background: var(--success-bg); color: var(--success); }
        .badge-warning { background: var(--warning-bg); color: var(--warning); }
        .badge-danger { background: var(--danger-bg); color: var(--danger); }
        .badge-info { background: var(--info-bg); color: var(--info); }
        .badge-gray { background: #f1f5f9; color: #64748b; }
        
        /* Tables */
        .table-responsive-modern {
            overflow-x: auto;
        }
        .table-modern {
            width: 100%;
            border-collapse: collapse;
            white-space: nowrap;
        }
        .table-modern th {
            background-color: var(--background);
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 16px 24px;
            border-bottom: 1px solid var(--border);
            text-align: left;
        }
        .table-modern td {
            padding: 16px 24px;
            border-bottom: 1px solid var(--border);
            color: var(--text-main);
            font-size: 0.95rem;
            vertical-align: middle;
        }
        .table-modern tbody tr {
            transition: background-color 0.2s;
        }
        .table-modern tbody tr:hover {
            background-color: rgba(248, 250, 252, 0.8);
        }
        .table-modern tbody tr:last-child td {
            border-bottom: none;
        }

        /* Forms */
        .form-label-modern {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-main);
            margin-bottom: 8px;
        }
        .form-control-modern, .form-select-modern {
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 0.95rem;
            color: var(--text-main);
            transition: all 0.2s;
            background-color: var(--surface);
            width: 100%;
            box-shadow: var(--shadow-sm);
        }
        .form-control-modern:focus, .form-select-modern:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            outline: none;
        }

        /* Sidebar Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.5);
            z-index: 995;
            backdrop-filter: blur(2px);
        }

        /* Utility */
        .text-muted-modern {
            color: var(--text-muted) !important;
        }

        /* RESPONSIVE */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .sidebar-overlay.show {
                display: block;
            }
            .main-content {
                margin-left: 0;
            }
            .sidebar-toggle {
                display: block;
            }
            .topbar {
                padding: 0 16px;
            }
            .content-wrapper {
                padding: 16px;
            }
        }
        
        /* Print styles */
        @media print {
            .sidebar, .topbar, .btn-modern, .sidebar-overlay { display: none !important; }
            .main-content { margin: 0 !important; }
            .content-wrapper { padding: 0 !important; }
            .card-modern { border: none !important; box-shadow: none !important; }
        }
    </style>
</head>
<body>

<!-- Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <a href="dashboard.php" class="sidebar-brand">
        <div class="logo-icon">
            <i data-lucide="layers"></i>
        </div>
        Admin Panitia
    </a>
    <ul class="sidebar-menu">
        <li class="nav-item">
            <a class="nav-link <?= ($activeMenu ?? '') == 'dashboard' ? 'active' : '' ?>" href="dashboard.php">
                <i data-lucide="layout-dashboard"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($activeMenu ?? '') == 'kegiatan' ? 'active' : '' ?>" href="kegiatan.php">
                <i data-lucide="calendar-days"></i> Kegiatan
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($activeMenu ?? '') == 'rab' ? 'active' : '' ?>" href="rab.php">
                <i data-lucide="calculator"></i> RAPB
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($activeMenu ?? '') == 'keuangan' ? 'active' : '' ?>" href="keuangan.php">
                <i data-lucide="wallet"></i> Keuangan
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($activeMenu ?? '') == 'catatan' ? 'active' : '' ?>" href="catatan.php">
                <i data-lucide="notebook-pen"></i> Catatan
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($activeMenu ?? '') == 'laporan' ? 'active' : '' ?>" href="laporan.php">
                <i data-lucide="file-text"></i> Laporan
            </a>
        </li>
        
        <li class="nav-item mt-4">
            <a class="nav-link text-danger" href="logout.php">
                <i data-lucide="log-out"></i> Keluar
            </a>
        </li>
    </ul>

    <div class="mt-auto px-3 pb-4 pt-4 text-center" style="font-size: 0.8rem; border-top: 1px solid var(--border); margin-top: auto;">
        <div class="d-flex justify-content-center gap-3 mb-2">
            <a href="tentang.php" class="text-decoration-none text-muted-modern fw-semibold">Tentang</a>
            <a href="privasi.php" class="text-decoration-none text-muted-modern fw-semibold">Privasi</a>
        </div>
        <div class="text-muted-modern">&copy; <?= date('Y') ?> Admin Panitia</div>
    </div>
</aside>

<div class="main-content">
    <header class="topbar">
        <div class="topbar-left">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i data-lucide="menu"></i>
            </button>
            <h1 class="page-header-title d-none d-sm-block"><?= htmlspecialchars($title ?? 'Sistem Administrasi Panitia') ?></h1>
        </div>

        <div class="dropdown">
            <button class="user-profile-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="<?= htmlspecialchars(getCurrentUser()['avatar'] ?? 'https://ui-avatars.com/api/?name='.urlencode(getCurrentUser()['name'] ?? 'User')) ?>" alt="Avatar">
                <span class="d-none d-md-inline"><?= htmlspecialchars(getCurrentUser()['name'] ?? 'Pengguna') ?></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 12px; margin-top: 10px; padding: 10px;">
                <li><h6 class="dropdown-header text-truncate" style="max-width: 200px;"><?= htmlspecialchars(getCurrentUser()['email'] ?? '') ?></h6></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger d-flex align-items-center gap-2 rounded" href="logout.php"><i data-lucide="log-out" style="width:16px;"></i> Keluar</a></li>
            </ul>
        </div>
    </header>

    <main class="content-wrapper">
