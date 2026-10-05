<?php
// Wajib dipanggil paling atas sebelum mengakses/memanipulasi session
session_start();

// Cek apakah ada request untuk Reset Data
if (isset($_GET['action']) && $_GET['action'] === 'reset') {
    // 1. Mengosongkan array $_SESSION di memori
    $_SESSION = array();

    // 2. Menghapus cookie session di browser (jika ada)
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // 3. Menghapus/menghancurkan data session di server
    session_destroy();

    // Redirect kembali ke halaman ini agar URL bersih (tanpa query ?action=reset)
    header('Location: debug_session.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Debug Session - SIMPUS</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f4f4f4; }
        .box { background: #fff; padding: 15px; border-radius: 5px; border: 1px solid #ccc; }
        .btn-danger {
            display: inline-block;
            background-color: #dc3545;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
        .btn-danger:hover { background-color: #bd2130; }
        .nav-links { margin-top: 15px; }
        .nav-links a { text-decoration: none; color: #0066cc; margin-right: 10px; }
    </style>
</head>
<body>

    <h2>Isi Raw $_SESSION Saat Ini:</h2>
    
    <div class="box">
        <pre><?php print_r($_SESSION); ?></pre>
    </div>

    <br>
    
    <!-- Tombol Reset Data -->
    <a href="debug_session.php?action=reset" class="btn-danger" onclick="return confirm('Yakin ingin menghapus seluruh data session?')">
        Reset Data (session_destroy)
    </a>

    <div class="nav-links">
        <hr>
        <a href="buku/tambah.php">Ke Form Tambah Buku</a> | 
        <a href="anggota/tambah.php">Ke Form Tambah Anggota</a>
    </div>

</body>
</html>