<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1" style="font-weight: 800;">Selamat datang, <?= htmlspecialchars(explode(' ', getCurrentUser()['name'])[0]) ?> 👋</h2>
        <p class="text-muted-modern mb-0">Berikut adalah ringkasan administrasi kepanitiaan Anda hari ini.</p>
    </div>
    <div class="d-none d-sm-block text-end">
        <p class="mb-0 fw-bold"><?= formatTanggal(date('Y-m-d')) ?></p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="card-modern h-100 border-0" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%); color: white;">
            <div class="card-body-modern">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="mb-1 opacity-75" style="font-size: 0.9rem;">Kegiatan Aktif</p>
                        <h3 class="mb-0 fw-bold"><?= $kegiatanAktif ?> <span class="fs-6 opacity-75 fw-normal">/ <?= $totalKegiatan ?> total</span></h3>
                    </div>
                    <div style="background: rgba(255,255,255,0.2); padding: 10px; border-radius: 12px;">
                        <i data-lucide="calendar-check" style="color: white;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card-modern h-100">
            <div class="card-body-modern">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="mb-1 text-muted-modern" style="font-size: 0.9rem;">Total Anggaran (RAB)</p>
                        <h4 class="mb-0 fw-bold text-main"><?= formatRupiah($totalRab) ?></h4>
                    </div>
                    <div style="background: var(--info-bg); padding: 10px; border-radius: 12px;">
                        <i data-lucide="calculator" style="color: var(--info);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card-modern h-100">
            <div class="card-body-modern">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="mb-1 text-muted-modern" style="font-size: 0.9rem;">Total Pemasukan</p>
                        <h4 class="mb-0 fw-bold text-success"><?= formatRupiah($totalPemasukan) ?></h4>
                    </div>
                    <div style="background: var(--success-bg); padding: 10px; border-radius: 12px;">
                        <i data-lucide="arrow-down-to-line" style="color: var(--success);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card-modern h-100">
            <div class="card-body-modern">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="mb-1 text-muted-modern" style="font-size: 0.9rem;">Total Pengeluaran</p>
                        <h4 class="mb-0 fw-bold text-danger"><?= formatRupiah($totalPengeluaran) ?></h4>
                    </div>
                    <div style="background: var(--danger-bg); padding: 10px; border-radius: 12px;">
                        <i data-lucide="arrow-up-from-line" style="color: var(--danger);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card-modern mb-5" style="border: 2px solid var(--primary); background-color: #f8faff;">
    <div class="card-body-modern d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div style="background: var(--primary); padding: 12px; border-radius: 12px;">
                <i data-lucide="wallet" style="color: white; width:24px; height:24px;"></i>
            </div>
            <div>
                <p class="mb-0 text-muted-modern fw-semibold">Saldo Kas Saat Ini</p>
                <h2 class="mb-0 fw-bold text-main" style="letter-spacing: -1px;"><?= formatRupiah($saldo) ?></h2>
            </div>
        </div>
        <div>
            <a href="keuangan.php" class="btn-modern btn-primary-modern">
                <i data-lucide="plus"></i> Catat Transaksi
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-modern h-100">
            <div class="card-header-modern">
                Aktivitas Terbaru
                <a href="catatan.php" class="btn-ghost-modern text-decoration-none" style="font-size:0.85rem;"><i data-lucide="arrow-right" style="width:16px;"></i> Lihat Semua</a>
            </div>
            <div class="card-body-modern">
                <?php if (empty($recentActivities)): ?>
                    <div class="text-center py-5">
                        <div style="background: var(--background); width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;">
                            <i data-lucide="activity" style="color: var(--text-muted); width: 32px; height: 32px;"></i>
                        </div>
                        <h6 class="fw-semibold">Belum ada aktivitas</h6>
                        <p class="text-muted-modern mb-0">Aktivitas dari catatan dan keuangan akan muncul di sini.</p>
                    </div>
                <?php else: ?>
                    <div class="activity-timeline">
                        <?php foreach ($recentActivities as $activity): ?>
                            <div class="d-flex gap-3 mb-4 position-relative">
                                <div style="position: absolute; left: 19px; top: 40px; bottom: -24px; width: 2px; background-color: var(--border); z-index: 1;"></div>
                                <div style="position: relative; z-index: 2; background: <?= $activity['type'] == 'catatan' ? 'var(--info-bg)' : 'var(--success-bg)' ?>; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 0 0 4px var(--surface);">
                                    <?php if ($activity['type'] == 'catatan'): ?>
                                        <i data-lucide="notebook-pen" style="color: var(--info); width: 20px;"></i> 
                                    <?php else: ?>
                                        <i data-lucide="banknote" style="color: var(--success); width: 20px;"></i> 
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold"><?= htmlspecialchars($activity['title']) ?></h6>
                                    <p class="mb-1 text-muted-modern" style="font-size: 0.9rem;">
                                        Pada kegiatan <a href="kegiatan.php" class="text-decoration-none fw-semibold"><?= htmlspecialchars($activity['nama_kegiatan']) ?></a>
                                    </p>
                                    <small class="text-muted-modern fw-medium"><?= formatTanggalWaktu($activity['date']) ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <style>
                        .activity-timeline > div:last-child > div:first-child { display: none; }
                    </style>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card-modern h-100">
            <div class="card-header-modern">
                Aksi Cepat
            </div>
            <div class="card-body-modern">
                <div class="d-flex flex-column gap-3">
                    <a href="kegiatan.php?action=create" class="btn-modern btn-secondary-modern justify-content-start py-3">
                        <div style="background: var(--info-bg); padding: 8px; border-radius: 8px; margin-right: 8px;">
                            <i data-lucide="calendar-plus" style="color: var(--info);"></i>
                        </div>
                        <div class="text-start">
                            <div class="fw-bold">Buat Kegiatan</div>
                            <small class="text-muted-modern fw-normal">Rencanakan agenda baru</small>
                        </div>
                    </a>
                    
                    <a href="rab.php" class="btn-modern btn-secondary-modern justify-content-start py-3">
                        <div style="background: var(--warning-bg); padding: 8px; border-radius: 8px; margin-right: 8px;">
                            <i data-lucide="file-spreadsheet" style="color: var(--warning);"></i>
                        </div>
                        <div class="text-start">
                            <div class="fw-bold">Susun RAB</div>
                            <small class="text-muted-modern fw-normal">Rencanakan anggaran biaya</small>
                        </div>
                    </a>
                    
                    <a href="laporan.php" class="btn-modern btn-secondary-modern justify-content-start py-3">
                        <div style="background: var(--success-bg); padding: 8px; border-radius: 8px; margin-right: 8px;">
                            <i data-lucide="printer" style="color: var(--success);"></i>
                        </div>
                        <div class="text-start">
                            <div class="fw-bold">Export Laporan</div>
                            <small class="text-muted-modern fw-normal">Unduh laporan ke DOCX</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
