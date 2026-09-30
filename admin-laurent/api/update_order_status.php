<?php
header('Content-Type: application/json');
require_once '../db.php';

// Tangkap data JSON yang dikirimkan
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id']) || !isset($data['status'])) {
    die(json_encode(['status' => 'error', 'message' => 'Data id atau status tidak lengkap.']));
}

try {
    // Update status di database
    $stmt = $pdo->prepare("UPDATE orders SET status = :status WHERE id = :id");
    $stmt->execute([
        ':status' => $data['status'],
        ':id' => $data['id']
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Status pesanan berhasil diperbarui.']);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>