<?php
require_once __DIR__ . '/includes/auth_helper.php';
require_once __DIR__ . '/includes/format_helper.php';
require_once __DIR__ . '/models/Kegiatan.php';
require_once __DIR__ . '/models/Rab.php';
require_once __DIR__ . '/models/RabItem.php';
require_once __DIR__ . '/models/Transaksi.php';
require_once __DIR__ . '/models/CatatanKegiatan.php';

requireLogin();
$userId = getCurrentUserId();
$action = $_GET['action'] ?? 'index';

if ($action === 'detail') {
    $id = $_GET['id'] ?? null;
    $kegiatan = Kegiatan::findById($id, $userId);
    
    if (!$kegiatan) {
        die("Kegiatan tidak ditemukan atau Anda tidak memiliki akses.");
    }

    // Get Data
    $rabList = Rab::getAll($userId, $id);
    $totalRab = 0;
    foreach ($rabList as $r) {
        $totalRab += $r['total_rab'];
    }

    $rekap = Transaksi::getRekap($userId, $id);
    $totalPemasukan = $rekap['pemasukan'];
    $totalPengeluaran = $rekap['pengeluaran'];
    $saldo = $totalPemasukan - $totalPengeluaran;

    $sisaAnggaran = $totalRab - $totalPengeluaran;
    $persentaseRealisasi = $totalRab > 0 ? ($totalPengeluaran / $totalRab) * 100 : 0;

    $catatanList = CatatanKegiatan::getAll($userId, $id);

    $activeMenu = 'laporan';
    $title = 'Laporan Kegiatan - ' . htmlspecialchars($kegiatan['nama_kegiatan']);
    include __DIR__ . '/views/laporan/detail.php';
} else {
    // List Kegiatan for reporting
    $kegiatanList = Kegiatan::getAll($userId, '', '', 100, 0); 
    
    $activeMenu = 'laporan';
    $title = 'Laporan - Sistem Administrasi Panitia';
    include __DIR__ . '/views/laporan/index.php';
}
