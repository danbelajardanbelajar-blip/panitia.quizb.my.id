<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Daftar Kegiatan</h2>
    <a href="kegiatan.php?action=create" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Kegiatan</a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="kegiatan.php" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari nama kegiatan..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="Draft" <?= $status == 'Draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="Persiapan" <?= $status == 'Persiapan' ? 'selected' : '' ?>>Persiapan</option>
                    <option value="Berlangsung" <?= $status == 'Berlangsung' ? 'selected' : '' ?>>Berlangsung</option>
                    <option value="Selesai" <?= $status == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                    <option value="Diarsipkan" <?= $status == 'Diarsipkan' ? 'selected' : '' ?>>Diarsipkan</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-search"></i> Cari</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Kegiatan</th>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($kegiatanList)): ?>
                        <tr><td colspan="6" class="text-center text-muted py-3">Belum ada kegiatan.</td></tr>
                    <?php else: ?>
                        <?php foreach ($kegiatanList as $index => $item): ?>
                        <tr>
                            <td><?= $offset + $index + 1 ?></td>
                            <td>
                                <strong><?= htmlspecialchars($item['nama_kegiatan']) ?></strong><br>
                                <small class="text-muted"><?= htmlspecialchars($item['tema'] ?? '-') ?></small>
                            </td>
                            <td><?= formatTanggal($item['tanggal_mulai']) ?> s.d <?= formatTanggal($item['tanggal_selesai']) ?></td>
                            <td><?= htmlspecialchars($item['lokasi'] ?? '-') ?></td>
                            <td>
                                <?php
                                $badgeClass = 'bg-secondary';
                                if ($item['status'] == 'Persiapan') $badgeClass = 'bg-warning text-dark';
                                elseif ($item['status'] == 'Berlangsung') $badgeClass = 'bg-primary';
                                elseif ($item['status'] == 'Selesai') $badgeClass = 'bg-success';
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= $item['status'] ?></span>
                            </td>
                            <td>
                                <a href="kegiatan.php?action=edit&id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form action="kegiatan.php?action=delete" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini beserta seluruh data RAB dan Keuangannya?');">
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

        <?php if ($totalPages > 1): ?>
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>">Sebelumnya</a>
                </li>
                <?php for($i=1; $i<=$totalPages; $i++): ?>
                    <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>">Selanjutnya</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
