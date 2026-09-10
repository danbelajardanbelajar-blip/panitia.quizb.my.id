<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1" style="font-weight: 800;"><?= $kegiatan ? 'Edit' : 'Tambah' ?> Kegiatan</h2>
        <p class="text-muted-modern mb-0">Isi formulir di bawah untuk mengatur kegiatan panitia.</p>
    </div>
    <a href="kegiatan.php" class="btn-modern btn-secondary-modern">
        <i data-lucide="arrow-left"></i> Kembali
    </a>
</div>

<div class="card-modern">
    <div class="card-header-modern">
        Informasi Kegiatan
    </div>
    <div class="card-body-modern">
        <form method="POST" action="kegiatan.php?action=<?= $kegiatan ? 'edit&id='.$kegiatan['id'] : 'create' ?>">
            <?= csrfField() ?>
            
            <div class="row mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label class="form-label-modern">Nama Kegiatan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_kegiatan" class="form-control-modern" placeholder="Misal: Seminar Nasional Teknologi 2026" required value="<?= htmlspecialchars($kegiatan['nama_kegiatan'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label-modern">Tema <span class="text-muted fw-normal fs-6">(Opsional)</span></label>
                    <input type="text" name="tema" class="form-control-modern" placeholder="Tema besar kegiatan" value="<?= htmlspecialchars($kegiatan['tema'] ?? '') ?>">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label-modern">Deskripsi Singkat <span class="text-muted fw-normal fs-6">(Opsional)</span></label>
                <textarea name="deskripsi" class="form-control-modern" rows="3" placeholder="Jelaskan tujuan atau deskripsi singkat kegiatan ini..."><?= htmlspecialchars($kegiatan['deskripsi'] ?? '') ?></textarea>
            </div>

            <hr class="mb-4" style="border-color: var(--border);">

            <div class="row mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label class="form-label-modern">Tanggal Mulai <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_mulai" class="form-control-modern" required value="<?= htmlspecialchars($kegiatan['tanggal_mulai'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label-modern">Tanggal Selesai <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_selesai" class="form-control-modern" required value="<?= htmlspecialchars($kegiatan['tanggal_selesai'] ?? '') ?>">
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="form-label-modern">Lokasi</label>
                    <input type="text" name="lokasi" class="form-control-modern" placeholder="Misal: Gedung Serbaguna" value="<?= htmlspecialchars($kegiatan['lokasi'] ?? '') ?>">
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="form-label-modern">Penanggung Jawab</label>
                    <input type="text" name="penanggung_jawab" class="form-control-modern" placeholder="Nama ketua pelaksana" value="<?= htmlspecialchars($kegiatan['penanggung_jawab'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label-modern">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select-modern" required>
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

            <div class="mb-5">
                <label class="form-label-modern">Catatan Internal <span class="text-muted fw-normal fs-6">(Opsional)</span></label>
                <textarea name="catatan" class="form-control-modern" rows="2" placeholder="Catatan tambahan untuk panitia inti..."><?= htmlspecialchars($kegiatan['catatan'] ?? '') ?></textarea>
            </div>

            <div class="d-flex justify-content-end gap-3 pt-3 border-top" style="border-color: var(--border) !important;">
                <a href="kegiatan.php" class="btn-modern btn-secondary-modern">Batal</a>
                <button type="submit" class="btn-modern btn-primary-modern">
                    <i data-lucide="save"></i> Simpan Data Kegiatan
                </button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
