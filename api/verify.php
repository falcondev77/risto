<?php
require __DIR__.'/../config.php';
require __DIR__.'/../functions.php';

$email = trim($_POST['email'] ?? '');
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  json_out(['ok'=>false,'error'=>'Email non valida.'], 400);
}

$mode = setting($pdo,'mode') ?? 'auto';

$st = $pdo->prepare("SELECT id, booking_date, people, status, created_at
                     FROM bookings
                     WHERE email=?
                     ORDER BY created_at DESC
                     LIMIT 50");
$st->execute([$email]);
$rows = $st->fetchAll();

$mapped = array_map(function($b) use ($mode){
  $label = $b['status'];
  if ($b['status']==='pending' && $mode==='manual') $label = 'in attesa di conferma';
  return [
    'id' => (int)$b['id'],
    'booking_date' => $b['booking_date'],
    'people' => (int)$b['people'],
    'status' => $b['status'],
    'status_label' => $label
  ];
}, $rows);

json_out(['ok'=>true,'bookings'=>$mapped]);
