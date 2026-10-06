<?php
$page_title = "Dashboard";
include __DIR__ . '/includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<section>
    <h2>Selamat Datang di SIMPUS-Mini</h2>

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo e($flash['type']); ?>"><?php echo e($flash['pesan']); ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_nama'])): ?>
        <p>Halo, <strong><?php echo e($_SESSION['user_nama']); ?></strong>!</p>
    <?php endif; ?>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>