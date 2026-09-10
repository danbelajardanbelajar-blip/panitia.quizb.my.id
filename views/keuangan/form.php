<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><?= $transaksi ? 'Edit' : 'Tambah' ?> Transaksi</h2>
    <a href="keuangan.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="POST" action="keuangan.php?action=<?= $transaksi ? 'edit&id='.$transaksi['id'] : 'create' ?>">
            <?= csrfField() ?>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Kegiatan <span class="text-danger">*</span></label>
                    <select name="kegiatan_id" class="form-select" required>
                        <option value="">-- Pilih Kegiatan --</option>
                        <?php foreach ($kegiatanList as $k): ?>
                            <option value="<?= $k['id'] ?>" <?= ($transaksi['kegiatan_id'] ?? '') == $k['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($k['nama_kegiatan']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Transaksi <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control" required value="<?= htmlspecialchars($transaksi['tanggal'] ?? date('Y-m-d')) ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Jenis Transaksi <span class="text-danger">*</span></label>
                    <select name="jenis" id="jenis" class="form-select" required onchange="updateKategori()">
                        <option value="pemasukan" <?= ($transaksi['jenis'] ?? '') == 'pemasukan' ? 'selected' : '' ?>>Pemasukan</option>
                        <option value="pengeluaran" <?= ($transaksi['jenis'] ?? '') == 'pengeluaran' ? 'selected' : '' ?>>Pengeluaran</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="kategori" id="kategori" class="form-control" required list="kategoriList" value="<?= htmlspecialchars($transaksi['kategori'] ?? '') ?>">
                    <datalist id="kategoriList">
                        <!-- Populated by JS -->
                    </datalist>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Transaksi <span class="text-danger">*</span></label>
                <input type="text" name="nama_transaksi" class="form-control" required value="<?= htmlspecialchars($transaksi['nama_transaksi'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Nominal (Rp) <span class="text-danger">*</span></label>
                <input type="text" name="nominal" id="nominal" class="form-control fw-bold" required value="<?= htmlspecialchars($transaksi['nominal'] ?? '') ?>" onkeyup="formatAngka(this)">
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                    <select name="metode_pembayaran" class="form-select" required>
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
                <div class="col-md-4">
                    <label class="form-label">Nomor Bukti / Referensi</label>
                    <input type="text" name="nomor_bukti" class="form-control" value="<?= htmlspecialchars($transaksi['nomor_bukti'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sumber / Dibayarkan Kepada</label>
                    <input type="text" name="sumber_tujuan" class="form-control" value="<?= htmlspecialchars($transaksi['sumber_tujuan'] ?? '') ?>">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Deskripsi Tambahan</label>
                <textarea name="deskripsi" class="form-control" rows="2"><?= htmlspecialchars($transaksi['deskripsi'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Transaksi</button>
        </form>
    </div>
</div>

<script>
const katPemasukan = ['Dana Panitia', 'Sponsor', 'Donasi', 'Iuran', 'Lainnya'];
const katPengeluaran = ['Konsumsi', 'Transportasi', 'ATK', 'Dokumentasi', 'Publikasi', 'Honor', 'Perlengkapan', 'Sewa', 'Lainnya'];

function updateKategori() {
    const jenis = document.getElementById('jenis').value;
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
