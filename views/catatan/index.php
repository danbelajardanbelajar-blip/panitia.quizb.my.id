<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Catatan Kegiatan (Timeline)</h2>
    <a href="catatan.php?action=create" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Catatan</a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="catatan.php" class="row g-3 mb-4">
            <div class="col-md-3">
                <select name="kegiatan_id" class="form-select">
                    <option value="">Semua Kegiatan</option>
                    <?php foreach ($kegiatanList as $kegiatan): ?>
                        <option value="<?= $kegiatan['id'] ?>" <?= $kegiatanIdFilter == $kegiatan['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($kegiatan['nama_kegiatan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select name="kategori" class="form-select">
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
                <input type="text" name="search" class="form-control" placeholder="Cari judul/isi..." value="<?= htmlspecialchars($searchFilter ?? '') ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-search"></i> Cari</button>
            </div>
        </form>

        <div class="timeline mt-4">
            <?php if (empty($catatanList)): ?>
                <div class="text-center text-muted py-5">Belum ada catatan kegiatan.</div>
            <?php else: ?>
                <div class="list-group">
                    <?php foreach ($catatanList as $item): ?>
                        <div class="list-group-item list-group-item-action flex-column align-items-start p-4">
                            <div class="d-flex w-100 justify-content-between mb-2">
                                <h5 class="mb-1 text-primary"><?= htmlspecialchars($item['judul']) ?></h5>
                                <small class="text-muted fw-bold">
                                    <i class="bi bi-clock"></i> <?= formatTanggal($item['tanggal']) ?> | <?= date('H:i', strtotime($item['waktu'])) ?>
                                </small>
                            </div>
                            <p class="mb-2 text-muted"><i class="bi bi-calendar-event"></i> <?= htmlspecialchars($item['nama_kegiatan']) ?></p>
                            <p class="mb-3" style="white-space: pre-wrap;"><?= htmlspecialchars($item['isi']) ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-secondary me-2"><?= htmlspecialchars($item['kategori']) ?></span>
                                    <?php if($item['status']): ?>
                                        <span class="badge bg-info text-dark me-2">Status: <?= htmlspecialchars($item['status']) ?></span>
                                    <?php endif; ?>
                                    <?php if($item['penanggung_jawab']): ?>
                                        <small class="text-muted"><i class="bi bi-person"></i> <?= htmlspecialchars($item['penanggung_jawab']) ?></small>
                                    <?php endif; ?>
                                </div>
                                <div class="btn-group">
                                    <a href="catatan.php?action=edit&id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i> Edit</a>
                                    <form action="catatan.php?action=delete" method="POST" class="d-inline" onsubmit="return confirm('Hapus catatan ini?');">
                                        <?= csrfField() ?>
                                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
