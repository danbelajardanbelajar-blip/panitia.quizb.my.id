<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><?= $kegiatan ? 'Edit' : 'Tambah' ?> Kegiatan</h2>
    <a href="kegiatan.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="POST" action="kegiatan.php?action=<?= $kegiatan ? 'edit&id='.$kegiatan['id'] : 'create' ?>">
            <?= csrfField() ?>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_kegiatan" class="form-control" required value="<?= htmlspecialchars($kegiatan['nama_kegiatan'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tema</label>
                    <input type="text" name="tema" class="form-control" value="<?= htmlspecialchars($kegiatan['tema'] ?? '') ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($kegiatan['deskripsi'] ?? '') ?></textarea>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_mulai" class="form-control" required value="<?= htmlspecialchars($kegiatan['tanggal_mulai'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_selesai" class="form-control" required value="<?= htmlspecialchars($kegiatan['tanggal_selesai'] ?? '') ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Lokasi</label>
                    <input type="text" name="lokasi" class="form-control" value="<?= htmlspecialchars($kegiatan['lokasi'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Penanggung Jawab</label>
                    <input type="text" name="penanggung_jawab" class="form-control" value="<?= htmlspecialchars($kegiatan['penanggung_jawab'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <?php
                        $statuses = ['Draft', 'Persiapan', 'Berlangsung', 'Selesai', 'Diarsipkan'];
                        $currentStatus = $kegiatan['status'] ?? 'Draft';
                        foreach ($statuses as $s) {
                            $selected = $currentStatus === $s ? 'selected' : '';
                            echo "<option value=\"$s\" $selected>$s</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Catatan Tambahan</label>
                <textarea name="catatan" class="form-control" rows="2"><?= htmlspecialchars($kegiatan['catatan'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Data</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
