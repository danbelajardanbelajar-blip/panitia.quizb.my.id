<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Laporan Kegiatan</h2>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <p class="text-muted">Pilih kegiatan untuk melihat laporan lengkap (identitas, deskripsi, RAB, keuangan, dan catatan kegiatan).</p>
        
        <div class="list-group">
            <?php if (empty($kegiatanList)): ?>
                <div class="text-center text-muted py-3">Belum ada kegiatan.</div>
            <?php else: ?>
                <?php foreach ($kegiatanList as $kegiatan): ?>
                    <a href="laporan.php?action=detail&id=<?= $kegiatan['id'] ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1"><?= htmlspecialchars($kegiatan['nama_kegiatan']) ?></h5>
                            <small class="text-muted"><?= formatTanggal($kegiatan['tanggal_mulai']) ?> s.d <?= formatTanggal($kegiatan['tanggal_selesai']) ?></small>
                        </div>
                        <div>
                            <span class="badge bg-primary rounded-pill"><i class="bi bi-eye"></i> Lihat Laporan</span>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
