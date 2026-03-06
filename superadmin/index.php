<?php
require __DIR__.'/../config.php';
require __DIR__.'/../functions.php';
require __DIR__.'/config.php';
require_superadmin();

$siteName     = setting($pdo, 'site_name')        ?? 'La Mozzata';
$siteSubtitle = setting($pdo, 'site_subtitle')     ?? 'Sapori autentici italiani attorno all\'arte della mozzarella fresca.';
$siteBgUrl    = setting($pdo, 'site_bg_url')       ?? 'https://images.pexels.com/photos/1640777/pexels-photo-1640777.jpeg?auto=compress&cs=tinysrgb&w=1600';
$siteLocation = setting($pdo, 'site_location')     ?? 'Roma';
$siteHours    = setting($pdo, 'site_hours')        ?? '19:00 - 23:00';
$adminBrand   = setting($pdo, 'admin_brand')       ?? 'La Mozzata';
$adminSub     = setting($pdo, 'admin_subbrand')    ?? 'Pannello Admin';
$adminPrimary = setting($pdo, 'admin_primary')     ?? '#ec4913';
$adminBg      = setting($pdo, 'admin_bg')          ?? '#221510';
$adminSurface = setting($pdo, 'admin_surface')     ?? '#2e1e19';
?>
<!DOCTYPE html>
<html class="dark" lang="it">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Developer Panel</title>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@400,0&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms"></script>
<style>
body { font-family: 'Manrope', sans-serif; }
.ms { font-family: 'Material Symbols Outlined'; font-style: normal; font-weight: normal; line-height: 1; display: inline-block; }

