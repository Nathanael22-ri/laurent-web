<?php
header('Content-Type: application/json');
require_once '../db.php'; // Pastikan path ini mengarah ke file koneksi Anda

// Menangkap data JSON yang dikirim dari Frontend (Alpine.js)
$data = json_decode(file_get_contents("php://input"), true);

// Validasi sederhana
if (!$data || empty($data['cart'])) {
    die(json_encode(['status' => 'error', 'message' => 'Data pesanan kosong atau tidak valid.']));
}

try {
    // Mulai Transaksi Database
    $pdo->beginTransaction();

    // 1. Simpan Data Pelanggan (ke tabel customers)
    $stmtCust = $pdo->prepare("INSERT INTO customers (name, phone, address, created_at) VALUES (:name, :phone, :address, NOW())");
    $stmtCust->execute([
        ':name' => $data['customer']['name'],
        ':phone' => $data['customer']['phone'],
        ':address' => $data['customer']['address']
    ]);
    $customerId = $pdo->lastInsertId(); // Ambil ID pelanggan yang baru saja dibuat

    // 2. Simpan Data Pesanan Utama (ke tabel orders)
    // Membuat nomor invoice unik otomatis (Contoh: LAU-20261025-A1B2C)
    $invoiceNumber = 'LAU-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 5));
    $grandTotal = $data['grandTotal'];
    $paymentMethod = strtoupper($data['paymentMethod']); // BCA atau QRIS
    $status = 'pending'; // Status default untuk diverifikasi admin nanti

    $stmtOrder = $pdo->prepare("INSERT INTO orders (invoice_number, customer_id, grand_total, payment_method, status, created_at) VALUES (:invoice, :customer_id, :total, :method, :status, NOW())");
    $stmtOrder->execute([
        ':invoice' => $invoiceNumber,
        ':customer_id' => $customerId,
        ':total' => $grandTotal,
        ':method' => $paymentMethod,
        ':status' => $status
    ]);
    $orderId = $pdo->lastInsertId(); // Ambil ID pesanan yang baru dibuat

    // 3. Simpan Rincian Barang (ke tabel orders_item)
    $stmtItem = $pdo->prepare("INSERT INTO orders_item (order_id, product_id, qty, price) VALUES (:order_id, :product_id, :qty, :price)");
    
    foreach ($data['cart'] as $item) {
        $stmtItem->execute([
            ':order_id' => $orderId,
            ':product_id' => $item['id'],
            ':qty' => $item['qty'],
            ':price' => $item['price']
        ]);
    }

    // Jika semua berhasil, simpan permanen ke database
    $pdo->commit();

    // Kembalikan nomor invoice ke frontend untuk ditampilkan
    echo json_encode([
        'status' => 'success',
        'invoice_number' => $invoiceNumber,
        'message' => 'Pesanan berhasil dibuat'
    ]);

} catch (PDOException $e) {
    // Batalkan semua query jika terjadi eror
    $pdo->rollBack();
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal menyimpan pesanan: ' . $e->getMessage()
    ]);
}
?>