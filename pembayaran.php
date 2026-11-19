<?php
require_once __DIR__ . '/config/database.php';require_once __DIR__ . '/includes/functions.php';require_login();
$id=(int)($_GET['id']??$_POST['booking_id']??0);
$sql="SELECT b.*,w.nama AS wisata_nama,w.lokasi,r.bank,r.pemilik,r.nomor_rekening FROM bookings b JOIN wisata w ON w.id=b.wisata_id LEFT JOIN rekening r ON r.wisata_id=w.id AND r.aktif=1 WHERE b.id=? AND b.user_id=?";
$stmt=$pdo->prepare($sql);$stmt->execute([$id,$_SESSION['user']['id']]);$b=$stmt->fetch();
if(!$b){$_SESSION['flash']=['type'=>'error','message'=>'Booking tidak ditemukan.'];redirect('riwayat.php');}
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 if(in_array($b['status'],['terverifikasi','dibatalkan'],true))$error='Booking ini tidak dapat menerima bukti pembayaran baru.';
 elseif(empty($_FILES['bukti'])||$_FILES['bukti']['error']===UPLOAD_ERR_NO_FILE)$error='Pilih file bukti pembayaran.';
 else {
  try {
   $path=upload_image('bukti','uploads/pembayaran');
   $stmt=$pdo->prepare("INSERT INTO pembayaran(booking_id,bukti,status,catatan) VALUES(?,?,'menunggu',NULL) ON DUPLICATE KEY UPDATE bukti=VALUES(bukti),status='menunggu',catatan=NULL,updated_at=CURRENT_TIMESTAMP");
   $stmt->execute([$b['id'],$path]);
   $stmt=$pdo->prepare("UPDATE bookings SET status='menunggu' WHERE id=? AND status<>'terverifikasi'");$stmt->execute([$b['id']]);
   $_SESSION['flash']=['type'=>'success','message'=>'Bukti pembayaran berhasil diunggah. Tunggu verifikasi admin.'];redirect('riwayat.php');
  } catch(Throwable $e){$error=$e->getMessage();}
 }
}
$pageTitle='Pembayaran';require __DIR__.'/includes/header.php';
?>
<div class="container"><section class="form-card wide"><span class="eyebrow" style="color:var(--green)">PEMBAYARAN MANUAL</span><h1>Selesaikan pembayaran</h1><p>Kode booking: <strong><?= e($b['kode_booking']) ?></strong></p><div class="note">Transfer sesuai nominal dan unggah bukti pembayaran yang jelas. Pembayaran akan dicek oleh admin dinas.</div><div class="detail-meta"><div><strong>Destinasi</strong><br><?= e($b['wisata_nama']) ?></div><div><strong>Tanggal kunjungan</strong><br><?= e($b['tanggal']) ?> · <?= (int)$b['jumlah'] ?> tiket</div><div><strong>Total pembayaran</strong><br><span class="price"><?= rupiah($b['total']) ?></span></div><div><strong>Rekening tujuan</strong><br><?php if($b['nomor_rekening']): ?><?= e($b['bank']) ?> · <?= e($b['pemilik']) ?><br><strong><?= e($b['nomor_rekening']) ?></strong><?php else: ?><span class="muted">Rekening belum diatur. Hubungi admin sebelum transfer.</span><?php endif; ?></div></div>
<?php if($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data"><input type="hidden" name="booking_id" value="<?= (int)$b['id'] ?>"><div class="field"><label for="bukti">Upload bukti pembayaran (JPG, PNG, WEBP; maks. 4 MB)</label><input id="bukti" type="file" name="bukti" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" required></div><div class="form-actions"><button class="btn" type="submit" <?= in_array($b['status'],['terverifikasi','dibatalkan'],true)?'disabled':'' ?>>Kirim bukti pembayaran</button><a class="btn secondary" href="riwayat.php">Lihat riwayat</a></div></form></section></div>
<?php require __DIR__.'/includes/footer.php'; ?>
