<?php
require_once __DIR__ . '/includes/auth_helper.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/User.php';

// If already logged in
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$clientId = $_ENV['GOOGLE_CLIENT_ID'] ?? '';
$clientSecret = $_ENV['GOOGLE_CLIENT_SECRET'] ?? '';

// Gunakan host dinamis agar tidak terjadi masalah antara www dan non-www yang menghilangkan session
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$redirectUri = $protocol . $host . '/login.php';

// Handle Google OAuth Callback
if (isset($_GET['code'])) {
    if (isset($_GET['state']) && $_GET['state'] !== ($_SESSION['oauth_state'] ?? '')) {
        die('Invalid state parameter. Hal ini biasanya terjadi jika Anda membuka web menggunakan "www" namun URL redirect Google tidak menggunakan "www" (atau sebaliknya) sehingga sesi terputus. Silakan kembali ke halaman utama dan login ulang tanpa mengetik www.');
    }

    $code = $_GET['code'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'code' => $code,
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'redirect_uri' => $redirectUri,
        'grant_type' => 'authorization_code'
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $tokenInfo = json_decode($response, true);

    if (isset($tokenInfo['access_token'])) {
        $accessToken = $tokenInfo['access_token'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/oauth2/v2/userinfo');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $userInfo = json_decode($response, true);

        if (isset($userInfo['id'])) {
            $googleId = $userInfo['id'];
            $email = $userInfo['email'];
            $name = $userInfo['name'];
            $avatar = $userInfo['picture'] ?? null;

            $user = User::findByGoogleId($googleId);
            if ($user) {
                User::update($user['id'], ['email' => $email, 'name' => $name, 'avatar' => $avatar]);
                $user['name'] = $name;
                $user['email'] = $email;
                $user['avatar'] = $avatar;
            } else {
                $userId = User::create([
                    'google_id' => $googleId,
                    'email' => $email,
                    'name' => $name,
                    'avatar' => $avatar
                ]);
                $user = [
                    'id' => $userId,
                    'name' => $name,
                    'email' => $email,
                    'avatar' => $avatar
                ];
            }

            loginUser($user);
            header('Location: dashboard.php');
            exit;
        }
    }
    
    $error = "Gagal masuk dengan Google. Silakan coba lagi.";
}

// Prepare OAuth URL
$_SESSION['oauth_state'] = bin2hex(random_bytes(16));
$authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
    'client_id' => $clientId,
    'redirect_uri' => $redirectUri,
    'response_type' => 'code',
    'scope' => 'email profile',
    'state' => $_SESSION['oauth_state']
]);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Administrasi Panitia</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --surface: #ffffff;
            --background: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--background);
            color: var(--text-main);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .login-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        /* Left Side: Branding / Visual */
        .login-visual {
            flex: 1;
            background: linear-gradient(135deg, #4f46e5 0%, #0ea5e9 100%);
            color: white;
            padding: 4rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        .visual-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('data:image/svg+xml;utf8,<svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="1.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100%" height="100%" fill="url(%23dots)"/></svg>');
            z-index: 1;
        }
        .visual-content {
            position: relative;
            z-index: 2;
            max-width: 500px;
        }
        .visual-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 4rem;
        }
        .visual-brand i {
            width: 32px;
            height: 32px;
        }
        .visual-title {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }
        .visual-subtitle {
            font-size: 1.125rem;
            opacity: 0.9;
            line-height: 1.6;
        }
        .visual-illustration {
            position: relative;
            z-index: 2;
            display: flex;
            gap: 20px;
            margin-top: 4rem;
            opacity: 0.8;
        }
        .visual-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Right Side: Login Form */
        .login-form-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--surface);
            padding: 2rem;
            position: relative;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            text-align: center;
        }
        .login-card-icon {
            width: 64px;
            height: 64px;
            background-color: #eef2ff;
            color: var(--primary);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem auto;
        }
        .login-card h2 {
            font-weight: 800;
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
            color: var(--text-main);
        }
        .login-card p {
            color: var(--text-muted);
            margin-bottom: 2.5rem;
            font-size: 1rem;
        }
        
        .google-btn {
            background-color: #ffffff;
            color: #3c4043;
            border: 1px solid #dadce0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            font-weight: 600;
            font-size: 1rem;
            padding: 12px 24px;
            width: 100%;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .google-btn:hover {
            background-color: #f8f9fa;
            border-color: #d2e3fc;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transform: translateY(-1px);
        }
        .google-icon {
            width: 24px;
            height: 24px;
        }
        
        .secure-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 2rem;
            padding: 8px 16px;
            background: var(--background);
            border-radius: 50px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .login-visual {
                display: none; /* Hide visual on smaller screens */
            }
            .login-form-container {
                background-color: var(--background);
            }
            .login-card {
                background: var(--surface);
                padding: 40px;
                border-radius: 24px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            }
        }
        @media (max-width: 576px) {
            .login-card {
                padding: 30px 20px;
                background: transparent;
                box-shadow: none;
            }
            .login-form-container {
                align-items: flex-start;
                padding-top: 15vh;
            }
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    
    <!-- Left: Branding & Visuals (Desktop Only) -->
    <div class="login-visual">
        <div class="visual-overlay"></div>
        <div class="visual-content">
            <div class="visual-brand">
                <i data-lucide="layers"></i>
                Admin Panitia
            </div>
            <h1 class="visual-title">Satu tempat untuk semua kebutuhan administrasi.</h1>
            <p class="visual-subtitle">Kelola jadwal kegiatan, otomatisasi penyusunan RAB, dan catat arus keuangan secara profesional dalam satu dashboard.</p>
        </div>
        
        <div class="visual-illustration">
            <div class="visual-card">
                <i data-lucide="calendar-days" style="width:32px; height:32px; margin-bottom:16px;"></i>
                <h5>Kegiatan</h5>
                <small style="opacity:0.8;">Jadwal & Milestone</small>
            </div>
            <div class="visual-card">
                <i data-lucide="calculator" style="width:32px; height:32px; margin-bottom:16px;"></i>
                <h5>Anggaran</h5>
                <small style="opacity:0.8;">RAB otomatis</small>
            </div>
        </div>
    </div>

    <!-- Right: Login Form -->
    <div class="login-form-container">
        <div class="login-card">
            
            <div class="login-card-icon">
                <i data-lucide="fingerprint" style="width: 32px; height: 32px;"></i>
            </div>
            
            <h2>Selamat Datang</h2>
            <p>Silakan masuk menggunakan akun Google Anda untuk mengakses sistem kepanitiaan.</p>
            
            <?php if(isset($error)): ?>
                <div class="alert alert-danger mb-4 rounded-3 text-start" style="font-size: 0.9rem;">
                    <i data-lucide="alert-circle" style="width:16px; margin-right:4px;"></i> 
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <a href="<?= $authUrl ?>" class="google-btn">
                <svg class="google-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
                    <path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8c-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4C12.955 4 4 12.955 4 24s8.955 20 20 20s20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z"/>
                    <path fill="#FF3D00" d="M6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4C16.318 4 9.656 8.337 6.306 14.691z"/>
                    <path fill="#4CAF50" d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238C29.211 35.091 26.715 36 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z"/>
                    <path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303c-.792 2.237-2.231 4.166-4.087 5.571c.001-.001.002-.001.003-.002l6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z"/>
                </svg>
                Lanjutkan dengan Google
            </a>

            <div class="secure-badge">
                <i data-lucide="shield-check" style="width: 16px;"></i> Akses aman dan terenkripsi
            </div>
        </div>
    </div>

</div>

<script>
    lucide.createIcons();
</script>
</body>
</html>
