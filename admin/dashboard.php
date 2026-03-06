<?php
require __DIR__.'/../config.php';
require __DIR__.'/../functions.php';
require_admin();
$mode        = setting($pdo,'mode')             ?? 'auto';
$defaultCap  = (int)(setting($pdo,'default_capacity') ?? '0');
$adminBrand  = setting($pdo,'admin_brand')     ?? 'La Mozzata';
$adminSub    = setting($pdo,'admin_subbrand')  ?? 'Pannello Admin';
$adminPrimary= setting($pdo,'admin_primary')   ?? '#ec4913';
$adminBg     = setting($pdo,'admin_bg')        ?? '#221510';
$adminSurface= setting($pdo,'admin_surface')   ?? '#2e1e19';
?>
<!DOCTYPE html>
<html class="dark" lang="it">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>La Mozzata — Admin</title>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;700&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms"></script>
<script>
tailwind.config = {
  darkMode: "class",
  theme: {
    extend: {
      colors: { "primary": "<?= h($adminPrimary) ?>", "background-dark": "<?= h($adminBg) ?>", "surface-dark": "<?= h($adminSurface) ?>" },
      fontFamily: { "display": ["Manrope","sans-serif"], "serif": ["Playfair Display","serif"] }
    }
  }
}
</script>
<style>
:root {
  --primary: <?= h($adminPrimary) ?>;
  --admin-bg: <?= h($adminBg) ?>;
  --admin-surface: <?= h($adminSurface) ?>;
}
body { font-family: 'Manrope', sans-serif; background: var(--admin-bg) !important; }
.ms { font-family: 'Material Symbols Outlined'; font-style: normal; font-weight: normal; font-size: 20px; line-height: 1; white-space: nowrap; display: inline-block; }

