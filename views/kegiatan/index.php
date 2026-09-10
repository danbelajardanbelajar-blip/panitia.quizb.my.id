<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h2 class="mb-1" style="font-weight: 800;">Daftar Kegiatan</h2>
        <p class="text-muted-modern mb-0">Kelola semua jadwal dan informasi kegiatan Anda.</p>
    </div>
    <a href="kegiatan.php?action=create" class="btn-modern btn-primary-modern">
        <i data-lucide="plus-circle"></i> Tambah Kegiatan
    </a>
</div>

<div class="card-modern">
    <div class="card-body-modern" style="border-bottom: 1px solid var(--border); padding-bottom: 16px;">
        <form method="GET" action="kegiatan.php" class="row g-3 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-color: var(--border);">
                        <i data-lucide="search" style="color: var(--text-muted); width: 18px;"></i>
                    </span>
                    <input type="text" name="search" class="form-control-modern border-start-0 ps-0" placeholder="Cari nama kegiatan..." value="<?= htmlspecialchars($search) ?>" style="box-shadow: none;">
                </div>
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select-modern">
                    <option value="">Semua Status</option>
                    <option value="Draft" <?= $status == 'Draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="Persiapan" <?= $status == 'Persiapan' ? 'selected' : '' ?>>Persiapan</option>
                    <option value="Berlangsung" <?= $status == 'Berlangsung' ? 'selected' : '' ?>>Berlangsung</option>
                    <option value="Selesai" <?= $status == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                    <option value="Diarsipkan" <?= $status == 'Diarsipkan' ? 'selected' : '' ?>>Diarsipkan</option>
                </select>
            </div>
            <div class="col-md-3 text-end">
                <button type="submit" class="btn-modern btn-secondary-modern w-100 justify-content-center">Terapkan Filter</button>
            </div>
        </form>
    </div>

    <div class="table-responsive-modern">
        <table class="table-modern">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width: 35%">Info Kegiatan</th>
                    <th style="width: 25%">Jadwal & Lokasi</th>
                    <th style="width: 20%">Status</th>
                    <th style="width: 15%" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($kegiatanList)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div style="background: var(--background); width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;">
                                <i data-lucide="calendar-x" style="color: var(--text-muted); width: 32px; height: 32px;"></i>
                            </div>
                            <h6 class="fw-semibold">Data tidak ditemukan</h6>
                            <p class="text-muted-modern mb-4">Belum ada kegiatan yang ditambahkan atau cocok dengan filter.</p>
                            <a href="kegiatan.php?action=create" class="btn-modern btn-primary-modern">
                                <i data-lucide="plus"></i> Buat Kegiatan
                            </a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($kegiatanList as $index => $item): ?>
                    <tr>
                        <td class="text-muted-modern fw-semibold"><?= $offset + $index + 1 ?></td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold" style="font-size: 1rem; color: var(--text-main);"><?= htmlspecialchars($item['nama_kegiatan']) ?></span>
                                <span class="text-muted-modern mt-1" style="font-size: 0.85rem;"><i data-lucide="tag" style="width: 12px; height:12px; display:inline-block; margin-right:4px;"></i> <?= htmlspecialchars($item['tema'] ?? '-') ?></span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1 text-muted-modern" style="font-size: 0.85rem;">
                                <span><i data-lucide="calendar" style="width: 14px; display:inline-block; margin-right:6px; color:var(--text-main);"></i> <?= formatTanggal($item['tanggal_mulai']) ?></span>
                                <span><i data-lucide="map-pin" style="width: 14px; display:inline-block; margin-right:6px; color:var(--text-main);"></i> <?= htmlspecialchars($item['lokasi'] ?? '-') ?></span>
                            </div>
                        </td>
                        <td>
                            <?php
                            $badgeClass = 'badge-gray';
                            $icon = 'circle-dashed';
                            if ($item['status'] == 'Draft') { $badgeClass = 'badge-gray'; $icon = 'pencil'; }
                            elseif ($item['status'] == 'Persiapan') { $badgeClass = 'badge-warning'; $icon = 'clock'; }
                            elseif ($item['status'] == 'Berlangsung') { $badgeClass = 'badge-info'; $icon = 'play-circle'; }
                            elseif ($item['status'] == 'Selesai') { $badgeClass = 'badge-success'; $icon = 'check-circle-2'; }
                            ?>
                            <span class="badge-modern <?= $badgeClass ?>">
                                <i data-lucide="<?= $icon ?>"></i> <?= $item['status'] ?>
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="kegiatan.php?action=edit&id=<?= $item['id'] ?>" class="btn-ghost-modern d-inline-flex align-items-center justify-content-center" title="Edit">
                                <i data-lucide="pencil" style="width:18px;"></i>
                            </a>
                            <form action="kegiatan.php?action=delete" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini beserta seluruh data RAB dan Keuangannya?');">
                                <?= csrfField() ?>
                                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                <button type="submit" class="btn-ghost-modern btn-ghost-danger d-inline-flex align-items-center justify-content-center border-0" title="Hapus">
                                    <i data-lucide="trash-2" style="width:18px;"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if ($totalPages > 1): ?>
    <div class="card-body-modern border-top" style="border-color: var(--border);">
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-end mb-0">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link shadow-sm text-dark border-0 rounded-start-2 px-3" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>">Sebelumnya</a>
                </li>
                <?php for($i=1; $i<=$totalPages; $i++): ?>
                    <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                        <a class="page-link shadow-sm border-0 <?= $page == $i ? 'bg-primary text-white' : 'text-dark' ?>" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link shadow-sm text-dark border-0 rounded-end-2 px-3" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>">Selanjutnya</a>
                </li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
