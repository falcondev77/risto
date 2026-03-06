<?php
require __DIR__.'/../../config.php';
require __DIR__.'/../../functions.php';
require __DIR__.'/../config.php';
require_superadmin();

$key   = trim($_POST['key']   ?? '');
$value = trim($_POST['value'] ?? '');

$allowed = [
  'site_name','site_subtitle','site_bg_url','site_location','site_hours',
  'admin_brand','admin_subbrand','admin_primary','admin_bg','admin_surface',
];

if (!in_array($key, $allowed, true)) {
  json_out(['ok' => false, 'error' => 'Chiave non valida.'], 400);
}

set_setting($pdo, $key, $value);
json_out(['ok' => true]);
