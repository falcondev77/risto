<?php
require __DIR__.'/../config.php';
require __DIR__.'/config.php';

if (!empty($_SESSION['superadmin'])) {
  header('Location: /superadmin/');
  exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user = trim($_POST['username'] ?? '');
  $pass = trim($_POST['password'] ?? '');
  if ($user === SA_USERNAME && $pass === SA_PASSWORD) {
    $_SESSION['superadmin'] = true;
    header('Location: /superadmin/');
    exit;
  }
  $error = 'Credenziali non valide.';
}
?>
<!DOCTYPE html>
<html class="dark" lang="it">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Super Admin — Login</title>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@400,0&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms"></script>
<style>
body { font-family: 'Manrope', sans-serif; }
.ms { font-family: 'Material Symbols Outlined'; font-style: normal; font-weight: normal; font-size: 20px; line-height: 1; display: inline-block; }
.fi { background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.12); border-radius: 8px; padding: 10px 14px; font-family: inherit; font-size: 14px; color: #e2e8f0; outline: none; width: 100%; transition: border-color .15s, box-shadow .15s; }
.fi:focus { border-color: #64748b; box-shadow: 0 0 0 3px rgba(100,116,139,.2); }
.fi::placeholder { color: #475569; }
.btn-sa { width: 100%; background: #1e293b; color: #e2e8f0; border: 1px solid rgba(255,255,255,.15); border-radius: 8px; padding: 11px 20px; font-family: inherit; font-size: 14px; font-weight: 700; cursor: pointer; transition: all .15s; letter-spacing: .3px; }
.btn-sa:hover { background: #273548; border-color: rgba(255,255,255,.25); }
</style>
</head>
<body class="bg-[#0f1219] min-h-screen flex items-center justify-center">

<div class="w-full max-w-sm px-4">
  <div class="bg-[#161d2b] border border-white/[.08] rounded-2xl p-8 shadow-2xl">

    <div class="flex items-center gap-3 mb-7">
      <div class="w-9 h-9 rounded-lg bg-slate-800 border border-white/10 flex items-center justify-center">
        <span class="ms text-slate-400" style="font-size:17px">code</span>
      </div>
      <div>
        <div class="text-sm font-bold text-slate-200 tracking-wide">Developer Panel</div>
        <div class="text-xs text-slate-600">Accesso riservato</div>
      </div>
    </div>

    <?php if ($error): ?>
    <div class="mb-4 px-3 py-2.5 bg-red-500/10 border border-red-500/25 text-red-400 text-xs rounded-lg font-medium">
      <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <form method="post" class="space-y-4">
      <div>
        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Username</label>
        <input class="fi" name="username" type="text" autocomplete="username" placeholder="devadmin" required />
      </div>
      <div>
        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Password</label>
        <input class="fi" name="password" type="password" autocomplete="current-password" placeholder="••••••••••••" required />
      </div>
      <button class="btn-sa mt-2" type="submit">Accedi</button>
    </form>

    <div class="mt-6 text-center">
      <a href="/admin/login.php" class="text-xs text-slate-600 hover:text-slate-500 transition-colors">Pannello Admin normale</a>
    </div>
  </div>
</div>

</body>
</html>
