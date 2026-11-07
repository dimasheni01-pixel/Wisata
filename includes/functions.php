<?php
function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function redirect(string $url): never {
    header('Location: ' . $url);
    exit;
}
function user_logged_in(): bool {
    return isset($_SESSION['user']['id']);
}
function admin_logged_in(): bool {
    return user_logged_in() && ($_SESSION['user']['role'] ?? '') === 'admin';
}
function require_login(): void {
    if (!user_logged_in()) {
        $_SESSION['flash'] = ['type'=>'error','message'=>'Silakan login terlebih dahulu.'];
        redirect('login.php');
    }
}
function require_admin(): void {
    if (!admin_logged_in()) {
        $_SESSION['flash'] = ['type'=>'error','message'=>'Halaman ini hanya untuk admin.'];
        redirect('../login.php');
    }
}
function flash_message(): void {
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        echo '<div class="alert '.e($f['type']).'">'.e($f['message']).'</div>';
        unset($_SESSION['flash']);
    }
}
function rupiah($number): string {
    return 'Rp' . number_format((float)$number, 0, ',', '.');
}
function upload_image(string $field, string $folder = 'uploads/wisata'): ?string {
    if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) return null;
    $file = $_FILES[$field];
    if ($file['error'] !== UPLOAD_ERR_OK) throw new RuntimeException('Upload gambar gagal.');
    if ($file['size'] > 4 * 1024 * 1024) throw new RuntimeException('Ukuran gambar maksimal 4 MB.');
    $info = getimagesize($file['tmp_name']);
    if ($info === false) throw new RuntimeException('File yang diunggah bukan gambar yang valid.');
    $mime = $info['mime'] ?? '';
    $exts = ['image/jpeg'=>'jpg', 'image/png'=>'png', 'image/webp'=>'webp'];
    if (!isset($exts[$mime])) throw new RuntimeException('Format gambar harus JPG, PNG, atau WEBP.');
    $absolute = dirname(__DIR__) . '/' . $folder;
    if (!is_dir($absolute)) mkdir($absolute, 0775, true);
    $name = bin2hex(random_bytes(12)) . '.' . $exts[$mime];
    if (!move_uploaded_file($file['tmp_name'], $absolute . '/' . $name)) throw new RuntimeException('Gagal menyimpan gambar.');
    return $folder . '/' . $name;
}
function delete_local_upload(?string $path): void {
    if (!$path) return;
    $prefixes = ['uploads/wisata/', 'uploads/pembayaran/'];
    foreach ($prefixes as $prefix) {
        if (str_starts_with($path, $prefix)) {
            $full = dirname(__DIR__) . '/' . $path;
            if (is_file($full)) @unlink($full);
            return;
        }
    }
}
