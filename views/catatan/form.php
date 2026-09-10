<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h2 class="mb-1" style="font-weight: 800;"><?= $catatan ? 'Edit' : 'Tambah' ?> Catatan Kegiatan</h2>
        <p class="text-muted-modern mb-0">Dokumentasikan progres, rapat, atau masalah dalam kegiatan.</p>
    </div>
    <a href="catatan.php" class="btn-modern btn-secondary-modern">
        <i data-lucide="arrow-left"></i> Kembali
    </a>
</div>

<div class="card-modern">
    <div class="card-header-modern">
        Detail Catatan
    </div>
    <div class="card-body-modern">
        <form method="POST" action="catatan.php?action=<?= $catatan ? 'edit&id='.$catatan['id'] : 'create' ?>">
            <?= csrfField() ?>
            
            <div class="mb-4">
                <label class="form-label-modern">Kegiatan Terkait <span class="text-danger">*</span></label>
                <select name="kegiatan_id" class="form-select-modern" required>
                    <option value="">-- Pilih Kegiatan --</option>
                    <?php foreach ($kegiatanList as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= ($catatan['kegiatan_id'] ?? '') == $k['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($k['nama_kegiatan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <hr class="mb-4" style="border-color: var(--border);">

            <div class="row mb-4">
                <div class="col-md-8 mb-3 mb-md-0">
                    <label class="form-label-modern">Judul Catatan <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control-modern" placeholder="Misal: Hasil Rapat Koordinasi Panitia" required value="<?= htmlspecialchars($catatan['judul'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label-modern">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" class="form-select-modern" required>
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
            </div>

            <div class="mb-4">
                <label class="form-label-modern">Isi / Deskripsi Lengkap <span class="text-danger">*</span></label>
                <textarea name="isi" class="form-control-modern" rows="6" placeholder="Tuliskan detail catatan di sini..." required><?= htmlspecialchars($catatan['isi'] ?? '') ?></textarea>
            </div>

            <div class="row mb-5">
                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="form-label-modern">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control-modern" required value="<?= htmlspecialchars($catatan['tanggal'] ?? date('Y-m-d')) ?>">
                </div>
                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="form-label-modern">Waktu <span class="text-danger">*</span></label>
                    <input type="time" name="waktu" class="form-control-modern" required value="<?= htmlspecialchars($catatan['waktu'] ?? date('H:i')) ?>">
                </div>
                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="form-label-modern">Penanggung Jawab <span class="text-muted fw-normal fs-6">(Opsional)</span></label>
                    <input type="text" name="penanggung_jawab" class="form-control-modern" placeholder="Nama PIC" value="<?= htmlspecialchars($catatan['penanggung_jawab'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label-modern">Status Progress <span class="text-muted fw-normal fs-6">(Opsional)</span></label>
                    <input type="text" name="status" class="form-control-modern" placeholder="Cth: Selesai, Follow-up" value="<?= htmlspecialchars($catatan['status'] ?? '') ?>">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 pt-3 border-top" style="border-color: var(--border) !important;">
                <a href="catatan.php" class="btn-modern btn-secondary-modern">Batal</a>
                <button type="submit" class="btn-modern btn-primary-modern">
                    <i data-lucide="save"></i> Simpan Catatan
                </button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
