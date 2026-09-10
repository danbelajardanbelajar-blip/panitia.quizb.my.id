<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><?= $rab ? 'Edit' : 'Tambah' ?> RAB</h2>
    <a href="rab.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="POST" action="rab.php?action=<?= $rab ? 'edit&id='.$rab['id'] : 'create' ?>">
            <?= csrfField() ?>
            
            <div class="mb-3">
                <label class="form-label">Kegiatan <span class="text-danger">*</span></label>
                <select name="kegiatan_id" class="form-select" required>
                    <option value="">-- Pilih Kegiatan --</option>
                    <?php foreach ($kegiatanList as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= ($rab['kegiatan_id'] ?? '') == $k['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($k['nama_kegiatan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nama RAB <span class="text-danger">*</span></label>
                    <input type="text" name="nama_rab" class="form-control" required value="<?= htmlspecialchars($rab['nama_rab'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nomor Dokumen</label>
                    <input type="text" name="nomor_dokumen" class="form-control" value="<?= htmlspecialchars($rab['nomor_dokumen'] ?? '') ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control" required value="<?= htmlspecialchars($rab['tanggal'] ?? date('Y-m-d')) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <?php
                        $statuses = ['Draft', 'Diajukan', 'Disetujui', 'Ditolak'];
                        $currentStatus = $rab['status'] ?? 'Draft';
                        foreach ($statuses as $s) {
                            $selected = $currentStatus === $s ? 'selected' : '';
                            echo "<option value=\"$s\" $selected>$s</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" class="form-control" rows="2"><?= htmlspecialchars($rab['catatan'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Data</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
