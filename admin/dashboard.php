<?php
require __DIR__.'/../config.php';
require __DIR__.'/../functions.php';
require_admin();

$mode = setting($pdo,'mode') ?? 'auto';
$defaultCap = (int)(setting($pdo,'default_capacity') ?? '0');
?>
<!doctype html>
<html lang="it">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard — Admin</title>
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
  --sidebar-w: 220px;
}
body { font-family: 'Inter', system-ui, sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; line-height: 1.55; }

/* LAYOUT */
.layout { display: flex; min-height: 100vh; }

.sidebar {
  width: var(--sidebar-w);
  background: var(--surface);
  border-right: 1px solid var(--border);
  display: flex;
  flex-direction: column;
  position: fixed;
  top: 0; left: 0; bottom: 0;
  z-index: 20;
  transition: transform .2s;
}
.sidebar-logo {
  padding: 20px 18px 16px;
  border-bottom: 1px solid var(--border);
}
.sidebar-logo .brand { font-size: 15px; font-weight: 600; letter-spacing: -.2px; }
.sidebar-logo .sub { font-size: 11px; color: var(--text-muted); margin-top: 1px; }

.sidebar-nav { flex: 1; padding: 10px 8px; }
.nav-item {
  display: flex; align-items: center; gap: 9px;
  padding: 8px 10px;
  border-radius: 8px;
  font-size: 13.5px;
  font-weight: 500;
  color: var(--text-muted);
  cursor: pointer;
  transition: all .12s;
  border: none; background: none; width: 100%; text-align: left;
}
.nav-item:hover { background: var(--bg); color: var(--text); }
.nav-item.active { background: var(--text); color: #fff; }
.nav-item .nav-icon { font-size: 15px; width: 18px; text-align: center; flex-shrink: 0; }

.sidebar-footer {
  padding: 12px 8px;
  border-top: 1px solid var(--border);
}

.main {
  margin-left: var(--sidebar-w);
  flex: 1;
  min-width: 0;
  display: flex;
  flex-direction: column;
}

.topbar {
  background: var(--surface);
  border-bottom: 1px solid var(--border);
  height: 54px;
  padding: 0 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  position: sticky;
  top: 0;
  z-index: 10;
}
.topbar-title { font-size: 14px; font-weight: 600; }
.topbar-right { display: flex; align-items: center; gap: 12px; }

.pulse {
  display: inline-flex; align-items: center; gap: 6px;
  font-size: 12px;
  color: var(--text-muted);
}
.pulse-dot {
  width: 7px; height: 7px;
  border-radius: 50%;
  background: #22c55e;
  animation: pulse 1.8s ease-in-out infinite;
}
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.3} }

.content { padding: 24px; max-width: 1100px; }

/* STATS ROW */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
  gap: 12px;
  margin-bottom: 20px;
}
.stat-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 16px 18px;
  box-shadow: var(--shadow);
}
.stat-label { font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 6px; }
.stat-value { font-size: 22px; font-weight: 600; letter-spacing: -.5px; }
.stat-sub { font-size: 12px; color: var(--text-muted); margin-top: 3px; }

/* CARDS */
.card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  margin-bottom: 16px;
  overflow: hidden;
}
.card-head {
  padding: 16px 20px;
  border-bottom: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 10px;
}
.card-head-title { font-size: 13.5px; font-weight: 600; }
.card-body { padding: 18px 20px; }
.card-body-pad { padding: 20px; }

/* SETTINGS GRID */
.settings-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 14px;
}
.settings-group { display: flex; flex-direction: column; gap: 5px; }
.settings-label { font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: .5px; }
.settings-row { display: flex; gap: 8px; }

/* FORM ELEMENTS */
input, select {
  padding: 8px 12px;
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  font-family: inherit;
  font-size: 13.5px;
  color: var(--text);
  background: var(--surface);
  outline: none;
  transition: border-color .15s, box-shadow .15s;
}
input:focus, select:focus {
  border-color: var(--text);
  box-shadow: 0 0 0 3px rgba(26,26,26,.07);
}
input::placeholder { color: var(--text-light); }

