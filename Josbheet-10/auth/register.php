<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika pengguna sudah login, alihkan langsung ke halaman utama
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$pageTitle = 'Registrasi Petugas';
include '../includes/header.php';
?>

<div class="container margin-top">
    <h2>Registrasi Petugas Baru</h2>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>

    <form action="proses_register.php" method="POST" class="form-container">
        <div class="form-group">
            <label for="nama">Nama Lengkap:</label>
            <input type="text" id="nama" name="nama" required autocomplete="off">
        </div>

        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required autocomplete="off">
        </div>

        <div class="form-group">
            <label for="password">Password (minimal 6 karakter):</label>
            <input type="password" id="password" name="password" minlength="6" required>
        </div>

        <div class="form-group">
            <label for="confirm_password">Konfirmasi Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" minlength="6" required>
        </div>

        <button type="submit" class="btn btn-primary">Daftar</button>
    </form>

    <p style="margin-top: 1rem;">
        Sudah punya akun? <a href="login.php">Login di sini</a>
    </p>
</div>

<?php include '../includes/footer.php'; ?>