<?php
require_once __DIR__.'/_init.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $id=(int)($_POST['pembayaran_id']??0);$aksi=$_POST['aksi']??'';$catatan=trim($_POST['catatan']??'');
 if(in_array($aksi,['terverifikasi','ditolak'],true)){
  $pdo->beginTransaction();
  try {
   $st=$pdo->prepare("SELECT p.booking_id FROM pembayaran p JOIN bookings b ON b.id=p.booking_id WHERE p.id=? FOR UPDATE");$st->execute([$id]);$row=$st->fetch();
   if(!$row)throw new RuntimeException('Data pembayaran tidak ditemukan.');
   $st=$pdo->prepare("UPDATE pembayaran SET status=?,catatan=?,verified_by=?,updated_at=CURRENT_TIMESTAMP WHERE id=?");$st->execute([$aksi,$catatan?:null,$_SESSION['user']['id'],$id]);
   $st=$pdo->prepare("UPDATE bookings SET status=? WHERE id=?");$st->execute([$aksi,$row['booking_id']]);
   $pdo->commit();$_SESSION['flash']=['type'=>'success','message'=>'Status pembayaran berhasil diperbarui.'];
  }catch(Throwable $e){$pdo->rollBack();$_SESSION['flash']=['type'=>'error','message'=>'Gagal memproses pembayaran: '.$e->getMessage()];}
 }
 redirect('booking.php');
}
$rows=$pdo->query("SELECT b.*,u.nama AS user_nama,u.email,w.nama AS wisata_nama,p.id AS pembayaran_id,p.bukti,p.status AS pembayaran_status,p.catatan FROM bookings b JOIN users u ON u.id=b.user_id JOIN wisata w ON w.id=b.wisata_id LEFT JOIN pembayaran p ON p.booking_id=b.id ORDER BY b.created_at DESC")->fetchAll();
$pageTitle='Pemesanan & Verifikasi';require __DIR__.'/_header.php';
?>
<?php if(!$rows): ?><div class="empty">Belum ada pemesanan.</div><?php else: ?><div class="table-wrap"><table class="data-table"><thead><tr><th>Booking / Pengguna</th><th>Wisata / Tanggal</th><th>Total</th><th>Bukti</th><th>Status</th><th>Verifikasi</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td><strong><?= e($r['kode_booking']) ?></strong><br><?= e($r['user_nama']) ?><br><small><?= e($r['email']) ?></small></td><td><?= e($r['wisata_nama']) ?><br><?= e($r['tanggal']) ?> · <?= (int)$r['jumlah'] ?> tiket</td><td><?= rupiah($r['total']) ?></td><td><?php if($r['bukti']): ?><a href="../<?= e($r['bukti']) ?>" target="_blank" rel="noopener"><img class="proof-img" src="../<?= e($r['bukti']) ?>" alt="Bukti pembayaran"></a><?php else: ?><span class="muted">Belum diunggah</span><?php endif; ?></td><td><span class="status <?= e($r['pembayaran_status']??$r['status']) ?>"><?= e($r['pembayaran_status']??$r['status']) ?></span><?php if($r['catatan']): ?><br><small><?= e($r['catatan']) ?></small><?php endif; ?></td><td><?php if($r['pembayaran_id'] && $r['pembayaran_status']==='menunggu'): ?><form method="post"><input type="hidden" name="pembayaran_id" value="<?= (int)$r['pembayaran_id'] ?>"><div class="field"><input name="catatan" placeholder="Catatan (opsional)"></div><button class="btn small" name="aksi" value="terverifikasi">Setujui</button> <button class="btn small danger" name="aksi" value="ditolak">Tolak</button></form><?php else: ?><span class="muted">Tidak ada tindakan</span><?php endif; ?></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?>
<?php require __DIR__.'/_footer.php'; ?>
