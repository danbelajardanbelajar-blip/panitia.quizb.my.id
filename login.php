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
$redirectUri = rtrim($_ENV['APP_URL'] ?? 'http://localhost/panitia', '/') . '/login.php';

// Handle Google OAuth Callback
if (isset($_GET['code'])) {
    if (isset($_GET['state']) && $_GET['state'] !== $_SESSION['oauth_state']) {
        die('Invalid state parameter.');
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
    <title>Login - Sistem Administrasi Panitia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            max-width: 400px;
            width: 100%;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            background: white;
            text-align: center;
        }
        .google-btn {
            background-color: #fff;
            color: #757575;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-weight: 500;
            padding: 10px 20px;
            width: 100%;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .google-btn:hover {
            background-color: #f1f1f1;
            color: #555;
        }
        .google-icon {
            width: 24px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h3 class="mb-3">Sistem Administrasi Panitia</h3>
    <p class="text-muted mb-4">Masuk untuk mengelola kegiatan dan anggaran kepanitiaan Anda.</p>
    
    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <a href="<?= $authUrl ?>" class="google-btn">
        <svg class="google-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
            <path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8c-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4C12.955 4 4 12.955 4 24s8.955 20 20 20s20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z"/>
            <path fill="#FF3D00" d="M6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4C16.318 4 9.656 8.337 6.306 14.691z"/>
            <path fill="#4CAF50" d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238C29.211 35.091 26.715 36 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z"/>
            <path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303c-.792 2.237-2.231 4.166-4.087 5.571c.001-.001.002-.001.003-.002l6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z"/>
        </svg>
        Masuk dengan Google
    </a>
</div>

</body>
</html>
