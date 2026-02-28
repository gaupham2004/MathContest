<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/SMTP.php';
require_once __DIR__ . '/../PHPMailer/Exception.php';
require_once "pdo.php";

function sendForumNotification($pdo, $subject, $body, $excludeUserId) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'gaupham2004@gmail.com';        
        $mail->Password = 'slxq ddnn djlm jbcq'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('gaupham2004@gmail.com', 'Mathema Forum');
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ]
        ];

        $stmt = $pdo->prepare("SELECT email FROM users WHERE id != ?");
        $stmt->execute([$excludeUserId]);
        $emails = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($emails as $email) {
            $mail->clearAddresses(); 
            $mail->addAddress($email);
            $mail->send();
        }
    } catch (Exception $e) {
        error_log("Forum notification error: " . $mail->ErrorInfo);
    }
}
