<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><?= $catatan ? 'Edit' : 'Tambah' ?> Catatan Kegiatan</h2>
    <a href="catatan.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="POST" action="catatan.php?action=<?= $catatan ? 'edit&id='.$catatan['id'] : 'create' ?>">
            <?= csrfField() ?>
            
            <div class="mb-3">
                <label class="form-label">Kegiatan <span class="text-danger">*</span></label>
                <select name="kegiatan_id" class="form-select" required>
                    <option value="">-- Pilih Kegiatan --</option>
                    <?php foreach ($kegiatanList as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= ($catatan['kegiatan_id'] ?? '') == $k['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($k['nama_kegiatan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control" required value="<?= htmlspecialchars($catatan['tanggal'] ?? date('Y-m-d')) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Waktu <span class="text-danger">*</span></label>
                    <input type="time" name="waktu" class="form-control" required value="<?= htmlspecialchars($catatan['waktu'] ?? date('H:i')) ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Judul Catatan <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control" required value="<?= htmlspecialchars($catatan['judul'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Isi / Deskripsi Lengkap <span class="text-danger">*</span></label>
                <textarea name="isi" class="form-control" rows="5" required><?= htmlspecialchars($catatan['isi'] ?? '') ?></textarea>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" class="form-select" required>
                        <?php
                        $kategoris = ['Rapat', 'Persiapan', 'Pelaksanaan', 'Evaluasi', 'Kendala', 'Keputusan', 'Lainnya'];
                        $currentKategori = $catatan['kategori'] ?? 'Lainnya';
                        foreach ($kategoris as $k) {
                            $selected = $currentKategori === $k ? 'selected' : '';
                            echo "<option value=\"$k\" $selected>$k</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Penanggung Jawab</label>
                    <input type="text" name="penanggung_jawab" class="form-control" value="<?= htmlspecialchars($catatan['penanggung_jawab'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <input type="text" name="status" class="form-control" placeholder="Cth: Selesai, Pending, Follow-up" value="<?= htmlspecialchars($catatan['status'] ?? '') ?>">
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Catatan</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
