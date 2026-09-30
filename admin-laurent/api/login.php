<?php
// api/login.php
header('Content-Type: application/json');
require_once '../db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Permintaan tidak valid."]);
    exit;
}

$username = trim($data['username']);
$password = $data['password'];

try {
    // Cari user berdasarkan username
    $stmt = $pdo->prepare("SELECT id, fullname, username, password, role FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifikasi keberadaan user dan kecocokan password
    if ($user && password_verify($password, $user['password'])) {
        // Hapus field password agar tidak terkirim kembali ke frontend
        unset($user['password']);
        
        echo json_encode([
            "status" => "success", 
            "message" => "Login berhasil!",
            "user" => $user // Mengirim data nama untuk ditampilkan di Navbar
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Username atau kata sandi tidak cocok."]);
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Kesalahan server: " . $e->getMessage()]);
}
?>