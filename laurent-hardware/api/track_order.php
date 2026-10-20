<?php
header('Content-Type: application/json');
require_once '../db.php';

$invoice = isset($_GET['invoice']) ? trim($_GET['invoice']) : '';

if (empty($invoice)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Masukkan nomor invoice terlebih dahulu.'
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT 
            invoice_number,
            grand_total,
            payment_method,
            status,
            verified_by,
            created_at,
            updated_at
        FROM orders 
        WHERE invoice_number = :invoice
        LIMIT 1
    ");
    
    $stmt->execute([':invoice' => $invoice]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo json_encode([
            'status' => 'not_found',
            'message' => 'Nomor invoice tidak ditemukan.'
        ]);
        exit;
    }

    // Mengubah status database menjadi keterangan status pelacakan
    $trackingSteps = [
        'is_paid' => ($order['status'] === 'paid'),
        'is_verified' => (!empty($order['verified_by'])),
        'is_canceled' => ($order['status'] === 'canceled')
    ];

    echo json_encode([
        'status' => 'success',
        'data' => array_merge($order, $trackingSteps)
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
    ]);
}
?>