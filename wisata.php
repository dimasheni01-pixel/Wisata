<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
$q = trim($_GET['q'] ?? '');
if ($q !== '') {
 $stmt = $pdo->prepare("SELECT * FROM wisata WHERE aktif=1 AND (nama LIKE ? OR lokasi LIKE ?) ORDER BY nama");
 $like = "%{$q}%"; $stmt->execute([$like,$like]); $wisataList=$stmt->fetchAll();
} else $wisataList=$pdo->query("SELECT * FROM wisata WHERE aktif=1 ORDER BY nama")->fetchAll();
$pageTitle='Daftar Wisata'; require __DIR__ . '/includes/header.php';
?>
<section class="container page-top"><span class="eyebrow" style="color:var(--green)">JELAJAHI DESTINASI</span><h1>Daftar wisata</h1><p>Pilih destinasi, cek informasi dan harga tiket, lalu rencanakan kunjunganmu.</p>
<form class="searchbar" method="get"><input name="q" value="<?= e($q) ?>" placeholder="Cari nama wisata atau lokasi..."><button class="btn" type="submit">Cari wisata</button><?php if($q!==''): ?><a class="btn secondary" href="wisata.php">Reset</a><?php endif; ?></form></section>
<section class="container section" style="padding-top:0">
<?php if(!$wisataList): ?><div class="empty">Wisata tidak ditemukan. Coba kata kunci lain.</div><?php else: ?><div class="grid"><?php foreach($wisataList as $w): ?><article class="card"><a href="detail_wisata.php?id=<?= (int)$w['id'] ?>"><img class="card-img" src="<?= e($w['gambar'] ?: 'assets/img/placeholder.svg') ?>" alt="<?= e($w['nama']) ?>" onerror="this.onerror=null;this.src='assets/img/placeholder.svg';"></a><div class="card-body"><span class="pill">Destinasi wisata</span><h3 style="margin-top:12px"><a href="detail_wisata.php?id=<?= (int)$w['id'] ?>"><?= e($w['nama']) ?></a></h3><div class="location">⌖ <?= e($w['lokasi']) ?></div><p><?= e(mb_strimwidth($w['deskripsi'],0,110,'…')) ?></p><div class="card-bottom"><span class="price"><?= rupiah($w['harga']) ?><small> / tiket</small></span><a class="text-link" href="detail_wisata.php?id=<?= (int)$w['id'] ?>">Lihat detail →</a></div></div></article><?php endforeach; ?></div><?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
