<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_login();

$userId = (int) $_SESSION['user']['id'];
$stmt = $pdo->prepare('SELECT id,nama,email,username,no_hp,foto_profil,role,created_at FROM users WHERE id=? LIMIT 1');
$stmt->execute([$userId]);
$profile = $stmt->fetch();
if (!$profile) { session_destroy(); redirect('login.php'); }
$error = '';
if (empty($_SESSION['csrf_profile'])) $_SESSION['csrf_profile'] = bin2hex(random_bytes(32));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!hash_equals($_SESSION['csrf_profile'], (string)($_POST['csrf_token'] ?? ''))) throw new RuntimeException('Token keamanan tidak valid. Muat ulang halaman.');
        $nama = trim((string)($_POST['nama'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $username = trim((string)($_POST['username'] ?? ''));
        $noHp = trim((string)($_POST['no_hp'] ?? ''));
        if ($nama === '' || $email === '' || $username === '') throw new RuntimeException('Nama, email, dan username wajib diisi.');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) throw new RuntimeException('Format email tidak valid.');
        if (mb_strlen($username) < 3 || mb_strlen($username) > 60) throw new RuntimeException('Username harus 3–60 karakter.');
        if (mb_strlen($nama) > 120 || mb_strlen($email) > 190 || mb_strlen($noHp) > 30) throw new RuntimeException('Ada data yang terlalu panjang.');
        $dup = $pdo->prepare('SELECT id FROM users WHERE (email=? OR username=?) AND id<>? LIMIT 1');
        $dup->execute([$email, $username, $userId]);
        if ($dup->fetch()) throw new RuntimeException('Email atau username sudah dipakai akun lain.');

        $photoPath = $profile['foto_profil'] ?? null;
        if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] !== UPLOAD_ERR_NO_FILE) {
            $file = $_FILES['foto_profil'];
            if ($file['error'] !== UPLOAD_ERR_OK) throw new RuntimeException('Upload foto gagal.');
            if ($file['size'] > 2 * 1024 * 1024) throw new RuntimeException('Ukuran foto maksimal 2 MB.');
            $info = @getimagesize($file['tmp_name']);
            $mime = $info['mime'] ?? '';
            $ext = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'][$mime] ?? null;
            if (!$info || !$ext) throw new RuntimeException('Foto harus berupa JPG, PNG, atau WEBP.');
            $folder = __DIR__ . '/uploads/profil';
            if (!is_dir($folder) && !mkdir($folder, 0755, true) && !is_dir($folder)) throw new RuntimeException('Folder foto profil tidak dapat dibuat.');
            $filename = bin2hex(random_bytes(16)) . '.' . $ext;
            if (!move_uploaded_file($file['tmp_name'], $folder . '/' . $filename)) throw new RuntimeException('Foto gagal disimpan.');
            $photoPath = 'uploads/profil/' . $filename;
        }

        $newPassword = (string)($_POST['password_baru'] ?? '');
        if ($newPassword !== '') {
            if (strlen($newPassword) < 8) throw new RuntimeException('Password baru minimal 8 karakter.');
            if (!password_verify((string)($_POST['password_lama'] ?? ''), (string)($_SESSION['user']['password'] ?? ''))) {
                // The session intentionally does not hold the password hash; fetch it securely.
                $ph = $pdo->prepare('SELECT password FROM users WHERE id=?'); $ph->execute([$userId]);
                $hash = (string)$ph->fetchColumn();
                if (!password_verify((string)($_POST['password_lama'] ?? ''), $hash)) throw new RuntimeException('Password lama tidak sesuai.');
            }
            $up = $pdo->prepare('UPDATE users SET nama=?,email=?,username=?,no_hp=?,foto_profil=?,password=? WHERE id=?');
            $up->execute([$nama,$email,$username,$noHp !== '' ? $noHp : null,$photoPath,password_hash($newPassword,PASSWORD_DEFAULT),$userId]);
        } else {
            $up = $pdo->prepare('UPDATE users SET nama=?,email=?,username=?,no_hp=?,foto_profil=? WHERE id=?');
            $up->execute([$nama,$email,$username,$noHp !== '' ? $noHp : null,$photoPath,$userId]);
        }
        $fresh = $pdo->prepare('SELECT id,nama,email,username,role FROM users WHERE id=?'); $fresh->execute([$userId]);
        $_SESSION['user'] = $fresh->fetch();
        $_SESSION['flash'] = ['type'=>'success','message'=>'Profil berhasil diperbarui.'];
        redirect('profil.php');
    } catch (Throwable $ex) { $error = $ex instanceof PDOException ? 'Email atau username sudah digunakan.' : $ex->getMessage(); }
    $stmt = $pdo->prepare('SELECT id,nama,email,username,no_hp,foto_profil,role,created_at FROM users WHERE id=?'); $stmt->execute([$userId]); $profile = $stmt->fetch();
}
$pageTitle = 'Profil Saya'; require __DIR__ . '/includes/header.php';
?>
<div class="container"><section class="form-card wide"><span class="eyebrow" style="color:var(--green)">AKUN PENGGUNA</span><h1>Profil Saya</h1><p>Perbarui informasi akun. Perubahan hanya berlaku untuk akun yang sedang login.</p>
<?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
<div style="display:flex;align-items:center;gap:16px;margin:20px 0"><?php if (!empty($profile['foto_profil']) && is_file(__DIR__ . '/' . $profile['foto_profil'])): ?><img src="<?= e($profile['foto_profil']) ?>" alt="Foto profil" style="width:88px;height:88px;object-fit:cover;border-radius:50%"><?php else: ?><div style="width:88px;height:88px;border-radius:50%;display:grid;place-items:center;background:#e8f2ec;color:#176b48;font-size:32px;font-weight:700"><?= e(mb_strtoupper(mb_substr($profile['nama'],0,1))) ?></div><?php endif; ?><div><strong><?= e($profile['nama']) ?></strong><br><span class="muted">Bergabung <?= e(date('d-m-Y', strtotime($profile['created_at']))) ?></span></div></div>
<form method="post" enctype="multipart/form-data"><input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_profile']) ?>"><div class="form-grid"><div class="field"><label>Nama lengkap</label><input name="nama" maxlength="120" required value="<?= e($profile['nama']) ?>"></div><div class="field"><label>Email</label><input type="email" name="email" maxlength="190" required value="<?= e($profile['email']) ?>"></div><div class="field"><label>Username</label><input name="username" minlength="3" maxlength="60" required value="<?= e($profile['username']) ?>"></div><div class="field"><label>Nomor HP</label><input name="no_hp" maxlength="30" inputmode="tel" value="<?= e($profile['no_hp'] ?? '') ?>"></div></div><div class="field"><label>Foto profil (JPG/PNG/WEBP, maksimal 2 MB)</label><input type="file" name="foto_profil" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"></div><hr style="margin:26px 0;border:0;border-top:1px solid #e2e8e5"><h2>Ganti password (opsional)</h2><div class="form-grid"><div class="field"><label>Password lama</label><input type="password" name="password_lama" autocomplete="current-password"></div><div class="field"><label>Password baru (minimal 8 karakter)</label><input type="password" name="password_baru" minlength="8" autocomplete="new-password"></div></div><button class="btn" type="submit">Simpan perubahan</button> <a class="btn secondary" href="index.php">Kembali</a></form></section></div>
<?php require __DIR__ . '/includes/footer.php'; ?>
