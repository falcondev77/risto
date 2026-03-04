<?php
require __DIR__.'/../../config.php';
require __DIR__.'/../../functions.php';
require_admin();

$from = trim($_GET['from'] ?? date('Y-m-01'));
$to   = trim($_GET['to']   ?? date('Y-m-t', strtotime('+3 months')));

$rows = get_closure_days_range($pdo, $from, $to);
json_out(['ok' => true, 'closure_days' => $rows]);
