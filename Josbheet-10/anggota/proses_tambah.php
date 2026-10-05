<?php
require __DIR__ . '/../includes/auth.php';

// Aturan: Admin dan Petugas boleh menambah data anggota
check_role(['admin', 'petugas']);

// Lanjutan kode form / proses tambah...