<?php
header('Content-Type: application/json');

// Pastikan path ini mengarah ke file db.php yang benar.
// Jika db.php ada di dalam folder admin-laurent, gunakan path di bawah ini:
require_once '../db.php'; 

try {
    // Mengambil produk yang is_active = 1 (aktif). 
    // Menggunakan AS untuk menyesuaikan nama kunci dengan Alpine.js
    $query = "
        SELECT 
            id, 
            name, 
            short_desc AS `desc`, 
            price, 
            image_url AS image, 
            badge 
        FROM products 
        WHERE is_active = 1 
        ORDER BY id DESC
    ";
    
    $stmt =$pdo->prepare($query);$stmt->execute();
    
    $products =$stmt->fetchAll(PDO::FETCH_ASSOC);

    // Konversi tipe data harga menjadi integer/float agar bisa dikalkulasi oleh JavaScript
    foreach ($products as &$product) {
        $product['price'] = (float)$product['price'];
    }

    echo json_encode([
        'status' => 'success',
        'data' => $products
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal mengambil data produk: ' . $e->getMessage()
    ]);
}
?>