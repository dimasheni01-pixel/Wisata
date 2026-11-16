<?php
require_once __DIR__ . '/config/database.php'; require_once __DIR__ . '/includes/functions.php';
if(user_logged_in()) redirect(admin_logged_in()?'admin/index.php':'index.php');
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $username=trim($_POST['username']??'');$password=$_POST['password']??'';
 $stmt=$pdo->prepare("SELECT id,nama,email,username,password,role FROM users WHERE username=? OR email=? LIMIT 1");$stmt->execute([$username,$username]);$u=$stmt->fetch();
 if($u && password_verify($password,$u['password'])){session_regenerate_id(true);unset($u['password']);$_SESSION['user']=$u;redirect(($u['role']==='admin')?'admin/index.php':'index.php');}
 $error='Username/email atau password salah.';
}
$pageTitle='Masuk';require __DIR__.'/includes/header.php';
?>
<div class="container"><section class="form-card"><span class="eyebrow" style="color:var(--green)">SELAMAT DATANG</span><h1>Masuk akun</h1><p>Login untuk mengelola pemesanan tiket wisata.</p><?php if($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?><form method="post"><div class="field"><label>Username atau email</label><input name="username" required autocomplete="username" value="<?= e($_POST['username']??'') ?>"></div><div class="field"><label>Password</label><input type="password" name="password" required autocomplete="current-password"></div><button class="btn" type="submit">Login</button></form><p style="margin-top:20px">Belum punya akun? <a href="register.php"><strong>Daftar sekarang</strong></a></p><div class="note">Akun admin awal: <strong>admin</strong> / <strong>admin123</strong>. Ganti password admin setelah login pertama.</div></section></div>
<?php require __DIR__.'/includes/footer.php'; ?>
