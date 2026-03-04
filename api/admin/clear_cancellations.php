<?php
require __DIR__.'/../../config.php';
require __DIR__.'/../../functions.php';
require_admin();

$pdo->exec("DELETE FROM bookings WHERE status='cancelled'");
push_event($pdo, 'cancellations_cleared');
json_out(['ok'=>true]);
