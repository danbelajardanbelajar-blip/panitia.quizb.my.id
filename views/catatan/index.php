<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h2 class="mb-1" style="font-weight: 800;">Catatan Kegiatan</h2>
        <p class="text-muted-modern mb-0">Timeline rapat, evaluasi, kendala, dan progres kegiatan panitia.</p>
    </div>
    <a href="catatan.php?action=create" class="btn-modern btn-primary-modern">
        <i data-lucide="plus-circle"></i> Tambah Catatan
    </a>
</div>

<div class="card-modern">
    <div class="card-body-modern" style="border-bottom: 1px solid var(--border); padding-bottom: 16px;">
        <form method="GET" action="catatan.php" class="row g-3 align-items-center">
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
            <div class="col-md-3">
                <select name="kategori" class="form-select-modern">
                    <option value="">Semua Kategori</option>
                    <?php
                    $kategoris = ['Rapat', 'Persiapan', 'Pelaksanaan', 'Evaluasi', 'Kendala', 'Keputusan', 'Lainnya'];
                    foreach ($kategoris as $k) {
                        $selected = $kategoriFilter == $k ? 'selected' : '';
                        echo "<option value=\"$k\" $selected>$k</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-color: var(--border);">
                        <i data-lucide="search" style="color: var(--text-muted); width: 18px;"></i>
                    </span>
                    <input type="text" name="search" class="form-control-modern border-start-0 ps-0" placeholder="Cari judul/isi catatan..." value="<?= htmlspecialchars($searchFilter ?? '') ?>" style="box-shadow: none;">
                </div>
            </div>
            <div class="col-md-2 text-end">
                <button type="submit" class="btn-modern btn-secondary-modern w-100 justify-content-center">Cari</button>
            </div>
        </form>
    </div>

    <div class="card-body-modern bg-white">
        <?php if (empty($catatanList)): ?>
            <div class="text-center py-5">
                <div style="background: var(--background); width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;">
                    <i data-lucide="notebook-pen" style="color: var(--text-muted); width: 32px; height: 32px;"></i>
                </div>
                <h6 class="fw-semibold">Belum Ada Catatan</h6>
                <p class="text-muted-modern mb-4">Tambahkan catatan rapat, progres, atau evaluasi untuk memantau berjalannya kegiatan.</p>
                <a href="catatan.php?action=create" class="btn-modern btn-primary-modern">
                    <i data-lucide="plus"></i> Buat Catatan
                </a>
            </div>
        <?php else: ?>
            <div class="timeline-modern mt-3" style="position: relative; padding-left: 24px;">
                <!-- Vertical Line -->
                <div style="position: absolute; left: 32px; top: 0; bottom: 0; width: 2px; background: var(--border); z-index: 1;"></div>
                
                <?php foreach ($catatanList as $item): ?>
                    <div class="timeline-item position-relative mb-4" style="padding-left: 32px; z-index: 2;">
                        <!-- Timeline Dot -->
                        <div style="position: absolute; left: 1px; top: 2px; width: 14px; height: 14px; border-radius: 50%; background: white; border: 2px solid var(--primary); z-index: 3;"></div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="fw-bold mb-0" style="color: var(--text-main); font-size: 1.1rem;"><?= htmlspecialchars($item['judul']) ?></h5>
                            <span class="text-muted-modern d-flex align-items-center gap-1 font-monospace" style="font-size: 0.85rem;">
                                <i data-lucide="clock" style="width: 14px;"></i> <?= formatTanggal($item['tanggal']) ?> &bull; <?= date('H:i', strtotime($item['waktu'])) ?>
                            </span>
                        </div>
                        
                        <p class="mb-3 d-inline-block px-2 py-1" style="background: var(--background); border-radius: 6px; font-size: 0.8rem; font-weight: 500; color: var(--text-muted);">
                            <i data-lucide="tag" style="width: 12px; margin-right:4px;"></i> <?= htmlspecialchars($item['nama_kegiatan']) ?>
                        </p>
                        
                        <div class="p-3 mb-3" style="background: #f8fafc; border: 1px solid var(--border); border-radius: 12px; color: var(--text-main); line-height: 1.6;">
                            <?= nl2br(htmlspecialchars($item['isi'])) ?>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="badge-modern badge-gray" style="font-weight: 500;"><i data-lucide="folder" style="width:12px;"></i> <?= htmlspecialchars($item['kategori']) ?></span>
                                <?php if($item['status']): ?>
                                    <span class="badge-modern badge-info"><i data-lucide="activity" style="width:12px;"></i> <?= htmlspecialchars($item['status']) ?></span>
                                <?php endif; ?>
                                <?php if($item['penanggung_jawab']): ?>
                                    <span class="text-muted-modern d-flex align-items-center gap-1 ms-2" style="font-size: 0.85rem;">
                                        <i data-lucide="user" style="width: 14px;"></i> <?= htmlspecialchars($item['penanggung_jawab']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex gap-1">
                                <a href="catatan.php?action=edit&id=<?= $item['id'] ?>" class="btn-ghost-modern d-inline-flex align-items-center gap-1" style="font-size: 0.85rem;">
                                    <i data-lucide="pencil" style="width:16px;"></i> Edit
                                </a>
                                <form action="catatan.php?action=delete" method="POST" class="d-inline" onsubmit="return confirm('Hapus catatan ini secara permanen?');">
                                    <?= csrfField() ?>
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="btn-ghost-modern btn-ghost-danger d-inline-flex align-items-center gap-1 border-0" style="font-size: 0.85rem;">
                                        <i data-lucide="trash-2" style="width:16px;"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