/* SIDEBAR */
.sidebar { width: 240px; background: #0f1219; border-right: 1px solid rgba(255,255,255,.07); }
.nv { display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 8px; font-size: 13px; font-weight: 500; color: #475569; cursor: pointer; transition: all .12s; border: none; background: none; width: 100%; text-align: left; text-decoration: none; }
.nv:hover { background: rgba(255,255,255,.05); color: #94a3b8; }
.nv.active { background: rgba(100,116,139,.12); color: #94a3b8; }

/* CARDS */
.card { background: #161d2b; border: 1px solid rgba(255,255,255,.07); border-radius: 14px; overflow: hidden; margin-bottom: 16px; }
.ch { padding: 14px 20px; border-bottom: 1px solid rgba(255,255,255,.06); display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
.cht { font-size: 13px; font-weight: 700; color: #cbd5e1; display: flex; align-items: center; gap: 8px; }
.cb { padding: 20px; }

/* INPUTS */
.fi { background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1); border-radius: 8px; padding: 8px 12px; font-family: inherit; font-size: 13.5px; color: #e2e8f0; outline: none; transition: border-color .15s, box-shadow .15s; width: 100%; }
.fi:focus { border-color: #475569; box-shadow: 0 0 0 3px rgba(71,85,105,.25); }
.fi::placeholder { color: #334155; }

/* BUTTONS */
.btn-p { background: #1e293b; color: #e2e8f0; border: 1px solid rgba(255,255,255,.12); border-radius: 8px; padding: 8px 16px; font-family: inherit; font-size: 13px; font-weight: 700; cursor: pointer; transition: all .15s; display: inline-flex; align-items: center; gap: 6px; }
.btn-p:hover:not(:disabled) { background: #273548; border-color: rgba(255,255,255,.22); }
.btn-p:disabled { opacity: .5; cursor: not-allowed; }
.btn-g { background: rgba(255,255,255,.04); color: #64748b; border: 1px solid rgba(255,255,255,.08); border-radius: 8px; padding: 7px 13px; font-family: inherit; font-size: 13px; font-weight: 500; cursor: pointer; transition: all .12s; display: inline-flex; align-items: center; gap: 6px; }
.btn-g:hover { background: rgba(255,255,255,.08); color: #94a3b8; }

/* MSG */
.msg { padding: 9px 13px; border-radius: 8px; font-size: 12.5px; font-weight: 500; display: none; margin-top: 10px; }
.msg.show { display: block; }
.msg-success { background: rgba(34,197,94,.12); border: 1px solid rgba(34,197,94,.25); color: #4ade80; }
.msg-error { background: rgba(239,68,68,.12); border: 1px solid rgba(239,68,68,.25); color: #f87171; }

/* COLOR SWATCH */
.swatch-row { display: flex; align-items: center; gap: 12px; }
.swatch { width: 36px; height: 36px; border-radius: 8px; border: 2px solid rgba(255,255,255,.15); cursor: pointer; flex-shrink: 0; }

/* PREVIEW */
#indexPreview, #adminPreview { border-radius: 10px; overflow: hidden; border: 1px solid rgba(255,255,255,.1); }

/* TAB */
.tab-panel { display: none; }
.tab-panel.active { display: block; }

/* SECTION LABEL */
.slbl { font-size: 10.5px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: .6px; margin-bottom: 8px; }

.spinner { width: 12px; height: 12px; border: 2px solid rgba(255,255,255,.2); border-top-color: #fff; border-radius: 50%; animation: spin .5s linear infinite; display: none; }
.loading .spinner { display: inline-block; }
.loading .btn-label { display: none; }
@keyframes spin { to { transform: rotate(360deg); } }
</style>
</head>
<body class="bg-[#0c1018] text-slate-100 min-h-screen flex">

<!-- SIDEBAR -->
<aside class="sidebar fixed top-0 left-0 h-screen flex flex-col z-40">
  <div class="px-5 py-5 border-b border-white/[.06]">
    <div class="flex items-center gap-2.5">
      <div class="w-7 h-7 rounded bg-slate-800 border border-white/10 flex items-center justify-center">
        <span class="ms text-slate-500" style="font-size:14px">code</span>
      </div>
      <div>
        <div class="text-xs font-bold text-slate-300 tracking-wide">Developer Panel</div>
        <div class="text-[10px] text-slate-600">Super Admin</div>
      </div>
    </div>
  </div>
  <nav class="flex-1 p-3 space-y-0.5">
    <button class="nv active" data-tab="index_settings" onclick="switchTab('index_settings',this)">
      <span class="ms text-[17px]">web</span> Pagina Principale
    </button>
    <button class="nv" data-tab="admin_settings" onclick="switchTab('admin_settings',this)">
      <span class="ms text-[17px]">palette</span> Pannello Admin
    </button>
  </nav>
  <div class="p-3 border-t border-white/[.06] space-y-0.5">
    <a href="/admin/dashboard.php" class="nv"><span class="ms text-[17px]">admin_panel_settings</span> Vai al Admin</a>
    <a href="/superadmin/logout.php" class="nv"><span class="ms text-[17px]">logout</span> Esci</a>
  </div>
</aside>

<!-- MAIN -->
<div class="ml-[240px] flex-1 flex flex-col min-h-screen">

  <!-- TOPBAR -->
  <header class="sticky top-0 z-20 bg-[#0c1018]/80 backdrop-blur-md border-b border-white/[.06] h-13 px-6 flex items-center gap-3" style="height:52px">
    <span class="ms text-slate-600 text-[18px]">code</span>
    <span class="text-sm font-semibold text-slate-400" id="topbarTitle">Pagina Principale</span>
    <span class="ml-auto text-xs text-slate-700 font-mono">devadmin</span>
  </header>

  <main class="flex-1 p-6 max-w-[960px] w-full">

    <!-- INDEX SETTINGS -->
    <div class="tab-panel active" id="tab_index_settings">

      <!-- Live Preview -->
      <div class="card mb-5">
        <div class="ch"><span class="cht"><span class="ms text-[16px] text-slate-500">preview</span> Anteprima Pagina Principale</span>
          <button class="btn-g text-xs" onclick="refreshIndexPreview()"><span class="ms text-[14px]">refresh</span> Aggiorna</button>
        </div>
        <div class="cb p-0">
          <div id="indexPreview" style="height:340px; position:relative; background:#111;">
            <iframe id="indexFrame" src="/" style="width:100%;height:340px;border:none;transform-origin:top left;" scrolling="no"></iframe>
          </div>
        </div>
      </div>

      <!-- Background Image -->
      <div class="card">
        <div class="ch"><span class="cht"><span class="ms text-[16px] text-slate-500">image</span> Immagine di sfondo</span></div>
        <div class="cb space-y-3">
          <div id="bgThumbWrap" class="rounded-lg overflow-hidden" style="height:120px;background:#0a0f16;">
            <img id="bgThumb" src="<?= h($siteBgUrl) ?>" style="width:100%;height:120px;object-fit:cover;opacity:.7;" />
          </div>
          <div class="slbl">URL immagine (Pexels o altro)</div>
          <div class="flex gap-2">
            <input id="siteBgUrl" class="fi" type="url" value="<?= h($siteBgUrl) ?>" placeholder="https://images.pexels.com/..." />
            <button class="btn-g text-xs whitespace-nowrap" onclick="previewBg()"><span class="ms text-[14px]">visibility</span> Preview</button>
          </div>
          <div class="slbl mt-2">Oppure scegli una preset</div>
          <div class="flex flex-wrap gap-2" id="bgPresets"></div>
          <button class="btn-p" id="saveBgBtn" onclick="saveSetting('site_bg_url', document.getElementById('siteBgUrl').value, 'saveBgBtn', 'bgMsg')">
            <span class="spinner"></span><span class="btn-label"><span class="ms text-[14px]">save</span> Salva sfondo</span>
          </button>
          <div id="bgMsg" class="msg"></div>
        </div>
      </div>

      <!-- Testi principali -->
      <div class="card">
        <div class="ch"><span class="cht"><span class="ms text-[16px] text-slate-500">title</span> Testi pagina</span></div>
        <div class="cb space-y-4">
          <div>
            <div class="slbl">Nome ristorante (titolo grande)</div>
            <input id="siteName" class="fi" type="text" value="<?= h($siteName) ?>" placeholder="La Mozzata" maxlength="60" />
          </div>
          <div>
            <div class="slbl">Sottotitolo</div>
            <input id="siteSubtitle" class="fi" type="text" value="<?= h($siteSubtitle) ?>" placeholder="Sapori autentici..." maxlength="160" />
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <div class="slbl">Posizione</div>
              <input id="siteLocation" class="fi" type="text" value="<?= h($siteLocation) ?>" placeholder="Roma" maxlength="40" />
            </div>
            <div>
              <div class="slbl">Orari</div>
              <input id="siteHours" class="fi" type="text" value="<?= h($siteHours) ?>" placeholder="19:00 - 23:00" maxlength="40" />
            </div>
          </div>
          <button class="btn-p" id="saveTextsBtn" onclick="saveIndexTexts()">
            <span class="spinner"></span><span class="btn-label"><span class="ms text-[14px]">save</span> Salva testi</span>
          </button>
          <div id="textsMsg" class="msg"></div>
        </div>
      </div>

    </div><!-- /index_settings -->

    <!-- ADMIN SETTINGS -->
    <div class="tab-panel" id="tab_admin_settings">

      <!-- Live Preview -->
      <div class="card mb-5">
        <div class="ch"><span class="cht"><span class="ms text-[16px] text-slate-500">preview</span> Anteprima Pannello Admin</span>
          <button class="btn-g text-xs" onclick="refreshAdminPreview()"><span class="ms text-[14px]">refresh</span> Aggiorna</button>
        </div>
        <div class="cb p-0">
          <div id="adminPreview" style="height:340px; position:relative; background:#111;">
            <iframe id="adminFrame" src="/admin/dashboard.php" style="width:100%;height:340px;border:none;" scrolling="no"></iframe>
          </div>
        </div>
      </div>

      <!-- Brand -->
      <div class="card">
        <div class="ch"><span class="cht"><span class="ms text-[16px] text-slate-500">badge</span> Nome brand nel pannello admin</span></div>
        <div class="cb space-y-3">
          <div>
            <div class="slbl">Testo in alto a sinistra (nome)</div>
            <input id="adminBrand" class="fi" type="text" value="<?= h($adminBrand) ?>" placeholder="La Mozzata" maxlength="50" />
          </div>
          <div>
            <div class="slbl">Testo secondario (sottotitolo)</div>
            <input id="adminSub" class="fi" type="text" value="<?= h($adminSub) ?>" placeholder="Pannello Admin" maxlength="50" />
          </div>
          <button class="btn-p" id="saveBrandBtn" onclick="saveAdminBrand()">
            <span class="spinner"></span><span class="btn-label"><span class="ms text-[14px]">save</span> Salva brand</span>
          </button>
          <div id="brandMsg" class="msg"></div>
        </div>
      </div>

      <!-- Colors -->
      <div class="card">
        <div class="ch"><span class="cht"><span class="ms text-[16px] text-slate-500">palette</span> Colori pannello admin</span></div>
        <div class="cb space-y-5">

          <div>
            <div class="slbl">Colore primario (bottoni, accenti)</div>
            <div class="swatch-row">
              <input type="color" id="adminPrimaryPicker" value="<?= h($adminPrimary) ?>" class="swatch" oninput="syncColorInput('adminPrimary','adminPrimaryPicker')" />
              <input id="adminPrimary" class="fi" type="text" value="<?= h($adminPrimary) ?>" placeholder="#ec4913" maxlength="7" oninput="syncColorPicker('adminPrimary','adminPrimaryPicker')" />
            </div>
            <div class="flex flex-wrap gap-1.5 mt-2" id="primaryPresets"></div>
          </div>

          <div>
            <div class="slbl">Colore sfondo principale</div>
            <div class="swatch-row">
              <input type="color" id="adminBgPicker" value="<?= h($adminBg) ?>" class="swatch" oninput="syncColorInput('adminBg','adminBgPicker')" />
              <input id="adminBg" class="fi" type="text" value="<?= h($adminBg) ?>" placeholder="#221510" maxlength="7" oninput="syncColorPicker('adminBg','adminBgPicker')" />
            </div>
          </div>

          <div>
            <div class="slbl">Colore superfici (card, sidebar)</div>
            <div class="swatch-row">
              <input type="color" id="adminSurfacePicker" value="<?= h($adminSurface) ?>" class="swatch" oninput="syncColorInput('adminSurface','adminSurfacePicker')" />
              <input id="adminSurface" class="fi" type="text" value="<?= h($adminSurface) ?>" placeholder="#2e1e19" maxlength="7" oninput="syncColorPicker('adminSurface','adminSurfacePicker')" />
            </div>
          </div>

          <button class="btn-p" id="saveColorsBtn" onclick="saveAdminColors()">
            <span class="spinner"></span><span class="btn-label"><span class="ms text-[14px]">save</span> Salva colori</span>
          </button>
          <div id="colorsMsg" class="msg"></div>
        </div>
      </div>

    </div><!-- /admin_settings -->

  </main>
</div>

<script>
const TITLES = { index_settings: 'Pagina Principale', admin_settings: 'Pannello Admin' };

function switchTab(tab, btn) {
  document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('[data-tab]').forEach(n => n.classList.remove('active'));
  document.getElementById('tab_' + tab).classList.add('active');
  btn.classList.add('active');
  document.getElementById('topbarTitle').textContent = TITLES[tab] || tab;
}

// ---- PREVIEW ----
function refreshIndexPreview() { document.getElementById('indexFrame').src = '/?_=' + Date.now(); }
function refreshAdminPreview()  { document.getElementById('adminFrame').src = '/admin/dashboard.php?_=' + Date.now(); }

// ---- BG PRESETS ----
const BG_PRESETS = [
  { label: 'Cucina italiana', url: 'https://images.pexels.com/photos/1640777/pexels-photo-1640777.jpeg?auto=compress&cs=tinysrgb&w=1600' },
  { label: 'Pizza', url: 'https://images.pexels.com/photos/315755/pexels-photo-315755.jpeg?auto=compress&cs=tinysrgb&w=1600' },
  { label: 'Tavolo ristorante', url: 'https://images.pexels.com/photos/262047/pexels-photo-262047.jpeg?auto=compress&cs=tinysrgb&w=1600' },
  { label: 'Vino', url: 'https://images.pexels.com/photos/1407846/pexels-photo-1407846.jpeg?auto=compress&cs=tinysrgb&w=1600' },
  { label: 'Pasta', url: 'https://images.pexels.com/photos/1435895/pexels-photo-1435895.jpeg?auto=compress&cs=tinysrgb&w=1600' },
  { label: 'Sala buia', url: 'https://images.pexels.com/photos/941861/pexels-photo-941861.jpeg?auto=compress&cs=tinysrgb&w=1600' },
];

const PRIMARY_PRESETS = ['#ec4913','#e53e3e','#dd6b20','#d69e2e','#38a169','#3182ce','#805ad5','#d53f8c','#1a202c','#2d3748'];

function buildBgPresets() {
  const ct = document.getElementById('bgPresets');
  ct.innerHTML = BG_PRESETS.map(p => `
    <button type="button" onclick="selectBgPreset('${p.url}')"
      style="width:80px;height:54px;border-radius:7px;overflow:hidden;border:2px solid transparent;padding:0;cursor:pointer;transition:border-color .12s;"
      title="${p.label}"
      onmouseenter="this.style.borderColor='rgba(255,255,255,.35)'" onmouseleave="this.style.borderColor='transparent'">
      <img src="${p.url}" style="width:100%;height:100%;object-fit:cover;opacity:.75;" />
    </button>
  `).join('');
}

function selectBgPreset(url) {
  document.getElementById('siteBgUrl').value = url;
  document.getElementById('bgThumb').src = url;
}

function previewBg() {
  const url = document.getElementById('siteBgUrl').value.trim();
  if (url) document.getElementById('bgThumb').src = url;
}

function buildPrimaryPresets() {
  const ct = document.getElementById('primaryPresets');
  ct.innerHTML = PRIMARY_PRESETS.map(c => `
    <button type="button" onclick="selectPrimary('${c}')"
      style="width:26px;height:26px;border-radius:6px;background:${c};border:2px solid rgba(255,255,255,.15);cursor:pointer;transition:transform .12s;"
      onmouseenter="this.style.transform='scale(1.18)'" onmouseleave="this.style.transform='scale(1)'">
    </button>
  `).join('');
}

function selectPrimary(c) {
  document.getElementById('adminPrimary').value = c;
  document.getElementById('adminPrimaryPicker').value = c;
}

function syncColorInput(inputId, pickerId) {
  document.getElementById(inputId).value = document.getElementById(pickerId).value;
}
function syncColorPicker(inputId, pickerId) {
  const v = document.getElementById(inputId).value;
  if (/^#[0-9a-fA-F]{6}$/.test(v)) document.getElementById(pickerId).value = v;
}

// ---- MSG ----
function showMsg(el, text, type) {
  el.textContent = text;
  el.className = 'msg show msg-' + type;
  setTimeout(() => { el.classList.remove('show'); }, 3500);
}

function setLoading(btn, on) {
  btn.classList.toggle('loading', on);
  btn.disabled = on;
}

// ---- SAVE HELPERS ----
async function saveSetting(key, value, btnId, msgId) {
  const btn = document.getElementById(btnId);
  const msg = document.getElementById(msgId);
  setLoading(btn, true);
  try {
    const fd = new FormData();
    fd.append('key', key);
    fd.append('value', value);
    const j = await fetch('/superadmin/api/save_setting.php', { method: 'POST', body: fd }).then(r => r.json());
    showMsg(msg, j.ok ? 'Salvato.' : (j.error || 'Errore'), j.ok ? 'success' : 'error');
  } catch { showMsg(msg, 'Errore di rete.', 'error'); }
  setLoading(btn, false);
}

async function saveMultiple(pairs, btnId, msgId) {
  const btn = document.getElementById(btnId);
  const msg = document.getElementById(msgId);
  setLoading(btn, true);
  try {
    const fd = new FormData();
    fd.append('pairs', JSON.stringify(pairs));
    const j = await fetch('/superadmin/api/save_settings.php', { method: 'POST', body: fd }).then(r => r.json());
    showMsg(msg, j.ok ? 'Salvato.' : (j.error || 'Errore'), j.ok ? 'success' : 'error');
    if (j.ok) setTimeout(refreshAdminPreview, 400);
  } catch { showMsg(msg, 'Errore di rete.', 'error'); }
  setLoading(btn, false);
}

function saveIndexTexts() {
  const pairs = [
    ['site_name',     document.getElementById('siteName').value],
    ['site_subtitle', document.getElementById('siteSubtitle').value],
    ['site_location', document.getElementById('siteLocation').value],
    ['site_hours',    document.getElementById('siteHours').value],
  ];
  saveMultipleIndex(pairs, 'saveTextsBtn', 'textsMsg');
}

async function saveMultipleIndex(pairs, btnId, msgId) {
  const btn = document.getElementById(btnId);
  const msg = document.getElementById(msgId);
  setLoading(btn, true);
  try {
    const fd = new FormData();
    fd.append('pairs', JSON.stringify(pairs));
    const j = await fetch('/superadmin/api/save_settings.php', { method: 'POST', body: fd }).then(r => r.json());
    showMsg(msg, j.ok ? 'Salvato.' : (j.error || 'Errore'), j.ok ? 'success' : 'error');
    if (j.ok) setTimeout(refreshIndexPreview, 400);
  } catch { showMsg(msg, 'Errore di rete.', 'error'); }
  setLoading(btn, false);
}

function saveAdminBrand() {
  saveMultiple([
    ['admin_brand',    document.getElementById('adminBrand').value],
    ['admin_subbrand', document.getElementById('adminSub').value],
  ], 'saveBrandBtn', 'brandMsg');
}

function saveAdminColors() {
  saveMultiple([
    ['admin_primary', document.getElementById('adminPrimary').value],
    ['admin_bg',      document.getElementById('adminBg').value],
    ['admin_surface', document.getElementById('adminSurface').value],
  ], 'saveColorsBtn', 'colorsMsg');
}

buildBgPresets();
buildPrimaryPresets();
</script>

</body>
</html>
