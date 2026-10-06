<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/koneksi.php';
// ...

// ATURAN HAK AKSES: Hanya 'admin' yang boleh menghapus data
check_role(['admin']);

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare('DELETE FROM anggota WHERE id = :id');
        $stmt->execute([':id' => $id]);
        
        header('Location: list.php?success=' . urlencode('Data anggota berhasil dihapus!'));
        exit;
    } catch (PDOException $e) {
        die('Gagal menghapus data: ' . $e->getMessage());
    }
} else {
    header('Location: list.php');
    exit;
}