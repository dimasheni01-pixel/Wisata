<?php
require_once __DIR__.'/_init.php';$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $id=(int)($_POST['wisata_id']??0);$bank=trim($_POST['bank']??'');$pemilik=trim($_POST['pemilik']??'');$nomor=preg_replace('/\s+/','',trim($_POST['nomor_rekening']??''));$aktif=isset($_POST['aktif'])?1:0;
 try {
  $st=$pdo->prepare("SELECT id FROM wisata WHERE id=?");$st->execute([$id]);if(!$st->fetch())throw new RuntimeException('Pilih wisata yang valid.');
  if($bank===''||$pemilik===''||$nomor==='')throw new RuntimeException('Semua data rekening wajib diisi.');
  $st=$pdo->prepare("INSERT INTO rekening(wisata_id,bank,pemilik,nomor_rekening,aktif) VALUES(?,?,?,?,?) ON DUPLICATE KEY UPDATE bank=VALUES(bank),pemilik=VALUES(pemilik),nomor_rekening=VALUES(nomor_rekening),aktif=VALUES(aktif)");
  $st->execute([$id,$bank,$pemilik,$nomor,$aktif]);$_SESSION['flash']=['type'=>'success','message'=>'Rekening berhasil disimpan.'];redirect('rekening.php');
 }catch(Throwable $e){$error=$e->getMessage();}
}
$wisata=$pdo->query("SELECT w.id,w.nama,r.bank,r.pemilik,r.nomor_rekening,r.aktif FROM wisata w LEFT JOIN rekening r ON r.wisata_id=w.id ORDER BY w.nama")->fetchAll();
$pageTitle='Rekening Pembayaran';require __DIR__.'/_header.php';
?>
<?php if($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
<div class="note admin-note">Rekening dikelola oleh dinas, tetapi detail rekening dapat berbeda untuk setiap destinasi wisata. Pastikan data rekening sudah diverifikasi sebelum pengguna melakukan transfer.</div>
<div class="form-card wide admin-form-card"><h2>Atur rekening per wisata</h2><form method="post"><div class="form-grid"><div class="field"><label>Destinasi wisata</label><select name="wisata_id" required><option value="">Pilih wisata</option><?php foreach($wisata as $w): ?><option value="<?= (int)$w['id'] ?>"><?= e($w['nama']) ?></option><?php endforeach; ?></select></div><div class="field"><label>Nama bank / metode</label><input name="bank" required placeholder="Contoh: Bank BRI"></div><div class="field"><label>Nama pemilik rekening</label><input name="pemilik" required></div><div class="field"><label>Nomor rekening</label><input name="nomor_rekening" required inputmode="numeric"></div></div><label class="checkbox-row field"><input type="checkbox" name="aktif" checked> Aktifkan rekening ini</label><button class="btn" type="submit">Simpan rekening</button></form></div>
<div class="table-wrap"><table class="data-table"><thead><tr><th>Wisata</th><th>Bank</th><th>Pemilik</th><th>Nomor rekening</th><th>Status</th></tr></thead><tbody><?php foreach($wisata as $w): ?><tr><td><?= e($w['nama']) ?></td><td><?= e($w['bank']??'Belum diatur') ?></td><td><?= e($w['pemilik']??'—') ?></td><td><?= e($w['nomor_rekening']??'—') ?></td><td><?= !empty($w['aktif'])?'Aktif':'—' ?></td></tr><?php endforeach; ?></tbody></table></div>
<?php require __DIR__.'/_footer.php'; ?>
