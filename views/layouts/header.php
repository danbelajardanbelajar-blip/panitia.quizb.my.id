<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sistem Administrasi Panitia' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            height: 100vh;
            background-color: #2c3e50;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            overflow-y: auto;
            z-index: 1000;
        }
        .sidebar-brand {
            padding: 1.5rem 1rem;
            text-align: center;
            font-weight: bold;
            font-size: 1.2rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1rem;
            color: #fff;
            text-decoration: none;
            display: block;
        }
        .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.8rem 1.5rem;
            margin: 0.2rem 1rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }
        .nav-link:hover, .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
        }
        .main-content {
            margin-left: 250px;
            padding: 2rem;
            min-height: 100vh;
        }
        .top-nav {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin: -2rem -2rem 2rem -2rem;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .user-profile img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            margin-right: 10px;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .top-nav {
                justify-content: space-between;
            }
        }
    </style>
</head>
<body>

<div class="sidebar" id="sidebar">
    <a href="dashboard.php" class="sidebar-brand">Admin Panitia</a>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link <?= ($activeMenu ?? '') == 'dashboard' ? 'active' : '' ?>" href="dashboard.php">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($activeMenu ?? '') == 'kegiatan' ? 'active' : '' ?>" href="kegiatan.php">
                <i class="bi bi-calendar-event"></i> Kegiatan
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($activeMenu ?? '') == 'rab' ? 'active' : '' ?>" href="rab.php">
                <i class="bi bi-file-earmark-spreadsheet"></i> RAB
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($activeMenu ?? '') == 'keuangan' ? 'active' : '' ?>" href="keuangan.php">
                <i class="bi bi-wallet2"></i> Keuangan
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($activeMenu ?? '') == 'catatan' ? 'active' : '' ?>" href="catatan.php">
                <i class="bi bi-journal-text"></i> Catatan Kegiatan
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($activeMenu ?? '') == 'laporan' ? 'active' : '' ?>" href="laporan.php">
                <i class="bi bi-file-earmark-pdf"></i> Laporan
            </a>
        </li>
        <li class="nav-item mt-4">
            <a class="nav-link text-danger" href="logout.php">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </li>
    </ul>
</div>

<div class="main-content">
    <div class="top-nav d-flex justify-content-between align-items-center">
        <!-- Tombol Menu Mobile -->
        <button class="btn btn-outline-dark d-md-none" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>
        
        <!-- Spacer untuk Desktop agar profile tetap di kanan -->
        <div class="d-none d-md-block"></div>

        <div class="dropdown user-profile">
            <a class="text-decoration-none text-dark dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="<?= htmlspecialchars(getCurrentUser()['avatar'] ?? 'https://ui-avatars.com/api/?name='.urlencode(getCurrentUser()['name'])) ?>" alt="User Avatar">
                <?= htmlspecialchars(getCurrentUser()['name'] ?? 'User') ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
