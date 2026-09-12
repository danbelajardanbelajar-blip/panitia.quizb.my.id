<?php
require_once __DIR__ . '/includes/auth_helper.php';
require_once __DIR__ . '/includes/csrf_helper.php';
require_once __DIR__ . '/config/database.php';

requireLogin();
$userId = getOriginalUserId(); // Only the real owner can add members to their own workspace
$action = $_GET['action'] ?? 'index';
$pdo = getDB();

// Switch Workspace Action
if ($action === 'switch') {
    $targetId = $_GET['id'] ?? null;
    if ($targetId) {
        if ($targetId == $userId) {
            unset($_SESSION['active_workspace_id']);
        } else {
            // Verify if user is invited
            $stmt = $pdo->prepare("SELECT 1 FROM workspace_members WHERE owner_id = ? AND email = ?");
            $stmt->execute([$targetId, $_SESSION['user_email']]);
            if ($stmt->fetch()) {
                $_SESSION['active_workspace_id'] = $targetId;
            }
        }
    }
    header('Location: dashboard.php');
    exit;
}

// Ensure the user hasn't switched workspace while trying to manage their own team
$isOwner = (getCurrentUserId() == getOriginalUserId());

if ($action === 'add' && $isOwner) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            die("CSRF token tidak valid.");
        }
        $email = trim($_POST['email']);
        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Avoid adding self
            if ($email !== $_SESSION['user_email']) {
                // Check if already added
                $stmt = $pdo->prepare("SELECT 1 FROM workspace_members WHERE owner_id = ? AND email = ?");
                $stmt->execute([$userId, $email]);
                if (!$stmt->fetch()) {
                    $stmt = $pdo->prepare("INSERT INTO workspace_members (owner_id, email) VALUES (?, ?)");
                    $stmt->execute([$userId, $email]);

                    // Send Email Notification
                    try {
                        if (function_exists('mail')) {
                            $ownerName = $_SESSION['user_name'] ?? 'Seseorang';
                            $subject = "Undangan Kolaborasi - Sistem Administrasi Panitia";
                            
                            $message = "Halo,\n\n";
                            $message .= "Anda telah diundang oleh {$ownerName} untuk berkolaborasi mengelola kepanitiaan di Sistem Administrasi Panitia.\n\n";
                            $message .= "Sekarang Anda dapat melihat, menginput, dan mengelola data kegiatan tersebut secara bersama-sama.\n\n";
                            $message .= "Silakan login menggunakan akun Google Anda melalui tautan berikut:\n";
                            $message .= "https://panitia.quizb.my.id/\n\n";
                            $message .= "Setelah login, klik tombol 'Workspace Pribadi' di pojok kanan atas untuk beralih ke Kepanitiaan {$ownerName}.\n\n";
                            $message .= "Terima kasih,\nTim Admin Panitia";
                            
                            $headers = "From: noreply@panitia.quizb.my.id\r\n";
                            $headers .= "Reply-To: noreply@panitia.quizb.my.id\r\n";
                            $headers .= "X-Mailer: PHP/" . phpversion();
                            
                            @mail($email, $subject, $message, $headers);
                        }
                    } catch (\Throwable $e) {
                        // Ignore mail error
                    }
                }
            }
        }
        header('Location: tim.php');
        exit;
    }
} elseif ($action === 'delete' && $isOwner) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            die("CSRF token tidak valid.");
        }
        $memberId = $_POST['id'] ?? null;
        if ($memberId) {
            $stmt = $pdo->prepare("DELETE FROM workspace_members WHERE id = ? AND owner_id = ?");
            $stmt->execute([$memberId, $userId]);
        }
        header('Location: tim.php');
        exit;
    }
}

// Fetch members of my workspace
$members = [];
if ($isOwner) {
    $stmt = $pdo->prepare("SELECT * FROM workspace_members WHERE owner_id = ? ORDER BY created_at DESC");
    $stmt->execute([$userId]);
    $members = $stmt->fetchAll();
}

$activeMenu = 'tim';
$title = 'Kolaborasi Tim - Sistem Administrasi Panitia';
include __DIR__ . '/views/tim/index.php';
