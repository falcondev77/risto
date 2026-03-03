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
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin — Accesso</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
  --bg: #f8f8f7;
  --surface: #ffffff;
  --border: #e4e4e4;
  --text: #1a1a1a;
  --text-muted: #6b6b6b;
  --text-light: #b0b0b0;
  --radius: 14px;
  --radius-sm: 9px;
  --shadow-lg: 0 10px 25px -5px rgba(0,0,0,.08), 0 4px 10px -5px rgba(0,0,0,.06);
}
body {
  font-family: 'Inter', system-ui, sans-serif;
  background: var(--bg);
  color: var(--text);
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 24px 16px;
}
.login-box {
  width: 100%;
  max-width: 380px;
}
.login-header { text-align: center; margin-bottom: 28px; }
.login-icon {
  width: 44px; height: 44px;
  background: var(--text);
  border-radius: 12px;
  display: inline-flex; align-items: center; justify-content: center;
  font-size: 20px;
  margin-bottom: 14px;
}
.login-title { font-size: 20px; font-weight: 600; letter-spacing: -.3px; margin-bottom: 4px; }
.login-sub { font-size: 13.5px; color: var(--text-muted); }

.card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 28px;
  box-shadow: var(--shadow-lg);
}

.form-group { display: flex; flex-direction: column; gap: 5px; margin-bottom: 14px; }
.form-group:last-of-type { margin-bottom: 20px; }
label { font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: .6px; }
input {
  width: 100%;
  padding: 10px 13px;
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  font-family: inherit;
  font-size: 14px;
  color: var(--text);
  background: var(--surface);
  outline: none;
  transition: border-color .15s, box-shadow .15s;
}
input:focus { border-color: var(--text); box-shadow: 0 0 0 3px rgba(26,26,26,.07); }
input::placeholder { color: var(--text-light); }

.btn {
  width: 100%;
  padding: 11px;
  background: var(--text);
  color: #fff;
  border: 1px solid var(--text);
  border-radius: var(--radius-sm);
  font-family: inherit;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: background .15s;
}
.btn:hover { background: #333; border-color: #333; }
.btn:active { transform: scale(.99); }

.error-msg {
  margin-top: 14px;
  padding: 10px 12px;
  background: #fef2f2;
  border: 1px solid #fecaca;
  color: #991b1b;
  border-radius: var(--radius-sm);
  font-size: 13px;
  font-weight: 500;
}
</style>
</head>
<body>
  <div class="login-box">
    <div class="login-header">
      <div class="login-icon">🔐</div>
      <div class="login-title">Accesso Admin</div>
      <div class="login-sub">Ristorante — Pannello di controllo</div>
    </div>
    <div class="card">
      <form method="post">
        <div class="form-group">
          <label for="username">Username</label>
          <input id="username" name="username" placeholder="Inserisci username" required autocomplete="username" />
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input id="password" name="password" type="password" placeholder="••••••••" required autocomplete="current-password" />
        </div>
        <button class="btn" type="submit">Accedi</button>
      </form>
      <?php if ($error): ?>
        <div class="error-msg"><?= h($error) ?></div>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
