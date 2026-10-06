<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/koneksi.php';
// ...

// Aturan: Admin dan Petugas boleh menambah data anggota
check_role(['admin', 'petugas']);

// Lanjutan kode form / proses tambah...