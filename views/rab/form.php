<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h2 class="mb-1" style="font-weight: 800;"><?= $rab ? 'Edit' : 'Tambah' ?> RAB</h2>
        <p class="text-muted-modern mb-0">Informasi utama dokumen Rencana Anggaran Biaya.</p>
    </div>
    <a href="rab.php" class="btn-modern btn-secondary-modern">
        <i data-lucide="arrow-left"></i> Kembali
    </a>
</div>

<div class="card-modern">
    <div class="card-header-modern">
        Informasi Dokumen
    </div>
    <div class="card-body-modern">
        <form method="POST" action="rab.php?action=<?= $rab ? 'edit&id='.$rab['id'] : 'create' ?>">
            <?= csrfField() ?>
            
            <div class="mb-4">
                <label class="form-label-modern">Kegiatan Terkait <span class="text-danger">*</span></label>
                <select name="kegiatan_id" class="form-select-modern" required>
                    <option value="">-- Pilih Kegiatan --</option>
                    <?php foreach ($kegiatanList as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= ($rab['kegiatan_id'] ?? '') == $k['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($k['nama_kegiatan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text mt-2 text-muted-modern"><i data-lucide="info" style="width: 14px; margin-right: 4px;"></i>Pilih kegiatan mana yang akan didanai oleh RAB ini.</div>
            </div>

            <hr class="mb-4" style="border-color: var(--border);">

            <div class="row mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label class="form-label-modern">Judul RAB <span class="text-danger">*</span></label>
                    <input type="text" name="nama_rab" class="form-control-modern" placeholder="Misal: RAB Konsumsi Hari H" required value="<?= htmlspecialchars($rab['nama_rab'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label-modern">Nomor Surat/Dokumen <span class="text-muted fw-normal fs-6">(Opsional)</span></label>
                    <input type="text" name="nomor_dokumen" class="form-control-modern" placeholder="Misal: 001/RAB/PAN/2026" value="<?= htmlspecialchars($rab['nomor_dokumen'] ?? '') ?>">
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label class="form-label-modern">Tanggal Pembuatan <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control-modern" required value="<?= htmlspecialchars($rab['tanggal'] ?? date('Y-m-d')) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label-modern">Status Persetujuan <span class="text-danger">*</span></label>
                    <select name="status" class="form-select-modern" required>
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

            <div class="mb-5">
                <label class="form-label-modern">Catatan Tambahan <span class="text-muted fw-normal fs-6">(Opsional)</span></label>
                <textarea name="catatan" class="form-control-modern" rows="3" placeholder="Tuliskan catatan khusus terkait pengajuan anggaran ini..."><?= htmlspecialchars($rab['catatan'] ?? '') ?></textarea>
            </div>

            <div class="d-flex justify-content-end gap-3 pt-3 border-top" style="border-color: var(--border) !important;">
                <a href="rab.php" class="btn-modern btn-secondary-modern">Batal</a>
                <button type="submit" class="btn-modern btn-primary-modern">
                    <i data-lucide="save"></i> Simpan Info Dokumen
                </button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
