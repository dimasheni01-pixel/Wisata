<?php
require_once __DIR__.'/_init.php';
$counts=[
 'Wisata'=>$pdo->query("SELECT COUNT(*) FROM wisata")->fetchColumn(),
 'Pengguna'=>$pdo->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetchColumn(),
 'Pemesanan'=>$pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn(),
 'Menunggu verifikasi'=>$pdo->query("SELECT COUNT(*) FROM pembayaran WHERE status='menunggu'")->fetchColumn(),
];
$pageTitle='Dashboard Admin';require __DIR__.'/_header.php';
?>
<div class="stats"><?php foreach($counts as $label=>$num): ?><div class="stat"><span><?= e($label) ?></span><strong><?= (int)$num ?></strong></div><?php endforeach; ?></div>
<div class="card"><div class="card-body"><h2>Selamat datang, <?= e($_SESSION['user']['nama']) ?>!</h2><p>Gunakan menu di atas untuk mengelola destinasi wisata, rekening per destinasi, dan status pembayaran.</p><div class="form-actions"><a class="btn" href="wisata.php">Kelola wisata</a><a class="btn secondary" href="booking.php">Periksa pembayaran</a></div></div></div>
<?php require __DIR__.'/_footer.php'; ?>
