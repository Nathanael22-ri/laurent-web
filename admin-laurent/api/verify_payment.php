<?php
header('Content-Type: application/json');
require_once '../db.php';

// Membaca data JSON yang dikirimkan oleh Alpine.js via fungsi fetch()
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['order_id'])) {
    die(json_encode(['status' => 'error', 'message' => 'ID pesanan tidak ditemukan.']));
}

$orderId = $data['order_id'];

try {
    // Update status pesanan menjadi 'paid'
    // Catatan: Jika menggunakan fitur session login nanti, kolom 'verified_by' bisa diisi dengan ID admin yang sedang login
    $stmt = $pdo->prepare("UPDATE orders SET status = 'paid' WHERE id = :id");
    $stmt->bindParam(':id', $orderId, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pembayaran berhasil diverifikasi.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Gagal memperbarui status.'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
    ]);
}
?>