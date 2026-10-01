<?php
session_start();
require_once '../koneksi.php';

// Tangkap kata kunci pencarian dari URL
$search = trim($_GET['q'] ?? '');

try {
    // Query pencarian server-side
    if ($search !== '') {
        $stmt = $pdo->prepare("SELECT id, judul, pengarang, tahun, isbn, stok, kategori, tanggal_ditambahkan 
                               FROM buku 
                               WHERE judul LIKE :keyword 
                               ORDER BY id DESC");
        $stmt->execute([':keyword' => '%' . $search . '%']);
    } else {
        $stmt = $pdo->prepare("SELECT id, judul, pengarang, tahun, isbn, stok, kategori, tanggal_ditambahkan 
                               FROM buku 
                               ORDER BY id DESC");
        $stmt->execute();
    }
    $daftar_buku = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Gagal mengambil data: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMPUS-Mini | Daftar Buku</title>
    <style>
        * { box-sizing: border-box; font-family: system-ui, -apple-system, sans-serif; }
        body { margin: 0; background-color: #f4f6f9; color: #333; }
        
        /* Navbar Biru Khas SIMPUS-Mini */
        .navbar {
            background-color: #1e5f8a;
            color: white;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar .brand { font-size: 20px; font-weight: bold; }
        .navbar .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 14px;
        }
        .navbar .nav-links a:hover { text-decoration: underline; }

        /* Container Utama */
        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        h2 { margin-top: 0; color: #1e5f8a; }

        /* Form Pencarian */
        .search-container {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .search-container input[type="text"] {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 300px;
        }
        .search-container button, .search-container a {
            padding: 8px 16px;
            border: 1px solid #ccc;
            background: #e9ecef;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .search-container button:hover, .search-container a:hover { background: #ddd; }

        /* Tabel SIMPUS-Mini */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #1e5f8a;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        tr:hover { background-color: #f9f9f9; }

        /* Footer */
        .footer {
            text-align: center;
            padding: 20px;
            color: #888;
            font-size: 13px;
            margin-top: 40px;
        }
    </style>
</head>
<body>

    <!-- Navbar SIMPUS-Mini -->
    <div class="navbar">
        <div class="brand">SIMPUS-Mini</div>
        <div class="nav-links">
            <a href="../index.php">Beranda</a>
            <a href="list.php">Daftar Buku</a>
            <a href="tambah.php">Tambah Buku</a>
            <a href="../anggota/list.php">Daftar Anggota</a>
        </div>
    </div>

    <!-- Konten Utama -->
    <div class="container">
        <h2>Daftar Buku</h2>

        <!-- Form Pencarian Server-Side -->
        <form method="GET" action="list.php" class="search-container">
            <input type="text" name="q" placeholder="Ketik kata kunci pencarian..." value="<?= htmlspecialchars($search); ?>">
            <button type="submit">Cari</button>
            <?php if ($search !== ''): ?>
                <a href="list.php">Muat Ulang</a>
            <?php endif; ?>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th>Tahun</th>
                    <th>Stok</th>
                    <th>Kategori</th>
                    <th>Ditambahkan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($daftar_buku)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #888;">Data buku tidak ditemukan.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($daftar_buku as $buku): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($buku['judul']); ?></strong></td>
                            <td><?= htmlspecialchars($buku['pengarang']); ?></td>
                            <td><?= htmlspecialchars($buku['tahun']); ?></td>
                            <td><?= htmlspecialchars($buku['stok']); ?></td>
                            <td><?= htmlspecialchars($buku['kategori'] ?? '-'); ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($buku['tanggal_ditambahkan'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        © 2026 SIMPUS-Mini — Jobsheet 6
    </div>

</body>
</html>