<?php
header('Content-Type: application/json');
require_once '../db.php'; 

try {
    // Menggunakan subquery untuk spesifikasi agar aman dari error ONLY_FULL_GROUP_BY
    $query = "
        SELECT 
            p.id, 
            p.name, 
            c.slug AS category, 
            p.short_desc AS `desc`, 
            p.full_desc AS fullDesc, 
            p.price, 
            p.image_url AS image, 
            p.images AS images_string,
            p.badge,
            (SELECT GROUP_CONCAT(specs_text SEPARATOR '|||') FROM product_specs WHERE product_id = p.id) AS specs_string
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.is_active = 1
        ORDER BY p.id DESC
    ";
    
    $stmt =$pdo->prepare($query);$stmt->execute();
    $products =$stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as &$product) {
        $product['price'] = (float)$product['price'];
        
        // Parsing Spesifikasi
if (!empty($product['specs_string'])) {
    $product['specs'] = explode('|||', $product['specs_string']);
} else {
    $product['specs'] = [];
}
unset($product['specs_string']);

// Parsing Multi Gambar / Galeri
if (!empty($product['images_string'])) {
    $product['images'] = explode('|||', $product['images_string']);
} else {
    $product['images'] = [$product['image']];
}
unset($product['images_string']);
    }

    echo json_encode([
        'status' => 'success',
        'data' => $products
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal mengambil katalog produk: ' . $e->getMessage()
    ]);
}
?>