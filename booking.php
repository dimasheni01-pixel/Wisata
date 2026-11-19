<?php
require_once __DIR__ . '/config/database.php'; require_once __DIR__ . '/includes/functions.php';require_login();
$id=(int)($_GET['id']??$_POST['wisata_id']??0);$stmt=$pdo->prepare("SELECT * FROM wisata WHERE id=? AND aktif=1");$stmt->execute([$id]);$w=$stmt->fetch();
if(!$w){$_SESSION['flash']=['type'=>'error','message'=>'Destinasi wisata tidak ditemukan.'];redirect('wisata.php');}
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $tanggal=$_POST['tanggal']??'';$jumlah=(int)($_POST['jumlah']??0);
 if(!$tanggal || $tanggal < date('Y-m-d'))$error='Tanggal kunjungan harus hari ini atau setelahnya.';
 elseif($jumlah<1 || $jumlah>20)$error='Jumlah tiket harus antara 1 sampai 20.';
 else {
  $kode='WK'.date('ymd').strtoupper(bin2hex(random_bytes(3)));
  $total=(float)$w['harga']*$jumlah;
  $stmt=$pdo->prepare("INSERT INTO bookings(kode_booking,user_id,wisata_id,tanggal,jumlah,total,status) VALUES(?,?,?,?,?,?,'menunggu')");
  $stmt->execute([$kode,$_SESSION['user']['id'],$w['id'],$tanggal,$jumlah,$total]);
  $_SESSION['flash']=['type'=>'success','message'=>'Booking berhasil dibuat. Silakan selesaikan pembayaran.'];
  redirect('pembayaran.php?id='.(int)$pdo->lastInsertId());
 }
}
$pageTitle='Pesan tiket';require __DIR__.'/includes/header.php';
?>
<div class="container"><section class="form-card wide"><span class="eyebrow" style="color:var(--green)">PEMESANAN TIKET</span><h1>Pesan <?= e($w['nama']) ?></h1><p><?= e($w['lokasi']) ?> · Harga <?= rupiah($w['harga']) ?> per tiket</p><?php if($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?><form method="post" id="booking-form"><input type="hidden" name="wisata_id" value="<?= (int)$w['id'] ?>"><div class="form-grid"><div class="field"><label for="tanggal">Tanggal kunjungan</label><input id="tanggal" type="date" name="tanggal" min="<?= date('Y-m-d') ?>" required value="<?= e($_POST['tanggal']??'') ?>"></div><div class="field"><label for="jumlah">Jumlah tiket (maksimal 20)</label><input type="number" id="jumlah" name="jumlah" min="1" max="20" required value="<?= e($_POST['jumlah']??1) ?>"></div></div><div class="note">Perkiraan total pembayaran: <strong id="total-preview" data-price="<?= e($w['harga']) ?>"><?= rupiah($w['harga']) ?></strong></div><p class="booking-help">Setelah booking dibuat, website akan menampilkan rekening pembayaran untuk destinasi ini.</p><div class="form-actions"><button class="btn" type="submit">Buat pemesanan</button><a class="btn secondary" href="detail_wisata.php?id=<?= (int)$w['id'] ?>">Kembali</a></div></form></section></div>
<script>document.addEventListener('DOMContentLoaded',()=>{const q=document.querySelector('#jumlah'),t=document.querySelector('#total-preview');if(q&&t){const p=Number(t.dataset.price);const fmt=n=>'Rp'+new Intl.NumberFormat('id-ID').format(n);const update=()=>t.textContent=fmt(Math.max(1,Math.min(20,Number(q.value)||1))*p);q.addEventListener('input',update);update();}});</script>
<?php require __DIR__.'/includes/footer.php'; ?>
