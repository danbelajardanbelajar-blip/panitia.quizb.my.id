<?php
require_once __DIR__ . '/includes/auth_helper.php';
require_once __DIR__ . '/includes/csrf_helper.php';
require_once __DIR__ . '/includes/format_helper.php';
require_once __DIR__ . '/models/Kegiatan.php';
require_once __DIR__ . '/models/Transaksi.php';

requireLogin();
$userId = getCurrentUserId();
$action = $_GET['action'] ?? 'index';

if ($action === 'create' || $action === 'edit') {
    $id = $_GET['id'] ?? null;
    $transaksi = null;
    
    $kegiatanList = Kegiatan::getAll($userId, '', '', 1000, 0); 
    
    if ($action === 'edit' && $id) {
        $transaksi = Transaksi::findById($id, $userId);
        if (!$transaksi) {
            die("Transaksi tidak ditemukan atau Anda tidak memiliki akses.");
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            die("CSRF token tidak valid.");
        }
        
        $kegiatanId = $_POST['kegiatan_id'];
        $kegiatan = Kegiatan::findById($kegiatanId, $userId);
        if (!$kegiatan) die("Akses ditolak.");

        $data = [
            'kegiatan_id' => $kegiatanId,
            'jenis' => $_POST['jenis'],
            'kategori' => $_POST['kategori'],
            'nama_transaksi' => $_POST['nama_transaksi'],
            'deskripsi' => $_POST['deskripsi'] ?? null,
            'nominal' => str_replace(['.', ','], '', $_POST['nominal']), // bersihkan format angka
            'sumber_tujuan' => $_POST['sumber_tujuan'] ?? null,
            'metode_pembayaran' => $_POST['metode_pembayaran'],
            'nomor_bukti' => $_POST['nomor_bukti'] ?? null,
            'tanggal' => $_POST['tanggal'],
            'catatan' => $_POST['catatan'] ?? null
        ];
        
        if ($action === 'create') {
            Transaksi::create($data);
        } else {
            Transaksi::update($id, $data);
        }
        
        header('Location: keuangan.php');
        exit;
    }
    
    $activeMenu = 'keuangan';
    $title = ($action === 'create' ? 'Tambah' : 'Edit') . ' Transaksi - Sistem Administrasi Panitia';
    include __DIR__ . '/views/keuangan/form.php';

} elseif ($action === 'delete') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            die("CSRF token tidak valid.");
        }
        $id = $_POST['id'] ?? null;
        if ($id) {
            $transaksi = Transaksi::findById($id, $userId);
            if ($transaksi) {
                Transaksi::delete($id);
            }
        }
        header('Location: keuangan.php');
        exit;
    }
} else {
    // List / Index
    $kegiatanIdFilter = $_GET['kegiatan_id'] ?? null;
    $jenisFilter = $_GET['jenis'] ?? null;
    $searchFilter = $_GET['search'] ?? null;

    $kegiatanList = Kegiatan::getAll($userId, '', '', 100, 0); 
    $transaksiList = Transaksi::getAll($userId, $kegiatanIdFilter, $jenisFilter, $searchFilter);
    $rekap = Transaksi::getRekap($userId, $kegiatanIdFilter);
    $saldo = $rekap['pemasukan'] - $rekap['pengeluaran'];

    $activeMenu = 'keuangan';
    $title = 'Keuangan - Sistem Administrasi Panitia';
    include __DIR__ . '/views/keuangan/index.php';
}
