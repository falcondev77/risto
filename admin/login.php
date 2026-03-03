<?php
require __DIR__.'/../config.php';
require __DIR__.'/../functions.php';

$error = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $u = trim($_POST['username'] ?? '');
  $p = $_POST['password'] ?? '';

  $st = $pdo->prepare("SELECT id, password_hash FROM admin_users WHERE username=?");
  $st->execute([$u]);
  $row = $st->fetch();

  if ($row && password_verify($p, $row['password_hash'])) {
    $_SESSION['admin_id'] = (int)$row['id'];
    header('Location: /admin/dashboard.php');
    exit;
  }
  $error = 'Credenziali errate.';
}
?>
<!doctype html>
<html lang="it">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Login</title>
<style>body{font-family:system-ui;margin:24px;max-width:420px}input,button{width:100%;padding:12px;border-radius:10px;border:1px solid #ccc;margin:8px 0}button{background:#111;color:#fff;border-color:#111}</style>
</head>
<body>
  <h3>Admin</h3>
  <form method="post">
    <input name="username" placeholder="Username" required />
    <input name="password" type="password" placeholder="Password" required />
    <button>Entra</button>
  </form>
  <div style="color:#b00"><?=h($error)?></div>
</body>
</html>
