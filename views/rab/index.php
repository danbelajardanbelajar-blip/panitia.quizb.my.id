<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Daftar Rencana Anggaran Biaya (RAB)</h2>
    <a href="rab.php?action=create" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah RAB</a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="rab.php" class="row g-3 mb-4">
            <div class="col-md-5">
                <select name="kegiatan_id" class="form-select">
                    <option value="">Semua Kegiatan</option>
                    <?php foreach ($kegiatanList as $kegiatan): ?>
                        <option value="<?= $kegiatan['id'] ?>" <?= $kegiatanIdFilter == $kegiatan['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($kegiatan['nama_kegiatan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-filter"></i> Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Kegiatan</th>
                        <th>RAB / Dokumen</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($rabList)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-3">Belum ada RAB.</td></tr>
                    <?php else: ?>
                        <?php foreach ($rabList as $index => $item): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($item['nama_kegiatan']) ?></td>
                            <td>
                                <strong><?= htmlspecialchars($item['nama_rab']) ?></strong><br>
                                <small class="text-muted"><?= htmlspecialchars($item['nomor_dokumen'] ?? '-') ?></small>
                            </td>
                            <td><?= formatTanggal($item['tanggal']) ?></td>
                            <td><strong><?= formatRupiah($item['total_rab']) ?></strong></td>
                            <td>
                                <?php
                                $badgeClass = 'bg-secondary';
                                if ($item['status'] == 'Diajukan') $badgeClass = 'bg-info text-dark';
                                elseif ($item['status'] == 'Disetujui') $badgeClass = 'bg-success';
                                elseif ($item['status'] == 'Ditolak') $badgeClass = 'bg-danger';
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= $item['status'] ?></span>
                            </td>
                            <td>
                                <a href="rab.php?action=items&id=<?= $item['id'] ?>" class="btn btn-sm btn-info text-white" title="Detail Item"><i class="bi bi-list-check"></i> Detail</a>
                                <a href="rab.php?action=edit&id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form action="rab.php?action=delete" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus RAB ini?');">
                                    <?= csrfField() ?>
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                                <a href="export_rab.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-success" title="Download DOCX"><i class="bi bi-file-word"></i></a>
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
