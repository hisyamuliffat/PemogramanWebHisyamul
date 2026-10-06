<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../includes/helpers.php';
require_once '../includes/csrf.php';
require_once '../koneksi.php';

$max_attempts = 3;
$lockout_time = 60; // 60 detik (1 menit)

// 1. Cek apakah pengguna masih dalam status diblokir
if (isset($_SESSION['lockout_time'])) {
    $elapsed = time() - $_SESSION['lockout_time'];
    if ($elapsed < $lockout_time) {
        $remaining = $lockout_time - $elapsed;
        header('Location: login.php?error=' . urlencode("Terlalu banyak percobaan gagal. Coba lagi dalam $remaining detik."));
        exit;
    } else {
        // Lewat durasi blokir, reset penanda
        unset($_SESSION['lockout_time']);
        $_SESSION['login_attempts'] = 0;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifikasi Token CSRF
    csrf_verify();

    $username    = trim($_POST['username'] ?? '');
    $password    = $_POST['password'] ?? '';
    $remember_me = isset($_POST['remember_me']);

    if (empty($username) || empty($password)) {
        header('Location: login.php?error=' . urlencode('Username dan password wajib diisi.'));
        exit;
    }

    try {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // LOGIN BERHASIL: Regenerasi ID Sesi untuk mencegah Session Fixation
            session_regenerate_id(true);

            // Reset penghitung percobaan gagal
            unset($_SESSION['login_attempts']);
            unset($_SESSION['lockout_time']);

            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama']     = $user['nama'];
            $_SESSION['role']     = $user['role'];

            // Proses Remember Me
            if ($remember_me) {
                $selector  = bin2hex(random_bytes(16));
                $validator = bin2hex(random_bytes(32));
                $hashed_validator = hash('sha256', $validator);
                $expires          = date('Y-m-d H:i:s', time() + (86400 * 30));

                $stmt_token = $pdo->prepare('INSERT INTO user_tokens (user_id, selector, hashed_validator, expires_at) VALUES (:user_id, :selector, :hashed_validator, :expires)');
                $stmt_token->execute([
                    ':user_id'          => $user['id'],
                    ':selector'         => $selector,
                    ':hashed_validator' => $hashed_validator,
                    ':expires'          => $expires
                ]);

                setcookie('remember_me', $selector . ':' . $validator, [
                    'expires'  => time() + (86400 * 30),
                    'path'     => '/',
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]);
            }

            header('Location: ../index.php');
            exit;
        } else {
            // LOGIN GAGAL: Tambah hitungan percobaan
            $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;

            if ($_SESSION['login_attempts'] >= $max_attempts) {
                $_SESSION['lockout_time'] = time(); // Catat waktu awal pemblokiran
                $msg = "Anda salah memasukkan password sebanyak $max_attempts kali. Akses ditangguhkan selama 1 menit.";
            } else {
                $sisa = $max_attempts - $_SESSION['login_attempts'];
                $msg = "Username atau password salah! Sisa percobaan: $sisa kali.";
            }

            header('Location: login.php?error=' . urlencode($msg));
            exit;
        }
    } catch (PDOException $e) {
        error_log("Login Error: " . $e->getMessage());
        header('Location: login.php?error=' . urlencode('Terjadi kesalahan pada sistem. Silakan coba lagi.'));
        exit;
    }
} else {
    header('Location: login.php');
    exit;
}