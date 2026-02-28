<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/SMTP.php';
require_once __DIR__ . '/../PHPMailer/Exception.php';
require_once "pdo.php";

header('Content-Type: application/json');

$transaction_id = $_POST['transaction_id'] ?? null;

if ($transaction_id) {
    $stmt = $pdo->prepare("SELECT username, email FROM users WHERE transaction_id = :txn LIMIT 1");
    $stmt->execute([':txn' => $transaction_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $isTeam = false;
    $teamMembersHtml = "";

    if (!$user) {
        $stmt = $pdo->prepare("SELECT id, email FROM teams WHERE transaction_id = :txn LIMIT 1");
        $stmt->execute([':txn' => $transaction_id]);
        $team = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($team && !empty($team['email'])) {
            $isTeam = true;
            $user = [
                'username' => 'Team Participants',
                'email' => $team['email']
            ];

            $stmt = $pdo->prepare("SELECT member_name FROM team_members WHERE team_id = :team_id");
            $stmt->execute([':team_id' => $team['id']]);
            $members = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if ($members) {
                $teamMembersHtml = "<ul>";
                foreach ($members as $member) {
                    $teamMembersHtml .= "<li>" . htmlspecialchars($member) . "</li>";
                }
                $teamMembersHtml .= "</ul>";
            }
        }
    }

    if ($user && !empty($user['email'])) {
        $recipient_email = $user['email'];
        $username = htmlspecialchars($user['username']);

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'gaupham2004@gmail.com';
            $mail->Password   = 'your-new-app-password'; 
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('gaupham2004@gmail.com', 'Mathema Contest');
            $mail->addAddress($recipient_email, $username);

            $mail->isHTML(true);
            $mail->Subject = 'Mathema Contest - Payment Confirmation';

            $mailBody = "
                <h3>Hi {$username},</h3>
                <p>Thank you for registering for the Mathema Contest.</p>
                <p>Your payment has been received.</p>
                <p><strong>Transaction ID:</strong> {$transaction_id}</p>
            ";

            if ($isTeam && !empty($teamMembersHtml)) {
                $mailBody .= "<p><strong>Your Team Members:</strong></p>{$teamMembersHtml}";
            }

            $mailBody .= "<p>We look forward to your participation!</p>";

            $mail->Body = $mailBody;

            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ]
            ];

            $mail->send();
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $mail->ErrorInfo]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Email not found for this transaction.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Missing transaction ID.']);
}
