<?php
require_once __DIR__ . '/includes/auth_helper.php';
require_once __DIR__ . '/includes/csrf_helper.php';
require_once __DIR__ . '/includes/format_helper.php';
require_once __DIR__ . '/models/Kegiatan.php';
require_once __DIR__ . '/models/CatatanKegiatan.php';

requireLogin();
$userId = getCurrentUserId();
$action = $_GET['action'] ?? 'index';

if ($action === 'create' || $action === 'edit') {
    $id = $_GET['id'] ?? null;
    $catatan = null;
    
    $kegiatanList = Kegiatan::getAll($userId, '', '', 1000, 0); 
    
    if ($action === 'edit' && $id) {
        $catatan = CatatanKegiatan::findById($id, $userId);
        if (!$catatan) {
            die("Catatan tidak ditemukan atau Anda tidak memiliki akses.");
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
            'tanggal' => $_POST['tanggal'],
            'waktu' => $_POST['waktu'],
            'judul' => $_POST['judul'],
            'isi' => $_POST['isi'],
            'kategori' => $_POST['kategori'],
            'penanggung_jawab' => $_POST['penanggung_jawab'] ?? null,
            'status' => $_POST['status'] ?? null
        ];
        
        if ($action === 'create') {
            CatatanKegiatan::create($data);
        } else {
            CatatanKegiatan::update($id, $data);
        }
        
        header('Location: catatan.php');
        exit;
    }
    
    $activeMenu = 'catatan';
    $title = ($action === 'create' ? 'Tambah' : 'Edit') . ' Catatan - Sistem Administrasi Panitia';
    include __DIR__ . '/views/catatan/form.php';

} elseif ($action === 'delete') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            die("CSRF token tidak valid.");
        }
        $id = $_POST['id'] ?? null;
        if ($id) {
            $catatan = CatatanKegiatan::findById($id, $userId);
            if ($catatan) {
                CatatanKegiatan::delete($id);
            }
        }
        header('Location: catatan.php');
        exit;
    }
} else {
    // List / Index
    $kegiatanIdFilter = $_GET['kegiatan_id'] ?? null;
    $kategoriFilter = $_GET['kategori'] ?? null;
    $searchFilter = $_GET['search'] ?? null;

    $kegiatanList = Kegiatan::getAll($userId, '', '', 100, 0); 
    $catatanList = CatatanKegiatan::getAll($userId, $kegiatanIdFilter, $kategoriFilter, $searchFilter);

    $activeMenu = 'catatan';
    $title = 'Catatan Kegiatan - Sistem Administrasi Panitia';
    include __DIR__ . '/views/catatan/index.php';
}
