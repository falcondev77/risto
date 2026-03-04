<?php
require __DIR__.'/../config.php';
require __DIR__.'/../functions.php';

$from = trim($_GET['from'] ?? date('Y-m-01'));
$to   = trim($_GET['to']   ?? date('Y-m-t', strtotime('+2 months')));

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $from) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $to)) {
  json_out(['ok' => false, 'error' => 'Parametri non validi.'], 400);
}

$rows = get_closure_days_range($pdo, $from, $to);
$dates = array_column($rows, 'date');
json_out(['ok' => true, 'closure_days' => $dates]);
