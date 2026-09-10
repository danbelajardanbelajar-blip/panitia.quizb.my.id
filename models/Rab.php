<?php
require_once __DIR__ . '/../config/database.php';

class Rab {
    public static function getAll($userId, $kegiatanId = null) {
        $pdo = getDB();
        $query = "SELECT r.*, k.nama_kegiatan, 
                 (SELECT COALESCE(SUM(volume * harga_satuan), 0) FROM rab_items WHERE rab_id = r.id) as total_rab 
                 FROM rab r 
                 JOIN kegiatan k ON r.kegiatan_id = k.id 
                 WHERE k.user_id = ?";
        $params = [$userId];

        if ($kegiatanId) {
            $query .= " AND r.kegiatan_id = ?";
            $params[] = $kegiatanId;
        }

        $query .= " ORDER BY r.created_at DESC";

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function findById($id, $userId) {
        $pdo = getDB();
        $stmt = $pdo->prepare("
            SELECT r.*, k.nama_kegiatan 
            FROM rab r 
            JOIN kegiatan k ON r.kegiatan_id = k.id 
            WHERE r.id = ? AND k.user_id = ?
        ");
        $stmt->execute([$id, $userId]);
        return $stmt->fetch();
    }

    public static function create($data) {
        $pdo = getDB();
        $stmt = $pdo->prepare("
            INSERT INTO rab (kegiatan_id, nama_rab, nomor_dokumen, tanggal, catatan, status)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['kegiatan_id'],
            $data['nama_rab'],
            $data['nomor_dokumen'] ?? null,
            $data['tanggal'],
            $data['catatan'] ?? null,
            $data['status'] ?? 'Draft'
        ]);
        return $pdo->lastInsertId();
    }

    public static function update($id, $data) {
        $pdo = getDB();
        $stmt = $pdo->prepare("
            UPDATE rab 
            SET kegiatan_id = ?, nama_rab = ?, nomor_dokumen = ?, tanggal = ?, catatan = ?, status = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $data['kegiatan_id'],
            $data['nama_rab'],
            $data['nomor_dokumen'] ?? null,
            $data['tanggal'],
            $data['catatan'] ?? null,
            $data['status'],
            $id
        ]);
    }

    public static function delete($id) {
        $pdo = getDB();
        $stmt = $pdo->prepare("DELETE FROM rab WHERE id = ?");
        $stmt->execute([$id]);
    }
}
