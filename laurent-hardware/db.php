<?php
// db.php
$host = "127.0.0.1";
$user = "root";       // Sesuaikan dengan username database Anda
$pass = "";           // Sesuaikan dengan password database Anda
$dbname = "laurent_hw"; // Sesuaikan dengan nama database Anda

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // Set mode error PDO menjadi Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    die(json_encode([
        "status" => "error", 
        "message" => "Koneksi database gagal: " . $e->getMessage()
    ]));
}
?>