<?php
session_start();

// Ambil pesan flash jika ada
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']); // Hapus flash message setelah dibaca

// Ambil isian lama jika ada error
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
?>

<!-- Tampilkan alert jika ada pesan flash -->
<?php if ($flash): ?>
    <div style="padding: 10px; margin-bottom: 15px; color: white; background-color: <?= $flash['type'] === 'success' ? 'green' : 'red'; ?>;">
        <?= htmlspecialchars($flash['message']); ?>
    </div>
<?php endif; ?>

<!-- Contoh pengisian nilai bawaan dari $old jika gagal submit -->
<form action="proses_tambah.php" method="POST">
    <label>No Anggota:</label><br>
    <input type="text" name="no_anggota" value="<?= htmlspecialchars($old['no_anggota'] ?? ''); ?>" required><br><br>

    <label>Nama:</label><br>
    <input type="text" name="nama" value="<?= htmlspecialchars($old['nama'] ?? ''); ?>" required><br><br>

    <label>Alamat:</label><br>
    <textarea name="alamat"><?= htmlspecialchars($old['alamat'] ?? ''); ?></textarea><br><br>

    <label>No HP:</label><br>
    <input type="text" name="no_hp" value="<?= htmlspecialchars($old['no_hp'] ?? ''); ?>"><br><br>

    <button type="submit">Simpan</button>
</form>