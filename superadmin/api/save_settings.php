<?php
require __DIR__.'/../../config.php';
require __DIR__.'/../../functions.php';
require __DIR__.'/../config.php';
require_superadmin();

$allowed = [
  'site_name','site_subtitle','site_bg_url','site_location','site_hours',
  'admin_brand','admin_subbrand','admin_primary','admin_bg','admin_surface',
];

$raw   = $_POST['pairs'] ?? '';
$pairs = json_decode($raw, true);

if (!is_array($pairs)) {
  json_out(['ok' => false, 'error' => 'Payload non valido.'], 400);
}

foreach ($pairs as $pair) {
  if (!is_array($pair) || count($pair) < 2) continue;
  [$key, $value] = $pair;
  $key   = trim((string)$key);
  $value = trim((string)$value);
  if (!in_array($key, $allowed, true)) continue;
  set_setting($pdo, $key, $value);
}

json_out(['ok' => true]);
