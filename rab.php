<?php
require_once __DIR__ . '/includes/auth_helper.php';
require_once __DIR__ . '/includes/csrf_helper.php';
require_once __DIR__ . '/includes/format_helper.php';
require_once __DIR__ . '/models/Kegiatan.php';
require_once __DIR__ . '/models/Rab.php';
require_once __DIR__ . '/models/RabItem.php';

requireLogin();
$userId = getCurrentUserId();
$action = $_GET['action'] ?? 'index';

if ($action === 'create' || $action === 'edit') {
    $id = $_GET['id'] ?? null;
    $rab = null;
    
    // Get all user activities for dropdown
    $kegiatanList = Kegiatan::getAll($userId, '', '', 1000, 0); // Get max 1000 for dropdown
    
    if ($action === 'edit' && $id) {
        $rab = Rab::findById($id, $userId);
        if (!$rab) {
            die("RAB tidak ditemukan atau Anda tidak memiliki akses.");
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            die("CSRF token tidak valid.");
        }
        
        $kegiatanId = $_POST['kegiatan_id'];
        // Verifikasi kepemilikan kegiatan
        $kegiatan = Kegiatan::findById($kegiatanId, $userId);
        if (!$kegiatan) die("Akses ditolak.");

        $data = [
            'kegiatan_id' => $kegiatanId,
            'nama_rab' => $_POST['nama_rab'],
            'nomor_dokumen' => $_POST['nomor_dokumen'],
            'tanggal' => $_POST['tanggal'],
            'status' => $_POST['status'],
            'catatan' => $_POST['catatan']
        ];
        
        if ($action === 'create') {
            Rab::create($data);
        } else {
            Rab::update($id, $data);
        }
        
        header('Location: rab.php');
        exit;
    }
    
    $activeMenu = 'rab';
    $title = ($action === 'create' ? 'Tambah' : 'Edit') . ' RAB - Sistem Administrasi Panitia';
    include __DIR__ . '/views/rab/form.php';

} elseif ($action === 'delete') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            die("CSRF token tidak valid.");
        }
        $id = $_POST['id'] ?? null;
        if ($id) {
            $rab = Rab::findById($id, $userId);
            if ($rab) {
                Rab::delete($id);
            }
        }
        header('Location: rab.php');
        exit;
    }
} elseif ($action === 'items') {
    $rabId = $_GET['id'] ?? null;
    $rab = Rab::findById($rabId, $userId);
    
    if (!$rab) {
        die("RAB tidak ditemukan.");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            die("CSRF token tidak valid.");
        }
        
        $itemAction = $_POST['item_action'] ?? '';
        
        if ($itemAction === 'create' || $itemAction === 'edit') {
            $itemId = $_POST['item_id'] ?? null;
            $data = [
                'rab_id' => $rabId,
                'kategori' => $_POST['kategori'],
                'nama_item' => $_POST['nama_item'],
                'deskripsi' => $_POST['deskripsi'] ?? null,
                'volume' => $_POST['volume'],
                'satuan' => $_POST['satuan'],
                'harga_satuan' => $_POST['harga_satuan']
            ];
            
            if ($itemAction === 'create') {
                RabItem::create($data);
            } else {
                RabItem::update($itemId, $data);
            }
        } elseif ($itemAction === 'delete') {
            $itemId = $_POST['item_id'] ?? null;
            if ($itemId) {
                RabItem::delete($itemId);
            }
        }
        
        header("Location: rab.php?action=items&id=$rabId");
        exit;
    }

    $items = RabItem::getByRabId($rabId);
    
    $activeMenu = 'rab';
    $title = 'Detail RAB - Sistem Administrasi Panitia';
    include __DIR__ . '/views/rab/items.php';

} else {
    // List / Index
    $kegiatanIdFilter = $_GET['kegiatan_id'] ?? null;
    $kegiatanList = Kegiatan::getAll($userId, '', '', 100, 0); 
    
    $rabList = Rab::getAll($userId, $kegiatanIdFilter);

    $activeMenu = 'rab';
    $title = 'Daftar RAB - Sistem Administrasi Panitia';
    include __DIR__ . '/views/rab/index.php';
}
