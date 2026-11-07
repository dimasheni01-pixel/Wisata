<?php
require_once __DIR__.'/_init.php';
$pageTitle=$pageTitle??'Panel Admin';
require dirname(__DIR__).'/includes/header.php';
?>
<section class="container page-top"><span class="eyebrow" style="color:var(--green)">PENGELOLAAN DINAS</span><h1><?= e($pageTitle) ?></h1><p>Kelola data destinasi, rekening pembayaran, dan pemesanan wisata.</p><nav class="admin-nav"><a href="index.php">Dashboard</a><a href="wisata.php">Kelola Wisata</a><a href="rekening.php">Rekening</a><a href="booking.php">Pemesanan</a><a href="users.php">Pengguna</a></nav></section><section class="container section" style="padding-top:0">
