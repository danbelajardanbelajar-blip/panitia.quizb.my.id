<?php
require_once __DIR__ . '/config/database.php';

try {
    $pdo = getDB();
} catch (\PDOException $e) {
    die("Koneksi / Inisialisasi Database Gagal. Pastikan kredensial database sudah benar. Error: " . $e->getMessage());
}

// Redirect ke dashboard
header('Location: dashboard.php');
exit;
