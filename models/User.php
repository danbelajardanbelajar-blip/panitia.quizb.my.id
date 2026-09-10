<?php
require_once __DIR__ . '/../config/database.php';

class User {
    public static function findByGoogleId($googleId) {
        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE google_id = ?");
        $stmt->execute([$googleId]);
        return $stmt->fetch();
    }

    public static function create($data) {
        $pdo = getDB();
        $stmt = $pdo->prepare("INSERT INTO users (google_id, email, name, avatar) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data['google_id'],
            $data['email'],
            $data['name'],
            $data['avatar']
        ]);
        return $pdo->lastInsertId();
    }
    
    public static function update($id, $data) {
        $pdo = getDB();
        $stmt = $pdo->prepare("UPDATE users SET email = ?, name = ?, avatar = ? WHERE id = ?");
        $stmt->execute([
            $data['email'],
            $data['name'],
            $data['avatar'],
            $id
        ]);
    }
}
