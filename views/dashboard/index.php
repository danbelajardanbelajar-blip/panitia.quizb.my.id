<?php include __DIR__ . '/../layouts/header.php'; ?>

<h2 class="mb-4">Dashboard</h2>

<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-calendar-event"></i> Kegiatan Aktif</h6>
                <h3 class="mt-3"><?= $kegiatanAktif ?> / <?= $totalKegiatan ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-file-earmark-spreadsheet"></i> Total RAB</h6>
                <h3 class="mt-3"><?= formatRupiah($totalRab) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-arrow-down-circle"></i> Pemasukan</h6>
                <h3 class="mt-3"><?= formatRupiah($totalPemasukan) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white h-100">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-arrow-up-circle"></i> Pengeluaran</h6>
                <h3 class="mt-3"><?= formatRupiah($totalPengeluaran) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-4">
        <div class="card bg-dark text-white">
            <div class="card-body d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-wallet2"></i> Saldo Saat Ini</h5>
                <h3 class="mb-0"><?= formatRupiah($saldo) ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Aktivitas Terbaru</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php if (empty($recentActivities)): ?>
                        <div class="list-group-item text-center text-muted py-4">Belum ada aktivitas.</div>
                    <?php else: ?>
                        <?php foreach ($recentActivities as $activity): ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">
                                        <?php if ($activity['type'] == 'catatan'): ?>
                                            <i class="bi bi-journal-text text-primary"></i> 
                                        <?php else: ?>
                                            <i class="bi bi-cash text-success"></i> 
                                        <?php endif; ?>
                                        <?= htmlspecialchars($activity['title']) ?>
                                    </h6>
                                    <small class="text-muted"><?= formatTanggalWaktu($activity['date']) ?></small>
                                </div>
                                <small class="text-muted">Kegiatan: <?= htmlspecialchars($activity['nama_kegiatan']) ?></small>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="kegiatan.php?action=create" class="btn btn-outline-primary text-start"><i class="bi bi-plus-circle"></i> Tambah Kegiatan Baru</a>
                    <a href="rab.php" class="btn btn-outline-info text-start"><i class="bi bi-file-earmark-plus"></i> Buat RAB Baru</a>
                    <a href="keuangan.php" class="btn btn-outline-success text-start"><i class="bi bi-cash-coin"></i> Catat Transaksi</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
