<?php require __DIR__.'/config.php'; require __DIR__.'/functions.php'; ?>
<!doctype html>
<html lang="it">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Prenota</title>
  <style>
    body{font-family:system-ui;margin:24px;max-width:520px}
    input,button{width:100%;padding:12px;border-radius:10px;border:1px solid #ccc;margin:8px 0}
    button{border-color:#111;background:#111;color:#fff;cursor:pointer}
    .card{border:1px solid #eee;border-radius:14px;padding:16px;margin-top:16px}
    a{color:#111}
  </style>
</head>
<body>
  <a href="/index.php">← Home</a>

  <div class="card">
    <h3>Prenota</h3>
    <form id="bookForm">
      <input name="first_name" placeholder="Nome" required />
      <input name="last_name" placeholder="Cognome" required />
      <input name="phone" placeholder="Telefono" required />
      <input type="email" name="email" placeholder="Email (per verifica)" required />
      <input type="date" name="booking_date" required />
      <input type="number" name="people" min="1" max="30" placeholder="Numero persone" required />
      <button type="submit">Invia prenotazione</button>
    </form>
    <div id="bookMsg"></div>
  </div>

  <div class="card">
    <h3>Verifica prenotazione</h3>
    <form id="verifyForm">
      <input type="email" name="email" placeholder="Inserisci email" required />
      <button type="submit">Verifica</button>
    </form>
    <div id="verifyOut"></div>
  </div>

<script>
document.getElementById('bookForm').addEventListener('submit', async (e)=>{
  e.preventDefault();
  const fd = new FormData(e.target);
  const r = await fetch('/api/book.php', {method:'POST', body:fd});
  const j = await r.json();
  document.getElementById('bookMsg').textContent = j.ok ? j.message : (j.error || 'Errore');
});

document.getElementById('verifyForm').addEventListener('submit', async (e)=>{
  e.preventDefault();
  const fd = new FormData(e.target);
  const r = await fetch('/api/verify.php', {method:'POST', body:fd});
  const j = await r.json();
  const out = document.getElementById('verifyOut');
  if(!j.ok){ out.textContent = j.error || 'Errore'; return; }
  if(j.bookings.length === 0){ out.textContent = 'Nessuna prenotazione trovata.'; return; }

  out.innerHTML = j.bookings.map(b => `
    <div style="border-top:1px solid #eee;padding-top:10px;margin-top:10px">
      <div><b>Data:</b> ${b.booking_date}</div>
      <div><b>Persone:</b> ${b.people}</div>
      <div><b>Stato:</b> ${b.status_label}</div>
      ${b.status === 'cancelled' ? '' : `<button data-id="${b.id}" style="margin-top:8px">Cancella</button>`}
    </div>
  `).join('');

  out.querySelectorAll('button[data-id]').forEach(btn=>{
    btn.addEventListener('click', async ()=>{
      const fd2 = new FormData();
      fd2.append('id', btn.dataset.id);
      fd2.append('email', e.target.email.value);
      const r2 = await fetch('/api/cancel.php', {method:'POST', body:fd2});
      const j2 = await r2.json();
      alert(j2.ok ? 'Cancellata' : (j2.error || 'Errore'));
      e.target.dispatchEvent(new Event('submit')); // refresh
    });
  });
});
</script>
</body>
</html>
