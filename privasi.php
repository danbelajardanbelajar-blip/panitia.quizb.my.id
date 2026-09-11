<?php
require_once __DIR__ . '/includes/auth_helper.php';
$isLoggedIn = isLoggedIn();
if ($isLoggedIn) {
    include __DIR__ . '/views/layouts/header.php';
} else {
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kebijakan Privasi - Sistem Administrasi Panitia</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="assets/images/favicon.svg">
    <link rel="apple-touch-icon" href="assets/images/favicon.svg">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #0f172a; }
        .card-modern { background: white; border-radius: 16px; padding: 40px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .privacy-content h5 { font-weight: 700; margin-top: 1.5rem; margin-bottom: 0.75rem; color: #1e293b; }
        .privacy-content p { line-height: 1.8; color: #475569; margin-bottom: 1rem; }
        .privacy-content ul { color: #475569; line-height: 1.8; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold m-0" style="color: #4f46e5;">Admin Panitia</h2>
            <a href="login.php" class="btn btn-outline-primary px-4 rounded-pill">Kembali</a>
        </div>
<?php } ?>

<div class="<?= $isLoggedIn ? 'container-fluid py-2' : '' ?>" style="max-width: 800px; margin: 0 auto;">
    <div class="card-modern <?= $isLoggedIn ? 'border-0 shadow-sm' : '' ?>">
        <div class="<?= $isLoggedIn ? 'card-body-modern' : '' ?>">
            <div class="text-center mb-5">
                <div style="background: var(--primary, #4f46e5); width: 80px; height: 80px; border-radius: 24px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto;">
                    <i data-lucide="shield-check" style="color: white; width: 40px; height: 40px;"></i>
                </div>
                <h2 class="fw-bold mb-2">Kebijakan Privasi</h2>
                <p class="text-muted" style="font-size: 1.1rem;">Terakhir Diperbarui: 11 September 2026</p>
            </div>

            <div class="privacy-content">
                <p>Sistem Administrasi Panitia ("Kami") berkomitmen untuk melindungi privasi dan keamanan data pengguna ("Anda"). Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, menyimpan, dan melindungi informasi pribadi Anda saat menggunakan layanan kami.</p>

                <h5>1. Informasi yang Kami Kumpulkan</h5>
                <p>Saat Anda menggunakan layanan kami, khususnya saat mendaftar menggunakan integrasi Google OAuth, kami mengumpulkan informasi dasar berikut:</p>
                <ul>
                    <li><strong>Data Profil Google:</strong> Nama lengkap dan alamat email.</li>
                    <li><strong>Data Aktivitas:</strong> Data kegiatan, RAPB, transaksi, dan catatan yang Anda masukkan secara sadar ke dalam sistem.</li>
                </ul>

                <h5>2. Penggunaan Informasi</h5>
                <p>Kami menggunakan informasi yang kami kumpulkan semata-mata untuk:</p>
                <ul>
                    <li>Memfasilitasi akses masuk (login) ke dalam aplikasi.</li>
                    <li>Menyediakan layanan manajemen kepanitiaan sesuai dengan yang Anda butuhkan (seperti mencetak laporan dengan nama Anda).</li>
                    <li>Menjaga keamanan dan mencegah akses yang tidak sah ke akun Anda.</li>
                </ul>

                <h5>3. Perlindungan Data</h5>
                <p>Data administrasi kepanitiaan Anda bersifat pribadi dan hanya dapat diakses oleh Anda melalui sesi login yang sah. Kami tidak akan menjual, menyewakan, atau menukar informasi pribadi maupun data acara Anda kepada pihak ketiga untuk tujuan pemasaran apa pun.</p>

                <h5>4. Penyimpanan Data</h5>
                <p>Data Anda disimpan dalam database terenkripsi yang aman. Anda memiliki kendali penuh untuk menghapus data kepanitiaan Anda kapan saja melalui antarmuka aplikasi (seperti menghapus kegiatan atau transaksi).</p>

                <h5>5. Perubahan Kebijakan</h5>
                <p>Kami berhak mengubah Kebijakan Privasi ini dari waktu ke waktu. Jika ada perubahan signifikan, kami akan memberitahukan pembaruan tersebut melalui platform kami.</p>

                <div class="mt-5 p-4 rounded" style="background-color: #f1f5f9; border-left: 4px solid #4f46e5;">
                    <h6 class="fw-bold mb-2">Pertanyaan lebih lanjut?</h6>
                    <p class="mb-0 text-muted" style="font-size: 0.95rem;">Jika Anda memiliki pertanyaan mengenai Kebijakan Privasi ini, silakan hubungi kami melalui <a href="tentang.php" class="text-decoration-none" style="color: #4f46e5; font-weight: 600;">Halaman Tentang / Kontak</a>.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
if ($isLoggedIn) {
    include __DIR__ . '/views/layouts/footer.php';
} else {
?>
    </div> <!-- end container -->
    <script>lucide.createIcons();</script>
</body>
</html>
<?php } ?>
