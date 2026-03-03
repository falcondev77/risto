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
<title>Dashboard</title>
<style>
  body{font-family:system-ui;margin:24px}
  .row{display:flex;gap:10px;flex-wrap:wrap;align-items:center}
  .card{border:1px solid #eee;border-radius:14px;padding:14px;margin:14px 0}
  button,input,select{padding:10px;border-radius:10px;border:1px solid #ccc}
  button{background:#111;color:#fff;border-color:#111;cursor:pointer}
  table{width:100%;border-collapse:collapse}
  th,td{padding:8px;border-bottom:1px solid #eee;text-align:left;font-size:14px}
  .tabs button{background:#fff;color:#111;border-color:#ddd}
  .tabs button.active{background:#111;color:#fff;border-color:#111}
</style>
</head>
<body>
  <div class="row" style="justify-content:space-between">
    <h3>Dashboard Admin</h3>
    <a href="/admin/logout.php">Logout</a>
  </div>

  <div class="card">
    <div class="row">
      <div><b>Modalità:</b> <span id="modeLabel"><?=h($mode)?></span></div>
      <button id="toggleMode">Cambia Auto/Manuale</button>
      <div style="margin-left:auto"><b>Coperti default:</b> <span id="defaultCapLabel"><?=h((string)$defaultCap)?></span></div>
    </div>
    <hr style="border:none;border-top:1px solid #eee;margin:12px 0">
    <div class="row">
      <input id="capDate" type="date">
      <input id="capValue" type="number" min="0" placeholder="Coperti (0=disabilita)">
      <button id="setCapDay">Imposta coperti per giorno</button>

      <input id="capDefault" type="number" min="0" value="<?=h((string)$defaultCap)?>">
      <button id="setCapDefault">Imposta coperti default</button>
    </div>
    <div id="settingsMsg"></div>
  </div>

  <div class="card tabs">
    <div class="row">
      <button class="active" data-tab="bookings">Prenotazioni</button>
      <button data-tab="cancellations">Cancellazioni</button>
    </div>
  </div>

  <div class="card" id="tab_bookings">
    <div class="row" style="justify-content:space-between">
      <h4 style="margin:0">Prenotazioni (live)</h4>
      <div><b>Coperti oggi:</b> <span id="todayCount">-</span></div>
    </div>
    <div id="bookingsTable"></div>
  </div>

  <div class="card" id="tab_cancellations" style="display:none">
    <h4 style="margin:0 0 10px 0">Cancellazioni (live)</h4>
    <div id="cancellationsTable"></div>
  </div>

<script>
const tabs = document.querySelectorAll('.tabs button[data-tab]');
tabs.forEach(b=>b.addEventListener('click', ()=>{
  tabs.forEach(x=>x.classList.remove('active'));
  b.classList.add('active');
  document.getElementById('tab_bookings').style.display = (b.dataset.tab==='bookings') ? '' : 'none';
  document.getElementById('tab_cancellations').style.display = (b.dataset.tab==='cancellations') ? '' : 'none';
}));

async function loadBookings(){
  const r = await fetch('/api/admin/list_bookings.php');
  const j = await r.json();
  if(!j.ok) return;
  document.getElementById('todayCount').textContent = j.today_used + ' / ' + j.today_capacity;

  document.getElementById('bookingsTable').innerHTML = `
    <table>
      <thead><tr>
        <th>Data</th><th>Nome</th><th>Email</th><th>Tel</th><th>Persone</th><th>Stato</th><th>Azioni</th>
      </tr></thead>
      <tbody>
        ${j.rows.map(x=>`
          <tr>
            <td>${x.booking_date}</td>
            <td>${x.first_name} ${x.last_name}</td>
            <td>${x.email}</td>
            <td>${x.phone}</td>
            <td>${x.people}</td>
            <td>${x.status}</td>
            <td>
              ${j.mode==='manual' && x.status==='pending' ? `
                <button data-act="confirm" data-id="${x.id}">Conferma</button>
                <button data-act="reject" data-id="${x.id}">Rifiuta</button>
              ` : ''}
              ${(x.status==='confirmed' || x.status==='pending') ? `<button data-act="cancel" data-id="${x.id}">Cancella</button>`:''}
            </td>
          </tr>
        `).join('')}
      </tbody>
    </table>
  `;

  document.querySelectorAll('button[data-act]').forEach(btn=>{
    btn.addEventListener('click', async ()=>{
      const fd = new FormData();
      fd.append('action', btn.dataset.act);
      fd.append('id', btn.dataset.id);
      const r2 = await fetch('/api/admin/decision.php', {method:'POST', body:fd});
      const j2 = await r2.json();
      if(!j2.ok) alert(j2.error || 'Errore');
      loadBookings(); loadCancellations();
    });
  });
}

async function loadCancellations(){
  const r = await fetch('/api/admin/list_cancellations.php');
  const j = await r.json();
  if(!j.ok) return;
  document.getElementById('cancellationsTable').innerHTML = `
    <table>
      <thead><tr><th>Data</th><th>Nome</th><th>Email</th><th>Persone</th><th>Aggiornata</th></tr></thead>
      <tbody>
        ${j.rows.map(x=>`
          <tr>
            <td>${x.booking_date}</td>
            <td>${x.first_name} ${x.last_name}</td>
            <td>${x.email}</td>
            <td>${x.people}</td>
            <td>${x.updated_at}</td>
          </tr>
        `).join('')}
      </tbody>
    </table>
  `;
}

document.getElementById('toggleMode').addEventListener('click', async ()=>{
  const r = await fetch('/api/admin/set_mode.php', {method:'POST'});
  const j = await r.json();
  if(j.ok){
    document.getElementById('modeLabel').textContent = j.mode;
    loadBookings();
  }
});

document.getElementById('setCapDay').addEventListener('click', async ()=>{
  const d = document.getElementById('capDate').value;
  const v = document.getElementById('capValue').value;
  const fd = new FormData(); fd.append('date', d); fd.append('capacity', v);
  const r = await fetch('/api/admin/set_capacity.php', {method:'POST', body:fd});
  const j = await r.json();
  document.getElementById('settingsMsg').textContent = j.ok ? 'Salvato.' : (j.error || 'Errore');
  loadBookings();
});

document.getElementById('setCapDefault').addEventListener('click', async ()=>{
  const v = document.getElementById('capDefault').value;
  const fd = new FormData(); fd.append('default_capacity', v);
  const r = await fetch('/api/admin/set_capacity.php', {method:'POST', body:fd});
  const j = await r.json();
  if(j.ok){
    document.getElementById('defaultCapLabel').textContent = v;
    document.getElementById('settingsMsg').textContent = 'Salvato.';
    loadBookings();
  } else {
    document.getElementById('settingsMsg').textContent = j.error || 'Errore';
  }
});

// REAL TIME via SSE
function startStream(){
  const es = new EventSource('/api/admin/stream.php');
  es.onmessage = (ev)=>{
    loadBookings();
    loadCancellations();
  };
  es.onerror = ()=>{ /* il browser riconnette da solo */ };
}

loadBookings();
loadCancellations();
startStream();
</script>
</body>
</html>
