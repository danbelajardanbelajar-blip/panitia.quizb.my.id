<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Pencatatan Keuangan</h2>
    <a href="keuangan.php?action=create" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Catat Transaksi</a>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6><i class="bi bi-arrow-down-circle"></i> Total Pemasukan</h6>
                <h3><?= formatRupiah($rekap['pemasukan']) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h6><i class="bi bi-arrow-up-circle"></i> Total Pengeluaran</h6>
                <h3><?= formatRupiah($rekap['pengeluaran']) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-dark text-white">
            <div class="card-body">
                <h6><i class="bi bi-wallet2"></i> Saldo Akhir</h6>
                <h3><?= formatRupiah($saldo) ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="keuangan.php" class="row g-3 mb-4">
            <div class="col-md-3">
                <select name="kegiatan_id" class="form-select">
                    <option value="">Semua Kegiatan</option>
                    <?php foreach ($kegiatanList as $kegiatan): ?>
                        <option value="<?= $kegiatan['id'] ?>" <?= $kegiatanIdFilter == $kegiatan['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($kegiatan['nama_kegiatan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select name="jenis" class="form-select">
                    <option value="">Semua Jenis</option>
                    <option value="pemasukan" <?= $jenisFilter == 'pemasukan' ? 'selected' : '' ?>>Pemasukan</option>
                    <option value="pengeluaran" <?= $jenisFilter == 'pengeluaran' ? 'selected' : '' ?>>Pengeluaran</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari transaksi / kategori..." value="<?= htmlspecialchars($searchFilter ?? '') ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-search"></i> Cari</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Nama Transaksi</th>
                        <th>Kegiatan</th>
                        <th>Pemasukan</th>
                        <th>Pengeluaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transaksiList)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-3">Belum ada transaksi.</td></tr>
                    <?php else: ?>
                        <?php foreach ($transaksiList as $item): ?>
                        <tr>
                            <td><?= formatTanggal($item['tanggal']) ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($item['kategori']) ?></span></td>
                            <td>
                                <strong><?= htmlspecialchars($item['nama_transaksi']) ?></strong>
                                <?php if($item['sumber_tujuan']): ?>
                                    <br><small class="text-muted"><i class="bi bi-person"></i> <?= htmlspecialchars($item['sumber_tujuan']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($item['nama_kegiatan']) ?></td>
                            <?php if ($item['jenis'] == 'pemasukan'): ?>
                                <td class="text-success fw-bold"><?= formatRupiah($item['nominal']) ?></td>
                                <td>-</td>
                            <?php else: ?>
                                <td>-</td>
                                <td class="text-danger fw-bold"><?= formatRupiah($item['nominal']) ?></td>
                            <?php endif; ?>
                            <td>
                                <a href="keuangan.php?action=edit&id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form action="keuangan.php?action=delete" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">
                                    <?= csrfField() ?>
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
