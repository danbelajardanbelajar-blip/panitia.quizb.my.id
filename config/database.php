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
        } catch (\PDOException $e) {
            // In production, do not output the real error message
            error_log($e->getMessage());
            die("Database connection failed. Please try again later.");
        }
    }
    
    return $pdo;
}
