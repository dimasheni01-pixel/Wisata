<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
$stmt = $pdo->query("SELECT * FROM wisata WHERE aktif = 1 ORDER BY id DESC LIMIT 6");
$wisataList = $stmt->fetchAll();
$pageTitle = 'Beranda';
require __DIR__ . '/includes/header.php';
?>
<section class="hero">
 <div class="container hero-grid">
  <div>
   <span class="eyebrow">✦ Jelajahi Kuningan</span>
   <h1>Temukan tempat indah, buat perjalanan jadi mudah.</h1>
   <p>Temukan destinasi wisata, pilih tanggal kunjungan, dan pesan tiket secara online melalui satu website.</p>
   <div class="hero-actions"><a class="btn" href="wisata.php">Jelajahi Wisata ↗</a><a class="btn secondary" href="#pilihan">Lihat pilihan wisata</a></div>
   <div class="hero-stat"><div><strong>Praktis</strong><span>Pesan dari rumah</span></div><div><strong>Terdata</strong><span>Riwayat pemesanan</span></div><div><strong>Terarah</strong><span>Info destinasi</span></div></div>
  </div>
  <div class="hero-art"><div class="art-card"><strong>Alam, budaya, pengalaman baru.</strong><span>Mulai rencanakan kunjunganmu ke destinasi favorit di Kuningan.</span></div></div>
 </div>
</section>
<section class="section" id="pilihan"><div class="container">
 <div class="section-head"><div><span class="eyebrow" style="color:var(--green)">PILIHAN DESTINASI</span><h2>Wisata pilihan</h2><p>Beberapa destinasi yang tersedia untuk dipesan.</p></div><a class="text-link" href="wisata.php">Lihat semua →</a></div>
 <?php if (!$wisataList): ?><div class="empty">Belum ada wisata. Admin dapat menambahkan destinasi melalui panel admin.</div><?php else: ?>
 <div class="grid"><?php foreach ($wisataList as $w): ?>
  <article class="card">
   <a href="detail_wisata.php?id=<?= (int)$w['id'] ?>"><img class="card-img" src="<?= e($w['gambar'] ?: 'assets/img/placeholder.svg') ?>" alt="<?= e($w['nama']) ?>" onerror="this.onerror=null;this.src='assets/img/placeholder.svg';"></a>
   <div class="card-body"><span class="pill">Destinasi wisata</span><h3 style="margin-top:12px"><a href="detail_wisata.php?id=<?= (int)$w['id'] ?>"><?= e($w['nama']) ?></a></h3><div class="location">⌖ <?= e($w['lokasi']) ?></div><div class="card-bottom"><span class="price"><?= rupiah($w['harga']) ?><small> / tiket</small></span><a class="text-link" href="detail_wisata.php?id=<?= (int)$w['id'] ?>">Detail →</a></div></div>
  </article>
 <?php endforeach; ?></div><?php endif; ?>
</div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
