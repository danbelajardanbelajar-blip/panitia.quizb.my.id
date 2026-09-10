<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
    <div>
        <h2>Laporan: <?= htmlspecialchars($kegiatan['nama_kegiatan']) ?></h2>
    </div>
    <div>
        <button onclick="window.print()" class="btn btn-secondary"><i class="bi bi-printer"></i> Print</button>
        <a href="export_laporan.php?id=<?= $kegiatan['id'] ?>" class="btn btn-success"><i class="bi bi-file-word"></i> Export DOCX</a>
        <a href="laporan.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="card shadow-sm mb-4" id="printableArea">
    <div class="card-body p-5">
        <div class="text-center mb-5">
            <h3 class="text-uppercase fw-bold">LAPORAN PELAKSANAAN KEGIATAN</h3>
            <h4 class="text-uppercase fw-bold"><?= htmlspecialchars($kegiatan['nama_kegiatan']) ?></h4>
        </div>

        <h5 class="fw-bold border-bottom pb-2">A. Identitas Kegiatan</h5>
        <table class="table table-borderless table-sm mb-4">
            <tr><th width="200">Nama Kegiatan</th><td>: <?= htmlspecialchars($kegiatan['nama_kegiatan']) ?></td></tr>
            <tr><th>Tema</th><td>: <?= htmlspecialchars($kegiatan['tema'] ?? '-') ?></td></tr>
            <tr><th>Tanggal Pelaksanaan</th><td>: <?= formatTanggal($kegiatan['tanggal_mulai']) ?> s.d <?= formatTanggal($kegiatan['tanggal_selesai']) ?></td></tr>
            <tr><th>Lokasi</th><td>: <?= htmlspecialchars($kegiatan['lokasi'] ?? '-') ?></td></tr>
            <tr><th>Penanggung Jawab</th><td>: <?= htmlspecialchars($kegiatan['penanggung_jawab'] ?? '-') ?></td></tr>
        </table>

        <h5 class="fw-bold border-bottom pb-2 mt-4">B. Deskripsi & Hasil</h5>
        <p style="white-space: pre-wrap;" class="mb-4"><?= htmlspecialchars($kegiatan['deskripsi'] ?? 'Tidak ada deskripsi.') ?></p>
        <?php if($kegiatan['catatan']): ?>
            <p style="white-space: pre-wrap;" class="mb-4"><strong>Catatan Akhir:</strong><br><?= htmlspecialchars($kegiatan['catatan']) ?></p>
        <?php endif; ?>

        <h5 class="fw-bold border-bottom pb-2 mt-4">C. Rekapitulasi RAB & Realisasi</h5>
        <table class="table table-bordered mb-4">
            <thead class="table-light">
                <tr>
                    <th>Komponen</th>
                    <th class="text-end">Nominal</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>Total Rencana Anggaran Biaya (RAB)</td><td class="text-end fw-bold"><?= formatRupiah($totalRab) ?></td></tr>
                <tr><td>Total Pemasukan (Dana Tersedia)</td><td class="text-end text-success"><?= formatRupiah($totalPemasukan) ?></td></tr>
                <tr><td>Total Pengeluaran (Realisasi)</td><td class="text-end text-danger"><?= formatRupiah($totalPengeluaran) ?></td></tr>
                <tr class="table-active"><td><strong>Saldo Akhir</strong></td><td class="text-end fw-bold"><?= formatRupiah($saldo) ?></td></tr>
                <tr><td>Sisa Anggaran dari RAB</td><td class="text-end"><?= formatRupiah($sisaAnggaran) ?></td></tr>
                <tr><td>Persentase Realisasi terhadap RAB</td><td class="text-end"><?= number_format($persentaseRealisasi, 2, ',', '.') ?>%</td></tr>
            </tbody>
        </table>

        <h5 class="fw-bold border-bottom pb-2 mt-4">D. Catatan Kegiatan Penting</h5>
        <?php if (empty($catatanList)): ?>
            <p class="text-muted">Tidak ada catatan kegiatan.</p>
        <?php else: ?>
            <ul class="list-unstyled mb-4">
            <?php foreach($catatanList as $c): ?>
                <li class="mb-2">
                    <strong><?= formatTanggal($c['tanggal']) ?>: <?= htmlspecialchars($c['judul']) ?></strong><br>
                    <?= htmlspecialchars($c['isi']) ?>
                </li>
            <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <div class="row mt-5 pt-5 text-center">
            <div class="col-6">
                <p>Mengetahui,<br>Ketua Panitia</p>
                <br><br><br>
                <p><strong>( <?= htmlspecialchars($kegiatan['penanggung_jawab'] ?: '........................') ?> )</strong></p>
            </div>
            <div class="col-6">
                <p>Disetujui,<br>Bendahara</p>
                <br><br><br>
                <p><strong>( ........................ )</strong></p>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body { background-color: #fff; }
    .sidebar, .top-nav, .btn { display: none !important; }
    .main-content { margin: 0 !important; padding: 0 !important; }
    .card { border: none !important; box-shadow: none !important; }
}
</style>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
