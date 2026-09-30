<?php
header('Content-Type: application/json');

// Pastikan path ini mengarah ke file db.php Anda dengan tepat.
// Jika db.php ada di dalam folder admin-laurent, gunakan '../admin-/db.php'
require_once '../db.php'; 

try {
    // Mengambil produk aktif beserta relasi slug kategori dan menggabungkan spesifikasi menjadi satu string
    $query = "
        SELECT 
            p.id, 
            p.name, 
            c.slug AS category, 
            p.short_desc AS `desc`, 
            p.full_desc AS fullDesc, 
            p.price, 
            p.image_url AS image, 
            p.badge,
            GROUP_CONCAT(ps.specs_text SEPARATOR '|||') AS specs_string
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN product_specs ps ON p.id = ps.product_id
        WHERE p.is_active = 1
        GROUP BY p.id
        ORDER BY p.id DESC
    ";
    
    $stmt =$pdo->prepare($query);$stmt->execute();
    
    $products =$stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as &$product) {
        $product['price'] = (float)$product['price'];
        
        // Memecah string spesifikasi kembali menjadi array untuk Alpine.js
        if (!empty($product['specs_string'])) {
            $product['specs'] = explode('\vert{}\vert{}\vert{}',$product['specs_string']);
        } else {
            $product['specs'] = [];
        }
        
        // Hapus key sementara agar response JSON bersih
        unset($product['specs_string']);
    }

    echo json_encode([
        'status' => 'success',
        'data' => $products
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal mengambil data katalog: ' . $e->getMessage()
    ]);
}
?>