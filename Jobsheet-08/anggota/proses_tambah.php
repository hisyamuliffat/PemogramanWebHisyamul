<?php
session_start();
require_once '../koneksi.php'; // Terhubung ke database simpus_mini

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama       = trim($_POST['nama'] ?? '');
    $no_anggota = trim($_POST['no_anggota'] ?? '');
    $alamat     = trim($_POST['alamat'] ?? '');
    $no_hp      = trim($_POST['no_hp'] ?? '');

    try {
        // Prepare statement untuk INSERT data
        $stmt = $pdo->prepare("INSERT INTO anggota (nama, no_anggota, alamat, no_hp) VALUES (?, ?, ?, ?)");
        
        // Bungkus eksekusi query dalam try/catch
        $stmt->execute([$nama, $no_anggota, $alamat, $no_hp]);

        // Jika berhasil, set flash message sukses lalu redirect ke list.php
        $_SESSION['flash'] = [
            'type'    => 'success',
            'message' => 'Data anggota berhasil ditambahkan.'
        ];
        header('Location: list.php');
        exit;

    } catch (PDOException $e) {
        // Memeriksa SQLSTATE code '23000' (Integrity Constraint Violation / Duplicate Key)
        if ($e->getCode() == '23000') {
            $_SESSION['flash'] = [
                'type'    => 'danger',
                'message' => 'No. Anggota sudah dipakai, gunakan nomor lain.'
            ];
            // Menyimpan isian form agar tidak terhapus saat di-redirect kembali
            $_SESSION['old'] = $_POST;
            header('Location: tambah.php');
            exit;
        } else {
            // Error database lainnya yang tidak terduga
            die("Terjadi kesalahan database: " . $e->getMessage());
        }
    }
}
?>