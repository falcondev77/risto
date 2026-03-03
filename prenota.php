<?php require __DIR__.'/config.php'; require __DIR__.'/functions.php'; ?>
<!doctype html>
<html lang="it">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Prenota — Ristorante</title>
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
      --text-light: #b0b0b0;
      --accent: #1a1a1a;
      --radius: 14px;
      --radius-sm: 9px;
      --shadow: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    }
    body { font-family: 'Inter', system-ui, sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; line-height: 1.55; }

    .page-header {
      background: var(--surface);
      border-bottom: 1px solid var(--border);
      padding: 0 20px;
      height: 54px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 10;
    }
    .header-back {
      display: flex;
      align-items: center;
      gap: 6px;
      color: var(--text-muted);
      text-decoration: none;
      font-size: 13px;
      font-weight: 500;
      transition: color .15s;
    }
    .header-back:hover { color: var(--text); }
    .header-logo { font-size: 14px; font-weight: 600; letter-spacing: -.2px; }

    .container { max-width: 540px; margin: 0 auto; padding: 36px 16px 72px; }

    .page-heading { font-size: 22px; font-weight: 600; letter-spacing: -.4px; margin-bottom: 4px; }
    .page-sub { color: var(--text-muted); font-size: 13.5px; margin-bottom: 28px; }

    .card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 22px;
      box-shadow: var(--shadow);
      margin-bottom: 14px;
    }
    .card-header {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 20px;
    }
    .card-icon {
      width: 34px; height: 34px;
      background: var(--bg);
      border: 1px solid var(--border);
      border-radius: 9px;
      display: flex; align-items: center; justify-content: center;
      font-size: 16px;
      flex-shrink: 0;
    }
    .card-title { font-size: 14px; font-weight: 600; }

    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 11px; }
    .form-group { display: flex; flex-direction: column; gap: 5px; }
    .form-group.full { grid-column: 1 / -1; }

    label { font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: .6px; }

    input {
      width: 100%;
      padding: 9px 12px;
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      font-family: inherit;
      font-size: 14px;
      color: var(--text);
      background: var(--surface);
      transition: border-color .15s, box-shadow .15s;
      outline: none;
    }
    input:focus { border-color: var(--text); box-shadow: 0 0 0 3px rgba(26,26,26,.07); }
    input::placeholder { color: var(--text-light); }

    .btn {
      width: 100%;
      padding: 10px 18px;
      border-radius: var(--radius-sm);
      font-family: inherit;
      font-size: 13.5px;
      font-weight: 500;
      cursor: pointer;
      margin-top: 10px;
      border: 1px solid;
      transition: all .15s;
      display: flex; align-items: center; justify-content: center; gap: 7px;
    }
    .btn:active { transform: scale(.99); }
    .btn:disabled { opacity: .5; cursor: not-allowed; transform: none; }
    .btn-primary { background: var(--accent); color: #fff; border-color: var(--accent); }
    .btn-primary:hover:not(:disabled) { background: #333; border-color: #333; }
    .btn-secondary { background: transparent; color: var(--text); border-color: var(--border); }
    .btn-secondary:hover:not(:disabled) { background: var(--bg); border-color: var(--text); }

    .spinner {
      width: 13px; height: 13px;
      border: 2px solid rgba(255,255,255,.25);
      border-top-color: #fff;
      border-radius: 50%;
      animation: spin .55s linear infinite;
      display: none;
    }
    .btn-secondary .spinner { border-color: rgba(0,0,0,.12); border-top-color: var(--text); }
    .btn.loading .spinner { display: block; }
    .btn.loading .btn-label { display: none; }
    @keyframes spin { to { transform: rotate(360deg); } }

    .msg {
      margin-top: 12px;
      padding: 11px 13px;
      border-radius: var(--radius-sm);
      font-size: 13px;
      font-weight: 500;
      display: none;
    }
    .msg.show { display: block; }
    .msg-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
    .msg-error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
    .msg-warning { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; }

    .divider { border: none; border-top: 1px solid var(--border); margin: 18px 0; }

    .booking-list { display: flex; flex-direction: column; gap: 10px; }
    .booking-card {
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      padding: 14px;
    }
    .booking-row { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
    .booking-info { display: flex; gap: 18px; flex-wrap: wrap; }
    .binfo { }
    .binfo-label { font-size: 10.5px; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; color: var(--text-muted); margin-bottom: 2px; }
    .binfo-value { font-size: 13.5px; font-weight: 500; }

    .badge {
      display: inline-flex; align-items: center;
      padding: 3px 9px;
      border-radius: 20px;
      font-size: 10.5px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .5px;
      white-space: nowrap;
    }
    .badge-confirmed { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .badge-pending { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
    .badge-cancelled { background: #f5f5f5; color: #737373; border: 1px solid #e5e5e5; }
    .badge-rejected { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

    .btn-inline-cancel {
      margin-top: 10px;
      padding: 6px 13px;
      font-size: 12.5px;
      width: auto;
      border-color: #fecaca;
      color: #991b1b;
      background: transparent;
    }
    .btn-inline-cancel:hover:not(:disabled) { background: #fef2f2; }

    @media (max-width: 480px) {
      .form-grid { grid-template-columns: 1fr; }
      .form-group.full { grid-column: 1; }
      .container { padding: 24px 14px 56px; }
      .booking-info { gap: 12px; }
    }
  </style>
</head>
<body>
  <header class="page-header">
    <a class="header-back" href="/index.php">
      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
      Home
    </a>
    <span class="header-logo">Ristorante</span>
    <div style="width:52px"></div>
  </header>

  <div class="container">
    <h1 class="page-heading">Prenotazione</h1>
    <p class="page-sub">Prenota un tavolo o controlla le tue prenotazioni.</p>

    <div class="card">
      <div class="card-header">
        <div class="card-icon">📅</div>
        <span class="card-title">Nuova prenotazione</span>
      </div>
      <form id="bookForm">
        <div class="form-grid">
          <div class="form-group">
            <label for="fn">Nome</label>
            <input id="fn" name="first_name" placeholder="Mario" required />
          </div>
          <div class="form-group">
            <label for="ln">Cognome</label>
            <input id="ln" name="last_name" placeholder="Rossi" required />
          </div>
          <div class="form-group">
            <label for="ph">Telefono</label>
            <input id="ph" name="phone" placeholder="+39 333 000 0000" required />
          </div>
          <div class="form-group">
            <label for="em">Email</label>
            <input id="em" type="email" name="email" placeholder="mario@email.it" required />
          </div>
          <div class="form-group">
            <label for="bd">Data</label>
            <input id="bd" type="date" name="booking_date" required />
          </div>
          <div class="form-group">
            <label for="pp">Persone</label>
            <input id="pp" type="number" name="people" min="1" max="30" placeholder="2" required />
          </div>
        </div>
        <button type="submit" class="btn btn-primary" id="bookBtn">
          <div class="spinner"></div>
          <span class="btn-label">Invia prenotazione</span>
        </button>
      </form>
      <div id="bookMsg" class="msg"></div>
    </div>

    <div class="card">
      <div class="card-header">
        <div class="card-icon">🔍</div>
        <span class="card-title">Le tue prenotazioni</span>
      </div>
      <form id="verifyForm">
        <div class="form-group">
          <label for="ve">Email di prenotazione</label>
          <input id="ve" type="email" name="email" placeholder="mario@email.it" required />
        </div>
        <button type="submit" class="btn btn-secondary" id="verifyBtn">
          <div class="spinner"></div>
          <span class="btn-label">Cerca</span>
        </button>
      </form>
      <div id="verifyMsg" class="msg"></div>
      <div id="verifyOut"></div>
    </div>
  </div>

<script>
function setLoading(btn, on) {
  btn.disabled = on;
  btn.classList.toggle('loading', on);
}

function showMsg(el, text, type) {
  el.textContent = text;
  el.className = 'msg show msg-' + type;
}

function badge(status) {
  const map = {
    confirmed: ['badge-confirmed','Confermata'],
    pending:   ['badge-pending','In attesa'],
    cancelled: ['badge-cancelled','Cancellata'],
    rejected:  ['badge-rejected','Rifiutata'],
  };
  const [cls, lbl] = map[status] || ['badge-cancelled', status];
  return `<span class="badge ${cls}">${lbl}</span>`;
}

document.getElementById('bookForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const btn = document.getElementById('bookBtn');
  const msg = document.getElementById('bookMsg');
  setLoading(btn, true);
  msg.className = 'msg';
  try {
    const r = await fetch('/api/book.php', { method: 'POST', body: new FormData(e.target) });
    const j = await r.json();
    if (j.ok) { showMsg(msg, j.message, 'success'); e.target.reset(); }
    else showMsg(msg, j.error || 'Errore durante la prenotazione.', 'error');
  } catch { showMsg(msg, 'Errore di rete. Riprova.', 'error'); }
  finally { setLoading(btn, false); }
});

document.getElementById('verifyForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const btn = document.getElementById('verifyBtn');
  const msg = document.getElementById('verifyMsg');
  const out = document.getElementById('verifyOut');
  setLoading(btn, true);
  msg.className = 'msg';
  out.innerHTML = '';
  try {
    const r = await fetch('/api/verify.php', { method: 'POST', body: new FormData(e.target) });
    const j = await r.json();
    if (!j.ok) { showMsg(msg, j.error || 'Errore.', 'error'); return; }
    if (j.bookings.length === 0) { showMsg(msg, 'Nessuna prenotazione trovata per questa email.', 'warning'); return; }

    out.innerHTML = '<hr class="divider"><div class="booking-list">' + j.bookings.map(b => `
      <div class="booking-card">
        <div class="booking-row">
          <div class="booking-info">
            <div class="binfo"><div class="binfo-label">Data</div><div class="binfo-value">${b.booking_date}</div></div>
            <div class="binfo"><div class="binfo-label">Persone</div><div class="binfo-value">${b.people}</div></div>
            <div class="binfo"><div class="binfo-label">Stato</div><div class="binfo-value">${badge(b.status)}</div></div>
          </div>
        </div>
        ${b.status !== 'cancelled' && b.status !== 'rejected' ? `
          <button class="btn btn-secondary btn-inline-cancel" data-id="${b.id}">Cancella</button>
        ` : ''}
      </div>
    `).join('') + '</div>';

    out.querySelectorAll('[data-id]').forEach(btn => {
      btn.addEventListener('click', async () => {
        if (!confirm('Cancellare questa prenotazione?')) return;
        const orig = btn.textContent;
        btn.disabled = true; btn.textContent = '...';
        const fd = new FormData();
        fd.append('id', btn.dataset.id);
        fd.append('email', e.target.email.value);
        try {
          const r2 = await fetch('/api/cancel.php', { method: 'POST', body: fd });
          const j2 = await r2.json();
          if (j2.ok) { showMsg(msg, 'Prenotazione cancellata.', 'success'); e.target.dispatchEvent(new Event('submit')); }
          else { showMsg(msg, j2.error || 'Errore.', 'error'); btn.disabled = false; btn.textContent = orig; }
        } catch { showMsg(msg, 'Errore di rete.', 'error'); btn.disabled = false; btn.textContent = orig; }
      });
    });
  } catch { showMsg(msg, 'Errore di rete. Riprova.', 'error'); }
  finally { setLoading(btn, false); }
});
</script>
</body>
</html>
