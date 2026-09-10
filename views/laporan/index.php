<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h2 class="mb-1" style="font-weight: 800;">Laporan Kegiatan</h2>
        <p class="text-muted-modern mb-0">Pilih kegiatan untuk melihat rekapitulasi lengkap, RAB, keuangan, dan catatan.</p>
    </div>
</div>

<div class="card-modern">
    <div class="card-body-modern p-0">
        <?php if (empty($kegiatanList)): ?>
            <div class="text-center py-5">
                <div style="background: var(--background); width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;">
                    <i data-lucide="folder-open" style="color: var(--text-muted); width: 32px; height: 32px;"></i>
                </div>
                <h6 class="fw-semibold">Belum Ada Kegiatan</h6>
                <p class="text-muted-modern mb-4">Buat kegiatan terlebih dahulu untuk melihat laporannya di sini.</p>
                <a href="kegiatan.php?action=create" class="btn-modern btn-primary-modern">
                    <i data-lucide="plus"></i> Buat Kegiatan Baru
                </a>
            </div>
        <?php else: ?>
            <div class="list-group list-group-flush">
                <?php foreach ($kegiatanList as $kegiatan): ?>
                    <a href="laporan.php?action=detail&id=<?= $kegiatan['id'] ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" style="padding: 24px; border-bottom: 1px solid var(--border);">
                        <div class="d-flex align-items-center gap-4">
                            <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--background); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                                <i data-lucide="folder-kanban" style="width: 24px;"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold" style="color: var(--text-main); font-size: 1.1rem;"><?= htmlspecialchars($kegiatan['nama_kegiatan']) ?></h5>
                                <div class="d-flex align-items-center gap-3 text-muted-modern" style="font-size: 0.85rem;">
                                    <span class="d-flex align-items-center gap-1"><i data-lucide="calendar" style="width: 14px;"></i> <?= formatTanggal($kegiatan['tanggal_mulai']) ?> s.d <?= formatTanggal($kegiatan['tanggal_selesai']) ?></span>
                                    <span class="d-flex align-items-center gap-1"><i data-lucide="activity" style="width: 14px;"></i> Status: <?= $kegiatan['status'] ?></span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <span class="btn-ghost-modern" style="color: var(--primary);">
                                Lihat Laporan <i data-lucide="chevron-right" style="width:18px;"></i>
                            </span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
