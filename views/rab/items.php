<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Detail RAB: <?= htmlspecialchars($rab['nama_rab']) ?></h2>
        <p class="text-muted mb-0">Kegiatan: <?= htmlspecialchars($rab['nama_kegiatan']) ?> | Status: <span class="badge bg-secondary"><?= $rab['status'] ?></span></p>
    </div>
    <div>
        <a href="export_rab.php?id=<?= $rab['id'] ?>" class="btn btn-outline-success"><i class="bi bi-file-word"></i> Export DOCX</a>
        <a href="rab.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Item Anggaran</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalItem" onclick="resetForm()">
            <i class="bi bi-plus"></i> Tambah Item
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Kategori</th>
                        <th width="25%">Nama Item</th>
                        <th width="10%">Volume</th>
                        <th width="10%">Satuan</th>
                        <th width="15%">Harga Satuan</th>
                        <th width="15%">Jumlah</th>
                        <th width="5%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $totalRab = 0;
                    if (empty($items)): 
                    ?>
                        <tr><td colspan="8" class="text-center text-muted py-3">Belum ada item ditambahkan.</td></tr>
                    <?php else: ?>
                        <?php foreach ($items as $index => $item): 
                            $jumlah = $item['volume'] * $item['harga_satuan'];
                            $totalRab += $jumlah;
                        ?>
                        <tr>
                            <td class="text-center"><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($item['kategori']) ?></td>
                            <td>
                                <?= htmlspecialchars($item['nama_item']) ?>
                                <?php if($item['deskripsi']): ?>
                                    <br><small class="text-muted"><?= htmlspecialchars($item['deskripsi']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?= $item['volume'] ?></td>
                            <td class="text-center"><?= htmlspecialchars($item['satuan']) ?></td>
                            <td class="text-end"><?= formatRupiah($item['harga_satuan']) ?></td>
                            <td class="text-end fw-bold"><?= formatRupiah($jumlah) ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick="editItem(<?= htmlspecialchars(json_encode($item)) ?>)">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="rab.php?action=items&id=<?= $rab['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus item ini?');">
                                        <?= csrfField() ?>
                                        <input type="hidden" name="item_action" value="delete">
                                        <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th colspan="6" class="text-end fs-5">TOTAL KESELURUHAN</th>
                        <th class="text-end fs-5 text-primary"><?= formatRupiah($totalRab) ?></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Modal Form Item -->
<div class="modal fade" id="modalItem" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="rab.php?action=items&id=<?= $rab['id'] ?>" class="modal-content">
            <?= csrfField() ?>
            <input type="hidden" name="item_action" id="item_action" value="create">
            <input type="hidden" name="item_id" id="item_id" value="">
            
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Item RAB</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="kategori" id="kategori" class="form-control" required list="kategoriList" placeholder="Contoh: Konsumsi">
                    <datalist id="kategoriList">
                        <option value="Konsumsi">
                        <option value="Kesekretariatan">
                        <option value="Transportasi">
                        <option value="Publikasi & Dokumentasi">
                        <option value="Perlengkapan">
                        <option value="Honorarium">
                        <option value="Sewa Tempat">
                    </datalist>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Item <span class="text-danger">*</span></label>
                    <input type="text" name="nama_item" id="nama_item" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi Tambahan</label>
                    <input type="text" name="deskripsi" id="deskripsi" class="form-control">
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label">Volume <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="volume" id="volume" class="form-control" required oninput="calculateTotal()">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Satuan <span class="text-danger">*</span></label>
                        <input type="text" name="satuan" id="satuan" class="form-control" required placeholder="Contoh: Box, Orang">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="harga_satuan" id="harga_satuan" class="form-control" required oninput="calculateTotal()">
                </div>
                <div class="mb-3">
                    <label class="form-label">Subtotal (Perkiraan)</label>
                    <input type="text" id="subtotal_display" class="form-control" readonly value="Rp 0">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Item</button>
            </div>
        </form>
    </div>
</div>

<script>
function calculateTotal() {
    let vol = parseFloat(document.getElementById('volume').value) || 0;
    let hrg = parseFloat(document.getElementById('harga_satuan').value) || 0;
    let total = vol * hrg;
    document.getElementById('subtotal_display').value = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
}

function resetForm() {
    document.getElementById('item_action').value = 'create';
    document.getElementById('item_id').value = '';
    document.getElementById('modalTitle').innerText = 'Tambah Item RAB';
    document.getElementById('kategori').value = '';
    document.getElementById('nama_item').value = '';
    document.getElementById('deskripsi').value = '';
    document.getElementById('volume').value = '';
    document.getElementById('satuan').value = '';
    document.getElementById('harga_satuan').value = '';
    calculateTotal();
}

function editItem(item) {
    document.getElementById('item_action').value = 'edit';
    document.getElementById('item_id').value = item.id;
    document.getElementById('modalTitle').innerText = 'Edit Item RAB';
    
    document.getElementById('kategori').value = item.kategori;
    document.getElementById('nama_item').value = item.nama_item;
    document.getElementById('deskripsi').value = item.deskripsi;
    document.getElementById('volume').value = item.volume;
    document.getElementById('satuan').value = item.satuan;
    document.getElementById('harga_satuan').value = item.harga_satuan;
    
    calculateTotal();
    
    var modal = new bootstrap.Modal(document.getElementById('modalItem'));
    modal.show();
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
