<?php
$host     = 'localhost';
$port     = '5432'; // Port default PostgreSQL
$db       = 'simpus_mini';
$user     = 'postgres';
$password = 'akuhisyam'; // GANTI dengan password akun postgres Anda saat install PostgreSQL/pgAdmin

$dsn = "pgsql:host=$host;port=$port;dbname=$db";

try {
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Koneksi ke PostgreSQL gagal: " . $e->getMessage());
}