<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h2 class="mb-1" style="font-weight: 800;">Rencana Anggaran Biaya (RAB)</h2>
        <p class="text-muted-modern mb-0">Kelola dan pantau seluruh anggaran kegiatan Anda.</p>
    </div>
    <a href="rab.php?action=create" class="btn-modern btn-primary-modern">
        <i data-lucide="plus-circle"></i> Buat RAB Baru
    </a>
</div>

<div class="card-modern">
    <div class="card-body-modern" style="border-bottom: 1px solid var(--border); padding-bottom: 16px;">
        <form method="GET" action="rab.php" class="row g-3 align-items-center">
            <div class="col-md-5">
                <select name="kegiatan_id" class="form-select-modern">
                    <option value="">Semua Kegiatan</option>
                    <?php foreach ($kegiatanList as $kegiatan): ?>
                        <option value="<?= $kegiatan['id'] ?>" <?= $kegiatanIdFilter == $kegiatan['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($kegiatan['nama_kegiatan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn-modern btn-secondary-modern w-100 justify-content-center">
                    <i data-lucide="filter"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <div class="table-responsive-modern">
        <table class="table-modern">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width: 25%">Informasi RAB</th>
                    <th style="width: 25%">Kegiatan & Tanggal</th>
                    <th style="width: 15%">Total Anggaran</th>
                    <th style="width: 10%">Status</th>
                    <th style="width: 20%" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rabList)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div style="background: var(--background); width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;">
                                <i data-lucide="file-spreadsheet" style="color: var(--text-muted); width: 32px; height: 32px;"></i>
                            </div>
                            <h6 class="fw-semibold">Belum ada RAB</h6>
                            <p class="text-muted-modern mb-4">Buat Rencana Anggaran Biaya pertama Anda untuk mulai mengelola keuangan kegiatan.</p>
                            <a href="rab.php?action=create" class="btn-modern btn-primary-modern">
                                <i data-lucide="plus"></i> Buat RAB
                            </a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($rabList as $index => $item): ?>
                    <tr>
                        <td class="text-muted-modern fw-semibold"><?= $index + 1 ?></td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold" style="font-size: 1rem; color: var(--text-main);"><?= htmlspecialchars($item['nama_rab']) ?></span>
                                <span class="text-muted-modern mt-1" style="font-size: 0.85rem;"><i data-lucide="hash" style="width: 12px; height:12px; display:inline-block; margin-right:4px;"></i> <?= htmlspecialchars($item['nomor_dokumen'] ?? '-') ?></span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1 text-muted-modern" style="font-size: 0.85rem;">
                                <span><i data-lucide="calendar-days" style="width: 14px; display:inline-block; margin-right:6px; color:var(--primary);"></i> <span class="text-main fw-medium"><?= htmlspecialchars($item['nama_kegiatan']) ?></span></span>
                                <span><i data-lucide="calendar" style="width: 14px; display:inline-block; margin-right:6px;"></i> <?= formatTanggal($item['tanggal']) ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="fw-bold fs-6 text-main"><?= formatRupiah($item['total_rab']) ?></span>
                        </td>
                        <td>
                            <?php
                            $badgeClass = 'badge-gray';
                            $icon = 'file';
                            if ($item['status'] == 'Draft') { $badgeClass = 'badge-gray'; $icon = 'file-edit'; }
                            elseif ($item['status'] == 'Diajukan') { $badgeClass = 'badge-info'; $icon = 'send'; }
                            elseif ($item['status'] == 'Disetujui') { $badgeClass = 'badge-success'; $icon = 'check-circle'; }
                            elseif ($item['status'] == 'Ditolak') { $badgeClass = 'badge-danger'; $icon = 'x-circle'; }
                            ?>
                            <span class="badge-modern <?= $badgeClass ?>">
                                <i data-lucide="<?= $icon ?>"></i> <?= $item['status'] ?>
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="rab.php?action=items&id=<?= $item['id'] ?>" class="btn-ghost-modern d-inline-flex align-items-center justify-content-center" style="color: var(--primary);" title="Kelola Item RAB">
                                    <i data-lucide="list-checks" style="width:18px;"></i>
                                </a>
                                <a href="export_rab.php?id=<?= $item['id'] ?>" class="btn-ghost-modern d-inline-flex align-items-center justify-content-center" style="color: var(--success);" title="Export DOCX">
                                    <i data-lucide="file-down" style="width:18px;"></i>
                                </a>
                                <a href="rab.php?action=edit&id=<?= $item['id'] ?>" class="btn-ghost-modern d-inline-flex align-items-center justify-content-center" title="Edit Info">
                                    <i data-lucide="pencil" style="width:18px;"></i>
                                </a>
                                <form action="rab.php?action=delete" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus RAB ini secara permanen?');">
                                    <?= csrfField() ?>
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="btn-ghost-modern btn-ghost-danger d-inline-flex align-items-center justify-content-center border-0" title="Hapus">
                                        <i data-lucide="trash-2" style="width:18px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
