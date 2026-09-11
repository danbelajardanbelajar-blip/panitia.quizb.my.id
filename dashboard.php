<?php
require_once __DIR__ . '/includes/auth_helper.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/format_helper.php';

requireLogin();

$userId = getCurrentUserId();
$pdo = getDB();

// Statistik
$totalKegiatan = $pdo->prepare("SELECT COUNT(*) FROM kegiatan WHERE user_id = ?");
$totalKegiatan->execute([$userId]);
$totalKegiatan = $totalKegiatan->fetchColumn();

$kegiatanAktif = $pdo->prepare("SELECT COUNT(*) FROM kegiatan WHERE user_id = ? AND status IN ('Persiapan', 'Berlangsung')");
$kegiatanAktif->execute([$userId]);
$kegiatanAktif = $kegiatanAktif->fetchColumn();

// Get total RAB from user's kegiatan
$totalRabQuery = $pdo->prepare("
    SELECT COALESCE(SUM(ri.volume * ri.harga_satuan), 0)
    FROM rab_items ri
    JOIN rab r ON ri.rab_id = r.id
    JOIN kegiatan k ON r.kegiatan_id = k.id
    WHERE k.user_id = ? AND ri.jenis = 'pengeluaran'
");
$totalRabQuery->execute([$userId]);
$totalRab = $totalRabQuery->fetchColumn();

// Keuangan (Pemasukan & Pengeluaran)
$pemasukanQuery = $pdo->prepare("
    SELECT COALESCE(SUM(t.nominal), 0) 
    FROM transaksi t
    JOIN kegiatan k ON t.kegiatan_id = k.id
    WHERE k.user_id = ? AND t.jenis = 'pemasukan'
");
$pemasukanQuery->execute([$userId]);
$totalPemasukan = $pemasukanQuery->fetchColumn();

$pengeluaranQuery = $pdo->prepare("
    SELECT COALESCE(SUM(t.nominal), 0) 
    FROM transaksi t
    JOIN kegiatan k ON t.kegiatan_id = k.id
    WHERE k.user_id = ? AND t.jenis = 'pengeluaran'
");
$pengeluaranQuery->execute([$userId]);
$totalPengeluaran = $pengeluaranQuery->fetchColumn();

$saldo = $totalPemasukan - $totalPengeluaran;

// Aktivitas terbaru (Catatan & Transaksi)
$recentActivities = [];

$catatanQuery = $pdo->prepare("
    SELECT 'catatan' as type, c.judul as title, c.created_at as date, k.nama_kegiatan
    FROM catatan_kegiatan c
    JOIN kegiatan k ON c.kegiatan_id = k.id
    WHERE k.user_id = ?
    ORDER BY c.created_at DESC LIMIT 5
");
$catatanQuery->execute([$userId]);
$recentCatatan = $catatanQuery->fetchAll();

$transaksiQuery = $pdo->prepare("
    SELECT 'transaksi' as type, t.nama_transaksi as title, t.created_at as date, k.nama_kegiatan
    FROM transaksi t
    JOIN kegiatan k ON t.kegiatan_id = k.id
    WHERE k.user_id = ?
    ORDER BY t.created_at DESC LIMIT 5
");
$transaksiQuery->execute([$userId]);
$recentTransaksi = $transaksiQuery->fetchAll();

$recentActivities = array_merge($recentCatatan, $recentTransaksi);
usort($recentActivities, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});
$recentActivities = array_slice($recentActivities, 0, 5);

$activeMenu = 'dashboard';
$title = 'Dashboard - Sistem Administrasi Panitia';

include __DIR__ . '/views/dashboard/index.php';
