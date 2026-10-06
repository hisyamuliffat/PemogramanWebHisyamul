<?php
$host     = 'localhost';
$port     = '5432';
$db       = 'simpus_mini'; // Sesuaikan nama database
$user     = 'postgres';           // Sesuaikan username
$password = 'akuhisyam';      // Sesuaikan password

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db;";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    // Catat pesan error mentah ke log internal server
    error_log("Database Error: " . $e->getMessage());

    // TAMPILKAN PESAN GENERIK SAJA KE BROWSER (Sembunyikan pesan SQLSTATE)
    die("Terjadi kesalahan pada sistem. Silakan coba beberapa saat lagi.");
}