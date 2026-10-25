<?php
header('Content-Type: application/json');
require_once '../db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || empty($data['cart'])) {
    echo json_encode(['status' => 'error', 'message' => 'Data pesanan kosong atau tidak valid.']);
    exit;
}

// === TAMBAHKAN VALIDASI RECAPTCHA DI SINI ===
$recaptchaToken = $data['captchaToken'] ?? '';
$secretKey = '6Lf4NP4tAAAAAKcvrkn1ib1Y_Nc0PhyCcF3ICkcR'; // Ganti dengan Secret Key Google reCAPTCHA Anda

$verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
$response = file_get_contents($verifyUrl . '?secret=' . $secretKey . '&response=' . $recaptchaToken);
$responseData = json_decode($response, true);

if (!$responseData || !$responseData['success']) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Verifikasi reCAPTCHA gagal atau kadaluwarsa. Silakan centang ulang.'
    ]);
    exit;
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

    // 2. Membuat Nomor Invoice Otomatis (Format: LAU-YYYYMMDD-0, LAU-YYYYMMDD-1, dst.)
    $todayPrefix = 'LAU-' . date('Ymd') . '-';

    // Cek nomor invoice terakhir pada hari yang sama di database
    $stmtSeq = $pdo->prepare("
        SELECT invoice_number 
        FROM orders 
        WHERE invoice_number LIKE ? 
        ORDER BY id DESC 
        LIMIT 1
    ");
    $stmtSeq->execute([$todayPrefix . '%']);
    $lastOrder = $stmtSeq->fetch(PDO::FETCH_ASSOC);

    // Tentukan nomor urut: mulai dari 0 jika belum ada pesanan hari ini, atau increment dari yang terakhir
    $nextNumber = 0; 
    if ($lastOrder) {
        $lastSequence = (int) substr($lastOrder['invoice_number'], strlen($todayPrefix));
        $nextNumber = $lastSequence + 1;
    }

    $invoiceNumber = $todayPrefix . $nextNumber;

    // 3. Simpan Data Pesanan Utama (ke tabel orders)
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

    // 4. Simpan Rincian Barang (ke tabel orders_item)
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