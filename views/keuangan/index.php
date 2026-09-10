<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h2 class="mb-1" style="font-weight: 800;">Pencatatan Keuangan</h2>
        <p class="text-muted-modern mb-0">Kelola arus kas, pemasukan, dan pengeluaran kegiatan.</p>
    </div>
    <a href="keuangan.php?action=create" class="btn-modern btn-primary-modern">
        <i data-lucide="plus-circle"></i> Catat Transaksi Baru
    </a>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card-modern" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none;">
            <div class="card-body-modern" style="padding: 24px;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="mb-1" style="color: rgba(255,255,255,0.8); font-size: 0.9rem; font-weight: 500;">Total Pemasukan</p>
                        <h3 class="mb-0 fw-bold font-monospace" style="font-size: 1.75rem;"><?= formatRupiah($rekap['pemasukan']) ?></h3>
                    </div>
                    <div style="background: rgba(255,255,255,0.2); padding: 12px; border-radius: 12px;">
                        <i data-lucide="arrow-down-left" style="width: 24px; height: 24px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-modern" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; border: none;">
            <div class="card-body-modern" style="padding: 24px;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="mb-1" style="color: rgba(255,255,255,0.8); font-size: 0.9rem; font-weight: 500;">Total Pengeluaran</p>
                        <h3 class="mb-0 fw-bold font-monospace" style="font-size: 1.75rem;"><?= formatRupiah($rekap['pengeluaran']) ?></h3>
                    </div>
                    <div style="background: rgba(255,255,255,0.2); padding: 12px; border-radius: 12px;">
                        <i data-lucide="arrow-up-right" style="width: 24px; height: 24px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-modern" style="background: linear-gradient(135deg, var(--slate-800) 0%, var(--slate-900) 100%); color: white; border: none;">
            <div class="card-body-modern" style="padding: 24px;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="mb-1" style="color: rgba(255,255,255,0.7); font-size: 0.9rem; font-weight: 500;">Saldo Kas Saat Ini</p>
                        <h3 class="mb-0 fw-bold font-monospace" style="font-size: 1.75rem; color: #38bdf8;"><?= formatRupiah($saldo) ?></h3>
                    </div>
                    <div style="background: rgba(255,255,255,0.1); padding: 12px; border-radius: 12px;">
                        <i data-lucide="wallet" style="width: 24px; height: 24px; color: #38bdf8;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card-modern">
    <div class="card-body-modern" style="border-bottom: 1px solid var(--border); padding-bottom: 16px;">
        <form method="GET" action="keuangan.php" class="row g-3 align-items-center">
            <div class="col-md-3">
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
                <select name="jenis" class="form-select-modern">
                    <option value="">Semua Tipe</option>
                    <option value="pemasukan" <?= $jenisFilter == 'pemasukan' ? 'selected' : '' ?>>Pemasukan</option>
                    <option value="pengeluaran" <?= $jenisFilter == 'pengeluaran' ? 'selected' : '' ?>>Pengeluaran</option>
                </select>
            </div>
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-color: var(--border);">
                        <i data-lucide="search" style="color: var(--text-muted); width: 18px;"></i>
                    </span>
                    <input type="text" name="search" class="form-control-modern border-start-0 ps-0" placeholder="Cari rincian transaksi..." value="<?= htmlspecialchars($searchFilter ?? '') ?>" style="box-shadow: none;">
                </div>
            </div>
            <div class="col-md-2 text-end">
                <button type="submit" class="btn-modern btn-secondary-modern w-100 justify-content-center">Cari</button>
            </div>
        </form>
    </div>

    <div class="table-responsive-modern">
        <table class="table-modern">
            <thead>
                <tr>
                    <th style="width: 15%">Tanggal</th>
                    <th style="width: 25%">Rincian Transaksi</th>
                    <th style="width: 20%">Kegiatan & Kategori</th>
                    <th style="width: 15%" class="text-end">Pemasukan</th>
                    <th style="width: 15%" class="text-end">Pengeluaran</th>
                    <th style="width: 10%" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transaksiList)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div style="background: var(--background); width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;">
                                <i data-lucide="receipt" style="color: var(--text-muted); width: 32px; height: 32px;"></i>
                            </div>
                            <h6 class="fw-semibold">Belum Ada Transaksi</h6>
                            <p class="text-muted-modern mb-4">Catat arus kas masuk atau keluar untuk mulai memantau keuangan.</p>
                            <a href="keuangan.php?action=create" class="btn-modern btn-primary-modern">
                                <i data-lucide="plus"></i> Catat Transaksi
                            </a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($transaksiList as $item): ?>
                    <tr>
                        <td class="text-muted-modern" style="font-size: 0.9rem;">
                            <div class="d-flex align-items-center gap-2">
                                <i data-lucide="calendar" style="width:14px; color:var(--text-muted);"></i>
                                <?= formatTanggal($item['tanggal']) ?>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-start gap-3">
                                <div style="margin-top: 2px;">
                                    <?php if ($item['jenis'] == 'pemasukan'): ?>
                                        <div style="width:32px; height:32px; border-radius:8px; background:#dcfce7; color:#166534; display:flex; align-items:center; justify-content:center;">
                                            <i data-lucide="arrow-down-left" style="width:18px;"></i>
                                        </div>
                                    <?php else: ?>
                                        <div style="width:32px; height:32px; border-radius:8px; background:#fee2e2; color:#991b1b; display:flex; align-items:center; justify-content:center;">
                                            <i data-lucide="arrow-up-right" style="width:18px;"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <span class="fw-bold d-block" style="color: var(--text-main); font-size: 0.95rem;"><?= htmlspecialchars($item['nama_transaksi']) ?></span>
                                    <?php if($item['sumber_tujuan']): ?>
                                        <span class="text-muted-modern mt-1 d-flex align-items-center gap-1" style="font-size: 0.8rem;">
                                            <i data-lucide="user" style="width:12px;"></i> <?= htmlspecialchars($item['sumber_tujuan']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                <span class="text-main fw-medium" style="font-size: 0.85rem;"><?= htmlspecialchars($item['nama_kegiatan']) ?></span>
                                <div>
                                    <span class="badge-modern badge-gray" style="font-size: 0.75rem; padding: 2px 8px;"><?= htmlspecialchars($item['kategori']) ?></span>
                                </div>
                            </div>
                        </td>
                        <td class="text-end font-monospace">
                            <?php if ($item['jenis'] == 'pemasukan'): ?>
                                <span class="fw-bold" style="color: #16a34a;">+ <?= formatRupiah($item['nominal']) ?></span>
                            <?php else: ?>
                                <span class="text-muted" style="opacity: 0.3;">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end font-monospace">
                            <?php if ($item['jenis'] == 'pengeluaran'): ?>
                                <span class="fw-bold" style="color: #dc2626;">- <?= formatRupiah($item['nominal']) ?></span>
                            <?php else: ?>
                                <span class="text-muted" style="opacity: 0.3;">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="keuangan.php?action=edit&id=<?= $item['id'] ?>" class="btn-ghost-modern d-inline-flex align-items-center justify-content-center" title="Edit Transaksi">
                                    <i data-lucide="pencil" style="width:16px;"></i>
                                </a>
                                <form action="keuangan.php?action=delete" method="POST" class="d-inline" onsubmit="return confirm('Hapus riwayat transaksi ini secara permanen?');">
                                    <?= csrfField() ?>
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="btn-ghost-modern btn-ghost-danger d-inline-flex align-items-center justify-content-center border-0" title="Hapus">
                                        <i data-lucide="trash-2" style="width:16px;"></i>
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
