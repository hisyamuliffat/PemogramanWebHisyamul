<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Prefix relatif ke root proyek ini
$__jobsheetRoot = dirname(__DIR__);
$__scriptDir = dirname($_SERVER['SCRIPT_FILENAME']);
$__rel = ltrim(str_replace('\\', '/', substr($__scriptDir, strlen($__jobsheetRoot))), '/');
$base = $__rel === '' ? '' : str_repeat('../', substr_count($__rel, '/') + 1);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMPUS-Mini<?php echo isset($page_title) ? ' | ' . $page_title : ''; ?></title>
    <link rel="stylesheet" href="<?php echo $base; ?>assets/css/style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="nav-brand">
                <a href="<?php echo $base; ?>index.php">SIMPUS-Mini</a>
            </div>
            
            <ul class="nav-menu">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="<?php echo $base; ?>index.php">Beranda</a></li>
                    <li><a href="<?php echo $base; ?>buku/list.php">Daftar Buku</a></li>
                    <li><a href="<?php echo $base; ?>buku/tambah.php">Tambah Buku</a></li>
                    <li><a href="<?php echo $base; ?>anggota/list.php">Daftar Anggota</a></li>
                    <li><a href="<?php echo $base; ?>anggota/tambah.php">Tambah Anggota</a></li>
                    
                    <li class="user-profile">
                        <span class="user-name">Halo, <strong><?= htmlspecialchars($_SESSION['nama'] ?? 'Petugas') ?></strong></span>
                        <a href="<?php echo $base; ?>auth/logout.php" class="btn-logout">Logout</a>
                    </li>
                <?php else: ?>
                    <li><a href="<?php echo $base; ?>auth/login.php">Login</a></li>
                    <li><a href="<?php echo $base; ?>auth/register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>