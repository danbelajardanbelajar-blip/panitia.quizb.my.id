<?php
require_once __DIR__ . '/includes/auth_helper.php';
require_once __DIR__ . '/includes/format_helper.php';
require_once __DIR__ . '/models/Kegiatan.php';
require_once __DIR__ . '/models/Rab.php';
require_once __DIR__ . '/models/RabItem.php';
require_once __DIR__ . '/vendor/autoload.php';

requireLogin();
$userId = getCurrentUserId();
$id = $_GET['id'] ?? null;

$rab = Rab::findById($id, $userId);
if (!$rab) {
    die("RAB tidak ditemukan atau Anda tidak memiliki akses.");
}

$items = RabItem::getByRabId($id);

// Init PHPWord
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->addSection();

$headerStyle = ['bold' => true, 'size' => 14, 'align' => 'center'];
$boldStyle = ['bold' => true];

$phpWord->addTitleStyle(1, $headerStyle);
$section->addTitle('RENCANA ANGGARAN PENDAPATAN DAN BELANJA (RAPB)', 1);
$section->addTextBreak(1);

$tableIdentitas = $section->addTable();
$tableIdentitas->addRow(); $tableIdentitas->addCell(3000)->addText('Nama Kegiatan', $boldStyle); $tableIdentitas->addCell(6000)->addText(': ' . $rab['nama_kegiatan']);
$tableIdentitas->addRow(); $tableIdentitas->addCell(3000)->addText('Nama RAPB', $boldStyle); $tableIdentitas->addCell(6000)->addText(': ' . $rab['nama_rab']);
$tableIdentitas->addRow(); $tableIdentitas->addCell(3000)->addText('Nomor Dokumen', $boldStyle); $tableIdentitas->addCell(6000)->addText(': ' . ($rab['nomor_dokumen'] ?? '-'));
$tableIdentitas->addRow(); $tableIdentitas->addCell(3000)->addText('Tanggal', $boldStyle); $tableIdentitas->addCell(6000)->addText(': ' . formatTanggal($rab['tanggal']));
$section->addTextBreak(2);

$tableStyle = ['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80];
$phpWord->addTableStyle('Rab Table', $tableStyle);

$pemasukan = [];
$pengeluaran = [];
foreach ($items as $item) {
    if (($item['jenis'] ?? 'pengeluaran') === 'pemasukan') {
        $pemasukan[] = $item;
    } else {
        $pengeluaran[] = $item;
    }
}

// Function to render table
$renderTable = function($section, $data, $title, $phpWord, $boldStyle) {
    $section->addText($title, $boldStyle);
    $table = $section->addTable('Rab Table');
    
    $table->addRow();
    $table->addCell(500, ['bgColor' => 'EEEEEE'])->addText('No', $boldStyle, ['align' => 'center']);
    $table->addCell(1500, ['bgColor' => 'EEEEEE'])->addText('Kategori', $boldStyle, ['align' => 'center']);
    $table->addCell(3000, ['bgColor' => 'EEEEEE'])->addText('Uraian', $boldStyle, ['align' => 'center']);
    $table->addCell(1000, ['bgColor' => 'EEEEEE'])->addText('Volume', $boldStyle, ['align' => 'center']);
    $table->addCell(1000, ['bgColor' => 'EEEEEE'])->addText('Satuan', $boldStyle, ['align' => 'center']);
    $table->addCell(1500, ['bgColor' => 'EEEEEE'])->addText('Harga Satuan', $boldStyle, ['align' => 'center']);
    $table->addCell(1500, ['bgColor' => 'EEEEEE'])->addText('Jumlah', $boldStyle, ['align' => 'center']);

    $total = 0;
    foreach ($data as $index => $item) {
        $jumlah = $item['volume'] * $item['harga_satuan'];
        $total += $jumlah;
        
        $table->addRow();
        $table->addCell(500)->addText($index + 1, null, ['align' => 'center']);
        $table->addCell(1500)->addText($item['kategori']);
        $table->addCell(3000)->addText($item['nama_item']);
        $table->addCell(1000)->addText($item['volume'], null, ['align' => 'center']);
        $table->addCell(1000)->addText($item['satuan'], null, ['align' => 'center']);
        $table->addCell(1500)->addText(formatRupiah($item['harga_satuan']), null, ['align' => 'right']);
        $table->addCell(1500)->addText(formatRupiah($jumlah), null, ['align' => 'right']);
    }

    $table->addRow();
    $table->addCell(8500, ['gridSpan' => 6])->addText('TOTAL ' . strtoupper($title), $boldStyle, ['align' => 'right']);
    $table->addCell(1500)->addText(formatRupiah($total), $boldStyle, ['align' => 'right']);
    $section->addTextBreak(1);
    
    return $total;
};

$totalPendapatan = $renderTable($section, $pemasukan, 'A. Target Pendapatan', $phpWord, $boldStyle);
$totalBelanja = $renderTable($section, $pengeluaran, 'B. Rencana Belanja', $phpWord, $boldStyle);

$section->addText('REKAPITULASI RAPB', $boldStyle);
$rekapTable = $section->addTable('Rab Table');
$rekapTable->addRow(); $rekapTable->addCell(4000)->addText('Total Target Pendapatan'); $rekapTable->addCell(4000)->addText(formatRupiah($totalPendapatan), null, ['align' => 'right']);
$rekapTable->addRow(); $rekapTable->addCell(4000)->addText('Total Rencana Belanja'); $rekapTable->addCell(4000)->addText(formatRupiah($totalBelanja), null, ['align' => 'right']);
$rekapTable->addRow(); $rekapTable->addCell(4000)->addText('Surplus / Defisit', $boldStyle); $rekapTable->addCell(4000)->addText(formatRupiah($totalPendapatan - $totalBelanja), $boldStyle, ['align' => 'right']);

$section->addTextBreak(3);
$tableTTD = $section->addTable();
$tableTTD->addRow();
$cellLeft = $tableTTD->addCell(4500);
$cellLeft->addText('Dibuat oleh,', [], ['align' => 'center']);
$cellLeft->addText('Sekretaris / Bendahara', [], ['align' => 'center']);
$cellLeft->addTextBreak(4);
$cellLeft->addText('( ........................ )', $boldStyle, ['align' => 'center']);

$cellRight = $tableTTD->addCell(4500);
$cellRight->addText('Mengetahui,', [], ['align' => 'center']);
$cellRight->addText('Ketua Panitia', [], ['align' => 'center']);
$cellRight->addTextBreak(4);
$cellRight->addText('( ........................ )', $boldStyle, ['align' => 'center']);

// Save and download
$fileName = 'RAB_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $rab['nama_rab']) . '.docx';
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
