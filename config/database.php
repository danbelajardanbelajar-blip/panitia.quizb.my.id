<?php
require_once __DIR__ . '/../includes/env_helper.php';

function getDB() {
    static $pdo = null;
    
    if ($pdo === null) {
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $db   = $_ENV['DB_NAME'] ?? 'quic1934_panitia';
        $user = $_ENV['DB_USER'] ?? 'quic1934_zenhkm';
        $pass = $_ENV['DB_PASS'] ?? '03Maret1990';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
            
            // Cek apakah tabel users sudah ada
            $check = $pdo->query("SHOW TABLES LIKE 'users'");
            if ($check->rowCount() == 0) {
                $schemaPath = __DIR__ . '/../database/schema.sql';
                if (file_exists($schemaPath)) {
                    $schema = file_get_contents($schemaPath);
                    $pdo->exec($schema);
                }
                $seedPath = __DIR__ . '/../database/seed.sql';
                if (file_exists($seedPath)) {
                    $seed = file_get_contents($seedPath);
                    $pdo->exec($seed);
                }
            }

            // Auto-Migrate kolom jenis pada rab_items (Upgrade RAB ke RAPB)
            // Lakukan try-catch khusus agar jika gagal tidak mematikan koneksi secara keseluruhan,
            // atau cukup gunakan SHOW COLUMNS
            $checkCol = $pdo->query("SHOW COLUMNS FROM `rab_items` LIKE 'jenis'");
            if ($checkCol->rowCount() == 0) {
                $pdo->exec("ALTER TABLE `rab_items` ADD COLUMN `jenis` ENUM('pemasukan', 'pengeluaran') NOT NULL DEFAULT 'pengeluaran' AFTER `rab_id`");
            }
            // Create workspace_members table for collaboration feature
            $checkWorkspace = $pdo->query("SHOW TABLES LIKE 'workspace_members'");
            if ($checkWorkspace->rowCount() == 0) {
                $pdo->exec("CREATE TABLE workspace_members (
                    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                    owner_id BIGINT UNSIGNED NOT NULL, 
                    email VARCHAR(255) NOT NULL, 
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
                    FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE
                )");
            }
        } catch (\PDOException $e) {
            // In production, do not output the real error message
            error_log($e->getMessage());
            die("Database connection failed. Please try again later. " . $e->getMessage());
        }
    }
    
    return $pdo;
}
