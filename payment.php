<?php
session_start();
require_once "pdo.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['purchase_units'][0]['amount']['value'], $data['id'], $data['payer'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
    exit;
}

$amount = $data['purchase_units'][0]['amount']['value'];
$status = $data['status'] ?? 'COMPLETED';
$orderId = $data['id'];
$capturedAtRaw = $data['create_time'] ?? date('Y-m-d H:i:s');
$capturedAt = date('Y-m-d H:i:s', strtotime($capturedAtRaw));
$payerEmail = $data['payer']['email_address'] ?? null;
$payerId = $data['payer']['payer_id'] ?? null;

try {
    $stmt = $pdo->prepare("INSERT INTO transactions (amount, status) VALUES (:amount, :status)");
    $stmt->execute([
        ':amount' => $amount,
        ':status' => $status
    ]);
    $transactionId = $pdo->lastInsertId();

    if (!$transactionId) {
        throw new Exception("Failed to get transaction ID");
    }

    $stmt = $pdo->prepare("INSERT INTO paypal_payments 
        (transaction_id, paypal_order_id, payer_email, payer_id, captured_at) 
        VALUES (:transaction_id, :order_id, :payer_email, :payer_id, :captured_at)");
    $stmt->execute([
        ':transaction_id' => $transactionId,
        ':order_id' => $orderId,
        ':payer_email' => $payerEmail,
        ':payer_id' => $payerId,
        ':captured_at' => $capturedAt
    ]);

    $_SESSION['transaction_id'] = $transactionId;

    echo json_encode(['success' => true, 'transaction_id' => $transactionId]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}
