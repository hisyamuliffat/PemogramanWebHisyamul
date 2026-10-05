<?php
session_start();
require __DIR__ . '/../includes/auth.php'; // Memastikan user sudah login
require __DIR__ . '/../includes/koneksi.php';

// Validasi Role: Hanya 'admin' yang diperbolehkan menghapus data
if (($_SESSION['role'] ?? '') !== 'admin') {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Akses ditolak. Hanya Admin yang dapat menghapus anggota.'
    ];
    header('Location: list.php');
    exit;
}

// Hanya menerima request POST untuk mencegah penghapusan tak disengaja
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id = $_POST['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM anggota WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Anggota berhasil dihapus.'];
}

header('Location: list.php');
exit;