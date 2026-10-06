<?php
// Koneksi ke database
require_once 'koneksi.php'; // Sesuaikan path jika diletakkan di dalam folder lain

// Path ke file JSON data lama
$json_path = __DIR__ . '/data/buku.json';

// 1. Cek apakah file JSON ada
if (!file_exists($json_path)) {
    die("Error: File JSON tidak ditemukan di path: " . $json_path);
}

// 2. Baca isi file JSON
$json_data = file_get_contents($json_path);

// 3. Konversi (decode) JSON menjadi Array Associative PHP
$buku_list = json_decode($json_data, true);

if ($buku_list === null) {
    die("Error: Gagal membaca atau mengurai (decode) isi file JSON.");
}

echo "Memulai proses migrasi data...<br><br>";

$jumlah_sukses = 0;
$jumlah_gagal  = 0;

// Prepare query INSERT menggunakan PDO
$sql = "INSERT INTO buku (judul, pengarang, tahun, isbn, stok, kategori) 
        VALUES (:judul, :pengarang, :tahun, :isbn, :stok, :kategori)";
$stmt = $pdo->prepare($sql);

// 4. Looping setiap data buku dari JSON dan masukkan ke database
foreach ($buku_list as $buku) {
    try {
        $stmt->execute([
            ':judul'     => $buku['judul'] ?? '',
            ':pengarang' => $buku['pengarang'] ?? '',
            ':tahun'     => $buku['tahun'] ?? 0,
            ':isbn'      => $buku['isbn'] ?? null,
            ':stok'      => $buku['stok'] ?? 0,
            ':kategori'  => $buku['kategori'] ?? null
        ]);
        
        echo "✅ Berhasil migrasi: <strong>" . htmlspecialchars($buku['judul']) . "</strong><br>";
        $jumlah_sukses++;
    } catch (PDOException $e) {
        echo "❌ Gagal migrasi: <strong>" . htmlspecialchars($buku['judul'] ?? 'Unknown') . "</strong> - Error: " . $e->getMessage() . "<br>";
        $jumlah_gagal++;
    }
}

echo "<hr>";
echo "<strong>Selesai!</strong> Total data berhasil diimpor: <strong>$jumlah_sukses</strong>, Gagal: <strong>$jumlah_gagal</strong>.";
?>