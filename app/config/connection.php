<?php
$host = "localhost";
$db_name = "peminjaman_db";
$username = "root";
$password = "";

try {
    // Membuat koneksi PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    
    // Mengatur mode error agar memunculkan exception (Penting untuk debugging)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Mengatur fetch mode default menjadi object/assoc agar mudah digunakan
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Skenario uji jika gagal koneksi [cite: 64]
    die("Koneksi database gagal: " . $e->getMessage());
}
?>   