<?php
require __DIR__.'/../../config.php';
require __DIR__.'/../../functions.php';
require_admin();

if (isset($_POST['default_capacity'])) {
  $v = max(0, (int)$_POST['default_capacity']);
  set_setting($pdo,'default_capacity', (string)$v);
  push_event($pdo,'capacity_default_changed',['v'=>$v]);
  json_out(['ok'=>true]);
}

$date = trim($_POST['date'] ?? '');
$cap  = (int)($_POST['capacity'] ?? -1);
if ($date==='' || $cap < 0) json_out(['ok'=>false,'error'=>'Dati non validi.'], 400);

$st = $pdo->prepare("INSERT INTO capacity_by_date(`date`,capacity) VALUES(?,?)
                     ON DUPLICATE KEY UPDATE capacity=VALUES(capacity)");
$st->execute([$date,$cap]);
push_event($pdo,'capacity_day_changed',['date'=>$date,'cap'=>$cap]);

json_out(['ok'=>true]);
