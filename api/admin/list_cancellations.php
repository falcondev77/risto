<?php
require __DIR__.'/../../config.php';
require __DIR__.'/../../functions.php';
require_admin();

$st = $pdo->query("SELECT first_name,last_name,email,booking_date,people,updated_at
                   FROM bookings
                   WHERE status='cancelled'
                   ORDER BY updated_at DESC
                   LIMIT 300");
json_out(['ok'=>true,'rows'=>$st->fetchAll()]);
