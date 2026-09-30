<?php
header('Content-Type: application/json');
require_once '../db.php'; // Sesuaikan path jika perlu

try {
    // 1. Total Pendapatan (Bulan Ini) - Hanya pesanan yang sukses/dibayar
    $stmtRev = $pdo->query("SELECT SUM(grand_total) as total FROM orders WHERE status != 'pending' AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
    $revenue = $stmtRev->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

    // 2. Pesanan Sukses (Bulan Ini)
    $stmtSuccess = $pdo->query("SELECT COUNT(*) as count FROM orders WHERE status != 'pending' AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
    $successOrders = $stmtSuccess->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

    // 3. Menunggu Verifikasi
    $stmtPending = $pdo->query("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'");
    $pendingOrders = $stmtPending->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

    // 4. Total Pelanggan Baru (Bulan Ini)
    $stmtCust = $pdo->query("SELECT COUNT(*) as count FROM customers WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
    $newCustomers = $stmtCust->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

    // 5. Data Grafik (7 Hari Terakhir)
    $chartQuery = "
        SELECT DATE(created_at) as date, SUM(grand_total) as total
        FROM orders
        WHERE status != 'pending' AND created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 6 DAY)
        GROUP BY DATE(created_at)
        ORDER BY date ASC
    ";
    $stmtChart = $pdo->query($chartQuery);
    $chartRaw = $stmtChart->fetchAll(PDO::FETCH_ASSOC);

    $chartData = [];
    $chartLabels = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $chartLabels[] = date('d M', strtotime($date));
        $total = 0;
        foreach ($chartRaw as $row) {
            if ($row['date'] === $date) {
                $total = (float)$row['total'];
                break;
            }
        }
        $chartData[] = $total;
    }

    // 6. Aktivitas Terbaru (5 Pesanan Terakhir)
    $stmtActivity = $pdo->query("
        SELECT o.id, o.invoice_number, o.grand_total, o.status, o.created_at, c.name as customer_name
        FROM orders o
        JOIN customers c ON o.customer_id = c.id
        ORDER BY o.created_at DESC
        LIMIT 5
    ");
    $activities = $stmtActivity->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => [
            'revenue' => (float)$revenue,
            'success_orders' => (int)$successOrders,
            'pending_orders' => (int)$pendingOrders,
            'new_customers' => (int)$newCustomers,
            'chart_labels' => $chartLabels,
            'chart_data' => $chartData,
            'activities' => $activities
        ]
    ]);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>