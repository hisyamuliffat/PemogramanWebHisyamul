<?php
$host = 'localhost';
$port = '5432';          // Port default PostgreSQL
$db   = 'simpus_mini';   // Nama database PostgreSQL di pgAdmin
$user = 'postgres';      // Username PostgreSQL
$pass = 'akuhisyam';     // Masukkan password akun postgres Anda di pgAdmin

$dsn = "pgsql:host=$host;port=$port;dbname=$db";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Terjadi kesalahan pada sistem. Silakan coba beberapa saat lagi.");
}
?>