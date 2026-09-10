<?php
function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function formatTanggal($tanggal) {
    if (empty($tanggal)) return '-';
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $timestamp = strtotime($tanggal);
    $hari = date('d', $timestamp);
    $bln = $bulan[(int)date('m', $timestamp)];
    $thn = date('Y', $timestamp);
    return "$hari $bln $thn";
}

function formatTanggalWaktu($datetime) {
    if (empty($datetime)) return '-';
    return formatTanggal(date('Y-m-d', strtotime($datetime))) . ' ' . date('H:i', strtotime($datetime));
}
