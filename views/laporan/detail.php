<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 d-print-none">
    <div>
        <h2 class="mb-1" style="font-weight: 800;">Detail Laporan</h2>
        <p class="text-muted-modern mb-0">Preview laporan sebelum dicetak atau diexport.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="laporan.php" class="btn-modern btn-secondary-modern"><i data-lucide="arrow-left"></i> Kembali</a>
        <button onclick="window.print()" class="btn-modern btn-secondary-modern"><i data-lucide="printer"></i> Cetak</button>
        <a href="export_laporan.php?id=<?= $kegiatan['id'] ?>" class="btn-modern btn-primary-modern"><i data-lucide="file-down"></i> Export DOCX</a>
    </div>
</div>

<div class="d-flex justify-content-center d-print-block">
    <!-- Document Container simulating A4 -->
    <div class="card-modern document-preview" style="max-width: 210mm; width: 100%; min-height: 297mm; background: white; margin: 0 auto; box-shadow: var(--shadow-md); padding: 40px;">
        <div class="card-body-modern p-0">
            
            <div class="text-center mb-5 pb-3 border-bottom" style="border-color: var(--border) !important;">
                <h3 class="fw-bold mb-1" style="color: var(--text-main); font-size: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">Laporan Pelaksanaan Kegiatan</h3>
                <h4 class="fw-bold" style="color: var(--primary); font-size: 1.25rem; text-transform: uppercase;"><?= htmlspecialchars($kegiatan['nama_kegiatan']) ?></h4>
            </div>

            <div class="mb-4">
                <h5 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: var(--text-main);"><span style="background: var(--primary); color: white; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; font-size: 0.9rem;">A</span> Identitas Kegiatan</h5>
                <table class="table table-sm table-borderless m-0 ms-4" style="color: var(--text-muted); font-size: 0.95rem;">
                    <tr><td width="200" class="py-1">Nama Kegiatan</td><td class="py-1">: <strong style="color: var(--text-main);"><?= htmlspecialchars($kegiatan['nama_kegiatan']) ?></strong></td></tr>
                    <tr><td class="py-1">Tema</td><td class="py-1">: <span style="color: var(--text-main);"><?= htmlspecialchars($kegiatan['tema'] ?? '-') ?></span></td></tr>
                    <tr><td class="py-1">Tanggal Pelaksanaan</td><td class="py-1">: <span style="color: var(--text-main);"><?= formatTanggal($kegiatan['tanggal_mulai']) ?> s.d <?= formatTanggal($kegiatan['tanggal_selesai']) ?></span></td></tr>
                    <tr><td class="py-1">Lokasi</td><td class="py-1">: <span style="color: var(--text-main);"><?= htmlspecialchars($kegiatan['lokasi'] ?? '-') ?></span></td></tr>
                    <tr><td class="py-1">Penanggung Jawab</td><td class="py-1">: <span style="color: var(--text-main);"><?= htmlspecialchars($kegiatan['penanggung_jawab'] ?? '-') ?></span></td></tr>
                </table>
            </div>

            <div class="mb-4">
                <h5 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: var(--text-main);"><span style="background: var(--primary); color: white; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; font-size: 0.9rem;">B</span> Deskripsi & Hasil</h5>
                <div class="ms-4 p-3" style="background: var(--background); border-radius: 8px; border: 1px solid var(--border);">
                    <p style="white-space: pre-wrap; font-size: 0.95rem; color: var(--text-main); line-height: 1.6;" class="mb-0"><?= htmlspecialchars($kegiatan['deskripsi'] ?? 'Tidak ada deskripsi yang ditambahkan.') ?></p>
                </div>
                <?php if($kegiatan['catatan']): ?>
                    <div class="ms-4 mt-3 p-3" style="background: #fef3c7; border: 1px solid #fde68a; border-radius: 8px; color: #92400e;">
                        <strong>Catatan Evaluasi / Follow-up:</strong><br>
                        <p style="white-space: pre-wrap; font-size: 0.95rem; line-height: 1.6;" class="mb-0 mt-1"><?= htmlspecialchars($kegiatan['catatan']) ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-4" style="page-break-inside: avoid;">
                <h5 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: var(--text-main);"><span style="background: var(--primary); color: white; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; font-size: 0.9rem;">C</span> Rekapitulasi Anggaran & Realisasi (RAPB)</h5>
                <div class="ms-4">
                    <table class="table-modern" style="border: 1px solid var(--border);">
                        <thead>
                            <tr style="background: var(--background);">
                                <th style="padding: 12px 16px; font-size: 0.85rem;">Komponen</th>
                                <th class="text-end" style="padding: 12px 16px; font-size: 0.85rem;">RAPB (Rencana)</th>
                                <th class="text-end" style="padding: 12px 16px; font-size: 0.85rem;">Realisasi</th>
                                <th class="text-end" style="padding: 12px 16px; font-size: 0.85rem;">Capaian</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 12px 16px; font-size: 0.95rem;">Pendapatan</td>
                                <td class="text-end font-monospace" style="padding: 12px 16px; font-size: 0.95rem; color: #16a34a;"><?= formatRupiah($totalTargetPendapatan) ?></td>
                                <td class="text-end font-monospace" style="padding: 12px 16px; font-size: 0.95rem; color: #16a34a;"><?= formatRupiah($totalPemasukan) ?></td>
                                <td class="text-end font-monospace" style="padding: 12px 16px; font-size: 0.95rem;"><?= number_format($persentasePendapatan, 2, ',', '.') ?>%</td>
                            </tr>
                            <tr>
                                <td style="padding: 12px 16px; font-size: 0.95rem;">Belanja / Pengeluaran</td>
                                <td class="text-end font-monospace" style="padding: 12px 16px; font-size: 0.95rem; color: #dc2626;"><?= formatRupiah($totalRencanaBelanja) ?></td>
                                <td class="text-end font-monospace" style="padding: 12px 16px; font-size: 0.95rem; color: #dc2626;"><?= formatRupiah($totalPengeluaran) ?></td>
                                <td class="text-end font-monospace" style="padding: 12px 16px; font-size: 0.95rem;"><?= number_format($persentaseBelanja, 2, ',', '.') ?>%</td>
                            </tr>
                            <tr style="background: var(--background);">
                                <td style="padding: 12px 16px; font-size: 0.95rem;"><strong>Surplus / Defisit</strong></td>
                                <td class="text-end fw-bold font-monospace" style="padding: 12px 16px; font-size: 1.1rem; color: var(--primary);"><?= formatRupiah($totalTargetPendapatan - $totalRencanaBelanja) ?></td>
                                <td class="text-end fw-bold font-monospace" style="padding: 12px 16px; font-size: 1.1rem; color: var(--primary);"><?= formatRupiah($totalPemasukan - $totalPengeluaran) ?></td>
                                <td class="text-end"></td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div class="d-flex gap-3 mt-3">
                        <div style="flex: 1; padding: 12px 16px; border: 1px solid var(--border); border-radius: 8px;">
                            <span class="d-block text-muted-modern" style="font-size: 0.8rem; margin-bottom: 4px;">Sisa / Hemat Belanja (Rencana - Realisasi)</span>
                            <strong class="font-monospace" style="font-size: 1.05rem;"><?= formatRupiah($totalRencanaBelanja - $totalPengeluaran) ?></strong>
                        </div>
                        <div style="flex: 1; padding: 12px 16px; border: 1px solid var(--border); border-radius: 8px; background: <?= ($saldo >= 0) ? 'var(--info-bg)' : 'var(--warning-bg)' ?>;">
                            <span class="d-block text-muted-modern" style="font-size: 0.8rem; margin-bottom: 4px; color: var(--text-main) !important;">Saldo Kas Saat Ini</span>
                            <strong class="font-monospace" style="font-size: 1.05rem; color: var(--text-main);"><?= formatRupiah($saldo) ?></strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-5" style="page-break-inside: avoid;">
                <h5 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: var(--text-main);"><span style="background: var(--primary); color: white; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; font-size: 0.9rem;">D</span> Timeline & Catatan Penting</h5>
                <div class="ms-4">
                    <?php if (empty($catatanList)): ?>
                        <p class="text-muted-modern" style="font-size: 0.95rem; font-style: italic;">Tidak ada catatan terkait kegiatan ini.</p>
                    <?php else: ?>
                        <div style="position: relative; padding-left: 20px;">
                            <div style="position: absolute; left: 0; top: 8px; bottom: 8px; width: 2px; background: var(--border);"></div>
                            <?php foreach($catatanList as $c): ?>
                                <div class="mb-3 position-relative">
                                    <div style="position: absolute; left: -24px; top: 4px; width: 10px; height: 10px; border-radius: 50%; background: var(--primary);"></div>
                                    <strong style="color: var(--text-main); font-size: 0.95rem;"><?= formatTanggal($c['tanggal']) ?> - <?= htmlspecialchars($c['judul']) ?></strong>
                                    <div class="mt-1 text-muted-modern" style="font-size: 0.9rem; line-height: 1.5;">
                                        <?= nl2br(htmlspecialchars($c['isi'])) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row mt-5 pt-5 text-center" style="page-break-inside: avoid;">
                <div class="col-6">
                    <p style="color: var(--text-main);">Mengetahui,<br>Ketua Pelaksana</p>
                    <br><br><br>
                    <p class="mb-0"><strong style="color: var(--text-main); text-decoration: underline;">( <?= htmlspecialchars($kegiatan['penanggung_jawab'] ?: '........................') ?> )</strong></p>
                </div>
                <div class="col-6">
                    <p style="color: var(--text-main);">Disetujui Oleh,<br>Bendahara</p>
                    <br><br><br>
                    <p class="mb-0"><strong style="color: var(--text-main); text-decoration: underline;">( ........................ )</strong></p>
                </div>
            </div>
            
        </div>
    </div>
</div>

<style>
@media print {
    body { background-color: #fff !important; }
    .sidebar, .top-nav, .btn-modern, .d-print-none { display: none !important; }
    .main-content { margin: 0 !important; padding: 0 !important; max-width: 100% !important; }
    .card-modern.document-preview { 
        box-shadow: none !important; 
        padding: 0 !important; 
        margin: 0 !important;
        border: none !important;
        max-width: 100% !important;
    }
}
</style>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
