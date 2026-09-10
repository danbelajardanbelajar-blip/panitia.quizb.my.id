<?php
require_once __DIR__ . '/../config/database.php';

class Transaksi {
    public static function getAll($userId, $kegiatanId = null, $jenis = null, $search = null) {
        $pdo = getDB();
        $query = "SELECT t.*, k.nama_kegiatan 
                 FROM transaksi t 
                 JOIN kegiatan k ON t.kegiatan_id = k.id 
                 WHERE k.user_id = ?";
        $params = [$userId];

        if ($kegiatanId) {
            $query .= " AND t.kegiatan_id = ?";
            $params[] = $kegiatanId;
        }

        if ($jenis) {
            $query .= " AND t.jenis = ?";
            $params[] = $jenis;
        }

        if ($search) {
            $query .= " AND (t.nama_transaksi LIKE ? OR t.kategori LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        $query .= " ORDER BY t.tanggal DESC, t.created_at DESC";

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function findById($id, $userId) {
        $pdo = getDB();
        $stmt = $pdo->prepare("
            SELECT t.*, k.nama_kegiatan 
            FROM transaksi t 
            JOIN kegiatan k ON t.kegiatan_id = k.id 
            WHERE t.id = ? AND k.user_id = ?
        ");
        $stmt->execute([$id, $userId]);
        return $stmt->fetch();
    }

    public static function create($data) {
        $pdo = getDB();
        $stmt = $pdo->prepare("
            INSERT INTO transaksi (kegiatan_id, jenis, kategori, nama_transaksi, deskripsi, nominal, sumber_tujuan, metode_pembayaran, nomor_bukti, tanggal, catatan)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['kegiatan_id'],
            $data['jenis'],
            $data['kategori'],
            $data['nama_transaksi'],
            $data['deskripsi'] ?? null,
            $data['nominal'],
            $data['sumber_tujuan'] ?? null,
            $data['metode_pembayaran'] ?? 'Cash',
            $data['nomor_bukti'] ?? null,
            $data['tanggal'],
            $data['catatan'] ?? null
        ]);
        return $pdo->lastInsertId();
    }

    public static function update($id, $data) {
        $pdo = getDB();
        $stmt = $pdo->prepare("
            UPDATE transaksi 
            SET kegiatan_id = ?, jenis = ?, kategori = ?, nama_transaksi = ?, deskripsi = ?, nominal = ?, sumber_tujuan = ?, metode_pembayaran = ?, nomor_bukti = ?, tanggal = ?, catatan = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $data['kegiatan_id'],
            $data['jenis'],
            $data['kategori'],
            $data['nama_transaksi'],
            $data['deskripsi'] ?? null,
            $data['nominal'],
            $data['sumber_tujuan'] ?? null,
            $data['metode_pembayaran'] ?? 'Cash',
            $data['nomor_bukti'] ?? null,
            $data['tanggal'],
            $data['catatan'] ?? null,
            $id
        ]);
    }

    public static function delete($id) {
        $pdo = getDB();
        $stmt = $pdo->prepare("DELETE FROM transaksi WHERE id = ?");
        $stmt->execute([$id]);
    }

    public static function getRekap($userId, $kegiatanId = null) {
        $pdo = getDB();
        $query = "SELECT t.jenis, SUM(t.nominal) as total 
                  FROM transaksi t 
                  JOIN kegiatan k ON t.kegiatan_id = k.id 
                  WHERE k.user_id = ?";
        $params = [$userId];

        if ($kegiatanId) {
            $query .= " AND t.kegiatan_id = ?";
            $params[] = $kegiatanId;
        }

        $query .= " GROUP BY t.jenis";

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetchAll();

        $rekap = ['pemasukan' => 0, 'pengeluaran' => 0];
        foreach ($result as $row) {
            $rekap[$row['jenis']] = $row['total'];
        }
        return $rekap;
    }
}
