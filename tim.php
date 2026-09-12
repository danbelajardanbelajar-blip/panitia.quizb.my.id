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

                    // Send Email Notification menggunakan PHPMailer
                    try {
                        require_once __DIR__ . '/includes/mail_helper.php';
                        if (class_exists('MailHelper')) {
                            $ownerName = $_SESSION['user_name'] ?? 'Seseorang';
                            $subject = "Undangan Kolaborasi - Sistem Administrasi Panitia";
                            
                            $messageHTML = "
                            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;'>
                                <div style='background-color: #4f46e5; padding: 24px; text-align: center;'>
                                    <h2 style='color: white; margin: 0;'>Undangan Kolaborasi</h2>
                                </div>
                                <div style='padding: 24px; color: #333;'>
                                    <p>Halo,</p>
                                    <p>Anda telah diundang oleh <strong>{$ownerName}</strong> untuk berkolaborasi mengelola kepanitiaan di Sistem Administrasi Panitia.</p>
                                    <p>Sekarang Anda dapat melihat, menginput, dan mengelola data kegiatan secara bersama-sama.</p>
                                    
                                    <div style='text-align: center; margin: 30px 0;'>
                                        <a href='https://panitia.quizb.my.id/' style='background-color: #4f46e5; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold;'>Login Sekarang</a>
                                    </div>
                                    
                                    <p style='font-size: 14px; color: #666;'>
                                        <em>Panduan Singkat:</em> Setelah login menggunakan akun Google, klik tombol <strong>'Workspace Pribadi'</strong> di pojok kanan atas, lalu pilih Kepanitiaan {$ownerName}.
                                    </p>
                                    <br>
                                    <p>Terima kasih,<br>Tim Admin Panitia</p>
                                </div>
                            </div>";
                            
                            MailHelper::sendNotification($email, $subject, $messageHTML);
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
