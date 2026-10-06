<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/csrf.php';

$page_title = 'Registrasi Petugas';
include '../includes/header.php';
?>

<div class="container margin-top">
    <h2>Registrasi Petugas Baru</h2>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?php echo e($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <form action="proses_register.php" method="POST" class="form-container">
        <?php echo csrf_field(); ?>

        <div class="form-group">
            <label for="nama">Nama Lengkap:</label>
            <input type="text" id="nama" name="nama" value="<?php echo e($_POST['nama'] ?? ''); ?>" required autocomplete="off">
        </div>

        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo e($_POST['username'] ?? ''); ?>" required autocomplete="off">
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" minlength="6" required>
        </div>

        <div class="form-group">
            <label for="confirm_password">Konfirmasi Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" minlength="6" required>
        </div>

        <button type="submit" class="btn btn-primary">Daftar</button>
    </form>
</div>