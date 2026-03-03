<?php
require __DIR__.'/../../config.php';
require __DIR__.'/../../functions.php';
require_admin();

$mode = setting($pdo,'mode') ?? 'auto';
$today = date('Y-m-d');

$st = $pdo->query("SELECT id, first_name, last_name, phone, email, booking_date, booking_time, people, status
                   FROM bookings
                   WHERE status IN ('pending','confirmed','rejected')
                   ORDER BY booking_date ASC, booking_time ASC, created_at DESC
                   LIMIT 300");
$rows = $st->fetchAll();

json_out([
  'ok'=>true,
  'mode'=>$mode,
  'today_capacity'=>capacity_for_date($pdo,$today),
  'today_used'=>seats_used($pdo,$today),
  'rows'=>$rows
]);
