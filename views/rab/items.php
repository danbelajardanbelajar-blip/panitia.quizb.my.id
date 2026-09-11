<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h2 class="mb-1" style="font-weight: 800;"><?= htmlspecialchars($rab['nama_rab']) ?></h2>
        <div class="d-flex align-items-center gap-2 text-muted-modern">
            <span class="badge-modern badge-gray"><i data-lucide="tag"></i> <?= htmlspecialchars($rab['nama_kegiatan']) ?></span>
            <span>&bull;</span>
            <span class="badge-modern <?= $rab['status'] == 'Disetujui' ? 'badge-success' : ($rab['status'] == 'Ditolak' ? 'badge-danger' : 'badge-info') ?>">
                <?= $rab['status'] ?>
            </span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="rab.php" class="btn-modern btn-secondary-modern"><i data-lucide="arrow-left"></i> Kembali</a>
        <a href="export_rab.php?id=<?= $rab['id'] ?>" class="btn-modern btn-primary-modern"><i data-lucide="file-down"></i> Export DOCX</a>
    </div>
</div>

<div class="card-modern">
    <div class="card-header-modern d-flex justify-content-between align-items-center">
        <span>Rincian Item Anggaran</span>
        <button class="btn-modern btn-secondary-modern" style="background: var(--primary); color: white;" data-bs-toggle="modal" data-bs-target="#modalItem" onclick="resetForm()">
            <i data-lucide="plus"></i> Tambah Item
        </button>
    </div>
    <div class="card-body-modern p-0">
        <?php 
        $pemasukan = [];
        $pengeluaran = [];
        $totalPemasukan = 0;
        $totalPengeluaran = 0;
        
        foreach ($items as $it) {
            if (($it['jenis'] ?? 'pengeluaran') === 'pemasukan') {
                $pemasukan[] = $it;
                $totalPemasukan += ($it['volume'] * $it['harga_satuan']);
            } else {
                $pengeluaran[] = $it;
                $totalPengeluaran += ($it['volume'] * $it['harga_satuan']);
            }
        }
        ?>

        <!-- RENCANA PENDAPATAN -->
        <div class="table-responsive-modern">
            <div class="px-4 py-3 bg-light border-bottom fw-bold" style="color: #16a34a;">
                <i data-lucide="arrow-down-left" style="width: 18px; height: 18px;"></i> A. TARGET PENDAPATAN
            </div>
            <table class="table-modern table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 5%; text-align: center;">No</th>
                        <th style="width: 20%;">Kategori</th>
                        <th style="width: 30%;">Uraian Item</th>
                        <th style="width: 10%; text-align: center;">Vol</th>
                        <th style="width: 15%; text-align: right;">Harga Satuan</th>
                        <th style="width: 15%; text-align: right;">Jumlah</th>
                        <th style="width: 5%; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($pemasukan)): ?>
                    <tr><td colspan="7" class="text-center text-muted-modern py-4">Belum ada target pendapatan yang dicatat.</td></tr>
                    <?php else: ?>
                        <?php foreach($pemasukan as $idx => $item): $jml = $item['volume'] * $item['harga_satuan']; ?>
                        <tr>
                            <td class="text-center text-muted-modern fw-semibold"><?= $idx + 1 ?></td>
                            <td><span class="badge-modern badge-gray" style="font-weight: 500; font-size: 0.8rem;"><?= htmlspecialchars($item['kategori']) ?></span></td>
                            <td>
                                <div class="fw-bold" style="color: var(--text-main); font-size: 0.95rem;"><?= htmlspecialchars($item['nama_item']) ?></div>
                                <?php if($item['deskripsi']): ?>
                                    <div class="text-muted-modern mt-1" style="font-size: 0.85rem;"><?= htmlspecialchars($item['deskripsi']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div><?= $item['volume'] ?></div>
                                <small class="text-muted-modern"><?= htmlspecialchars($item['satuan']) ?></small>
                            </td>
                            <td class="text-end font-monospace" style="font-size: 0.9rem;"><?= formatRupiah($item['harga_satuan']) ?></td>
                            <td class="text-end fw-bold font-monospace" style="color: #16a34a;">+<?= formatRupiah($jml) ?></td>
                            <td class="text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    <button type="button" class="btn-ghost-modern d-inline-flex align-items-center justify-content-center border-0 p-1" onclick="editItem(<?= htmlspecialchars(json_encode($item)) ?>)" title="Edit"><i data-lucide="pencil" style="width:16px;"></i></button>
                                    <form action="rab.php?action=items&id=<?= $rab['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus item ini?');">
                                        <?= csrfField() ?>
                                        <input type="hidden" name="item_action" value="delete">
                                        <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                        <button type="submit" class="btn-ghost-modern btn-ghost-danger d-inline-flex align-items-center justify-content-center border-0 p-1" title="Hapus"><i data-lucide="trash-2" style="width:16px;"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot style="background-color: var(--background);">
                    <tr>
                        <th colspan="5" class="text-end" style="font-size: 1.05rem; padding: 12px 24px;">TOTAL PENDAPATAN</th>
                        <th class="text-end fw-bold" style="font-size: 1.15rem; color: #16a34a; padding: 12px 24px;">+<?= formatRupiah($totalPemasukan) ?></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- RENCANA BELANJA -->
        <div class="table-responsive-modern mt-3 border-top">
            <div class="px-4 py-3 bg-light border-bottom fw-bold text-danger">
                <i data-lucide="arrow-up-right" style="width: 18px; height: 18px;"></i> B. RENCANA BELANJA
            </div>
            <table class="table-modern table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 5%; text-align: center;">No</th>
                        <th style="width: 20%;">Kategori</th>
                        <th style="width: 30%;">Uraian Item</th>
                        <th style="width: 10%; text-align: center;">Vol</th>
                        <th style="width: 15%; text-align: right;">Harga Satuan</th>
                        <th style="width: 15%; text-align: right;">Jumlah</th>
                        <th style="width: 5%; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($pengeluaran)): ?>
                    <tr><td colspan="7" class="text-center text-muted-modern py-4">Belum ada rencana belanja yang dicatat.</td></tr>
                    <?php else: ?>
                        <?php foreach($pengeluaran as $idx => $item): $jml = $item['volume'] * $item['harga_satuan']; ?>
                        <tr>
                            <td class="text-center text-muted-modern fw-semibold"><?= $idx + 1 ?></td>
                            <td><span class="badge-modern badge-gray" style="font-weight: 500; font-size: 0.8rem;"><?= htmlspecialchars($item['kategori']) ?></span></td>
                            <td>
                                <div class="fw-bold" style="color: var(--text-main); font-size: 0.95rem;"><?= htmlspecialchars($item['nama_item']) ?></div>
                                <?php if($item['deskripsi']): ?>
                                    <div class="text-muted-modern mt-1" style="font-size: 0.85rem;"><?= htmlspecialchars($item['deskripsi']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div><?= $item['volume'] ?></div>
                                <small class="text-muted-modern"><?= htmlspecialchars($item['satuan']) ?></small>
                            </td>
                            <td class="text-end font-monospace" style="font-size: 0.9rem;"><?= formatRupiah($item['harga_satuan']) ?></td>
                            <td class="text-end fw-bold font-monospace text-danger">-<?= formatRupiah($jml) ?></td>
                            <td class="text-end">
                                <div class="d-flex gap-1 justify-content-end">
                                    <button type="button" class="btn-ghost-modern d-inline-flex align-items-center justify-content-center border-0 p-1" onclick="editItem(<?= htmlspecialchars(json_encode($item)) ?>)" title="Edit"><i data-lucide="pencil" style="width:16px;"></i></button>
                                    <form action="rab.php?action=items&id=<?= $rab['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus item ini?');">
                                        <?= csrfField() ?>
                                        <input type="hidden" name="item_action" value="delete">
                                        <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                        <button type="submit" class="btn-ghost-modern btn-ghost-danger d-inline-flex align-items-center justify-content-center border-0 p-1" title="Hapus"><i data-lucide="trash-2" style="width:16px;"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot style="background-color: var(--background);">
                    <tr>
                        <th colspan="5" class="text-end" style="font-size: 1.05rem; padding: 12px 24px;">TOTAL BELANJA</th>
                        <th class="text-end fw-bold text-danger" style="font-size: 1.15rem; padding: 12px 24px;">-<?= formatRupiah($totalPengeluaran) ?></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="d-flex justify-content-end p-4 border-top">
            <div class="d-flex flex-column align-items-end p-3 rounded" style="background: <?= ($totalPemasukan >= $totalPengeluaran) ? 'var(--info-bg)' : 'var(--warning-bg)' ?>; border: 1px solid <?= ($totalPemasukan >= $totalPengeluaran) ? 'var(--info)' : 'var(--warning)' ?>;">
                <span class="text-muted-modern fw-semibold mb-1">REKAPITULASI RAPB (SURPLUS / DEFISIT)</span>
                <span class="fw-bold font-monospace" style="font-size: 1.5rem; color: <?= ($totalPemasukan >= $totalPengeluaran) ? 'var(--info)' : 'var(--warning)' ?>;">
                    <?= formatRupiah($totalPemasukan - $totalPengeluaran) ?>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form Item -->
<div class="modal fade" id="modalItem" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="rab.php?action=items&id=<?= $rab['id'] ?>" class="modal-content" style="border-radius: 16px; border: none; box-shadow: var(--shadow-lg);">
            <?= csrfField() ?>
            <input type="hidden" name="item_action" id="item_action" value="create">
            <input type="hidden" name="item_id" id="item_id" value="">
            
            <div class="modal-header" style="border-bottom: 1px solid var(--border); padding: 20px 24px;">
                <h5 class="modal-title fw-bold" id="modalTitle">Tambah Item RAPB</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="box-shadow: none;"></button>
            </div>
            <div class="modal-body" style="padding: 24px;">
                
                <div class="mb-4 d-flex gap-3 bg-light p-2 rounded border">
                    <div class="form-check form-check-inline m-0 flex-fill">
                        <input class="form-check-input" type="radio" name="jenis" id="jenis_pemasukan" value="pemasukan">
                        <label class="form-check-label w-100 p-2 text-center rounded fw-bold" for="jenis_pemasukan" style="cursor: pointer;">
                            <i data-lucide="arrow-down-left" class="text-success"></i> Pendapatan
                        </label>
                    </div>
                    <div class="form-check form-check-inline m-0 flex-fill">
                        <input class="form-check-input" type="radio" name="jenis" id="jenis_pengeluaran" value="pengeluaran" checked>
                        <label class="form-check-label w-100 p-2 text-center rounded fw-bold" for="jenis_pengeluaran" style="cursor: pointer;">
                            <i data-lucide="arrow-up-right" class="text-danger"></i> Belanja
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label-modern">Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="kategori" id="kategori" class="form-control-modern" required list="kategoriList" placeholder="Contoh: Konsumsi atau Sponsor">
                    <datalist id="kategoriList">
                        <!-- Pemasukan -->
                        <option value="Sponsor">
                        <option value="Donatur">
                        <option value="Tiket/Registrasi">
                        <option value="Subsidi">
                        <!-- Pengeluaran -->
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
                    <label class="form-label-modern">Nama Item <span class="text-danger">*</span></label>
                    <input type="text" name="nama_item" id="nama_item" class="form-control-modern" required placeholder="Contoh: Nasi Kotak Panitia">
                </div>
                <div class="mb-3">
                    <label class="form-label-modern">Deskripsi Tambahan <span class="text-muted fw-normal fs-6">(Opsional)</span></label>
                    <input type="text" name="deskripsi" id="deskripsi" class="form-control-modern" placeholder="Detail spesifikasi...">
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label-modern">Volume <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="volume" id="volume" class="form-control-modern" required oninput="calculateTotal()" placeholder="0">
                    </div>
                    <div class="col-6">
                        <label class="form-label-modern">Satuan <span class="text-danger">*</span></label>
                        <input type="text" name="satuan" id="satuan" class="form-control-modern" required placeholder="Contoh: Box, Orang">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label-modern">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="harga_satuan" id="harga_satuan" class="form-control-modern" required oninput="calculateTotal()" placeholder="0">
                </div>
                
                <div class="p-3" style="background-color: var(--background); border-radius: 12px; border: 1px solid var(--border);">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-muted-modern">Total Estimasi</span>
                        <input type="text" id="subtotal_display" class="form-control-plaintext text-end fw-bold text-main fs-5" readonly value="Rp 0" style="width: 200px; padding: 0;">
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--border); padding: 16px 24px;">
                <button type="button" class="btn-modern btn-secondary-modern" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn-modern btn-primary-modern">Simpan Item</button>
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
    document.getElementById('modalTitle').innerText = 'Tambah Item RAPB';
    document.getElementById('jenis_pengeluaran').checked = true;
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
    document.getElementById('modalTitle').innerText = 'Edit Item RAPB';
    
    if ((item.jenis || 'pengeluaran') === 'pemasukan') {
        document.getElementById('jenis_pemasukan').checked = true;
    } else {
        document.getElementById('jenis_pengeluaran').checked = true;
    }
    
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
