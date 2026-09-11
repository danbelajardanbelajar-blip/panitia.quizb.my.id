<?php
require_once __DIR__ . '/config/database.php';

try {
    $pdo = getDB();
    // Cek apakah tabel users sudah ada
    $check = $pdo->query("SHOW TABLES LIKE 'users'");
    
    if ($check->rowCount() == 0) {
        // Jika belum ada, jalankan schema.sql
        $schemaPath = __DIR__ . '/database/schema.sql';
        if (file_exists($schemaPath)) {
            $schema = file_get_contents($schemaPath);
            $pdo->exec($schema);
        }

        // Jalankan seed.sql untuk data dummy
        $seedPath = __DIR__ . '/database/seed.sql';
        if (file_exists($seedPath)) {
            $seed = file_get_contents($seedPath);
            $pdo->exec($seed);
        }
    }
    
    // Auto-Migrate kolom jenis pada rab_items (Upgrade RAB ke RAPB)
    $checkCol = $pdo->query("SHOW COLUMNS FROM `rab_items` LIKE 'jenis'");
    if ($checkCol->rowCount() == 0) {
        $pdo->exec("ALTER TABLE `rab_items` ADD COLUMN `jenis` ENUM('pemasukan', 'pengeluaran') NOT NULL DEFAULT 'pengeluaran' AFTER `rab_id`");
    }
} catch (\PDOException $e) {
    die("Koneksi / Inisialisasi Database Gagal. Pastikan kredensial database sudah benar. Error: " . $e->getMessage());
}

// Redirect ke dashboard
header('Location: dashboard.php');
exit;
