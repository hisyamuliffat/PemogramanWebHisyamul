<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika pengguna sudah login, alihkan langsung ke halaman utama
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Batas maksimal percobaan dan durasi blokir (dalam detik)
$max_attempts = 3;
$lockout_time = 60; // 1 menit

$is_locked = false;
$time_remaining = 0;

// Cek apakah user sedang dalam masa hukuman (lockout)
if (isset($_SESSION['lockout_time'])) {
    $elapsed = time() - $_SESSION['lockout_time'];
    if ($elapsed < $lockout_time) {
        $is_locked = true;
        $time_remaining = $lockout_time - $elapsed;
    } else {
        // Masa hukuman habis, reset hitungan
        unset($_SESSION['lockout_time']);
        $_SESSION['login_attempts'] = 0;
    }
}

$pageTitle = 'Login Petugas';
include '../includes/header.php';
?>

<div class="container margin-top">
    <h2>Login Petugas</h2>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>

    <?php if ($is_locked): ?>
        <div class="alert alert-danger">
            <strong>Terlalu banyak percobaan gagal!</strong><br>
            Akun ditangguhkan sementara. Silakan tunggu <strong><?= $time_remaining ?> detik</strong> lagi sebelum mencoba login kembali.
        </div>
    <?php else: ?>
        <form action="proses_login.php" method="POST" class="form-container">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required autocomplete="off">
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 8px; margin-top: 10px; margin-bottom: 15px;">
                <input type="checkbox" id="remember_me" name="remember_me" value="1">
                <label for="remember_me" style="margin: 0; cursor: pointer;">Ingat Saya (Remember Me)</label>
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    <?php endif; ?>

    <p style="margin-top: 1rem;">
        Belum punya akun? <a href="register.php">Daftar Akun Baru</a>
    </p>
</div>

<?php include '../includes/footer.php'; ?>