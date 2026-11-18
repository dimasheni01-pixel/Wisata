<?php
require_once __DIR__.'/_init.php';
$error='';$edit=null;
if(isset($_GET['edit'])){$st=$pdo->prepare("SELECT * FROM wisata WHERE id=?");$st->execute([(int)$_GET['edit']]);$edit=$st->fetch();if(!$edit){$_SESSION['flash']=['type'=>'error','message'=>'Data wisata tidak ditemukan.'];redirect('wisata.php');}}
if($_SERVER['REQUEST_METHOD']==='POST'){
 $action=$_POST['action']??'save';
 try {
  if($action==='delete'){
   $id=(int)$_POST['id'];
   $st=$pdo->prepare("SELECT gambar FROM wisata WHERE id=?");$st->execute([$id]);$old=$st->fetch();
   $st=$pdo->prepare("DELETE FROM wisata WHERE id=?");$st->execute([$id]);if($old)delete_local_upload($old['gambar']);
   $_SESSION['flash']=['type'=>'success','message'=>'Wisata berhasil dihapus.'];redirect('wisata.php');
  }
  $id=(int)($_POST['id']??0);$nama=trim($_POST['nama']??'');$lokasi=trim($_POST['lokasi']??'');$harga=(float)($_POST['harga']??0);$jam=trim($_POST['jam_buka']??'');$deskripsi=trim($_POST['deskripsi']??'');$fasilitas=trim($_POST['fasilitas']??'');$kontak_nama=trim($_POST['kontak_nama']??'');$kontak_wa=preg_replace('/\D+/', '', $_POST['kontak_wa']??'');$kontak_jam=trim($_POST['kontak_jam']??'');$aktif=isset($_POST['aktif'])?1:0;
  if($nama===''||$lokasi===''||$harga<0||$deskripsi==='')throw new RuntimeException('Nama, lokasi, harga yang valid, dan deskripsi wajib diisi.');
  $gambar=upload_image('gambar');
  if($id>0){
   if($gambar){$st=$pdo->prepare("SELECT gambar FROM wisata WHERE id=?");$st->execute([$id]);$old=$st->fetchColumn();$st=$pdo->prepare("UPDATE wisata SET nama=?,lokasi=?,harga=?,jam_buka=?,deskripsi=?,fasilitas=?,kontak_nama=?,kontak_wa=?,kontak_jam=?,aktif=?,gambar=? WHERE id=?");$st->execute([$nama,$lokasi,$harga,$jam,$deskripsi,$fasilitas,$kontak_nama,$kontak_wa,$kontak_jam,$aktif,$gambar,$id]);delete_local_upload($old);}
   else{$st=$pdo->prepare("UPDATE wisata SET nama=?,lokasi=?,harga=?,jam_buka=?,deskripsi=?,fasilitas=?,kontak_nama=?,kontak_wa=?,kontak_jam=?,aktif=? WHERE id=?");$st->execute([$nama,$lokasi,$harga,$jam,$deskripsi,$fasilitas,$kontak_nama,$kontak_wa,$kontak_jam,$aktif,$id]);}
   $_SESSION['flash']=['type'=>'success','message'=>'Data wisata berhasil diperbarui.'];
  }else{
   $st=$pdo->prepare("INSERT INTO wisata(nama,lokasi,harga,jam_buka,deskripsi,fasilitas,kontak_nama,kontak_wa,kontak_jam,gambar,aktif) VALUES(?,?,?,?,?,?,?,?,?,?,?)");$st->execute([$nama,$lokasi,$harga,$jam,$deskripsi,$fasilitas,$kontak_nama,$kontak_wa,$kontak_jam,$gambar,$aktif]);
   $_SESSION['flash']=['type'=>'success','message'=>'Wisata berhasil ditambahkan.'];
  }
  redirect('wisata.php');
 }catch(Throwable $e){$error=$e->getMessage();}
}
$rows=$pdo->query("SELECT * FROM wisata ORDER BY id DESC")->fetchAll();
$pageTitle='Kelola Wisata';require __DIR__.'/_header.php';
?>
<?php if($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
<div class="form-card wide" style="margin:0 0 28px;max-width:none"><h2><?= $edit?'Edit wisata':'Tambah wisata' ?></h2><form method="post" enctype="multipart/form-data"><input type="hidden" name="id" value="<?= (int)($edit['id']??0) ?>">
<div class="form-grid"><div class="field"><label>Nama wisata</label><input name="nama" required value="<?= e($edit['nama']??'') ?>"></div><div class="field"><label>Lokasi</label><input name="lokasi" required value="<?= e($edit['lokasi']??'') ?>"></div><div class="field"><label>Harga tiket (Rp)</label><input type="number" name="harga" min="0" step="500" required value="<?= e($edit['harga']??'') ?>"></div><div class="field"><label>Jam buka</label><input name="jam_buka" value="<?= e($edit['jam_buka']??'') ?>" placeholder="08.00–17.00 WIB"></div></div>
<div class="field"><label>Deskripsi</label><textarea name="deskripsi" required><?= e($edit['deskripsi']??'') ?></textarea></div><div class="field"><label>Fasilitas</label><textarea name="fasilitas"><?= e($edit['fasilitas']??'') ?></textarea></div>
<h3 style="margin:22px 0 12px">Kontak admin/pengelola wisata</h3><div class="form-grid"><div class="field"><label>Nama admin/pengelola</label><input name="kontak_nama" value="<?= e($edit['kontak_nama']??'') ?>" placeholder="Admin Wisata Linggarjati"></div><div class="field"><label>Nomor WhatsApp</label><input name="kontak_wa" inputmode="tel" value="<?= e($edit['kontak_wa']??'') ?>" placeholder="6281234567890"><small>Gunakan kode negara, contoh 6281234567890.</small></div><div class="field"><label>Jam layanan admin</label><input name="kontak_jam" value="<?= e($edit['kontak_jam']??'') ?>" placeholder="08.00–17.00 WIB"></div></div>
<?php if(!empty($edit['gambar'])): ?><img id="image-preview" class="proof-img" src="../<?= e($edit['gambar']) ?>" alt="Preview gambar" style="max-width:260px;max-height:180px;margin-bottom:12px"><?php else: ?><img id="image-preview" class="proof-img" src="../assets/img/placeholder.svg" alt="Preview gambar" style="max-width:260px;max-height:180px;margin-bottom:12px"><?php endif; ?>
<div class="field"><label>Gambar wisata (JPG/PNG/WEBP maks. 4 MB)</label><input type="file" name="gambar" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" data-preview="#image-preview"></div><label class="checkbox-row field"><input type="checkbox" name="aktif" <?= !isset($edit['aktif'])||$edit['aktif']?'checked':'' ?>> Tampilkan di website</label><div class="form-actions"><button class="btn" type="submit">Simpan wisata</button><?php if($edit): ?><a class="btn secondary" href="wisata.php">Batal edit</a><?php endif; ?></div></form></div>
<h2>Data wisata</h2><?php if(!$rows): ?><div class="empty">Belum ada data wisata.</div><?php else: ?><div class="table-wrap"><table class="data-table"><thead><tr><th>Gambar</th><th>Nama / Lokasi</th><th>Harga</th><th>Status</th><th>Aksi</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td><img class="proof-img" src="../<?= e($r['gambar']?:'assets/img/placeholder.svg') ?>" alt="" onerror="this.onerror=null;this.src='../assets/img/placeholder.svg';"></td><td><strong><?= e($r['nama']) ?></strong><br><small><?= e($r['lokasi']) ?></small></td><td><?= rupiah($r['harga']) ?></td><td><?= $r['aktif']?'Aktif':'Nonaktif' ?></td><td><a class="btn small" href="wisata.php?edit=<?= (int)$r['id'] ?>">Edit</a><form class="inline-form" method="post" onsubmit="return confirm('Hapus wisata ini?')"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>"><button class="btn small danger" type="submit">Hapus</button></form></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?>
<?php require __DIR__.'/_footer.php'; ?>
