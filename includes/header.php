<?php
require_once __DIR__ . '/functions.php';
$pageTitle = $pageTitle ?? 'WisataKuningan';
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle) ?> | WisataKuningan</title>
<link rel="stylesheet" href="<?= e($assetPrefix ?? '') ?>assets/css/style.css">
<script defer src="<?= e($assetPrefix ?? '') ?>assets/js/app.js"></script>
</head>
<body>
<header class="site-header">
  <div class="container nav-wrap">
    <a class="brand" href="<?= e($assetPrefix ?? '') ?>index.php"><span class="brand-mark">W</span><span>Wisata<span class="brand-light">Kuningan</span></span></a>
    <button class="nav-toggle" type="button" aria-label="Buka navigasi" aria-expanded="false">☰</button>
    <nav class="nav-links">
      <a href="<?= e($assetPrefix ?? '') ?>index.php">Beranda</a>
      <a href="<?= e($assetPrefix ?? '') ?>wisata.php">Daftar Wisata</a>
      <?php if (user_logged_in()): ?>
        <a href="<?= e($assetPrefix ?? '') ?>riwayat.php">Riwayat Booking</a>
        <a href="<?= e($assetPrefix ?? '') ?>profil.php">Profil Saya</a>
        <?php if (admin_logged_in()): ?><a href="<?= e($assetPrefix ?? '') ?>admin/index.php">Panel Admin</a><?php endif; ?>
        <a class="nav-cta" href="<?= e($assetPrefix ?? '') ?>logout.php">Keluar</a>
      <?php else: ?>
        <a href="<?= e($assetPrefix ?? '') ?>login.php">Masuk</a>
        <a class="nav-cta" href="<?= e($assetPrefix ?? '') ?>register.php">Daftar</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main>
<div class="container flash-wrap"><?php flash_message(); ?></div>
