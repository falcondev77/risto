<?php
require __DIR__.'/config.php';
require __DIR__.'/functions.php';
$_from = date('Y-m-01');
$_to   = date('Y-m-t', strtotime('+3 months'));
$_closureDays = array_column(get_closure_days_range($pdo, $_from, $_to), 'date');
$closureJson = json_encode($_closureDays);
?>
<!doctype html>
<html lang="it">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Prenota — Ristorante</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --coral: #FF6B6B;
      --coral-light: #FF8E8E;
      --coral-dark: #E85555;
      --dark: #2D3436;
      --dark-mid: #636E72;
      --light: #F7F7F7;
      --white: #FFFFFF;
      --border: #EBEBEB;
      --shadow: 0 8px 30px rgba(0,0,0,.08);
      --shadow-sm: 0 2px 10px rgba(0,0,0,.06);
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: var(--light);
      color: var(--dark);
      min-height: 100vh;
    }

    /* HEADER */
    .app-header {
      background: var(--white);
      height: 56px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 20px;
      position: sticky;
      top: 0;
      z-index: 20;
      border-bottom: 1px solid var(--border);
    }
    .header-icon { color: var(--coral); font-size: 20px; text-decoration: none; }
    .header-title { font-size: 14px; font-weight: 600; color: var(--dark); }

    /* TABS */
    .tab-bar {
      display: flex;
      background: var(--white);
      border-bottom: 1px solid var(--border);
    }
    .tab-btn {
      flex: 1;
      padding: 13px 0;
      font-family: inherit;
      font-size: 13px;
      font-weight: 500;
      color: var(--dark-mid);
      background: none;
      border: none;
      border-bottom: 2px solid transparent;
      cursor: pointer;
      transition: all .2s;
    }
    .tab-btn.active {
      color: var(--coral);
      border-bottom-color: var(--coral);
    }

    .tab-panel { display: none; }
    .tab-panel.active { display: block; }

    /* BOOKING PANEL */
    .booking-container {
      max-width: 480px;
      margin: 0 auto;
      padding: 0 0 100px;
    }

    /* TABLE VISUAL */
    .table-visual {
      background: var(--white);
      padding: 28px 20px 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
      border-bottom: 1px solid var(--border);
    }

    .table-scene {
      position: relative;
      width: 180px;
      height: 180px;
      margin-bottom: 6px;
    }

    .table-top {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 100px;
      height: 70px;
      background: var(--dark);
      border-radius: 10px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 4px;
      padding: 8px;
      align-items: center;
      justify-items: center;
    }

    .place {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 2px;
    }

    .fork-knife {
      font-size: 10px;
      color: rgba(255,255,255,.6);
      line-height: 1;
    }

    .plate {
      width: 18px;
      height: 18px;
      border-radius: 50%;
      border: 2px solid rgba(255,255,255,.5);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .plate::after {
      content: '';
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: rgba(255,255,255,.3);
    }

    /* Chairs */
    .chair {
      position: absolute;
      width: 28px;
      height: 28px;
      background: var(--coral);
      border-radius: 6px;
      transition: opacity .2s, transform .2s;
    }
    .chair.hidden { opacity: .18; transform: scale(.85); }

    .chair-top-1 { top: 14px; left: 40px; }
    .chair-top-2 { top: 14px; right: 40px; }
    .chair-bottom-1 { bottom: 14px; left: 40px; }
    .chair-bottom-2 { bottom: 14px; right: 40px; }
    .chair-left-1 { left: 14px; top: 40px; }
    .chair-left-2 { left: 14px; bottom: 40px; }
    .chair-right-1 { right: 14px; top: 40px; }
    .chair-right-2 { right: 14px; bottom: 40px; }

    /* SECTION */
    .section {
      background: var(--white);
      padding: 20px;
      border-bottom: 1px solid var(--border);
    }

    .section-label {
      font-size: 12px;
      font-weight: 600;
      color: var(--coral);
      margin-bottom: 14px;
    }

    /* PEOPLE COUNTER */
    .counter-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .counter-num {
      font-size: 42px;
      font-weight: 700;
      color: var(--dark);
      line-height: 1;
    }
    .counter-btns { display: flex; align-items: center; gap: 18px; }
    .counter-btn {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      border: 1.5px solid var(--border);
      background: var(--white);
      font-size: 20px;
      color: var(--dark-mid);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: inherit;
      transition: all .15s;
      line-height: 1;
    }
    .counter-btn:hover { border-color: var(--coral); color: var(--coral); }
    .counter-btn:active { transform: scale(.92); }

    /* CALENDAR */
    .cal-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 14px;
    }
    .cal-month { font-size: 13px; font-weight: 500; color: var(--dark-mid); }
    .cal-nav {
      background: none;
      border: none;
      cursor: pointer;
      color: var(--dark-mid);
      font-size: 16px;
      padding: 4px;
      transition: color .15s;
    }
    .cal-nav:hover { color: var(--coral); }

    .cal-grid {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 2px;
      text-align: center;
    }
    .cal-day-name {
      font-size: 11px;
      font-weight: 600;
      color: var(--dark-mid);
      padding: 4px 0 8px;
    }
    .cal-day {
      aspect-ratio: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 13px;
      font-weight: 400;
      color: var(--dark);
      border-radius: 50%;
      cursor: pointer;
      transition: all .15s;
      border: none;
      background: none;
      font-family: inherit;
    }
    .cal-day:hover:not(.empty):not(.past):not(.selected) { background: #FFF0F0; color: var(--coral); }
    .cal-day.selected { background: var(--coral); color: var(--white); font-weight: 600; }
    .cal-day.today:not(.selected) { font-weight: 700; color: var(--coral); }
    .cal-day.past { color: #D0D0D0; cursor: default; }
    .cal-day.empty { cursor: default; }
    .cal-day.closed { color: var(--coral-light); cursor: default; text-decoration: line-through; opacity: .5; }

    /* TIME SLOTS */
    .time-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 8px;
    }
    .time-chip {
      padding: 10px 4px;
      text-align: center;
      border-radius: 10px;
      border: 1.5px solid var(--border);
      background: var(--white);
      font-family: inherit;
      font-size: 13px;
      font-weight: 500;
      color: var(--dark);
      cursor: pointer;
      transition: all .15s;
    }
    .time-chip:hover { border-color: var(--coral); color: var(--coral); background: #FFF0F0; }
    .time-chip.selected { background: var(--coral); color: var(--white); border-color: var(--coral); font-weight: 700; }

    /* DETAILS FORM */
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .form-group { display: flex; flex-direction: column; gap: 5px; }
    .form-group.full { grid-column: 1 / -1; }
    .form-label { font-size: 11px; font-weight: 600; color: var(--dark-mid); text-transform: uppercase; letter-spacing: .5px; }
    .form-input {
      padding: 10px 13px;
      border: 1.5px solid var(--border);
      border-radius: 10px;
      font-family: inherit;
      font-size: 14px;
      color: var(--dark);
      background: var(--white);
      outline: none;
      transition: border-color .15s, box-shadow .15s;
    }
    .form-input:focus { border-color: var(--coral); box-shadow: 0 0 0 3px rgba(255,107,107,.12); }
    .form-input::placeholder { color: #C8C8C8; }

    /* BOOK NOW BTN */
    .book-footer {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      padding: 16px 20px;
      background: linear-gradient(to top, var(--light) 80%, transparent);
      max-width: 480px;
      margin: 0 auto;
    }
    .book-btn {
      width: 100%;
      padding: 15px;
      background: var(--dark);
      color: var(--white);
      border: none;
      border-radius: 14px;
      font-family: inherit;
      font-size: 13px;
      font-weight: 700;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      cursor: pointer;
      transition: background .15s, transform .1s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }
    .book-btn:hover { background: #1a1f21; }
    .book-btn:active { transform: scale(.99); }
    .book-btn:disabled { opacity: .5; cursor: not-allowed; transform: none; }

    .spinner {
      width: 14px; height: 14px;
      border: 2px solid rgba(255,255,255,.3);
      border-top-color: #fff;
      border-radius: 50%;
      animation: spin .55s linear infinite;
      display: none;
    }
    .book-btn.loading .spinner { display: block; }
    .book-btn.loading .btn-label { display: none; }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* MSG */
    .msg {
      margin: 14px 20px 0;
      padding: 12px 14px;
      border-radius: 10px;
      font-size: 13px;
      font-weight: 500;
      display: none;
    }
    .msg.show { display: block; }
    .msg-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
    .msg-error { background: #fff5f5; border: 1px solid #FFCDD2; color: #C62828; }
    .msg-warning { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; }

    /* VERIFY PANEL */
    .verify-container {
      max-width: 480px;
      margin: 0 auto;
      padding: 20px 20px 100px;
    }

    .booking-card {
      background: var(--white);
      border-radius: 14px;
      padding: 16px;
      margin-bottom: 12px;
      box-shadow: var(--shadow-sm);
      border: 1.5px solid var(--border);
    }
    .booking-card-row { display: flex; justify-content: space-between; align-items: flex-start; }
    .booking-card-left { }
    .booking-card-date { font-size: 16px; font-weight: 700; margin-bottom: 3px; }
    .booking-card-people { font-size: 13px; color: var(--dark-mid); }

    .badge {
      display: inline-flex; align-items: center;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .4px;
    }
    .badge-confirmed { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .badge-pending { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
    .badge-cancelled { background: #f5f5f5; color: #737373; border: 1px solid #e5e5e5; }
    .badge-rejected { background: #fff5f5; color: #C62828; border: 1px solid #FFCDD2; }

    .cancel-btn {
      margin-top: 10px;
      padding: 7px 14px;
      border-radius: 8px;
      font-family: inherit;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      border: 1.5px solid #FFCDD2;
      color: #C62828;
      background: transparent;
      transition: all .15s;
    }
    .cancel-btn:hover { background: #fff5f5; }

    .verify-form-card {
      background: var(--white);
      border-radius: 14px;
      padding: 20px;
      box-shadow: var(--shadow-sm);
      border: 1.5px solid var(--border);
      margin-bottom: 16px;
    }

    .verify-btn {
      width: 100%;
      padding: 12px;
      background: var(--coral);
      color: var(--white);
      border: none;
      border-radius: 10px;
      font-family: inherit;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      margin-top: 12px;
      transition: background .15s;
      display: flex; align-items: center; justify-content: center; gap: 7px;
    }
    .verify-btn:hover { background: var(--coral-dark); }
    .verify-btn:disabled { opacity: .5; cursor: not-allowed; }
    .verify-btn.loading .spinner { display: block; }
    .verify-btn.loading .btn-label { display: none; }

    .empty-state {
      text-align: center;
      padding: 40px 20px;
      color: var(--dark-mid);
      font-size: 14px;
    }
    .empty-icon { font-size: 40px; margin-bottom: 10px; }

    @media (max-width: 480px) {
      .form-grid { grid-template-columns: 1fr; }
      .form-group.full { grid-column: 1; }
    }
  </style>
</head>
<body>

  <header class="app-header">
    <a href="/index.php" class="header-icon">&#9776;</a>
    <span class="header-title">Table Reservation</span>
    <span style="font-size:20px">&#9750;</span>
  </header>

  <div class="tab-bar">
    <button class="tab-btn active" onclick="switchTab('book', this)">Prenota</button>
    <button class="tab-btn" onclick="switchTab('verify', this)">Le mie prenotazioni</button>
  </div>

  <!-- BOOK TAB -->
  <div class="tab-panel active" id="tab_book">
    <div class="booking-container">

      <!-- TABLE VISUAL -->
      <div class="table-visual">
        <div class="table-scene" id="tableScene">
          <div class="chair chair-top-1"></div>
          <div class="chair chair-top-2"></div>
          <div class="chair chair-left-1"></div>
          <div class="chair chair-left-2"></div>
          <div class="chair chair-right-1"></div>
          <div class="chair chair-right-2"></div>
          <div class="chair chair-bottom-1"></div>
          <div class="chair chair-bottom-2"></div>
          <div class="table-top">
            <div class="place"><div class="plate"></div></div>
            <div class="place"><div class="plate"></div></div>
            <div class="place"><div class="plate"></div></div>
            <div class="place"><div class="plate"></div></div>
          </div>
        </div>
      </div>

      <!-- PEOPLE -->
      <div class="section">
        <div class="section-label">Quante persone?</div>
        <div class="counter-row">
          <span class="counter-num" id="peopleDisplay">2</span>
          <div class="counter-btns">
            <button class="counter-btn" id="btnMinus">&#8722;</button>
            <button class="counter-btn" id="btnPlus">&#43;</button>
          </div>
        </div>
        <input type="hidden" id="people" value="2" />
      </div>

      <!-- CALENDAR -->
      <div class="section">
        <div class="section-label">Scegli una data</div>
        <div class="cal-header">
          <button class="cal-nav" id="calPrev">&#8249;</button>
          <span class="cal-month" id="calMonthLabel"></span>
          <button class="cal-nav" id="calNext">&#8250;</button>
        </div>
        <div class="cal-grid" id="calGrid">
          <div class="cal-day-name">D</div>
          <div class="cal-day-name">L</div>
          <div class="cal-day-name">M</div>
          <div class="cal-day-name">M</div>
          <div class="cal-day-name">G</div>
          <div class="cal-day-name">V</div>
          <div class="cal-day-name">S</div>
        </div>
        <input type="hidden" id="bookingDate" />
      </div>

      <!-- TIME SLOTS -->
      <div class="section">
        <div class="section-label">Scegli un orario</div>
        <div class="time-grid" id="timeGrid">
          <div style="grid-column:1/-1;text-align:center;color:var(--dark-mid);font-size:13px;padding:8px 0">Caricamento orari...</div>
        </div>
        <input type="hidden" id="bookingTime" />
      </div>

      <!-- DETAILS -->
      <div class="section" style="padding-bottom:24px">
        <div class="section-label">I tuoi dati</div>
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">Nome</label>
            <input class="form-input" id="first_name" placeholder="Mario" required />
          </div>
          <div class="form-group">
            <label class="form-label">Cognome</label>
            <input class="form-input" id="last_name" placeholder="Rossi" required />
          </div>
          <div class="form-group">
            <label class="form-label">Telefono</label>
            <input class="form-input" id="phone" placeholder="+39 333 000 0000" required />
          </div>
          <div class="form-group">
            <label class="form-label">Email</label>
            <input class="form-input" type="email" id="email" placeholder="mario@email.it" required />
          </div>
        </div>
      </div>

      <div id="bookMsg" class="msg"></div>

    </div>

    <!-- FIXED FOOTER -->
    <div class="book-footer">
      <button class="book-btn" id="bookBtn">
        <div class="spinner"></div>
        <span class="btn-label">Prenota ora</span>
      </button>
    </div>
  </div>

  <!-- VERIFY TAB -->
  <div class="tab-panel" id="tab_verify">
    <div class="verify-container">
      <div class="verify-form-card">
        <div class="section-label" style="margin-bottom:12px;font-size:12px;font-weight:600;color:var(--coral)">La tua email</div>
        <input class="form-input" type="email" id="verifyEmail" placeholder="mario@email.it" style="width:100%" />
        <button class="verify-btn" id="verifyBtn">
          <div class="spinner" style="border-color:rgba(255,255,255,.25);border-top-color:#fff"></div>
          <span class="btn-label">Cerca prenotazioni</span>
        </button>
      </div>
      <div id="verifyMsg" class="msg" style="margin:0 0 12px"></div>
      <div id="verifyOut"></div>
    </div>
  </div>

<script>
// TABS
function switchTab(tab, btn) {
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('tab_' + tab).classList.add('active');
}

// PEOPLE COUNTER
const CHAIRS = [
  'chair-top-1','chair-top-2',
  'chair-left-1','chair-left-2',
  'chair-right-1','chair-right-2',
  'chair-bottom-1','chair-bottom-2'
];
let people = 2;

function updateChairs() {
  CHAIRS.forEach((cls, i) => {
    const el = document.querySelector('.' + cls);
    el.classList.toggle('hidden', i >= people);
  });
  document.getElementById('peopleDisplay').textContent = people;
  document.getElementById('people').value = people;
}

document.getElementById('btnPlus').addEventListener('click', () => {
  if (people < 8) { people++; updateChairs(); }
});
document.getElementById('btnMinus').addEventListener('click', () => {
  if (people > 1) { people--; updateChairs(); }
});
updateChairs();

// CLOSURE DAYS
const CLOSURE_DAYS = <?= $closureJson ?>;

// CALENDAR
const monthNames = ['Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno','Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'];
let calYear, calMonth, selectedDate = null;

function initCal() {
  const today = new Date();
  calYear = today.getFullYear();
  calMonth = today.getMonth();
  renderCal();
}

function renderCal() {
  document.getElementById('calMonthLabel').textContent = monthNames[calMonth] + ' ' + calYear;
  const grid = document.getElementById('calGrid');
  const dayNames = grid.querySelectorAll('.cal-day-name');
  grid.innerHTML = '';
  dayNames.forEach(d => grid.appendChild(d.cloneNode(true)));

  const today = new Date(); today.setHours(0,0,0,0);
  const firstDay = new Date(calYear, calMonth, 1).getDay();
  const daysInMonth = new Date(calYear, calMonth + 1, 0).getDate();

  for (let i = 0; i < firstDay; i++) {
    const empty = document.createElement('button');
    empty.className = 'cal-day empty';
    grid.appendChild(empty);
  }

  for (let d = 1; d <= daysInMonth; d++) {
    const btn = document.createElement('button');
    btn.className = 'cal-day';
    btn.textContent = d;
    const thisDate = new Date(calYear, calMonth, d);
    const dateStr = calYear + '-' + String(calMonth+1).padStart(2,'0') + '-' + String(d).padStart(2,'0');

    if (CLOSURE_DAYS.includes(dateStr)) {
      btn.classList.add('closed');
    } else if (thisDate < today) {
      btn.classList.add('past');
    } else if (thisDate.getTime() === today.getTime()) {
      btn.classList.add('today');
    }

    if (selectedDate === dateStr) btn.classList.add('selected');

    if (!btn.classList.contains('past') && !btn.classList.contains('closed')) {
      btn.addEventListener('click', () => {
        selectedDate = dateStr;
        document.getElementById('bookingDate').value = dateStr;
        renderCal();
      });
    }
    grid.appendChild(btn);
  }
}

document.getElementById('calPrev').addEventListener('click', () => {
  calMonth--; if (calMonth < 0) { calMonth = 11; calYear--; } renderCal();
});
document.getElementById('calNext').addEventListener('click', () => {
  calMonth++; if (calMonth > 11) { calMonth = 0; calYear++; } renderCal();
});
initCal();
loadSlots();

// MESSAGES
function showMsg(el, text, type) {
  el.textContent = text;
  el.className = 'msg show msg-' + type;
}

function setLoading(btn, on) {
  btn.disabled = on;
  btn.classList.toggle('loading', on);
}

// TIME SLOTS
let selectedTime = null;

async function loadSlots() {
  const grid = document.getElementById('timeGrid');
  try {
    const r = await fetch('/api/get_slots.php');
    const j = await r.json();
    if (!j.ok || !j.slots.length) {
      grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;color:var(--dark-mid);font-size:13px;padding:8px 0">Nessun orario disponibile.</div>';
      return;
    }
    grid.innerHTML = j.slots.map(s =>
      `<button class="time-chip" data-time="${s.time}">${s.time}</button>`
    ).join('');
  } catch {
    grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;color:#C62828;font-size:13px;padding:8px 0">Errore caricamento orari.</div>';
  }
}

document.getElementById('timeGrid').addEventListener('click', e => {
  const chip = e.target.closest('.time-chip');
  if (!chip) return;
  document.querySelectorAll('.time-chip').forEach(c => c.classList.remove('selected'));
  chip.classList.add('selected');
  selectedTime = chip.dataset.time;
  document.getElementById('bookingTime').value = selectedTime;
});

// BOOK
document.getElementById('bookBtn').addEventListener('click', async () => {
  const fn = document.getElementById('first_name').value.trim();
  const ln = document.getElementById('last_name').value.trim();
  const ph = document.getElementById('phone').value.trim();
  const em = document.getElementById('email').value.trim();
  const date = document.getElementById('bookingDate').value;
  const time = document.getElementById('bookingTime').value;
  const ppl = document.getElementById('people').value;
  const msg = document.getElementById('bookMsg');

  if (!fn || !ln || !ph || !em || !date || !time) {
    showMsg(msg, 'Compila tutti i campi, seleziona una data e un orario.', 'error');
    msg.scrollIntoView({ behavior: 'smooth', block: 'center' });
    return;
  }

  const btn = document.getElementById('bookBtn');
  setLoading(btn, true);
  msg.className = 'msg';

  try {
    const fd = new FormData();
    fd.append('first_name', fn);
    fd.append('last_name', ln);
    fd.append('phone', ph);
    fd.append('email', em);
    fd.append('booking_date', date);
    fd.append('booking_time', time);
    fd.append('people', ppl);
    const r = await fetch('/api/book.php', { method: 'POST', body: fd });
    const j = await r.json();
    if (j.ok) {
      showMsg(msg, j.message, 'success');
      document.getElementById('first_name').value = '';
      document.getElementById('last_name').value = '';
      document.getElementById('phone').value = '';
      document.getElementById('email').value = '';
      selectedDate = null;
      selectedTime = null;
      document.getElementById('bookingDate').value = '';
      document.getElementById('bookingTime').value = '';
      document.querySelectorAll('.time-chip').forEach(c => c.classList.remove('selected'));
      renderCal();
    } else {
      showMsg(msg, j.error || 'Errore durante la prenotazione.', 'error');
    }
    msg.scrollIntoView({ behavior: 'smooth', block: 'center' });
  } catch { showMsg(msg, 'Errore di rete. Riprova.', 'error'); }
  finally { setLoading(btn, false); }
});

// VERIFY
document.getElementById('verifyBtn').addEventListener('click', async () => {
  const email = document.getElementById('verifyEmail').value.trim();
  const msg = document.getElementById('verifyMsg');
  const out = document.getElementById('verifyOut');
  const btn = document.getElementById('verifyBtn');

  if (!email) { showMsg(msg, 'Inserisci la tua email.', 'error'); return; }

  setLoading(btn, true);
  msg.className = 'msg';
  out.innerHTML = '';

  try {
    const fd = new FormData();
    fd.append('email', email);
    const r = await fetch('/api/verify.php', { method: 'POST', body: fd });
    const j = await r.json();

    if (!j.ok) { showMsg(msg, j.error || 'Errore.', 'error'); return; }
    if (j.bookings.length === 0) {
      out.innerHTML = '<div class="empty-state"><div class="empty-icon">📭</div>Nessuna prenotazione trovata.</div>';
      return;
    }

    const badgeMap = {
      confirmed: ['badge-confirmed','Confermata'],
      pending:   ['badge-pending','In attesa'],
      cancelled: ['badge-cancelled','Cancellata'],
      rejected:  ['badge-rejected','Rifiutata'],
    };

    out.innerHTML = j.bookings.map(b => {
      const [cls, lbl] = badgeMap[b.status] || ['badge-cancelled', b.status];
      const canCancel = b.status !== 'cancelled' && b.status !== 'rejected';
      const timeLabel = b.booking_time ? ` · ${b.booking_time}` : '';
      return `<div class="booking-card">
        <div class="booking-card-row">
          <div class="booking-card-left">
            <div class="booking-card-date">${b.booking_date}${timeLabel}</div>
            <div class="booking-card-people">${b.people} ${b.people === 1 ? 'persona' : 'persone'}</div>
          </div>
          <span class="badge ${cls}">${lbl}</span>
        </div>
        ${canCancel ? `<button class="cancel-btn" data-id="${b.id}">Cancella prenotazione</button>` : ''}
      </div>`;
    }).join('');

    out.querySelectorAll('[data-id]').forEach(btn2 => {
      btn2.addEventListener('click', async () => {
        if (!confirm('Cancellare questa prenotazione?')) return;
        btn2.disabled = true; btn2.textContent = '...';
        const fd2 = new FormData();
        fd2.append('id', btn2.dataset.id);
        fd2.append('email', email);
        try {
          const r2 = await fetch('/api/cancel.php', { method: 'POST', body: fd2 });
          const j2 = await r2.json();
          if (j2.ok) { showMsg(msg, 'Prenotazione cancellata.', 'success'); document.getElementById('verifyBtn').click(); }
          else { showMsg(msg, j2.error || 'Errore.', 'error'); btn2.disabled = false; btn2.textContent = 'Cancella prenotazione'; }
        } catch { showMsg(msg, 'Errore di rete.', 'error'); btn2.disabled = false; btn2.textContent = 'Cancella prenotazione'; }
      });
    });

  } catch { showMsg(msg, 'Errore di rete. Riprova.', 'error'); }
  finally { setLoading(btn, false); }
});
</script>
</body>
</html>
