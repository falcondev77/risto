<?php
require __DIR__.'/../config.php';
require __DIR__.'/../functions.php';

$id = (int)($_POST['id'] ?? 0);
$email = trim($_POST['email'] ?? '');

if ($id < 1 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
  json_out(['ok'=>false,'error'=>'Dati non validi.'], 400);
}

$st = $pdo->prepare("UPDATE bookings
                     SET status='cancelled'
                     WHERE id=? AND email=? AND status IN ('pending','confirmed')");
$st->execute([$id,$email]);

if ($st->rowCount() === 0) {
  json_out(['ok'=>false,'error'=>'Prenotazione non trovata o già cancellata.'], 404);
}

push_event($pdo, 'booking_cancelled', ['id'=>$id]);

json_out(['ok'=>true]);
