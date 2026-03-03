<?php require __DIR__.'/config.php'; require __DIR__.'/functions.php'; ?>
<!doctype html>
<html lang="it">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Ristorante</title>
  <style>
    body{font-family:system-ui;margin:0;min-height:100vh;display:grid;place-items:center;background:#fff}
    .box{display:flex;gap:12px}
    a.btn{border:1px solid #111;padding:14px 18px;border-radius:12px;text-decoration:none;color:#111}
    a.btn:hover{background:#111;color:#fff}
  </style>
</head>
<body>
  <div class="box">
    <a class="btn" href="/prenota.php">Prenota</a>
    <a class="btn" href="/menu.php">Menu</a>
  </div>
</body>
</html>