/* TABLE */
.dtable { width:100%; border-collapse:collapse; font-size:13px; }
.dtable thead th { padding:10px 14px; background:rgba(255,255,255,.03); border-bottom:1px solid rgba(255,255,255,.07); font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.6px; text-align:left; white-space:nowrap; }
.dtable tbody td { padding:11px 14px; border-bottom:1px solid rgba(255,255,255,.05); vertical-align:middle; color:#e2e8f0; }
.dtable tbody tr:last-child td { border-bottom:none; }
.dtable tbody tr:hover td { background:rgba(255,255,255,.02); }

/* BADGES */
.bdg { display:inline-flex; align-items:center; padding:2px 9px; border-radius:99px; font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.4px; white-space:nowrap; }
.bdg-confirmed { background:rgba(34,197,94,.12); color:#4ade80; border:1px solid rgba(34,197,94,.25); }
.bdg-pending { background:rgba(251,191,36,.12); color:#fbbf24; border:1px solid rgba(251,191,36,.25); }
.bdg-cancelled { background:rgba(148,163,184,.1); color:#94a3b8; border:1px solid rgba(148,163,184,.2); }
.bdg-rejected { background:rgba(239,68,68,.12); color:#f87171; border:1px solid rgba(239,68,68,.25); }

/* BUTTONS */
.btn-p { background:var(--primary); color:#fff; border:none; border-radius:8px; padding:8px 16px; font-family:inherit; font-size:13px; font-weight:700; cursor:pointer; transition:background .15s; display:inline-flex; align-items:center; gap:6px; }
.btn-p:hover:not(:disabled) { filter:brightness(.88); }
.btn-p:disabled { opacity:.5; cursor:not-allowed; }
.btn-g { background:rgba(255,255,255,.06); color:#94a3b8; border:1px solid rgba(255,255,255,.1); border-radius:8px; padding:7px 13px; font-family:inherit; font-size:13px; font-weight:500; cursor:pointer; transition:all .12s; display:inline-flex; align-items:center; gap:6px; }
.btn-g:hover:not(:disabled) { background:rgba(255,255,255,.1); color:#e2e8f0; }
.btn-g:disabled { opacity:.4; cursor:not-allowed; }
.btn-danger { background:rgba(239,68,68,.12); color:#f87171; border:1px solid rgba(239,68,68,.25); border-radius:8px; padding:7px 13px; font-family:inherit; font-size:13px; font-weight:600; cursor:pointer; transition:all .12s; display:inline-flex; align-items:center; gap:6px; }
.btn-danger:hover:not(:disabled) { background:rgba(239,68,68,.22); color:#fca5a5; }
.abt { display:inline-flex; align-items:center; gap:4px; padding:4px 10px; border-radius:6px; font-family:inherit; font-size:11.5px; font-weight:600; cursor:pointer; border:1px solid; transition:all .12s; white-space:nowrap; }
.abt:disabled { opacity:.4; cursor:not-allowed; }
.abt-ok { background:rgba(34,197,94,.1); color:#4ade80; border-color:rgba(34,197,94,.25); }
.abt-ok:hover:not(:disabled) { background:rgba(34,197,94,.2); }
.abt-warn { background:rgba(251,191,36,.1); color:#fbbf24; border-color:rgba(251,191,36,.25); }
.abt-warn:hover:not(:disabled) { background:rgba(251,191,36,.2); }
.abt-del { background:rgba(239,68,68,.08); color:#f87171; border-color:rgba(239,68,68,.2); }
.abt-del:hover:not(:disabled) { background:rgba(239,68,68,.18); }
.abt-x { background:rgba(239,68,68,.1); color:#f87171; border-color:rgba(239,68,68,.25); }
.abt-x:hover:not(:disabled) { background:rgba(239,68,68,.2); }

/* INPUTS */
.fi { background:#f5f0ee; border:1px solid rgba(0,0,0,.12); border-radius:8px; padding:8px 12px; font-family:inherit; font-size:13.5px; color:#1a0f0a; outline:none; transition:border-color .15s, box-shadow .15s; }
.fi:focus { border-color:var(--primary); box-shadow:0 0 0 3px color-mix(in srgb, var(--primary) 20%, transparent); }
.fi::placeholder { color:#9a7060; }

/* DATE FILTER SELECT */
.date-filter { background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.1); border-radius:8px; padding:6px 32px 6px 10px; font-family:inherit; font-size:12.5px; color:#e2e8f0; outline:none; cursor:pointer; appearance:none; -webkit-appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 10px center; min-width:160px; transition:border-color .12s; }
.date-filter:focus { border-color:var(--primary); }
.date-filter option { background:#2e1e19; color:#e2e8f0; }

/* NOTIFICATION BADGE */
.notif-wrap { position:relative; }
.notif-dot { position:absolute; top:-3px; right:-4px; min-width:16px; height:16px; border-radius:99px; font-size:9.5px; font-weight:800; display:flex; align-items:center; justify-content:center; padding:0 3px; border:1.5px solid var(--admin-surface); line-height:1; }
.notif-dot.new-bookings { background:var(--primary); color:#fff; }
.notif-dot.new-cancels { background:#f87171; color:#fff; }
.notif-dot.hidden { display:none; }

/* CARD */
.card { background:var(--admin-surface); border:1px solid rgba(255,255,255,.06); border-radius:14px; overflow:hidden; margin-bottom:16px; }
.ch { padding:15px 20px; border-bottom:1px solid rgba(255,255,255,.06); display:flex; align-items:center; justify-content:space-between; gap:10px; flex-wrap:wrap; }
.cht { font-size:13.5px; font-weight:700; color:#e2e8f0; display:flex; align-items:center; gap:8px; }
.cb { padding:20px; }

/* MSG */
.msg { padding:9px 13px; border-radius:8px; font-size:12.5px; font-weight:500; display:none; margin-top:10px; }
.msg.show { display:block; }
.msg-success { background:rgba(34,197,94,.12); border:1px solid rgba(34,197,94,.25); color:#4ade80; }
.msg-error { background:rgba(239,68,68,.12); border:1px solid rgba(239,68,68,.25); color:#f87171; }

/* NAV */
.nv { display:flex; align-items:center; gap:10px; padding:9px 12px; border-radius:8px; font-size:13.5px; font-weight:500; color:#64748b; cursor:pointer; transition:all .12s; border:none; background:none; width:100%; text-align:left; text-decoration:none; }
.nv:hover { background:rgba(255,255,255,.05); color:#94a3b8; }
.nv.active { background:color-mix(in srgb, var(--primary) 15%, transparent); color:var(--primary); }

/* STAT */
.sc { background:var(--admin-surface); border:1px solid rgba(255,255,255,.06); border-radius:12px; padding:16px 18px; }
.sl { font-size:11px; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:.6px; margin-bottom:6px; }
.sv { font-size:22px; font-weight:700; color:#e2e8f0; letter-spacing:-.5px; }
.ss { font-size:12px; color:#64748b; margin-top:3px; }

/* TOGGLE */
.tgl { position:relative; display:inline-flex; align-items:center; cursor:pointer; }
.tgl input { opacity:0; width:0; height:0; position:absolute; }
.tgl-tr { width:36px; height:20px; background:rgba(255,255,255,.1); border-radius:99px; transition:background .2s; border:1px solid rgba(255,255,255,.1); }
.tgl input:checked + .tgl-tr { background:var(--primary); border-color:var(--primary); }
.tgl-th { position:absolute; left:3px; top:50%; transform:translateY(-50%); width:14px; height:14px; background:#fff; border-radius:50%; transition:transform .2s; pointer-events:none; }
.tgl input:checked ~ .tgl-th { transform:translate(16px,-50%); }

/* MINI CAL */
.mcg { display:grid; grid-template-columns:repeat(7,1fr); gap:3px; }
.mcd-n { text-align:center; font-size:10px; font-weight:700; color:#334155; padding:4px 0 6px; text-transform:uppercase; }
.mcd { aspect-ratio:1; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:500; color:#64748b; border-radius:6px; cursor:pointer; transition:all .12s; border:none; background:none; font-family:inherit; }
.mcd:hover:not(.empty):not(.past) { background:rgba(255,255,255,.07); }
.mcd.past { color:#1e293b; cursor:default; }
.mcd.empty { cursor:default; }
.mcd.today { color:var(--primary); font-weight:700; }
.mcd.closed { background:color-mix(in srgb, var(--primary) 18%, transparent); color:var(--primary); font-weight:700; border:1px solid color-mix(in srgb, var(--primary) 30%, transparent); }
.mcd.closed:hover:not(.past) { background:color-mix(in srgb, var(--primary) 30%, transparent); }

/* SLOT ROW */
.sr { display:flex; align-items:center; gap:10px; padding:10px 0; border-bottom:1px solid rgba(255,255,255,.05); }
.sr:last-child { border-bottom:none; }

/* WEEK DAY TOGGLE */
.wday { display:inline-flex; align-items:center; justify-content:center; width:44px; height:44px; border-radius:10px; font-size:12.5px; font-weight:700; cursor:pointer; border:1px solid rgba(255,255,255,.1); background:rgba(255,255,255,.04); color:#64748b; transition:all .15s; user-select:none; }
.wday:hover { background:rgba(255,255,255,.08); color:#94a3b8; }
.wday.selected { background:rgba(251,146,60,.18); color:#fb923c; border-color:rgba(251,146,60,.4); }

/* TAB */
.tab-panel { display:none; }
.tab-panel.active { display:block; }

/* MISC */
.pulse-dot { width:7px; height:7px; border-radius:50%; background:#4ade80; animation:pulse 1.8s ease-in-out infinite; }
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.25} }
.empty-st { text-align:center; padding:48px 20px; color:#334155; font-size:14px; }
.spinner { width:12px; height:12px; border:2px solid rgba(255,255,255,.2); border-top-color:#fff; border-radius:50%; animation:spin .5s linear infinite; display:none; }
.loading .spinner { display:inline-block; }
.loading .btn-label { display:none; }
@keyframes spin { to { transform:rotate(360deg); } }
.tscroll { overflow-x:auto; }

@media (max-width:768px) {
  .sidebar { transform:translateX(-100%); transition:transform .25s; }
  .sidebar.open { transform:translateX(0); box-shadow:4px 0 30px rgba(0,0,0,.4); }
  .ml-sidebar { margin-left:0!important; }
  .overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:30; }
  .overlay.show { display:block; }
}
</style>
</head>
<body class="text-slate-100 min-h-screen" style="background:var(--admin-bg)">
<div class="overlay" id="overlay"></div>

<!-- SIDEBAR -->
<aside class="sidebar fixed top-0 left-0 h-screen w-64 border-r border-white/[.06] flex flex-col z-40" style="background:var(--admin-surface)">
  <div class="px-6 py-5 border-b border-white/[.06]">
    <div class="font-serif text-xl tracking-wide" style="color:var(--primary)"><?= h($adminBrand) ?></div>
    <div class="text-xs text-slate-500 mt-0.5"><?= h($adminSub) ?></div>
  </div>
  <nav class="flex-1 p-3 space-y-0.5">
    <button class="nv active" data-tab="bookings" onclick="switchTab('bookings',this)">
      <span class="notif-wrap flex items-center gap-2 w-full">
        <span class="ms text-[18px]">calendar_month</span>
        Prenotazioni
        <span class="notif-dot new-bookings hidden" id="navBadgeBookings">0</span>
      </span>
    </button>
    <button class="nv" data-tab="cancellations" onclick="switchTab('cancellations',this)">
      <span class="notif-wrap flex items-center gap-2 w-full">
        <span class="ms text-[18px]">cancel</span>
        Cancellazioni
        <span class="notif-dot new-cancels hidden" id="navBadgeCancels">0</span>
      </span>
    </button>
    <button class="nv" data-tab="settings" onclick="switchTab('settings',this)">
      <span class="ms text-[18px]">settings</span> Impostazioni
    </button>
  </nav>
  <div class="p-3 border-t border-white/[.06]">
    <a href="/" class="nv"><span class="ms text-[18px]">open_in_new</span> Vai al sito</a>
    <a href="/superadmin/login.php" class="nv" style="font-size:12px;color:#334155"><span class="ms text-[16px]">code</span> Developer Panel</a>
    <a href="/admin/logout.php" class="nv"><span class="ms text-[18px]">logout</span> Esci</a>
  </div>
</aside>

<!-- MAIN -->
<div class="ml-sidebar ml-64 flex flex-col min-h-screen">

  <!-- TOPBAR -->
  <header class="sticky top-0 z-20 backdrop-blur-md border-b border-white/[.06] h-14 px-5 flex items-center justify-between" style="background:color-mix(in srgb, var(--admin-surface) 80%, transparent)">
    <div class="flex items-center gap-3">
      <button class="md:hidden p-1.5 flex flex-col gap-1" id="hamburger">
        <span class="block w-5 h-0.5 bg-slate-400 rounded"></span>
        <span class="block w-5 h-0.5 bg-slate-400 rounded"></span>
        <span class="block w-5 h-0.5 bg-slate-400 rounded"></span>
      </button>
      <span class="font-semibold text-sm text-slate-200" id="topbarTitle">Prenotazioni</span>
    </div>
    <div class="flex items-center gap-3">
      <div class="flex items-center gap-1.5 text-xs text-slate-500">
        <div class="pulse-dot"></div> Live
      </div>
      <div id="modeBadge" class="px-3 py-1 rounded-full text-[10.5px] font-bold uppercase tracking-wider <?= $mode==='auto' ? 'bg-green-500/10 text-green-400 border border-green-500/25' : 'bg-amber-500/10 text-amber-400 border border-amber-500/25' ?>"><?= h($mode) ?></div>
    </div>
  </header>

  <main class="flex-1 p-5 md:p-6 w-full max-w-[1100px]">

    <!-- STATS -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
      <div class="sc"><div class="sl">Coperti oggi</div><div class="sv" id="statTodayUsed">—</div><div class="ss" id="statTodayCap">su — posti</div></div>
      <div class="sc"><div class="sl">Modalità</div><div class="sv text-base mt-1" id="statMode"><?= h($mode) ?></div><div class="ss">gestione</div></div>
      <div class="sc"><div class="sl">Cap. default</div><div class="sv" id="statDefaultCap"><?= h((string)$defaultCap) ?></div><div class="ss">per slot</div></div>
      <div class="sc"><div class="sl">Prenotazioni</div><div class="sv" id="statTotal">—</div><div class="ss">attive</div></div>
    </div>

    <!-- BOOKINGS -->
    <div class="tab-panel active" id="tab_bookings">
      <div class="card">
        <div class="ch">
          <span class="cht"><span class="ms text-[18px] text-primary">calendar_month</span> Prenotazioni attive</span>
          <div class="flex items-center gap-2 flex-wrap">
            <select id="bookingDateFilter" class="date-filter" onchange="applyBookingFilter()">
              <option value="">Tutte le date</option>
            </select>
            <button class="btn-g text-xs" onclick="loadBookings()"><span class="ms text-[15px]">refresh</span> Aggiorna</button>
          </div>
        </div>
        <div class="tscroll"><div id="bookingsTable"><div class="empty-st">Caricamento...</div></div></div>
      </div>
    </div>

    <!-- CANCELLATIONS -->
    <div class="tab-panel" id="tab_cancellations">
      <div class="card">
        <div class="ch">
          <span class="cht"><span class="ms text-[18px]" style="color:#f87171">cancel</span> Cancellazioni</span>
          <div class="flex items-center gap-2 flex-wrap">
            <select id="cancelDateFilter" class="date-filter" onchange="applyCancelFilter()">
              <option value="">Tutte le date</option>
            </select>
            <button class="btn-g text-xs" onclick="loadCancellations()"><span class="ms text-[15px]">refresh</span> Aggiorna</button>
            <button class="btn-danger text-xs" id="clearAllCancelsBtn" onclick="clearAllCancellations()">
              <span class="ms text-[15px]">delete_sweep</span> Cancella Tutto
            </button>
          </div>
        </div>
        <div class="tscroll"><div id="cancellationsTable"><div class="empty-st">Caricamento...</div></div></div>
      </div>
    </div>

    <!-- SETTINGS -->
    <div class="tab-panel" id="tab_settings">
      <div class="grid md:grid-cols-2 gap-4">

        <!-- Mode -->
        <div class="card">
          <div class="ch"><span class="cht"><span class="ms text-[18px] text-amber-400">tune</span> Modalità gestione</span></div>
          <div class="cb">
            <p class="text-sm text-slate-400 mb-4 leading-relaxed">
              In modalità <strong class="text-slate-200">automatica</strong> le prenotazioni sono confermate subito.
              In modalità <strong class="text-slate-200">manuale</strong> devono essere approvate.
            </p>
            <button class="btn-p" id="toggleMode"><span class="spinner"></span><span class="btn-label">Cambia modalità</span></button>
            <div id="modeMsg" class="msg"></div>
          </div>
        </div>

        <!-- Capacity by date -->
        <div class="card">
          <div class="ch"><span class="cht"><span class="ms text-[18px]" style="color:#60a5fa">group</span> Coperti per data</span></div>
          <div class="cb space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Default (tutti i giorni)</label>
              <div class="flex gap-2">
                <input id="capDefault" type="number" min="0" value="<?= h((string)$defaultCap) ?>" class="fi flex-1" placeholder="Es. 30" />
                <button class="btn-p" id="setCapDefault">Salva</button>
              </div>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Override per data specifica</label>
              <div class="flex gap-2 flex-wrap">
                <input id="capDate" type="date" class="fi flex-1 min-w-[130px]" />
                <input id="capValue" type="number" min="0" placeholder="Coperti" class="fi w-24" />
                <button class="btn-p" id="setCapDay">Salva</button>
              </div>
            </div>
            <div id="settingsMsg" class="msg"></div>
          </div>
        </div>

        <!-- Time Slots — full width -->
        <div class="card md:col-span-2">
          <div class="ch">
            <span class="cht"><span class="ms text-[18px] text-primary">schedule</span> Orari e coperti per fascia</span>
            <button class="btn-g text-xs" onclick="loadSlots()"><span class="ms text-[15px]">refresh</span> Aggiorna</button>
          </div>
          <div class="cb">
            <div id="slotsList" class="mb-5 min-h-[40px]"></div>
            <div class="pt-4 border-t border-white/[.06]">
              <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Aggiungi orario personalizzato</p>
              <div class="flex gap-3 flex-wrap items-end">
                <div class="flex flex-col gap-1.5">
                  <label class="text-xs text-slate-500">Orario (HH:MM)</label>
                  <input id="newSlotTime" type="time" step="600" class="fi w-36" />
                </div>
                <div class="flex flex-col gap-1.5">
                  <label class="text-xs text-slate-500">Coperti</label>
                  <input id="newSlotCap" type="number" min="1" max="999" placeholder="30" class="fi w-24" />
                </div>
                <button class="btn-p" id="addSlotBtn" onclick="addSlot()">
                  <span class="spinner"></span>
                  <span class="btn-label"><span class="ms text-[15px]">add</span> Aggiungi</span>
                </button>
              </div>
              <div id="slotMsg" class="msg mt-3"></div>
            </div>
          </div>
        </div>

        <!-- Weekly Closure Days -->
        <div class="card md:col-span-2">
          <div class="ch"><span class="cht"><span class="ms text-[18px]" style="color:#fb923c">event_repeat</span> Chiusure settimanali fisse</span></div>
          <div class="cb">
            <p class="text-sm text-slate-400 mb-4 leading-relaxed">Seleziona i giorni della settimana in cui il locale è sempre chiuso. Questi giorni non saranno prenotabili.</p>
            <div class="flex flex-wrap gap-2 mb-4" id="weekDayBtns"></div>
            <button class="btn-p" id="saveWeeklyClosures"><span class="spinner"></span><span class="btn-label"><span class="ms text-[15px]">save</span> Salva giorni fissi</span></button>
            <div id="weeklyClosureMsg" class="msg mt-3"></div>
          </div>
        </div>

        <!-- Closure Days — full width -->
        <div class="card md:col-span-2">
          <div class="ch">
            <span class="cht"><span class="ms text-[18px]" style="color:#f87171">event_busy</span> Giorni di chiusura</span>
            <div class="flex items-center gap-2">
              <button class="btn-g py-1.5 px-2" id="closurePrev"><span class="ms text-[16px]">chevron_left</span></button>
              <span class="text-sm font-semibold text-slate-300 w-36 text-center" id="closureMonthLabel"></span>
              <button class="btn-g py-1.5 px-2" id="closureNext"><span class="ms text-[16px]">chevron_right</span></button>
            </div>
          </div>
          <div class="cb">
            <div class="grid md:grid-cols-2 gap-6">
              <div>
                <div class="mcg mb-1" id="closureCal"></div>
                <p class="text-xs text-slate-600 mt-3">Clicca su un giorno per impostarlo come chiusura o riaprirlo.</p>
                <div id="closureMsg" class="msg mt-3"></div>
              </div>
              <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Prossime chiusure</p>
                <div id="closureList" class="space-y-2"></div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div><!-- /settings -->

  </main>
</div><!-- /main -->

<script>
const TITLES = { bookings:'Prenotazioni', cancellations:'Cancellazioni', settings:'Impostazioni' };
let currentMode = '<?= h($mode) ?>';
let pollTimer = null;

let allBookingRows = [];
let allCancelRows = [];
let prevBookingCount = null;
let prevCancelCount = null;
let newBookingsCount = 0;
let newCancelsCount = 0;
let activeTab = 'bookings';

// TAB
function switchTab(tab, btn) {
  activeTab = tab;
  document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('[data-tab]').forEach(n => n.classList.remove('active'));
  document.getElementById('tab_' + tab).classList.add('active');
  btn.classList.add('active');
  document.getElementById('topbarTitle').textContent = TITLES[tab] || tab;
  closeSidebar();
  if (tab === 'settings') { loadSlots(); loadClosureDays(); loadWeeklyClosures(); }
  if (tab === 'bookings') { clearBadge('bookings'); }
  if (tab === 'cancellations') { clearBadge('cancellations'); }
}

function clearBadge(type) {
  if (type === 'bookings') {
    newBookingsCount = 0;
    document.getElementById('navBadgeBookings').classList.add('hidden');
  } else {
    newCancelsCount = 0;
    document.getElementById('navBadgeCancels').classList.add('hidden');
  }
}

// SIDEBAR MOBILE
function closeSidebar() {
  document.querySelector('.sidebar').classList.remove('open');
  document.getElementById('overlay').classList.remove('show');
}
document.getElementById('hamburger').addEventListener('click', () => {
  document.querySelector('.sidebar').classList.toggle('open');
  document.getElementById('overlay').classList.toggle('show');
});
document.getElementById('overlay').addEventListener('click', closeSidebar);

// HELPERS
function badge(s) {
  const m = { confirmed:['bdg-confirmed','Confermata'], pending:['bdg-pending','In attesa'], cancelled:['bdg-cancelled','Cancellata'], rejected:['bdg-rejected','Rifiutata'] };
  const [cls,lbl] = m[s] || ['bdg-cancelled',s];
  return `<span class="bdg ${cls}">${lbl}</span>`;
}
function showMsg(el, text, type, ms=4000) {
  el.textContent = text; el.className = 'msg show msg-' + type;
  if (ms) setTimeout(() => el.className = 'msg', ms);
}
function setLoading(btn, on) { btn.disabled = on; btn.classList.toggle('loading', on); }

function formatDateLabel(d) {
  if (!d) return '';
  const [y,m,day] = d.split('-');
  const months = ['gen','feb','mar','apr','mag','giu','lug','ago','set','ott','nov','dic'];
  return `${parseInt(day)} ${months[parseInt(m)-1]} ${y}`;
}

// DATE FILTER HELPERS
function todayStr() {
  return new Date().toISOString().slice(0,10);
}

function buildDateOptions(rows, selectId, field, autoToday=false) {
  const sel = document.getElementById(selectId);
  const prev = sel.value;
  const dates = [...new Set(rows.map(r => r[field]))].sort();
  const today = todayStr();
  let chosen = prev;
  if (!prev && autoToday && dates.includes(today)) chosen = today;
  const opts = ['<option value="">Tutte le date</option>'];
  dates.forEach(d => {
    const isSel = chosen === d ? ' selected' : '';
    opts.push(`<option value="${d}"${isSel}>${formatDateLabel(d)}</option>`);
  });
  sel.innerHTML = opts.join('');
}

function filterRows(rows, date, field) {
  if (!date) return rows;
  return rows.filter(r => r[field] === date);
}

// BOOKINGS
async function loadBookings() {
  try {
    const j = await fetch('/api/admin/list_bookings.php').then(r => r.json());
    if (!j.ok) return;

    currentMode = j.mode;
    document.getElementById('statTodayUsed').textContent = j.today_used;
    document.getElementById('statTodayCap').textContent = 'su ' + j.today_capacity + ' posti';
    document.getElementById('statMode').textContent = j.mode;
    document.getElementById('statTotal').textContent = j.rows.length;

    const mb = document.getElementById('modeBadge');
    mb.textContent = j.mode;
    mb.className = 'px-3 py-1 rounded-full text-[10.5px] font-bold uppercase tracking-wider ' +
      (j.mode==='auto' ? 'bg-green-500/10 text-green-400 border border-green-500/25' : 'bg-amber-500/10 text-amber-400 border border-amber-500/25');

    const newCount = j.rows.length;
    if (prevBookingCount !== null && newCount > prevBookingCount && activeTab !== 'bookings') {
      newBookingsCount += (newCount - prevBookingCount);
      const badge = document.getElementById('navBadgeBookings');
      badge.textContent = newBookingsCount > 9 ? '9+' : newBookingsCount;
      badge.classList.remove('hidden');
    }
    prevBookingCount = newCount;

    allBookingRows = j.rows;
    buildDateOptions(j.rows, 'bookingDateFilter', 'booking_date', true);
    applyBookingFilter();
  } catch(e) { console.error(e); }
}

function applyBookingFilter() {
  const date = document.getElementById('bookingDateFilter').value;
  const rows = filterRows(allBookingRows, date, 'booking_date');
  renderBookingsTable(rows);
}

function renderBookingsTable(rows) {
  const ct = document.getElementById('bookingsTable');
  if (!rows.length) { ct.innerHTML = '<div class="empty-st">Nessuna prenotazione per questa data.</div>'; return; }

  ct.innerHTML = `<table class="dtable">
    <thead><tr><th>Data</th><th>Orario</th><th>Nome</th><th>Email</th><th>Tel.</th><th>Pers.</th><th>Stato</th><th>Azioni</th></tr></thead>
    <tbody>${rows.map(x=>`<tr>
      <td class="font-semibold">${x.booking_date}</td>
      <td><span class="text-primary font-bold">${x.booking_time ? x.booking_time.substring(0,5) : '—'}</span></td>
      <td>${x.first_name} ${x.last_name}</td>
      <td class="text-slate-400 text-xs">${x.email}</td>
      <td class="text-slate-400">${x.phone}</td>
      <td class="font-bold">${x.people}</td>
      <td>${badge(x.status)}</td>
      <td><div class="flex gap-1 flex-wrap">
        ${currentMode==='manual' && x.status==='pending' ? `
          <button class="abt abt-ok" data-act="confirm" data-id="${x.id}">Conferma</button>
          <button class="abt abt-warn" data-act="reject" data-id="${x.id}">Rifiuta</button>` : ''}
        ${(x.status==='confirmed'||x.status==='pending') ? `
          <button class="abt abt-x" data-act="cancel" data-id="${x.id}">Cancella</button>` : ''}
      </div></td>
    </tr>`).join('')}</tbody></table>`;

  ct.querySelectorAll('[data-act]').forEach(btn => {
    btn.addEventListener('click', async () => {
      btn.disabled = true;
      const fd = new FormData(); fd.append('action', btn.dataset.act); fd.append('id', btn.dataset.id);
      const j2 = await fetch('/api/admin/decision.php', { method:'POST', body:fd }).then(r=>r.json());
      if (!j2.ok) { alert(j2.error||'Errore'); btn.disabled=false; }
      else { loadBookings(); loadCancellations(); }
    });
  });
}

// CANCELLATIONS
async function loadCancellations() {
  try {
    const j = await fetch('/api/admin/list_cancellations.php').then(r=>r.json());
    if (!j.ok) return;

    const newCount = j.rows.length;
    if (prevCancelCount !== null && newCount > prevCancelCount && activeTab !== 'cancellations') {
      newCancelsCount += (newCount - prevCancelCount);
      const badge = document.getElementById('navBadgeCancels');
      badge.textContent = newCancelsCount > 9 ? '9+' : newCancelsCount;
      badge.classList.remove('hidden');
    }
    prevCancelCount = newCount;

    allCancelRows = j.rows;
    buildDateOptions(j.rows, 'cancelDateFilter', 'booking_date');
    applyCancelFilter();
  } catch(e) { console.error(e); }
}

function applyCancelFilter() {
  const date = document.getElementById('cancelDateFilter').value;
  const rows = filterRows(allCancelRows, date, 'booking_date');
  renderCancellationsTable(rows);
}

function renderCancellationsTable(rows) {
  const ct = document.getElementById('cancellationsTable');
  if (!rows.length) { ct.innerHTML = '<div class="empty-st">Nessuna cancellazione.</div>'; return; }
  ct.innerHTML = `<table class="dtable">
    <thead><tr><th>Data</th><th>Nome</th><th>Email</th><th>Persone</th><th>Cancellata il</th></tr></thead>
    <tbody>${rows.map(x=>`<tr>
      <td class="font-semibold">${x.booking_date}</td>
      <td>${x.first_name} ${x.last_name}</td>
      <td class="text-slate-400 text-xs">${x.email}</td>
      <td class="font-bold">${x.people}</td>
      <td class="text-slate-400 text-xs">${x.updated_at}</td>
    </tr>`).join('')}</tbody></table>`;
}

// CLEAR ALL CANCELLATIONS
async function clearAllCancellations() {
  if (!allCancelRows.length) return;
  if (!confirm('Eliminare definitivamente tutte le cancellazioni? Questa azione non è reversibile.')) return;
  const btn = document.getElementById('clearAllCancelsBtn');
  btn.disabled = true;
  try {
    const j = await fetch('/api/admin/clear_cancellations.php', { method:'POST' }).then(r=>r.json());
    if (j.ok) {
      allCancelRows = [];
      prevCancelCount = 0;
      buildDateOptions([], 'cancelDateFilter', 'booking_date');
      renderCancellationsTable([]);
    } else { alert(j.error||'Errore'); }
  } catch { alert('Errore di rete.'); }
  btn.disabled = false;
}

// SETTINGS: MODE
document.getElementById('toggleMode').addEventListener('click', async () => {
  const btn = document.getElementById('toggleMode');
  setLoading(btn, true);
  try {
    const j = await fetch('/api/admin/set_mode.php', { method:'POST' }).then(r=>r.json());
    if (j.ok) { currentMode=j.mode; showMsg(document.getElementById('modeMsg'), 'Modalità: '+j.mode, 'success'); loadBookings(); }
  } catch {}
  setLoading(btn, false);
});

// SETTINGS: CAPACITY
document.getElementById('setCapDay').addEventListener('click', async () => {
  const fd = new FormData(); fd.append('date', document.getElementById('capDate').value); fd.append('capacity', document.getElementById('capValue').value);
  const j = await fetch('/api/admin/set_capacity.php', { method:'POST', body:fd }).then(r=>r.json());
  showMsg(document.getElementById('settingsMsg'), j.ok?'Coperti salvati.':(j.error||'Errore'), j.ok?'success':'error');
  if(j.ok) loadBookings();
});
document.getElementById('setCapDefault').addEventListener('click', async () => {
  const v = document.getElementById('capDefault').value;
  const fd = new FormData(); fd.append('default_capacity', v);
  const j = await fetch('/api/admin/set_capacity.php', { method:'POST', body:fd }).then(r=>r.json());
  if(j.ok) { document.getElementById('statDefaultCap').textContent=v; showMsg(document.getElementById('settingsMsg'),'Coperti default aggiornati.','success'); loadBookings(); }
  else showMsg(document.getElementById('settingsMsg'), j.error||'Errore', 'error');
});

// ===== TIME SLOTS =====
async function loadSlots() {
  const ct = document.getElementById('slotsList');
  ct.innerHTML = '<div class="text-sm text-slate-500 py-2">Caricamento...</div>';
  try {
    const j = await fetch('/api/admin/get_time_slots.php').then(r=>r.json());
    if (j.ok) renderSlots(j.slots);
  } catch(e) { console.error(e); }
}

function renderSlots(slots) {
  const ct = document.getElementById('slotsList');
  if (!slots.length) { ct.innerHTML = '<div class="text-sm text-slate-500 py-2">Nessun orario configurato.</div>'; return; }
  ct.innerHTML = slots.map(s => `
    <div class="sr" data-id="${s.id}">
      <div class="font-bold text-primary w-16 text-base">${s.slot_time}</div>
      <div class="flex items-center gap-2 flex-1">
        <span class="text-xs text-slate-500 hidden sm:block">Coperti:</span>
        <input type="number" min="1" max="999" value="${s.capacity}" class="fi w-20 text-sm py-1.5" data-sid="${s.id}" data-field="cap" />
      </div>
      <label class="tgl" title="${s.is_active==1?'Attivo':'Inattivo'}">
        <input type="checkbox" ${s.is_active==1?'checked':''} data-sid="${s.id}" data-field="act" />
        <div class="tgl-tr"></div><div class="tgl-th"></div>
      </label>
      <span class="text-xs w-10 ${s.is_active==1?'text-green-400':'text-slate-600'}">${s.is_active==1?'On':'Off'}</span>
      <button class="abt abt-del" data-did="${s.id}"><span class="ms text-[14px]">delete</span></button>
    </div>
  `).join('');

  ct.querySelectorAll('[data-field="cap"]').forEach(inp => {
    inp.addEventListener('change', async () => {
      const id = inp.dataset.sid;
      const row = ct.querySelector(`[data-id="${id}"]`);
      const time = row.querySelector('.text-primary').textContent.trim();
      const active = row.querySelector('[data-field="act"]').checked ? 1 : 0;
      await saveSlot(id, time, parseInt(inp.value)||1, active);
    });
  });

  ct.querySelectorAll('[data-field="act"]').forEach(chk => {
    chk.addEventListener('change', async () => {
      const id = chk.dataset.sid;
      const row = ct.querySelector(`[data-id="${id}"]`);
      const time = row.querySelector('.text-primary').textContent.trim();
      const cap = parseInt(row.querySelector('[data-field="cap"]').value)||1;
      const lbl = row.querySelector('.text-xs.w-10');
      await saveSlot(id, time, cap, chk.checked?1:0);
      lbl.textContent = chk.checked?'On':'Off';
      lbl.className = `text-xs w-10 ${chk.checked?'text-green-400':'text-slate-600'}`;
    });
  });

  ct.querySelectorAll('[data-did]').forEach(btn => {
    btn.addEventListener('click', async () => {
      if (!confirm('Eliminare questo orario?')) return;
      btn.disabled = true;
      const fd = new FormData(); fd.append('id', btn.dataset.did);
      const j = await fetch('/api/admin/delete_time_slot.php', { method:'POST', body:fd }).then(r=>r.json());
      if (j.ok) renderSlots(j.slots);
      else { alert(j.error||'Errore'); btn.disabled=false; }
    });
  });
}

async function saveSlot(id, time, capacity, active) {
  const fd = new FormData();
  fd.append('id', id); fd.append('slot_time', time); fd.append('capacity', capacity); fd.append('is_active', active);
  try {
    const j = await fetch('/api/admin/save_time_slot.php', { method:'POST', body:fd }).then(r=>r.json());
    const msg = document.getElementById('slotMsg');
    if (j.ok) showMsg(msg, 'Salvato.', 'success', 2000);
    else showMsg(msg, j.error||'Errore', 'error');
  } catch {}
}

async function addSlot() {
  const btn = document.getElementById('addSlotBtn');
  const tv = document.getElementById('newSlotTime').value;
  const cv = parseInt(document.getElementById('newSlotCap').value||'30');
  const msg = document.getElementById('slotMsg');
  if (!tv) { showMsg(msg, 'Inserisci un orario valido.', 'error'); return; }
  setLoading(btn, true);
  const fd = new FormData(); fd.append('slot_time', tv.substring(0,5)); fd.append('capacity', cv||30); fd.append('is_active', 1);
  try {
    const j = await fetch('/api/admin/save_time_slot.php', { method:'POST', body:fd }).then(r=>r.json());
    if (j.ok) { renderSlots(j.slots); document.getElementById('newSlotTime').value=''; document.getElementById('newSlotCap').value=''; showMsg(msg,'Orario aggiunto.','success'); }
    else showMsg(msg, j.error||'Errore', 'error');
  } catch { showMsg(msg,'Errore di rete.','error'); }
  setLoading(btn, false);
}

// ===== CLOSURE DAYS =====
const MN = ['Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno','Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'];
let cYear, cMonth, cDays = [];

function initClosureCal() {
  const now = new Date(); cYear = now.getFullYear(); cMonth = now.getMonth();
  loadClosureDays();
}

async function loadClosureDays() {
  const from = cYear+'-'+String(cMonth+1).padStart(2,'0')+'-01';
  const last = new Date(cYear, cMonth+1, 0).getDate();
  const to = cYear+'-'+String(cMonth+1).padStart(2,'0')+'-'+String(last).padStart(2,'0');
  const today = new Date().toISOString().slice(0,10);
  const future = new Date(Date.now()+90*86400000).toISOString().slice(0,10);
  try {
    const [j1,j2] = await Promise.all([
      fetch(`/api/admin/list_closure_days.php?from=${from}&to=${to}`).then(r=>r.json()),
      fetch(`/api/admin/list_closure_days.php?from=${today}&to=${future}`).then(r=>r.json())
    ]);
    cDays = (j1.closure_days||[]).map(x=>x.date);
    renderClosureCal();
    renderClosureList(j2.closure_days||[]);
  } catch(e) { console.error(e); }
}

function renderClosureCal() {
  document.getElementById('closureMonthLabel').textContent = MN[cMonth]+' '+cYear;
  const cal = document.getElementById('closureCal');
  const today = new Date(); today.setHours(0,0,0,0);
  const first = new Date(cYear, cMonth, 1).getDay();
  const days = new Date(cYear, cMonth+1, 0).getDate();
  const dn = ['D','L','M','M','G','V','S'];
  let html = dn.map(d=>`<div class="mcd-n">${d}</div>`).join('');
  for(let i=0;i<first;i++) html+='<div class="mcd empty"></div>';
  for(let d=1;d<=days;d++) {
    const ds = cYear+'-'+String(cMonth+1).padStart(2,'0')+'-'+String(d).padStart(2,'0');
    const td = new Date(cYear,cMonth,d);
    const dow = td.getDay();
    const closed = cDays.includes(ds) || selectedWeekDays.has(dow);
    const past = td < today && !closed;
    const isToday = td.getTime()===today.getTime();
    let cls = 'mcd';
    if (past) cls+=' past'; else if (closed) cls+=' closed'; else if (isToday) cls+=' today';
    html += `<button class="${cls}" data-date="${ds}">${d}</button>`;
  }
  cal.innerHTML = html;

  cal.querySelectorAll('[data-date]').forEach(btn => {
    btn.addEventListener('click', async () => {
      const fd = new FormData(); fd.append('date', btn.dataset.date);
      try {
        const j = await fetch('/api/admin/toggle_closure_day.php', { method:'POST', body:fd }).then(r=>r.json());
        const msg = document.getElementById('closureMsg');
        if (j.ok) {
          showMsg(msg, j.action==='added'?`${j.date}: giorno di chiusura impostato.`:`${j.date}: chiusura rimossa.`, 'success');
          loadClosureDays();
        } else showMsg(msg, j.error||'Errore', 'error');
      } catch { showMsg(document.getElementById('closureMsg'),'Errore di rete.','error'); }
    });
  });
}

function renderClosureList(rows) {
  const ct = document.getElementById('closureList');
  if (!rows.length) { ct.innerHTML='<p class="text-slate-600 text-sm">Nessuna chiusura programmata.</p>'; return; }
  ct.innerHTML = rows.map(x=>`
    <div class="flex items-center justify-between bg-white/[.03] border border-white/[.06] rounded-lg px-3 py-2.5">
      <div>
        <span class="text-sm font-bold text-primary">${x.date}</span>
        ${x.reason?`<span class="text-xs text-slate-400 ml-2">${x.reason}</span>`:''}
      </div>
      <button class="abt abt-del" data-rd="${x.date}"><span class="ms text-[14px]">close</span></button>
    </div>
  `).join('');
  ct.querySelectorAll('[data-rd]').forEach(btn => {
    btn.addEventListener('click', async () => {
      const fd = new FormData(); fd.append('date', btn.dataset.rd);
      const j = await fetch('/api/admin/toggle_closure_day.php', { method:'POST', body:fd }).then(r=>r.json());
      if (j.ok) { showMsg(document.getElementById('closureMsg'),'Chiusura rimossa.','success'); loadClosureDays(); }
    });
  });
}

document.getElementById('closurePrev').addEventListener('click', () => { cMonth--; if(cMonth<0){cMonth=11;cYear--;} loadClosureDays(); });
document.getElementById('closureNext').addEventListener('click', () => { cMonth++; if(cMonth>11){cMonth=0;cYear++;} loadClosureDays(); });

// ===== WEEKLY CLOSURES =====
const WEEK_LABELS = ['Dom','Lun','Mar','Mer','Gio','Ven','Sab'];
let selectedWeekDays = new Set();

function renderWeekDayBtns() {
  const ct = document.getElementById('weekDayBtns');
  ct.innerHTML = WEEK_LABELS.map((lbl, i) => `
    <button type="button" class="wday${selectedWeekDays.has(i) ? ' selected' : ''}" data-dow="${i}">${lbl}</button>
  `).join('');
  ct.querySelectorAll('.wday').forEach(btn => {
    btn.addEventListener('click', () => {
      const d = parseInt(btn.dataset.dow);
      if (selectedWeekDays.has(d)) selectedWeekDays.delete(d);
      else selectedWeekDays.add(d);
      btn.classList.toggle('selected', selectedWeekDays.has(d));
    });
  });
}

async function loadWeeklyClosures() {
  try {
    const j = await fetch('/api/admin/get_weekly_closures.php').then(r => r.json());
    if (j.ok) {
      selectedWeekDays = new Set(j.days.map(Number));
      renderWeekDayBtns();
    }
  } catch(e) { console.error(e); }
}

document.getElementById('saveWeeklyClosures').addEventListener('click', async () => {
  const btn = document.getElementById('saveWeeklyClosures');
  setLoading(btn, true);
  try {
    const fd = new FormData();
    fd.append('days', [...selectedWeekDays].join(','));
    const j = await fetch('/api/admin/save_weekly_closures.php', { method:'POST', body:fd }).then(r => r.json());
    const msg = document.getElementById('weeklyClosureMsg');
    if (j.ok) {
      showMsg(msg, 'Giorni di chiusura settimanali salvati.', 'success');
      loadClosureDays();
    } else showMsg(msg, j.error||'Errore', 'error');
  } catch { showMsg(document.getElementById('weeklyClosureMsg'),'Errore di rete.','error'); }
  setLoading(btn, false);
});

// INIT
loadBookings();
loadCancellations();
initClosureCal();

function startPolling() {
  if (pollTimer) clearInterval(pollTimer);
  pollTimer = setInterval(() => { loadBookings(); loadCancellations(); }, 5000);
}
startPolling();
</script>
</body>
</html>
