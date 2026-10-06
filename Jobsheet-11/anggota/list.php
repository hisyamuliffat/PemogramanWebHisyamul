
<?php
require_once __DIR__ . '/../includes/auth.php';

$page_title = "Daftar Anggota";
include __DIR__ . '/../includes/header.php';
// ...

// Admin dan Petugas boleh melihat daftar
check_role(['admin', 'petugas']);

// Ambil data anggota dari PostgreSQL
try {
    $stmt = $pdo->query('SELECT id, no_anggota, nama, alamat, no_hp FROM anggota ORDER BY id DESC');
    $anggota_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Gagal mengambil data anggota.");
}

$page_title = 'Daftar Anggota';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: #1a5b8c; font-weight: 700; margin: 0;">Daftar Anggota</h2>
        <a href="tambah.php" class="btn btn-primary" style="background-color: #1a5b8c; text-decoration: none; padding: 8px 16px; border-radius: 4px; color: white;">+ Tambah Anggota</a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
            <?= e($_GET['success']) ?>
        </div>
    <?php endif; ?>

    <table class="table" style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6; text-align: left;">
                <th style="padding: 10px;">No</th>
                <th style="padding: 10px;">No. Anggota / NIM</th>
                <th style="padding: 10px;">Nama Lengkap</th>
                <th style="padding: 10px;">Alamat</th>
                <th style="padding: 10px;">No. HP</th>
                <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                    <th style="padding: 10px;">Aksi</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (count($anggota_list) > 0): ?>
                <?php $no = 1; foreach ($anggota_list as $row): ?>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 10px;"><?= $no++ ?></td>
                        <td style="padding: 10px;"><?= e($row['no_anggota'] ?? '-') ?></td>
                        <td style="padding: 10px;"><?= e($row['nama'] ?? '-') ?></td>
                        <td style="padding: 10px;"><?= e($row['alamat'] ?? '-') ?></td>
                        <td style="padding: 10px;"><?= e($row['no_hp'] ?? '-') ?></td>
                        <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                            <td style="padding: 10px;">
                                <a href="hapus.php?id=<?= e($row['id']) ?>" 
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" 
                                   style="color: #dc3545; text-decoration: none; font-weight: bold;">Hapus</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= (($_SESSION['role'] ?? '') === 'admin') ? 6 : 5 ?>" style="text-align: center; padding: 20px; color: #6c757d;">
                        Belum ada data anggota.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>