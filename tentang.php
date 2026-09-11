<?php
require_once __DIR__ . '/includes/auth_helper.php';
$isLoggedIn = isLoggedIn();
if ($isLoggedIn) {
    include __DIR__ . '/views/layouts/header.php';
} else {
    // Basic header for non-logged in users
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang - Sistem Administrasi Panitia</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #0f172a; }
        .card-modern { background: white; border-radius: 16px; padding: 30px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
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
                    <i data-lucide="layers" style="color: white; width: 40px; height: 40px;"></i>
                </div>
                <h2 class="fw-bold mb-2">Tentang Aplikasi</h2>
                <p class="text-muted" style="font-size: 1.1rem;">Sistem Informasi Administrasi Kepanitiaan Terpadu</p>
            </div>

            <div class="mb-5">
                <h4 class="fw-bold mb-3 border-bottom pb-2">Apa itu Sistem Administrasi Panitia?</h4>
                <p style="line-height: 1.8; font-size: 1.05rem; color: #334155;">
                    Aplikasi ini dirancang secara khusus untuk mempermudah panitia dalam mengelola berbagai kegiatan, mulai dari penjadwalan (timeline), penyusunan RAPB (Rencana Anggaran Pendapatan dan Belanja), hingga pencatatan transaksi keuangan secara <em>real-time</em>. 
                </p>
                <p style="line-height: 1.8; font-size: 1.05rem; color: #334155;">
                    Kami memahami bahwa transparansi dan kecepatan dalam pengelolaan data sangat krusial dalam keberhasilan sebuah acara. Oleh karena itu, aplikasi ini hadir dengan <em>interface</em> yang modern, bersih, dan sangat mudah dipelajari oleh siapa saja.
                </p>
            </div>

            <div class="mb-4">
                <h4 class="fw-bold mb-4 border-bottom pb-2">Hubungi Kami</h4>
                <p style="line-height: 1.8; font-size: 1.05rem; color: #334155;">
                    Apabila Anda memiliki pertanyaan, saran, atau kendala dalam menggunakan layanan kami, jangan ragu untuk menghubungi kami melalui kontak di bawah ini:
                </p>
                
                <div class="d-flex flex-column gap-3 mt-4">
                    <a href="https://wa.me/6285743399595" target="_blank" class="d-flex align-items-center gap-3 text-decoration-none p-3 rounded-4" style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; transition: transform 0.2s;">
                        <div style="background: #16a34a; color: white; padding: 12px; border-radius: 12px;">
                            <i data-lucide="phone"></i>
                        </div>
                        <div>
                            <div class="fw-bold" style="font-size: 1.1rem;">WhatsApp</div>
                            <div style="color: #15803d;">+62 857 4339 9595</div>
                        </div>
                    </a>

                    <a href="mailto:zenhkm@gmail.com" class="d-flex align-items-center gap-3 text-decoration-none p-3 rounded-4" style="background: #eff6ff; border: 1px solid #bfdbfe; color: #2563eb; transition: transform 0.2s;">
                        <div style="background: #2563eb; color: white; padding: 12px; border-radius: 12px;">
                            <i data-lucide="mail"></i>
                        </div>
                        <div>
                            <div class="fw-bold" style="font-size: 1.1rem;">Email</div>
                            <div style="color: #1d4ed8;">zenhkm@gmail.com</div>
                        </div>
                    </a>
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
