<?php
// Mencegah error jika dipanggil berulang kali
if (class_exists('MailHelper')) return;

// Mencari file PHPMailer
$possiblePaths = [
    '/home/quic1934/public_html/vendor/phpmailer/phpmailer/src/',
    '/home/quic1934/public_html/vendor/phpmailer/src/',
    dirname(__DIR__, 2) . '/vendor/phpmailer/phpmailer/src/',
    dirname(__DIR__, 2) . '/vendor/phpmailer/src/',
    $_SERVER['DOCUMENT_ROOT'] . '/../../vendor/phpmailer/phpmailer/src/',
    $_SERVER['DOCUMENT_ROOT'] . '/../vendor/phpmailer/phpmailer/src/'
];

foreach ($possiblePaths as $path) {
    if (file_exists($path . 'PHPMailer.php')) {
        require_once $path . 'Exception.php';
        require_once $path . 'PHPMailer.php';
        require_once $path . 'SMTP.php';
        break;
    }
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailHelper {
    public static function sendNotification($toEmail, $subject, $messageHTML) {
        if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            error_log('PHPMailer class not found.');
            return false;
        }

        $mail = new PHPMailer(true);
        try {
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
            return true;
        } catch (\Exception $e) {
            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}
