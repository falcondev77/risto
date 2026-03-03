<?php require __DIR__.'/config.php'; require __DIR__.'/functions.php'; ?>
<!doctype html>
<html lang="it">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Ristorante</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --bg: #f8f8f7;
      --surface: #ffffff;
      --border: #e4e4e4;
      --text: #1a1a1a;
      --text-muted: #6b6b6b;
      --radius: 14px;
      --radius-sm: 9px;
    }
    body {
      font-family: 'Inter', system-ui, sans-serif;
      background: var(--bg);
      color: var(--text);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 24px 16px;
    }
    .hero { text-align: center; max-width: 400px; }
    .hero-label {
      display: inline-block;
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      color: var(--text-muted);
      margin-bottom: 16px;
    }
    .hero-title {
      font-size: clamp(28px, 6vw, 40px);
      font-weight: 600;
      letter-spacing: -.8px;
      line-height: 1.15;
      margin-bottom: 10px;
    }
    .hero-sub {
      font-size: 15px;
      color: var(--text-muted);
      line-height: 1.6;
      margin-bottom: 36px;
    }
    .cta-group {
      display: flex;
      gap: 10px;
      justify-content: center;
      flex-wrap: wrap;
    }
    .btn {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 12px 22px;
      border-radius: var(--radius-sm);
      font-family: inherit;
      font-size: 14px;
      font-weight: 500;
      text-decoration: none;
      border: 1px solid;
      transition: all .15s;
      cursor: pointer;
    }
    .btn:active { transform: scale(.98); }
    .btn-primary { background: var(--text); color: #fff; border-color: var(--text); }
    .btn-primary:hover { background: #333; border-color: #333; }
    .btn-secondary { background: var(--surface); color: var(--text); border-color: var(--border); }
    .btn-secondary:hover { background: var(--bg); border-color: var(--text); }

    .divider {
      width: 40px; height: 1px;
      background: var(--border);
      margin: 40px auto;
    }

    .footer {
      text-align: center;
      font-size: 12px;
      color: var(--text-muted);
    }
    .footer a { color: var(--text-muted); text-decoration: none; }
    .footer a:hover { color: var(--text); }
  </style>
</head>
<body>
  <div class="hero">
    <span class="hero-label">Benvenuto</span>
    <h1 class="hero-title">Ristorante</h1>
    <p class="hero-sub">Prenota un tavolo o sfoglia il nostro menu.</p>
    <div class="cta-group">
      <a class="btn btn-primary" href="/prenota.php">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
        Prenota un tavolo
      </a>
      <a class="btn btn-secondary" href="/menu.php">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        Vedi il menu
      </a>
    </div>
  </div>

  <div class="divider"></div>

  <div class="footer">
    <a href="/admin/login.php">Area riservata</a>
  </div>
</body>
</html>
