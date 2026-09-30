<?php
// api/register.php
header('Content-Type: application/json');
require_once '../db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Permintaan tidak valid."]);
    exit;
}

$fullname = trim($data['fullname']);
$username = trim($data['username']);
$password = $data['password'];
$accessCode = strtoupper(trim($data['accessCode']));

// Keamanan Lapis 2: Validasi Kode Akses di sisi server
if ($accessCode !== 'LAURENT2026') {
    echo json_encode(["status" => "error", "message" => "Kode Otoritas Pendaftaran salah!"]);
    exit;
}

try {
    // Cek apakah username sudah dipakai
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    if ($stmt->fetch()) {
        echo json_encode(["status" => "error", "message" => "Username tersebut sudah digunakan. Pilih yang lain."]);
        exit;
    }

    // Enkripsi kata sandi
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Simpan ke database
    $stmt = $pdo->prepare("INSERT INTO users (fullname, username, password, role) VALUES (:fullname, :username, :password, 'admin')");
    $stmt->execute([
        'fullname' => $fullname,
        'username' => $username,
        'password' => $hashedPassword
    ]);

    echo json_encode(["status" => "success", "message" => "Pendaftaran berhasil!"]);
    
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Kesalahan server: " . $e->getMessage()]);
}
?>