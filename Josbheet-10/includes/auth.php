<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Cek Remember Me: HANYA panggil koneksi.php jika session kosong TAPI cookie remember_me ada
if (empty($_SESSION['user_id']) && !empty($_COOKIE['remember_me'])) {
    $cookie_parts = explode(':', $_COOKIE['remember_me']);

    if (count($cookie_parts) === 2) {
        // Panggil koneksi HANYA ketika dibutuhkan untuk validasi cookie
        require_once __DIR__ . '/../koneksi.php';
        
        list($selector, $validator) = $cookie_parts;

        try {
            $stmt = $pdo->prepare('SELECT * FROM user_tokens WHERE selector = :selector AND expires_at > NOW()');
            $stmt->execute([':selector' => $selector]);
            $token = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($token && hash_equals($token['hashed_validator'], hash('sha256', $validator))) {
                $stmt_user = $pdo->prepare('SELECT * FROM users WHERE id = :id');
                $stmt_user->execute([':id' => $token['user_id']]);
                $user = $stmt_user->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    $_SESSION['user_id']  = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['nama']     = $user['nama'];
                    $_SESSION['role']     = $user['role'];
                }
            }
        } catch (PDOException $e) {
            // Abaikan error jika database bermasalah saat cek cookie
        }
    }
}

// 2. Fungsi pembatas hak akses (Guard)
if (!function_exists('check_role')) {
    function check_role(array $allowed_roles = []) {
        if (empty($_SESSION['user_id'])) {
            header('Location: ../auth/login.php?error=' . urlencode('Silakan login terlebih dahulu.'));
            exit; // <-- Langsung memotong eksekusi sebelum menyentuh koneksi database
        }

        if (!empty($allowed_roles) && !in_array($_SESSION['role'] ?? '', $allowed_roles, true)) {
            die('Akses ditolak! Anda tidak memiliki izin untuk mengakses halaman ini.');
        }
    }
}