<?php
require __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../koneksi.php';

// Aturan: Admin dan Petugas boleh menambah data anggota
check_role(['admin', 'petugas']);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $no_anggota = trim($_POST['no_anggota'] ?? '');
    $nama       = trim($_POST['nama'] ?? '');
    $alamat     = trim($_POST['alamat'] ?? '');
    $no_hp      = trim($_POST['no_hp'] ?? '');

    if (empty($no_anggota) || empty($nama)) {
        $error = 'No. Anggota dan Nama Wajib diisi!';
    } else {
        try {
           
            $stmt = $pdo->prepare('INSERT INTO anggota (no_anggota, nama, alamat, no_hp) VALUES (:no_anggota, :nama, :alamat, :no_hp)');
            $stmt->execute([
                ':no_anggota' => $no_anggota,
                ':nama'       => $nama,
                ':alamat'     => $alamat,
                ':no_hp'      => $no_hp
            ]);
            
            header('Location: list.php?success=' . urlencode('Anggota berhasil ditambahkan!'));
            exit;
        } catch (PDOException $e) {
            $error = 'Gagal menyimpan data: ' . $e->getMessage();
        }
    }
}

$page_title = 'Tambah Anggota';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <h2 style="color: #1a5b8c; font-weight: 700; margin-bottom: 20px;">Tambah Anggota</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="form-group">
            <label for="no_anggota">No. Anggota / NIM / NIK</label>
            <input type="text" id="no_anggota" name="no_anggota" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="nama">Nama Lengkap</label>
            <input type="text" id="nama" name="nama" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="alamat">Alamat</label>
            <input type="text" id="alamat" name="alamat" class="form-control">
        </div>

        <div class="form-group">
            <label for="no_hp">No. HP / Telepon</label>
            <input type="text" id="no_hp" name="no_hp" class="form-control">
        </div>

        <div class="form-group" style="margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="list.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>