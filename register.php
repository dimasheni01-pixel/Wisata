<?php
require_once __DIR__ . '/config/database.php'; require_once __DIR__ . '/includes/functions.php';
if(user_logged_in()) redirect('index.php');
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $nama=trim($_POST['nama']??'');$email=trim($_POST['email']??'');$username=trim($_POST['username']??'');$password=$_POST['password']??'';$confirm=$_POST['confirm_password']??'';
 if($nama===''||$email===''||$username===''||$password==='')$error='Semua kolom wajib diisi.';
 elseif(!filter_var($email,FILTER_VALIDATE_EMAIL))$error='Format email tidak valid.';
 elseif(strlen($password)<8)$error='Password minimal 8 karakter.';
 elseif($password!==$confirm)$error='Konfirmasi password tidak sama.';
 else {
  $stmt=$pdo->prepare("SELECT id FROM users WHERE email=? OR username=?");$stmt->execute([$email,$username]);
  if($stmt->fetch())$error='Email atau username sudah digunakan.';
  else {$stmt=$pdo->prepare("INSERT INTO users(nama,email,username,password,role) VALUES(?,?,?,?, 'user')");$stmt->execute([$nama,$email,$username,password_hash($password,PASSWORD_DEFAULT)]);$_SESSION['flash']=['type'=>'success','message'=>'Registrasi berhasil. Silakan login.'];redirect('login.php');}
 }
}
$pageTitle='Daftar akun';require __DIR__.'/includes/header.php';
?>
<div class="container"><section class="form-card"><span class="eyebrow" style="color:var(--green)">BUAT AKUN</span><h1>Daftar akun</h1><p>Buat akun agar kamu bisa memesan tiket dan melihat riwayat booking.</p><?php if($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?><form method="post">
<div class="field"><label>Nama lengkap</label><input name="nama" required value="<?= e($_POST['nama']??'') ?>"></div>
<div class="field"><label>Email</label><input type="email" name="email" required value="<?= e($_POST['email']??'') ?>"></div>
<div class="field"><label>Username</label><input name="username" required minlength="3" value="<?= e($_POST['username']??'') ?>"></div>
<div class="form-grid"><div class="field"><label>Password (minimal 8 karakter)</label><input type="password" name="password" required minlength="8"></div><div class="field"><label>Konfirmasi password</label><input type="password" name="confirm_password" required minlength="8"></div></div>
<button class="btn" type="submit">Buat akun</button></form><p style="margin-top:20px">Sudah punya akun? <a href="login.php"><strong>Login di sini</strong></a></p></section></div>
<?php require __DIR__.'/includes/footer.php'; ?>
