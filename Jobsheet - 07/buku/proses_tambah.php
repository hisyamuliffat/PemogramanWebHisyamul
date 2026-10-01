<?php
session_start();
require_once '../koneksi.php'; // Terhubung ke database simpus_mini

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul     = trim($_POST['judul'] ?? '');
    $pengarang = trim($_POST['pengarang'] ?? '');
    $tahun     = $_POST['tahun'] ?? '';
    $isbn      = trim($_POST['isbn'] ?? '');
    $stok      = $_POST['stok'] ?? '';
    $kategori  = trim($_POST['kategori'] ?? '');

    // Validasi server-side
    $errors = [];
    if ($judul === '') {
        $errors[] = "Judul wajib diisi.";
    }
    if ($pengarang === '') {
        $errors[] = "Pengarang wajib diisi.";
    }
    if (!is_numeric($tahun) || $tahun < 1900 || $tahun > 2026) {
        $errors[] = "Tahun harus di antara 1900-2026.";
    }
    if ($isbn !== '' && !preg_match('/^[0-9-]+$/', $isbn)) {
        $errors[] = "ISBN hanya boleh berisi angka dan tanda hubung.";
    }
    if (!is_numeric($stok) || $stok < 0) {
        $errors[] = "Stok tidak boleh negatif.";
    }

    if (!empty($errors)) {
        $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
        $_SESSION['old']   = $_POST;
        header('Location: tambah.php');
        exit;
    }

    try {
        // Simpan ke tabel buku di MySQL
        $stmt = $pdo->prepare("INSERT INTO buku (judul, pengarang, tahun, isbn, stok, kategori) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$judul, $pengarang, (int)$tahun, $isbn, (int)$stok, $kategori]);

        $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Buku berhasil ditambahkan.'];
        header('Location: list.php');
        exit;

    } catch (PDOException $e) {
        die("Terjadi kesalahan database: " . $e->getMessage());
    }
}
?>