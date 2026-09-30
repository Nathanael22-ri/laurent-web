<?php
header('Content-Type: application/json');
require_once '../db.php';

try {
    // Hanya menghitung jumlah pesanan yang berstatus 'pending'
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
    
    echo json_encode([
        'status' => 'success', 
        'count' => (int)$count
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>