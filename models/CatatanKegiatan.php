<?php
require_once __DIR__ . '/../config/database.php';

class CatatanKegiatan {
    public static function getAll($userId, $kegiatanId = null, $kategori = null, $search = null) {
        $pdo = getDB();
        $query = "SELECT c.*, k.nama_kegiatan 
                 FROM catatan_kegiatan c 
                 JOIN kegiatan k ON c.kegiatan_id = k.id 
                 WHERE k.user_id = ?";
        $params = [$userId];

        if ($kegiatanId) {
            $query .= " AND c.kegiatan_id = ?";
            $params[] = $kegiatanId;
        }

        if ($kategori) {
            $query .= " AND c.kategori = ?";
            $params[] = $kategori;
        }

        if ($search) {
            $query .= " AND (c.judul LIKE ? OR c.isi LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        $query .= " ORDER BY c.tanggal DESC, c.waktu DESC";

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function findById($id, $userId) {
        $pdo = getDB();
        $stmt = $pdo->prepare("
            SELECT c.*, k.nama_kegiatan 
            FROM catatan_kegiatan c 
            JOIN kegiatan k ON c.kegiatan_id = k.id 
            WHERE c.id = ? AND k.user_id = ?
        ");
        $stmt->execute([$id, $userId]);
        return $stmt->fetch();
    }

    public static function create($data) {
        $pdo = getDB();
        $stmt = $pdo->prepare("
            INSERT INTO catatan_kegiatan (kegiatan_id, tanggal, waktu, judul, isi, kategori, penanggung_jawab, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['kegiatan_id'],
            $data['tanggal'],
            $data['waktu'],
            $data['judul'],
            $data['isi'],
            $data['kategori'],
            $data['penanggung_jawab'] ?? null,
            $data['status'] ?? null
        ]);
        return $pdo->lastInsertId();
    }

    public static function update($id, $data) {
        $pdo = getDB();
        $stmt = $pdo->prepare("
            UPDATE catatan_kegiatan 
            SET kegiatan_id = ?, tanggal = ?, waktu = ?, judul = ?, isi = ?, kategori = ?, penanggung_jawab = ?, status = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $data['kegiatan_id'],
            $data['tanggal'],
            $data['waktu'],
            $data['judul'],
            $data['isi'],
            $data['kategori'],
            $data['penanggung_jawab'] ?? null,
            $data['status'] ?? null,
            $id
        ]);
    }

    public static function delete($id) {
        $pdo = getDB();
        $stmt = $pdo->prepare("DELETE FROM catatan_kegiatan WHERE id = ?");
        $stmt->execute([$id]);
    }
}
