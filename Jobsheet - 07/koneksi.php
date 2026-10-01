<?php
$host = 'localhost';
$db   = 'simpus_mini'; // Sesuai nama database di HeidiSQL
$user = 'root';
$pass = 'akuhisyam'; // Masukkan password MySQL yang berhasil login di HeidiSQL tadi[cite: 5]

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Koneksi ke database gagal: " . $e->getMessage());
}
?>