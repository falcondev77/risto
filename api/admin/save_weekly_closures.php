<?php
require __DIR__.'/../../config.php';
require __DIR__.'/../../functions.php';
require_admin();

$raw  = $_POST['days'] ?? '';
$days = array_filter(array_map('intval', explode(',', $raw)), fn($d) => $d >= 0 && $d <= 6);
$days = array_values(array_unique($days));

$pdo->exec("CREATE TABLE IF NOT EXISTS weekly_closures (
  day_of_week TINYINT NOT NULL PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB");

$pdo->exec("DELETE FROM weekly_closures");
if (count($days)) {
  $vals = implode(',', array_map(fn($d) => "($d)", $days));
  $pdo->exec("INSERT INTO weekly_closures (day_of_week) VALUES $vals");
}

push_event($pdo, 'weekly_closures_updated');
json_out(['ok' => true, 'days' => $days]);