.btn {
  padding: 8px 14px;
  border-radius: var(--radius-sm);
  font-family: inherit;
  font-size: 13px;
  font-weight: 500;
  cursor: pointer;
  border: 1px solid;
  transition: all .12s;
  display: inline-flex; align-items: center; gap: 6px;
  white-space: nowrap;
}
.btn:disabled { opacity: .5; cursor: not-allowed; }
.btn-primary { background: var(--accent); color: #fff; border-color: var(--accent); }
.btn-primary:hover:not(:disabled) { background: #333; }
.btn-ghost { background: transparent; color: var(--text); border-color: var(--border); }
.btn-ghost:hover:not(:disabled) { background: var(--bg); border-color: var(--text); }
.btn-sm { padding: 5px 10px; font-size: 12px; }
.btn-success { background: #166534; color: #fff; border-color: #166534; }
.btn-success:hover:not(:disabled) { background: #15803d; }
.btn-danger { background: transparent; color: #991b1b; border-color: #fecaca; }
.btn-danger:hover:not(:disabled) { background: #fef2f2; }
.btn-warn { background: transparent; color: #92400e; border-color: #fde68a; }
.btn-warn:hover:not(:disabled) { background: #fffbeb; }

/* MODE BADGE */
.mode-badge {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 11.5px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: .4px;
}
.mode-auto { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
.mode-manual { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }

/* MSG */
.msg {
  padding: 9px 12px;
  border-radius: var(--radius-sm);
  font-size: 12.5px;
  font-weight: 500;
  display: none;
  margin-top: 10px;
}
.msg.show { display: block; }
.msg-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
.msg-error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }

/* TABLE */
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: 13px; }
thead th {
  padding: 10px 14px;
  background: var(--bg);
  border-bottom: 1px solid var(--border);
  font-size: 11px;
  font-weight: 600;
  color: var(--text-muted);
  text-transform: uppercase;
  letter-spacing: .5px;
  white-space: nowrap;
  text-align: left;
}
tbody td {
  padding: 11px 14px;
  border-bottom: 1px solid var(--border);
  vertical-align: middle;
}
tbody tr:last-child td { border-bottom: none; }
tbody tr:hover td { background: #fafafa; }
.actions { display: flex; gap: 6px; flex-wrap: wrap; }

/* STATUS BADGE */
.badge {
  display: inline-flex; align-items: center;
  padding: 2px 8px;
  border-radius: 20px;
  font-size: 10.5px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .4px;
  white-space: nowrap;
}
.badge-confirmed { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
.badge-pending { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
.badge-cancelled { background: #f5f5f5; color: #737373; border: 1px solid #e5e5e5; }
.badge-rejected { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

.empty { text-align: center; padding: 40px; color: var(--text-muted); font-size: 13.5px; }

/* HAMBURGER */
.hamburger {
  display: none;
  background: none; border: none; cursor: pointer; padding: 6px;
  flex-direction: column; gap: 4px;
}
.hamburger span { display: block; width: 20px; height: 2px; background: var(--text); border-radius: 2px; }

.overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.3); z-index: 15; }

/* TAB PANEL */
.tab-panel { display: none; }
.tab-panel.active { display: block; }

/* LOGOUT LINK */
.logout-link {
  display: flex; align-items: center; gap: 8px;
  padding: 8px 10px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 500;
  color: var(--text-muted);
  text-decoration: none;
  transition: all .12s;
  width: 100%;
}
.logout-link:hover { background: #fef2f2; color: #991b1b; }

@media (max-width: 768px) {
  .sidebar { transform: translateX(-100%); }
  .sidebar.open { transform: translateX(0); box-shadow: 4px 0 20px rgba(0,0,0,.15); }
  .overlay.show { display: block; }
  .main { margin-left: 0; }
  .hamburger { display: flex; }
  .content { padding: 16px; }
  .settings-grid { grid-template-columns: 1fr; }
  .stats-grid { grid-template-columns: 1fr 1fr; }
  .card-head { flex-direction: column; align-items: flex-start; }
  .topbar { padding: 0 16px; }
}
@media (max-width: 400px) {
  .stats-grid { grid-template-columns: 1fr; }
}
</style>
</head>
<body>
<div class="overlay" id="overlay"></div>

<div class="layout">
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
      <div class="brand">Ristorante</div>
      <div class="sub">Pannello amministratore</div>
    </div>
    <nav class="sidebar-nav">
      <button class="nav-item active" data-tab="bookings" onclick="switchTab('bookings', this)">
        <span class="nav-icon">📋</span> Prenotazioni
      </button>
      <button class="nav-item" data-tab="cancellations" onclick="switchTab('cancellations', this)">
        <span class="nav-icon">❌</span> Cancellazioni
      </button>
      <button class="nav-item" data-tab="settings" onclick="switchTab('settings', this)">
        <span class="nav-icon">⚙️</span> Impostazioni
      </button>
    </nav>
    <div class="sidebar-footer">
      <a href="/admin/logout.php" class="logout-link">
        <span>↩</span> Esci
      </a>
    </div>
  </aside>

  <div class="main">
    <header class="topbar">
      <div style="display:flex;align-items:center;gap:12px">
        <button class="hamburger" id="hamburger">
          <span></span><span></span><span></span>
        </button>
        <span class="topbar-title" id="topbarTitle">Prenotazioni</span>
      </div>
      <div class="topbar-right">
        <div class="pulse"><div class="pulse-dot"></div> Live</div>
        <span id="modeBadge" class="mode-badge <?= $mode === 'auto' ? 'mode-auto' : 'mode-manual' ?>"><?= h($mode) ?></span>
      </div>
    </header>

    <div class="content">
      <!-- STATS -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-label">Coperti oggi</div>
          <div class="stat-value" id="statTodayUsed">—</div>
          <div class="stat-sub" id="statTodayCap">su — disponibili</div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Modalità</div>
          <div class="stat-value" style="font-size:16px;margin-top:4px" id="statMode"><?= h($mode) ?></div>
          <div class="stat-sub">gestione prenotazioni</div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Coperti default</div>
          <div class="stat-value" id="statDefaultCap"><?= h((string)$defaultCap) ?></div>
          <div class="stat-sub">per giorno</div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Totale attive</div>
          <div class="stat-value" id="statTotal">—</div>
          <div class="stat-sub">prenotazioni</div>
        </div>
      </div>

      <!-- BOOKINGS TAB -->
      <div class="tab-panel active" id="tab_bookings">
        <div class="card">
          <div class="card-head">
            <span class="card-head-title">Prenotazioni attive</span>
            <button class="btn btn-ghost btn-sm" onclick="loadBookings()">↻ Aggiorna</button>
          </div>
          <div class="table-wrap">
            <div id="bookingsTable"><div class="empty">Caricamento...</div></div>
          </div>
        </div>
      </div>

      <!-- CANCELLATIONS TAB -->
      <div class="tab-panel" id="tab_cancellations">
        <div class="card">
          <div class="card-head">
            <span class="card-head-title">Cancellazioni</span>
            <button class="btn btn-ghost btn-sm" onclick="loadCancellations()">↻ Aggiorna</button>
          </div>
          <div class="table-wrap">
            <div id="cancellationsTable"><div class="empty">Caricamento...</div></div>
          </div>
        </div>
      </div>

      <!-- SETTINGS TAB -->
      <div class="tab-panel" id="tab_settings">
        <div class="card">
          <div class="card-head">
            <span class="card-head-title">Modalità gestione</span>
          </div>
          <div class="card-body-pad">
            <p style="font-size:13px;color:var(--text-muted);margin-bottom:14px">
              In modalità <b>automatica</b> le prenotazioni vengono confermate subito. In modalità <b>manuale</b> devono essere approvate dall'admin.
            </p>
            <button class="btn btn-primary" id="toggleMode">Cambia modalità</button>
            <div id="modeMsg" class="msg"></div>
          </div>
        </div>

        <div class="card">
          <div class="card-head">
            <span class="card-head-title">Coperti</span>
          </div>
          <div class="card-body-pad">
            <div class="settings-grid">
              <div class="settings-group">
                <div class="settings-label">Coperti default (tutti i giorni)</div>
                <div class="settings-row">
                  <input id="capDefault" type="number" min="0" value="<?= h((string)$defaultCap) ?>" style="flex:1" />
                  <button class="btn btn-primary" id="setCapDefault">Salva</button>
                </div>
              </div>
              <div class="settings-group">
                <div class="settings-label">Override per data specifica</div>
                <div class="settings-row" style="flex-wrap:wrap;gap:6px">
                  <input id="capDate" type="date" style="flex:1;min-width:120px" />
                  <input id="capValue" type="number" min="0" placeholder="Coperti" style="flex:1;min-width:90px" />
                  <button class="btn btn-primary" id="setCapDay">Salva</button>
                </div>
              </div>
            </div>
            <div id="settingsMsg" class="msg"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
let currentMode = '<?= h($mode) ?>';
let pollTimer = null;

function switchTab(tab, btn) {
  document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
  document.getElementById('tab_' + tab).classList.add('active');
  btn.classList.add('active');
  const titles = { bookings: 'Prenotazioni', cancellations: 'Cancellazioni', settings: 'Impostazioni' };
  document.getElementById('topbarTitle').textContent = titles[tab] || tab;
  closeSidebar();
}

function closeSidebar() {
  document.getElementById('sidebar').classList.remove('open');
  document.getElementById('overlay').classList.remove('show');
}

document.getElementById('hamburger').addEventListener('click', () => {
  document.getElementById('sidebar').classList.toggle('open');
  document.getElementById('overlay').classList.toggle('show');
});
document.getElementById('overlay').addEventListener('click', closeSidebar);

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

async function loadBookings() {
  try {
    const r = await fetch('/api/admin/list_bookings.php');
    const j = await r.json();
    if (!j.ok) return;

    currentMode = j.mode;
    document.getElementById('statTodayUsed').textContent = j.today_used;
    document.getElementById('statTodayCap').textContent = 'su ' + j.today_capacity + ' disponibili';
    document.getElementById('statMode').textContent = j.mode;
    document.getElementById('statTotal').textContent = j.rows.length;

    const modeBadge = document.getElementById('modeBadge');
    modeBadge.textContent = j.mode;
    modeBadge.className = 'mode-badge ' + (j.mode === 'auto' ? 'mode-auto' : 'mode-manual');

    const container = document.getElementById('bookingsTable');
    if (j.rows.length === 0) { container.innerHTML = '<div class="empty">Nessuna prenotazione.</div>'; return; }

    container.innerHTML = `<table>
      <thead><tr>
        <th>Data</th><th>Orario</th><th>Nome</th><th>Email</th><th>Telefono</th><th>Persone</th><th>Stato</th><th>Azioni</th>
      </tr></thead>
      <tbody>
        ${j.rows.map(x => `<tr>
          <td>${x.booking_date}</td>
          <td>${x.booking_time ? x.booking_time.substring(0,5) : '—'}</td>
          <td>${x.first_name} ${x.last_name}</td>
          <td><span style="color:var(--text-muted)">${x.email}</span></td>
          <td>${x.phone}</td>
          <td>${x.people}</td>
          <td>${badge(x.status)}</td>
          <td><div class="actions">
            ${j.mode === 'manual' && x.status === 'pending' ? `
              <button class="btn btn-success btn-sm" data-act="confirm" data-id="${x.id}">Conferma</button>
              <button class="btn btn-warn btn-sm" data-act="reject" data-id="${x.id}">Rifiuta</button>
            ` : ''}
            ${(x.status === 'confirmed' || x.status === 'pending') ? `
              <button class="btn btn-danger btn-sm" data-act="cancel" data-id="${x.id}">Cancella</button>
            ` : ''}
          </div></td>
        </tr>`).join('')}
      </tbody>
    </table>`;

    container.querySelectorAll('[data-act]').forEach(btn => {
      btn.addEventListener('click', async () => {
        btn.disabled = true;
        const fd = new FormData();
        fd.append('action', btn.dataset.act);
        fd.append('id', btn.dataset.id);
        const r2 = await fetch('/api/admin/decision.php', { method: 'POST', body: fd });
        const j2 = await r2.json();
        if (!j2.ok) { alert(j2.error || 'Errore'); btn.disabled = false; }
        else { loadBookings(); loadCancellations(); }
      });
    });
  } catch (e) { console.error('loadBookings', e); }
}

async function loadCancellations() {
  try {
    const r = await fetch('/api/admin/list_cancellations.php');
    const j = await r.json();
    if (!j.ok) return;
    const container = document.getElementById('cancellationsTable');
    if (j.rows.length === 0) { container.innerHTML = '<div class="empty">Nessuna cancellazione.</div>'; return; }
    container.innerHTML = `<table>
      <thead><tr><th>Data</th><th>Nome</th><th>Email</th><th>Persone</th><th>Cancellata il</th></tr></thead>
      <tbody>
        ${j.rows.map(x => `<tr>
          <td>${x.booking_date}</td>
          <td>${x.first_name} ${x.last_name}</td>
          <td><span style="color:var(--text-muted)">${x.email}</span></td>
          <td>${x.people}</td>
          <td><span style="color:var(--text-muted)">${x.updated_at}</span></td>
        </tr>`).join('')}
      </tbody>
    </table>`;
  } catch (e) { console.error('loadCancellations', e); }
}

document.getElementById('toggleMode').addEventListener('click', async () => {
  const btn = document.getElementById('toggleMode');
  btn.disabled = true; btn.textContent = '...';
  try {
    const r = await fetch('/api/admin/set_mode.php', { method: 'POST' });
    const j = await r.json();
    if (j.ok) {
      currentMode = j.mode;
      showMsg(document.getElementById('modeMsg'), 'Modalità cambiata: ' + j.mode, 'success');
      loadBookings();
    }
  } catch {}
  btn.disabled = false; btn.textContent = 'Cambia modalità';
});

document.getElementById('setCapDay').addEventListener('click', async () => {
  const d = document.getElementById('capDate').value;
  const v = document.getElementById('capValue').value;
  const fd = new FormData(); fd.append('date', d); fd.append('capacity', v);
  const r = await fetch('/api/admin/set_capacity.php', { method: 'POST', body: fd });
  const j = await r.json();
  showMsg(document.getElementById('settingsMsg'), j.ok ? 'Coperti salvati per il giorno selezionato.' : (j.error || 'Errore'), j.ok ? 'success' : 'error');
  if (j.ok) loadBookings();
});

document.getElementById('setCapDefault').addEventListener('click', async () => {
  const v = document.getElementById('capDefault').value;
  const fd = new FormData(); fd.append('default_capacity', v);
  const r = await fetch('/api/admin/set_capacity.php', { method: 'POST', body: fd });
  const j = await r.json();
  if (j.ok) {
    document.getElementById('statDefaultCap').textContent = v;
    showMsg(document.getElementById('settingsMsg'), 'Coperti default aggiornati.', 'success');
    loadBookings();
  } else {
    showMsg(document.getElementById('settingsMsg'), j.error || 'Errore', 'error');
  }
});

function showMsg(el, text, type) {
  el.textContent = text;
  el.className = 'msg show msg-' + type;
  setTimeout(() => { el.className = 'msg'; }, 3500);
}

function startPolling() {
  if (pollTimer) clearInterval(pollTimer);
  pollTimer = setInterval(() => {
    loadBookings();
    loadCancellations();
  }, 4000);
}

loadBookings();
loadCancellations();
startPolling();
</script>
</body>
</html>
