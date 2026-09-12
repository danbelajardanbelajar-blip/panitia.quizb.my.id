<?php
// Mencegah error jika dipanggil berulang kali
if (class_exists('MailHelper')) return;

// Mencari file PHPMailer secara dinamis dengan naik folder
$phpMailerFound = false;
$currentDir = __DIR__;
for ($i = 0; $i < 6; $i++) {
    $checkPath1 = $currentDir . '/vendor/phpmailer/phpmailer/src/';
    $checkPath2 = $currentDir . '/vendor/phpmailer/src/';
    
    if (file_exists($checkPath1 . 'PHPMailer.php')) {
        require_once $checkPath1 . 'Exception.php';
        require_once $checkPath1 . 'PHPMailer.php';
        require_once $checkPath1 . 'SMTP.php';
        $phpMailerFound = true;
        break;
    } elseif (file_exists($checkPath2 . 'PHPMailer.php')) {
        require_once $checkPath2 . 'Exception.php';
        require_once $checkPath2 . 'PHPMailer.php';
        require_once $checkPath2 . 'SMTP.php';
        $phpMailerFound = true;
        break;
    }
    $currentDir = dirname($currentDir);
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailHelper {
    public static function sendNotification($toEmail, $subject, $messageHTML) {
        $logFile = __DIR__ . '/../mail_debug.log';
        file_put_contents($logFile, "\n=== MENGIRIM EMAIL KE: $toEmail PADA " . date('Y-m-d H:i:s') . " ===\n", FILE_APPEND);
        
        if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            $msg = "STATUS: GAGAL! Class PHPMailer tidak ditemukan di path yang ditentukan.\n";
            file_put_contents($logFile, $msg, FILE_APPEND);
            error_log('PHPMailer class not found.');
            return false;
        }

        $mail = new PHPMailer(true);
        try {
            // Konfigurasi Debugging
            $mail->SMTPDebug = 2; // 2 = Server & client messages
            $mail->Debugoutput = function($str, $level) use ($logFile) {
                file_put_contents($logFile, "[$level] $str\n", FILE_APPEND);
            };

            $mail->isSMTP();
            $mail->Host       = 'maktabah.quizb.my.id'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = 'admin@maktabah.quizb.my.id'; 
            $mail->Password   = 'i3SPCi7r5998@kH'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
            $mail->Port       = 465; 
            
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->setFrom('admin@maktabah.quizb.my.id', 'Admin Panitia');

            if (is_array($toEmail)) {
                foreach ($toEmail as $email) {
                    $mail->addAddress($email);
                }
            } else {
                $mail->addAddress($toEmail);
            }

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $messageHTML;

            $mail->send();
            file_put_contents($logFile, "STATUS: BERHASIL TERKIRIM\n", FILE_APPEND);
            return true;
        } catch (\Exception $e) {
            $errorMsg = "STATUS: GAGAL! Error: {$mail->ErrorInfo} | Exception: " . $e->getMessage() . "\n";
            file_put_contents($logFile, $errorMsg, FILE_APPEND);
            error_log($errorMsg);
            return false;
        }
    }
}
