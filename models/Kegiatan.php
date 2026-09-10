<?php
require_once __DIR__ . '/../config/database.php';

class Kegiatan {
    public static function getAll($userId, $search = '', $status = '', $limit = 20, $offset = 0) {
        $pdo = getDB();
        $query = "SELECT * FROM kegiatan WHERE user_id = ?";
        $params = [$userId];

        if (!empty($search)) {
            $query .= " AND nama_kegiatan LIKE ?";
            $params[] = "%$search%";
        }

        if (!empty($status)) {
            $query .= " AND status = ?";
            $params[] = $status;
        }

        $query .= " ORDER BY created_at DESC LIMIT " . (int)$limit . " OFFSET " . (int)$offset;

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function count($userId, $search = '', $status = '') {
        $pdo = getDB();
        $query = "SELECT COUNT(*) FROM kegiatan WHERE user_id = ?";
        $params = [$userId];

        if (!empty($search)) {
            $query .= " AND nama_kegiatan LIKE ?";
            $params[] = "%$search%";
        }

        if (!empty($status)) {
            $query .= " AND status = ?";
            $params[] = $status;
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public static function findById($id, $userId) {
        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT * FROM kegiatan WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $userId]);
        return $stmt->fetch();
    }

    public static function create($userId, $data) {
        $pdo = getDB();
        $stmt = $pdo->prepare("
            INSERT INTO kegiatan (user_id, nama_kegiatan, tema, deskripsi, tanggal_mulai, tanggal_selesai, lokasi, penanggung_jawab, status, catatan)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $userId,
            $data['nama_kegiatan'],
            $data['tema'] ?? null,
            $data['deskripsi'] ?? null,
            $data['tanggal_mulai'],
            $data['tanggal_selesai'],
            $data['lokasi'] ?? null,
            $data['penanggung_jawab'] ?? null,
            $data['status'] ?? 'Draft',
            $data['catatan'] ?? null
        ]);
        return $pdo->lastInsertId();
    }

    public static function update($id, $userId, $data) {
        $pdo = getDB();
        $stmt = $pdo->prepare("
            UPDATE kegiatan 
            SET nama_kegiatan = ?, tema = ?, deskripsi = ?, tanggal_mulai = ?, tanggal_selesai = ?, lokasi = ?, penanggung_jawab = ?, status = ?, catatan = ?
            WHERE id = ? AND user_id = ?
        ");
        $stmt->execute([
            $data['nama_kegiatan'],
            $data['tema'] ?? null,
            $data['deskripsi'] ?? null,
            $data['tanggal_mulai'],
            $data['tanggal_selesai'],
            $data['lokasi'] ?? null,
            $data['penanggung_jawab'] ?? null,
            $data['status'],
            $data['catatan'] ?? null,
            $id,
            $userId
        ]);
    }

    public static function delete($id, $userId) {
        $pdo = getDB();
        $stmt = $pdo->prepare("DELETE FROM kegiatan WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $userId]);
    }
}
