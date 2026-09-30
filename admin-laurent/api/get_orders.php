<?php
header('Content-Type: application/json');
require_once '../db.php'; // Memanggil koneksi database

// Cek apakah request berupa GET (Ambil Data) atau POST (Ubah Data)
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {

        // ==========================================
        // FUNGSI BARU: MENGAMBIL RINCIAN ITEM
        // ==========================================
        if (isset($_GET['action']) &&$_GET['action'] === 'get_items') {
            $order_id =$_GET['order_id'];
            
            // Asumsi tabel produk Anda bernama `products`
            $stmt = $pdo->prepare("
                SELECT oi.qty, oi.price, p.name, p.image_url AS image
                FROM orders_item oi
                JOIN products p ON oi.product_id = p.id
                WHERE oi.order_id = :order_id
            ");
            $stmt->execute([':order_id' =>$order_id]);
            
            echo json_encode([
                'status' => 'success', 
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ]);
            exit; // Hentikan eksekusi agar tidak lanjut ke bawah
        }

        // ==========================================
        // FUNGSI 1: MENGAMBIL DATA (GET)
        // ==========================================
        $query = "
            SELECT 
                o.id,
                o.invoice_number,
                o.invoice_number AS poNumber, 
                c.name AS customer_name,
                c.name AS customer,
                c.phone,
                c.address,
                o.grand_total,
                o.grand_total AS total,
                o.payment_method,
                o.payment_method AS method,
                o.status,
                o.created_at,
                DATE_FORMAT(o.created_at, '%d %b %Y %H:%i') AS date,
                (SELECT COALESCE(SUM(qty), 0) FROM orders_item WHERE order_id = o.id) AS items
            FROM orders o
            LEFT JOIN customers c ON o.customer_id = c.id
            ORDER BY o.created_at DESC
        ";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Memastikan tipe data angka terbaca dengan benar oleh JavaScript
        foreach ($orders as &$order) {
            $order['total'] = (float) $order['total'];
            $order['grand_total'] = (float) $order['grand_total'];
            $order['items'] = (int) $order['items'];
        }

        echo json_encode([
            'status' => 'success',
            'data' => $orders
        ]);

    } elseif ($method === 'POST') {
        // ==========================================
        // FUNGSI 2: MENGUBAH STATUS (POST)
        // ==========================================
        $data = json_decode(file_get_contents("php://input"), true);

        // Validasi input
        if (!isset($data['id']) || !isset($data['status'])) {
            die(json_encode(['status' => 'error', 'message' => 'Data id atau status tidak lengkap.']));
        }

        // Jalankan query update
        $stmt = $pdo->prepare("UPDATE orders SET status = :status WHERE id = :id");
        $stmt->execute([
            ':status' => $data['status'],
            ':id' => $data['id']
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Status pesanan berhasil diperbarui.']);
        
    } else {
        // Jika metode bukan GET atau POST
        echo json_encode(['status' => 'error', 'message' => 'Metode HTTP tidak didukung.']);
    }

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal memproses data: ' . $e->getMessage()
    ]);
}
?>