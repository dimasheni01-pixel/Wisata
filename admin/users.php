<?php
require_once __DIR__.'/_init.php';$rows=$pdo->query("SELECT id,nama,email,username,role,created_at FROM users ORDER BY created_at DESC")->fetchAll();$pageTitle='Data Pengguna';require __DIR__.'/_header.php';
?>
<div class="table-wrap"><table class="data-table"><thead><tr><th>Nama</th><th>Email</th><th>Username</th><th>Role</th><th>Bergabung</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td><?= e($r['nama']) ?></td><td><?= e($r['email']) ?></td><td><?= e($r['username']) ?></td><td><?= e($r['role']) ?></td><td><?= e($r['created_at']) ?></td></tr><?php endforeach; ?></tbody></table></div>
<?php require __DIR__.'/_footer.php'; ?>
