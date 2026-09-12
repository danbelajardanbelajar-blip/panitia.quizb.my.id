<?php
require_once __DIR__ . '/config/database.php';
try {
    $pdo = getDB();
    $pdo->exec("CREATE TABLE IF NOT EXISTS workspace_members (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
        owner_id BIGINT UNSIGNED NOT NULL, 
        email VARCHAR(255) NOT NULL, 
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
        FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE
    )");
    echo "Success";
} catch (Exception $e) {
    echo $e->getMessage();
}
