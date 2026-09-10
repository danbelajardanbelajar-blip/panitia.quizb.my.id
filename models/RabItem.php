<?php
require_once __DIR__ . '/../config/database.php';

class RabItem {
    public static function getByRabId($rabId) {
        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT * FROM rab_items WHERE rab_id = ? ORDER BY kategori, created_at ASC");
        $stmt->execute([$rabId]);
        return $stmt->fetchAll();
    }

    public static function findById($id) {
        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT * FROM rab_items WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($data) {
        $pdo = getDB();
        $stmt = $pdo->prepare("
            INSERT INTO rab_items (rab_id, kategori, nama_item, deskripsi, volume, satuan, harga_satuan)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['rab_id'],
            $data['kategori'],
            $data['nama_item'],
            $data['deskripsi'] ?? null,
            $data['volume'],
            $data['satuan'],
            $data['harga_satuan']
        ]);
        return $pdo->lastInsertId();
    }

    public static function update($id, $data) {
        $pdo = getDB();
        $stmt = $pdo->prepare("
            UPDATE rab_items 
            SET kategori = ?, nama_item = ?, deskripsi = ?, volume = ?, satuan = ?, harga_satuan = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $data['kategori'],
            $data['nama_item'],
            $data['deskripsi'] ?? null,
            $data['volume'],
            $data['satuan'],
            $data['harga_satuan'],
            $id
        ]);
    }

    public static function delete($id) {
        $pdo = getDB();
        $stmt = $pdo->prepare("DELETE FROM rab_items WHERE id = ?");
        $stmt->execute([$id]);
    }
}
