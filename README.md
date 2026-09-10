# Sistem Administrasi Panitia

Aplikasi berbasis web untuk mempermudah pencatatan kegiatan kepanitiaan, manajemen anggaran (RAB), pencatatan transaksi keuangan (pemasukan/pengeluaran), serta pelaporan yang dapat di-export ke dalam format Microsoft Word (.docx).

Aplikasi ini dirancang **100% kompatibel dengan Shared Hosting PHP tradisional (cPanel)** dan **TIDAK membutuhkan Node.js, npm, PM2, atau terminal server access** di production.

## Fitur Utama

- **Login Google (OAuth 2.0)**: Aman, tanpa perlu menghafal password.
- **Manajemen Kegiatan**: Catat detail kegiatan, jadwal, lokasi, dan status pelaksanaan.
- **RAB (Rencana Anggaran Biaya)**: Kalkulasi otomatis dengan export DOCX.
- **Buku Keuangan**: Pencatatan pemasukan, pengeluaran, perhitungan saldo, dan realisasi anggaran otomatis.
- **Timeline Catatan Kegiatan**: Catat log rapat, keputusan, kendala, dll.
- **Laporan Otomatis**: Generate laporan pertanggungjawaban lengkap, bisa di-export ke DOCX.
- **Keamanan Lanjutan**: CSRF Token, PDO Prepared Statements, pembatasan akses data antar-user, dan `.htaccess` hardening.

---

## Persyaratan Server / Hosting

- Apache Web Server
- PHP 8.2 atau lebih baru
- Ekstensi PHP: `pdo_mysql`, `curl`, `json`, `mbstring`, `zip`, `xml`
- MySQL / MariaDB

---

## Panduan Instalasi (Shared Hosting cPanel)

Ikuti langkah-langkah di bawah ini untuk deploy aplikasi ke hosting Anda:

### 1. Persiapan Database
1. Buka **cPanel** > **MySQL® Databases**.
2. Buat database baru (misal: `u12345_panitia`).
3. Buat user database baru dan buat password yang kuat.
4. Tambahkan user ke database tersebut dengan hak akses **All Privileges**.
5. Buka **phpMyAdmin**, pilih database yang baru dibuat, klik menu **Import**.
6. Upload dan jalankan file `database/schema.sql` dari folder project ini.

### 2. Konfigurasi Google Cloud Console (OAuth)
1. Buka [Google Cloud Console](https://console.cloud.google.com/).
2. Buat Project baru atau gunakan yang sudah ada.
3. Buka **APIs & Services** > **OAuth consent screen**, pilih tipe "External", lalu lengkapi data aplikasi.
4. Buka **Credentials** > **Create Credentials** > **OAuth client ID**.
5. Pilih **Web application**.
6. Pada bagian **Authorized redirect URIs**, masukkan URL login aplikasi Anda. (Contoh: `https://domainanda.com/login.php` atau `https://domainanda.com/panitia/login.php`).
7. Simpan, lalu Anda akan mendapatkan **Client ID** dan **Client Secret**.

### 3. Konfigurasi Aplikasi (.env / config)
1. Buka folder source code.
2. Salin file `.env.example` menjadi `.env`.
3. Buka file `.env` dengan text editor, lalu isi konfigurasi:
   ```env
   DB_HOST=localhost
   DB_NAME=nama_database_anda
   DB_USER=user_database_anda
   DB_PASS=password_database_anda

   GOOGLE_CLIENT_ID=client_id_dari_google
   GOOGLE_CLIENT_SECRET=client_secret_dari_google

   APP_URL=https://domainanda.com/folder-aplikasi
   ```

### 4. Upload ke Hosting
1. *Compress* (ZIP) seluruh folder aplikasi ini **(termasuk folder `vendor` hasil download)**.
2. Buka **cPanel** > **File Manager**.
3. Masuk ke folder tujuan (misal: `public_html` atau subdirektori).
4. Upload file ZIP tersebut, lalu **Extract**.
5. Pastikan semua file termasuk yang tersembunyi seperti `.env` dan `.htaccess` ikut terekstrak.

### 5. Selesai
1. Buka URL aplikasi Anda di browser.
2. Silakan login menggunakan akun Google.

---

## Panduan Instalasi (Development Lokal)

Jika Anda ingin menjalankan atau memodifikasi aplikasi ini di komputer lokal (localhost):

1. Install XAMPP atau Laragon (PHP 8.2+).
2. Install [Composer](https://getcomposer.org/).
3. Clone atau ekstrak repository ini ke folder `htdocs` (XAMPP) atau `www` (Laragon).
4. Buka terminal di folder project, jalankan:
   ```bash
   composer install
   ```
   *(Catatan: Perintah ini akan mengunduh library `PhpWord` ke folder `vendor`. Pastikan Anda membawa folder `vendor` ini saat upload ke hosting nanti.)*
5. Buat database `sistem_panitia` di MySQL lokal via phpMyAdmin.
6. Import file `database/schema.sql` dan `database/seed.sql` (opsional untuk data dummy).
7. Salin `.env.example` ke `.env` dan isi konfigurasi DB & Google Client ID Anda.
8. Buka `http://localhost/panitia` di browser.

---

## Catatan Keamanan Tambahan
- Folder seperti `config`, `controllers`, `models`, dan `database` telah dilengkapi file `.htaccess` berisi `Deny from all` untuk mencegah akses langsung via browser.
- Pastikan ekstensi file yang diupload (jika Anda mengembangkan fitur lampiran) dikendalikan secara ketat untuk mencegah upload *shell script*. Folder `uploads` juga telah dilengkapi konfigurasi untuk mematikan eksekusi PHP.
- Selalu backup database Anda secara berkala melalui phpMyAdmin (Export).
