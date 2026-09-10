<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h2 class="mb-1" style="font-weight: 800;"><?= $transaksi ? 'Edit' : 'Catat' ?> Transaksi</h2>
        <p class="text-muted-modern mb-0">Catat pemasukan dan pengeluaran ke dalam buku kas.</p>
    </div>
    <a href="keuangan.php" class="btn-modern btn-secondary-modern">
        <i data-lucide="arrow-left"></i> Kembali
    </a>
</div>

<div class="card-modern">
    <div class="card-header-modern">
        Detail Transaksi
    </div>
    <div class="card-body-modern">
        <form method="POST" action="keuangan.php?action=<?= $transaksi ? 'edit&id='.$transaksi['id'] : 'create' ?>">
            <?= csrfField() ?>
            
            <div class="row mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label class="form-label-modern">Kegiatan Terkait <span class="text-danger">*</span></label>
                    <select name="kegiatan_id" class="form-select-modern" required>
                        <option value="">-- Pilih Kegiatan --</option>
                        <?php foreach ($kegiatanList as $k): ?>
                            <option value="<?= $k['id'] ?>" <?= ($transaksi['kegiatan_id'] ?? '') == $k['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($k['nama_kegiatan']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label-modern">Tanggal Transaksi <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control-modern" required value="<?= htmlspecialchars($transaksi['tanggal'] ?? date('Y-m-d')) ?>">
                </div>
            </div>

            <hr class="mb-4" style="border-color: var(--border);">

            <div class="row mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label class="form-label-modern">Jenis Arus Kas <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3">
                        <div class="form-check" style="flex: 1; border: 1px solid var(--border); border-radius: 8px; padding: 12px 12px 12px 36px;">
                            <input class="form-check-input" type="radio" name="jenis" id="jenisPemasukan" value="pemasukan" required onchange="updateKategori()" <?= ($transaksi['jenis'] ?? '') == 'pemasukan' ? 'checked' : '' ?>>
                            <label class="form-check-label w-100 fw-medium" for="jenisPemasukan" style="color: #16a34a; cursor: pointer;">
                                Pemasukan
                            </label>
                        </div>
                        <div class="form-check" style="flex: 1; border: 1px solid var(--border); border-radius: 8px; padding: 12px 12px 12px 36px;">
                            <input class="form-check-input" type="radio" name="jenis" id="jenisPengeluaran" value="pengeluaran" required onchange="updateKategori()" <?= ($transaksi['jenis'] ?? '') == 'pengeluaran' ? 'checked' : '' ?>>
                            <label class="form-check-label w-100 fw-medium" for="jenisPengeluaran" style="color: #dc2626; cursor: pointer;">
                                Pengeluaran
                            </label>
                        </div>
                    </div>
                    <!-- Hidden select to keep existing JS logic intact -->
                    <select id="jenis" class="d-none">
                        <option value="pemasukan" <?= ($transaksi['jenis'] ?? '') == 'pemasukan' ? 'selected' : '' ?>></option>
                        <option value="pengeluaran" <?= ($transaksi['jenis'] ?? '') == 'pengeluaran' ? 'selected' : '' ?>></option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label-modern">Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="kategori" id="kategori" class="form-control-modern" required list="kategoriList" value="<?= htmlspecialchars($transaksi['kategori'] ?? '') ?>" placeholder="Pilih kategori">
                    <datalist id="kategoriList">
                        <!-- Populated by JS -->
                    </datalist>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label-modern">Nama / Uraian Transaksi <span class="text-danger">*</span></label>
                <input type="text" name="nama_transaksi" class="form-control-modern" placeholder="Misal: Pembayaran DP Katering" required value="<?= htmlspecialchars($transaksi['nama_transaksi'] ?? '') ?>">
            </div>

            <div class="mb-4 p-3" style="background-color: var(--background); border-radius: 12px; border: 1px solid var(--border);">
                <label class="form-label-modern" style="color: var(--text-main);">Nominal Transaksi (Rp) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 fw-bold" style="border-color: var(--border); border-radius: 8px 0 0 8px; padding-left: 16px;">Rp</span>
                    <input type="text" name="nominal" id="nominal" class="form-control-modern border-start-0 ps-1" required value="<?= htmlspecialchars($transaksi['nominal'] ?? '') ?>" onkeyup="formatAngka(this)" style="font-size: 1.25rem; font-weight: 700; height: 50px;">
                </div>
            </div>

            <hr class="mb-4" style="border-color: var(--border);">

            <div class="row mb-4">
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="form-label-modern">Metode Pembayaran <span class="text-danger">*</span></label>
                    <select name="metode_pembayaran" class="form-select-modern" required>
                        <?php
                        $metode = ['Cash', 'Transfer', 'E-Wallet', 'Lainnya'];
                        $currentMetode = $transaksi['metode_pembayaran'] ?? 'Cash';
                        foreach ($metode as $m) {
                            $selected = $currentMetode === $m ? 'selected' : '';
                            echo "<option value=\"$m\" $selected>$m</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="form-label-modern">Nomor Bukti / Referensi <span class="text-muted fw-normal fs-6">(Opsional)</span></label>
                    <input type="text" name="nomor_bukti" class="form-control-modern" placeholder="No Invoice / Struk" value="<?= htmlspecialchars($transaksi['nomor_bukti'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label-modern">Sumber / Penerima <span class="text-muted fw-normal fs-6">(Opsional)</span></label>
                    <input type="text" name="sumber_tujuan" class="form-control-modern" placeholder="Nama orang/vendor" value="<?= htmlspecialchars($transaksi['sumber_tujuan'] ?? '') ?>">
                </div>
            </div>

            <div class="mb-5">
                <label class="form-label-modern">Deskripsi Tambahan <span class="text-muted fw-normal fs-6">(Opsional)</span></label>
                <textarea name="deskripsi" class="form-control-modern" rows="2" placeholder="Catatan ekstra..."><?= htmlspecialchars($transaksi['deskripsi'] ?? '') ?></textarea>
            </div>

            <div class="d-flex justify-content-end gap-3 pt-3 border-top" style="border-color: var(--border) !important;">
                <a href="keuangan.php" class="btn-modern btn-secondary-modern">Batal</a>
                <button type="submit" class="btn-modern btn-primary-modern">
                    <i data-lucide="save"></i> Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const katPemasukan = ['Dana Panitia', 'Sponsor', 'Donasi', 'Iuran', 'Lainnya'];
const katPengeluaran = ['Konsumsi', 'Transportasi', 'ATK', 'Dokumentasi', 'Publikasi', 'Honor', 'Perlengkapan', 'Sewa', 'Lainnya'];

function updateKategori() {
    let jenis = 'pemasukan';
    if (document.getElementById('jenisPengeluaran') && document.getElementById('jenisPengeluaran').checked) {
        jenis = 'pengeluaran';
    } else if (document.getElementById('jenis')) {
        // Fallback for hidden select
        jenis = document.getElementById('jenis').value || 'pemasukan';
    }
    
    // Update hidden select if it exists to maintain logic
    let hiddenSelect = document.getElementById('jenis');
    if (hiddenSelect) {
        hiddenSelect.value = jenis;
    }

    const dl = document.getElementById('kategoriList');
    dl.innerHTML = '';
    
    const list = jenis === 'pemasukan' ? katPemasukan : katPengeluaran;
    list.forEach(item => {
        const option = document.createElement('option');
        option.value = item;
        dl.appendChild(option);
    });
}

function formatAngka(input) {
    let value = input.value.replace(/[^,\d]/g, '').toString();
    let split = value.split(',');
    let sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    input.value = rupiah;
}

// Format initially
window.onload = function() {
    updateKategori();
    const nominalInput = document.getElementById('nominal');
    if (nominalInput.value) {
        formatAngka(nominalInput);
    }
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
