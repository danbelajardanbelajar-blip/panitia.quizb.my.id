<?php
require_once __DIR__ . '/includes/auth_helper.php';
require_once __DIR__ . '/includes/format_helper.php';
require_once __DIR__ . '/models/Kegiatan.php';
require_once __DIR__ . '/models/Rab.php';
require_once __DIR__ . '/models/Transaksi.php';
require_once __DIR__ . '/models/CatatanKegiatan.php';
require_once __DIR__ . '/vendor/autoload.php';

requireLogin();
$userId = getCurrentUserId();
$id = $_GET['id'] ?? null;

$kegiatan = Kegiatan::findById($id, $userId);
if (!$kegiatan) {
    die("Kegiatan tidak ditemukan atau Anda tidak memiliki akses.");
}

$rabList = Rab::getAll($userId, $id);
$totalRab = 0;
foreach ($rabList as $r) {
    $totalRab += $r['total_rab'];
}

$rekap = Transaksi::getRekap($userId, $id);
$totalPemasukan = $rekap['pemasukan'];
$totalPengeluaran = $rekap['pengeluaran'];
$saldo = $totalPemasukan - $totalPengeluaran;

$sisaAnggaran = $totalRab - $totalPengeluaran;
$persentaseRealisasi = $totalRab > 0 ? ($totalPengeluaran / $totalRab) * 100 : 0;

$catatanList = CatatanKegiatan::getAll($userId, $id);

// Init PHPWord
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->addSection();

$headerStyle = ['bold' => true, 'size' => 16, 'align' => 'center'];
$subHeaderStyle = ['bold' => true, 'size' => 12, 'spaceBefore' => 240, 'spaceAfter' => 120];
$boldStyle = ['bold' => true];

$phpWord->addTitleStyle(1, $headerStyle);
$section->addTitle('LAPORAN PELAKSANAAN KEGIATAN', 1);
$section->addText(strtoupper($kegiatan['nama_kegiatan']), ['bold' => true, 'size' => 14], ['align' => 'center']);
$section->addTextBreak(2);

// Identitas
$section->addText('A. Identitas Kegiatan', $subHeaderStyle);
$tableStyle = ['borderSize' => 6, 'borderColor' => '999999', 'cellMargin' => 80];
$phpWord->addTableStyle('Identitas Table', $tableStyle);
$table = $section->addTable('Identitas Table');

$table->addRow(); $table->addCell(3000)->addText('Nama Kegiatan', $boldStyle); $table->addCell(6000)->addText($kegiatan['nama_kegiatan']);
$table->addRow(); $table->addCell(3000)->addText('Tema', $boldStyle); $table->addCell(6000)->addText($kegiatan['tema'] ?? '-');
$table->addRow(); $table->addCell(3000)->addText('Tanggal', $boldStyle); $table->addCell(6000)->addText(formatTanggal($kegiatan['tanggal_mulai']) . ' s.d ' . formatTanggal($kegiatan['tanggal_selesai']));
$table->addRow(); $table->addCell(3000)->addText('Lokasi', $boldStyle); $table->addCell(6000)->addText($kegiatan['lokasi'] ?? '-');
$table->addRow(); $table->addCell(3000)->addText('Penanggung Jawab', $boldStyle); $table->addCell(6000)->addText($kegiatan['penanggung_jawab'] ?? '-');

// Deskripsi
$section->addText('B. Deskripsi & Hasil', $subHeaderStyle);
$section->addText($kegiatan['deskripsi'] ?? 'Tidak ada deskripsi.');
if ($kegiatan['catatan']) {
    $section->addTextBreak(1);
    $section->addText('Catatan Akhir:', $boldStyle);
    $section->addText($kegiatan['catatan']);
}

// Keuangan
$section->addText('C. Rekapitulasi RAB & Realisasi', $subHeaderStyle);
$phpWord->addTableStyle('Keuangan Table', $tableStyle);
$tableK = $section->addTable('Keuangan Table');
$tableK->addRow(); $tableK->addCell(5000)->addText('Komponen', $boldStyle); $tableK->addCell(4000)->addText('Nominal', $boldStyle);
$tableK->addRow(); $tableK->addCell(5000)->addText('Total Rencana Anggaran Biaya (RAB)'); $tableK->addCell(4000)->addText(formatRupiah($totalRab));
$tableK->addRow(); $tableK->addCell(5000)->addText('Total Pemasukan (Dana Tersedia)'); $tableK->addCell(4000)->addText(formatRupiah($totalPemasukan));
$tableK->addRow(); $tableK->addCell(5000)->addText('Total Pengeluaran (Realisasi)'); $tableK->addCell(4000)->addText(formatRupiah($totalPengeluaran));
$tableK->addRow(); $tableK->addCell(5000)->addText('Saldo Akhir', $boldStyle); $tableK->addCell(4000)->addText(formatRupiah($saldo), $boldStyle);
$tableK->addRow(); $tableK->addCell(5000)->addText('Sisa Anggaran dari RAB'); $tableK->addCell(4000)->addText(formatRupiah($sisaAnggaran));
$tableK->addRow(); $tableK->addCell(5000)->addText('Persentase Realisasi terhadap RAB'); $tableK->addCell(4000)->addText(number_format($persentaseRealisasi, 2, ',', '.') . '%');

// Catatan
$section->addText('D. Catatan Kegiatan Penting', $subHeaderStyle);
if (empty($catatanList)) {
    $section->addText('Tidak ada catatan kegiatan.');
} else {
    foreach ($catatanList as $c) {
        $section->addText(formatTanggal($c['tanggal']) . ': ' . $c['judul'], $boldStyle);
        $section->addText($c['isi']);
        $section->addTextBreak(1);
    }
}

// Tanda Tangan
$section->addTextBreak(3);
$tableTTD = $section->addTable();
$tableTTD->addRow();
$cellLeft = $tableTTD->addCell(4500);
$cellLeft->addText('Mengetahui,', [], ['align' => 'center']);
$cellLeft->addText('Ketua Panitia', [], ['align' => 'center']);
$cellLeft->addTextBreak(4);
$cellLeft->addText('( ' . ($kegiatan['penanggung_jawab'] ?: '........................') . ' )', $boldStyle, ['align' => 'center']);

$cellRight = $tableTTD->addCell(4500);
$cellRight->addText('Disetujui,', [], ['align' => 'center']);
$cellRight->addText('Bendahara', [], ['align' => 'center']);
$cellRight->addTextBreak(4);
$cellRight->addText('( ........................ )', $boldStyle, ['align' => 'center']);

// Save and download
$fileName = 'Laporan_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $kegiatan['nama_kegiatan']) . '.docx';
$fileFile = tempnam(sys_get_temp_dir(), 'phpword');
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save($fileFile);

header('Content-Description: File Transfer');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . filesize($fileFile));
flush();
readfile($fileFile);
unlink($fileFile);
exit;
