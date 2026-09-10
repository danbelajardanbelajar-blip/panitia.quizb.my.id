-- Seed Data for Sistem Panitia

-- Insert a test user (Note: Password is not needed since login uses Google OAuth)
INSERT INTO `users` (`id`, `google_id`, `email`, `name`, `avatar`) VALUES
(1, 'dummy_google_id_123', 'admin@example.com', 'Admin Panitia', 'https://ui-avatars.com/api/?name=Admin+Panitia');

-- Insert a test kegiatan
INSERT INTO `kegiatan` (`id`, `user_id`, `nama_kegiatan`, `tema`, `deskripsi`, `tanggal_mulai`, `tanggal_selesai`, `lokasi`, `penanggung_jawab`, `status`, `catatan`) VALUES
(1, 1, 'Seminar Nasional Teknologi 2026', 'Inovasi AI untuk Masa Depan', 'Seminar berskala nasional membahas implementasi Artificial Intelligence di berbagai sektor industri.', '2026-10-15', '2026-10-16', 'Gedung Serbaguna Utama', 'Budi Santoso', 'Persiapan', 'Pastikan semua pembicara sudah dikonfirmasi.');

-- Insert a test RAB
INSERT INTO `rab` (`id`, `kegiatan_id`, `nama_rab`, `nomor_dokumen`, `tanggal`, `catatan`, `status`) VALUES
(1, 1, 'RAB Utama Seminar', 'RAB/SEM/2026/01', '2026-09-01', 'RAB awal untuk pengajuan sponsor', 'Draft');

-- Insert RAB items
INSERT INTO `rab_items` (`id`, `rab_id`, `kategori`, `nama_item`, `deskripsi`, `volume`, `satuan`, `harga_satuan`) VALUES
(1, 1, 'Konsumsi', 'Nasi Kotak Peserta', 'Nasi kotak untuk makan siang peserta', 200, 'Box', 25000),
(2, 1, 'Konsumsi', 'Snack Box', 'Snack untuk coffeebreak', 200, 'Box', 15000),
(3, 1, 'ATK', 'Blocknote & Pulpen', 'Seminar kit peserta', 200, 'Set', 10000),
(4, 1, 'Honor', 'Honor Pemateri', 'Honor untuk 3 orang pemateri', 3, 'Orang', 1500000),
(5, 1, 'Sewa', 'Sewa Gedung', 'Sewa gedung 2 hari', 2, 'Hari', 5000000);

-- Insert Transaksi (Pemasukan)
INSERT INTO `transaksi` (`id`, `kegiatan_id`, `jenis`, `kategori`, `nama_transaksi`, `deskripsi`, `nominal`, `sumber_tujuan`, `metode_pembayaran`, `nomor_bukti`, `tanggal`) VALUES
(1, 1, 'pemasukan', 'Sponsor', 'Sponsorship PT Teknologi Jaya', 'Pencairan dana sponsor termin 1', 10000000, 'PT Teknologi Jaya', 'Transfer', 'BKT-001', '2026-09-05'),
(2, 1, 'pemasukan', 'Dana Panitia', 'Iuran Kepanitiaan', 'Iuran kas awal panitia', 500000, 'Kas Panitia', 'Cash', 'BKT-002', '2026-09-06');

-- Insert Transaksi (Pengeluaran)
INSERT INTO `transaksi` (`id`, `kegiatan_id`, `jenis`, `kategori`, `nama_transaksi`, `deskripsi`, `nominal`, `sumber_tujuan`, `metode_pembayaran`, `nomor_bukti`, `tanggal`) VALUES
(3, 1, 'pengeluaran', 'Sewa', 'DP Sewa Gedung', 'Pembayaran DP sewa gedung 50%', 5000000, 'Pengelola Gedung', 'Transfer', 'BKK-001', '2026-09-07'),
(4, 1, 'pengeluaran', 'ATK', 'Beli Kertas dan Tinta', 'Keperluan kesekretariatan', 250000, 'Toko ATK Maju', 'Cash', 'BKK-002', '2026-09-08');

-- Insert Catatan Kegiatan
INSERT INTO `catatan_kegiatan` (`id`, `kegiatan_id`, `tanggal`, `waktu`, `judul`, `isi`, `kategori`, `penanggung_jawab`, `status`) VALUES
(1, 1, '2026-09-02', '14:00:00', 'Rapat Perdana Kepanitiaan', 'Membahas pembagian divisi dan timeline kerja masing-masing divisi.', 'Rapat', 'Ketua Panitia', 'Selesai'),
(2, 1, '2026-09-07', '10:00:00', 'Pembayaran DP Gedung', 'Telah dilakukan pembayaran DP gedung sebesar 50%. Bukti terlampir di keuangan.', 'Persiapan', 'Bendahara', 'Selesai'),
(3, 1, '2026-09-10', '09:00:00', 'Follow up Sponsor', 'Belum ada balasan dari 3 perusahaan target sponsor utama. Perlu di-follow up via telepon.', 'Kendala', 'Divisi Dana Usaha', 'Dalam Proses');

