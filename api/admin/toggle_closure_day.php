<?php
require __DIR__.'/../../config.php';
require __DIR__.'/../../functions.php';
require_admin();

$date   = trim($_POST['date'] ?? '');
$reason = trim($_POST['reason'] ?? '');

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
  json_out(['ok' => false, 'error' => 'Data non valida.'], 400);
}

if (is_closure_day($pdo, $date)) {
  $pdo->prepare("DELETE FROM closure_days WHERE date=?")->execute([$date]);
  json_out(['ok' => true, 'action' => 'removed', 'date' => $date]);
} else {
  $pdo->prepare("INSERT INTO closure_days(date, reason) VALUES(?,?) ON DUPLICATE KEY UPDATE reason=VALUES(reason)")
      ->execute([$date, $reason ?: null]);
  json_out(['ok' => true, 'action' => 'added', 'date' => $date]);
}
