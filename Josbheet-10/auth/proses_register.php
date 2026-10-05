<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama             = trim($_POST['nama'] ?? '');
    $username         = trim($_POST['username'] ?? '');
    $password         = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validasi input
    if (empty($nama) || empty($username) || empty($password) || empty($confirm_password)) {
        header('Location: register.php?error=' . urlencode('Semua kolom wajib diisi.'));
        exit;
    }

    if (strlen($password) < 6) {
        header('Location: register.php?error=' . urlencode('Password minimal harus 6 karakter.'));
        exit;
    }

    if ($password !== $confirm_password) {
        header('Location: register.php?error=' . urlencode('Konfirmasi password tidak cocok.'));
        exit;
    }

    try {
        
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = :username');
        $stmt->execute([':username' => $username]);
        if ($stmt->fetch()) {
            header('Location: register.php?error=' . urlencode('Username sudah digunakan, pilih username lain.'));
            exit;
        }

        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        
        $stmt = $pdo->prepare('INSERT INTO users (nama, username, password, role) VALUES (:nama, :username, :password, :role)');
        $stmt->execute([
            ':nama'     => $nama,
            ':username' => $username,
            ':password' => $hashedPassword,
            ':role'     => 'petugas'
        ]);

        header('Location: login.php?msg=' . urlencode('Registrasi berhasil! Silakan login dengan akun Anda.'));
        exit;
    } catch (PDOException $e) {
        header('Location: register.php?error=' . urlencode('Terjadi kesalahan saat registrasi: ' . $e->getMessage()));
        exit;
    }
} else {
    header('Location: register.php');
    exit;
}