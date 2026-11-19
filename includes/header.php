<?php
require_once __DIR__ . '/functions.php';
$pageTitle = $pageTitle ?? 'WisataKuningan';
$currentPage = basename($_SERVER['SCRIPT_NAME'] ?? '');
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
    <a class="brand" href="<?= e($assetPrefix ?? '') ?>index.php">
      <span class="brand-mark">W</span>
      <span><strong>WisataNusantara</strong><small>Kabupaten Kuningan</small></span>
    </a>
    <button class="nav-toggle" type="button" title="Navigasi" aria-label="Buka navigasi" aria-controls="site-navigation" aria-expanded="false">&#9776;</button>
    <nav class="nav-links" id="site-navigation" aria-label="Navigasi utama">
      <a href="<?= e($assetPrefix ?? '') ?>index.php"<?= empty($assetPrefix) && $currentPage === 'index.php' ? ' aria-current="page"' : '' ?>>Beranda</a>
      <a href="<?= e($assetPrefix ?? '') ?>wisata.php"<?= empty($assetPrefix) && in_array($currentPage, ['wisata.php', 'detail_wisata.php', 'booking.php'], true) ? ' aria-current="page"' : '' ?>>Wisata</a>
      <?php if (user_logged_in()): ?>
        <a href="<?= e($assetPrefix ?? '') ?>profil.php"<?= empty($assetPrefix) && $currentPage === 'profil.php' ? ' aria-current="page"' : '' ?>>Profil</a>
        <a href="<?= e($assetPrefix ?? '') ?>riwayat.php"<?= empty($assetPrefix) && in_array($currentPage, ['riwayat.php', 'pembayaran.php'], true) ? ' aria-current="page"' : '' ?>>Riwayat</a>
        <?php if (admin_logged_in()): ?><a href="<?= e($assetPrefix ?? '') ?>admin/index.php"<?= !empty($assetPrefix) ? ' aria-current="page"' : '' ?>>Admin</a><?php endif; ?>
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
