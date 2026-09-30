<?php
// Tentukan path ke file .env (asumsi db.php ada di dalam folder proyek/api)
// Sesuaikan '../.env' menjadi __DIR__ . '/.env' jika db.php ada di root folder
$envPath = __DIR__ . '/../.env'; 

if (file_exists($envPath)) {
    // Membaca file .env menggunakan fungsi bawaan PHP
    $envVariables = parse_ini_file($envPath);
    
    $host = $envVariables['DB_HOST'] ?? 'localhost';
    $dbname = $envVariables['DB_NAME'] ?? 'laurent_hw';
    $username = $envVariables['DB_USER'] ?? 'root';
    $password = $envVariables['DB_PASS'] ?? '';
} else {
    // Fallback jika file .env tidak ditemukan (misal di server live belum dibuat)
    $host = 'localhost';
    $dbname = 'laurent_hw';
    $username = 'root';
    $password = '';
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Set mode error PDO ke Exception agar mudah dilacak jika ada error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die(json_encode([
        'status' => 'error', 
        'message' => 'Koneksi database gagal: ' . $e->getMessage()
    ]));
}
?>