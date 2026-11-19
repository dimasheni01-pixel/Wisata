<?php
require_once __DIR__ . '/_init.php';
$pageTitle = $pageTitle ?? 'Panel Admin';
require dirname(__DIR__) . '/includes/header.php';
?>
<div class="admin-shell">
  <aside class="admin-sidebar">
    <a class="admin-brand" href="index.php">
      <span class="admin-brand-icon">W</span>
      <strong>WisataNusantara</strong>
      <small>Admin Panel</small>
    </a>
    <nav class="admin-menu" aria-label="Navigasi admin">
      <a href="index.php"<?= $currentPage === 'index.php' ? ' aria-current="page"' : '' ?>>Dashboard</a>
      <a href="wisata.php"<?= $currentPage === 'wisata.php' ? ' aria-current="page"' : '' ?>>Kelola Wisata</a>
      <a href="booking.php"<?= $currentPage === 'booking.php' ? ' aria-current="page"' : '' ?>>Kelola Booking</a>
      <a href="users.php"<?= $currentPage === 'users.php' ? ' aria-current="page"' : '' ?>>Kelola Pengguna</a>
      <a href="rekening.php"<?= $currentPage === 'rekening.php' ? ' aria-current="page"' : '' ?>>Rekening</a>
      <a href="../logout.php" class="admin-logout">Logout</a>
    </nav>
  </aside>
  <div class="admin-main">
    <div class="admin-topbar">
      <span><?= e($pageTitle) ?></span>
      <a href="../profil.php">Profil Admin</a>
    </div>
    <section class="admin-heading">
      <span class="eyebrow">PENGELOLAAN WISATA</span>
      <h1><?= e($pageTitle) ?></h1>
      <p>Selamat datang, <?= e($_SESSION['user']['nama']) ?>. Kelola data WisataNusantara di sini.</p>
    </section>
    <section class="admin-content">
