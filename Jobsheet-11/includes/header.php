<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
<?php

header("Content-Security-Policy: default-src 'self'; script-src 'self'; object-src 'none';");
?>

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/csrf.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($page_title ?? 'SIMPUS-Mini'); ?></title>
    <link rel="stylesheet" href="/simpus/assets/css/style.css">
</head>
<body>