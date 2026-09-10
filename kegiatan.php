<?php
require_once __DIR__ . '/includes/auth_helper.php';
require_once __DIR__ . '/includes/csrf_helper.php';
require_once __DIR__ . '/includes/format_helper.php';
require_once __DIR__ . '/models/Kegiatan.php';

requireLogin();
$userId = getCurrentUserId();
$action = $_GET['action'] ?? 'index';

if ($action === 'create' || $action === 'edit') {
    $id = $_GET['id'] ?? null;
    $kegiatan = null;
    
    if ($action === 'edit' && $id) {
        $kegiatan = Kegiatan::findById($id, $userId);
        if (!$kegiatan) {
            die("Kegiatan tidak ditemukan atau Anda tidak memiliki akses.");
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            die("CSRF token tidak valid.");
        }
        
        $data = [
            'nama_kegiatan' => $_POST['nama_kegiatan'],
            'tema' => $_POST['tema'],
            'deskripsi' => $_POST['deskripsi'],
            'tanggal_mulai' => $_POST['tanggal_mulai'],
            'tanggal_selesai' => $_POST['tanggal_selesai'],
            'lokasi' => $_POST['lokasi'],
            'penanggung_jawab' => $_POST['penanggung_jawab'],
            'status' => $_POST['status'],
            'catatan' => $_POST['catatan']
        ];
        
        if ($action === 'create') {
            Kegiatan::create($userId, $data);
        } else {
            Kegiatan::update($id, $userId, $data);
        }
        
        header('Location: kegiatan.php');
        exit;
    }
    
    $activeMenu = 'kegiatan';
    $title = ($action === 'create' ? 'Tambah' : 'Edit') . ' Kegiatan - Sistem Administrasi Panitia';
    include __DIR__ . '/views/kegiatan/form.php';

} elseif ($action === 'delete') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            die("CSRF token tidak valid.");
        }
        $id = $_POST['id'] ?? null;
        if ($id) {
            Kegiatan::delete($id, $userId);
        }
        header('Location: kegiatan.php');
        exit;
    }
} else {
    // List / Index
    $search = $_GET['search'] ?? '';
    $status = $_GET['status'] ?? '';
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;

    $kegiatanList = Kegiatan::getAll($userId, $search, $status, $limit, $offset);
    $totalData = Kegiatan::count($userId, $search, $status);
    $totalPages = ceil($totalData / $limit);

    $activeMenu = 'kegiatan';
    $title = 'Daftar Kegiatan - Sistem Administrasi Panitia';
    include __DIR__ . '/views/kegiatan/index.php';
}
