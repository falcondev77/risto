<?php
require __DIR__.'/../../config.php';
require __DIR__.'/../../functions.php';
require_admin();

$action = $_POST['action'] ?? '';
$id = (int)($_POST['id'] ?? 0);
if ($id < 1) json_out(['ok'=>false,'error'=>'ID non valido'], 400);

$st = $pdo->prepare("SELECT booking_date, people, status FROM bookings WHERE id=?");
$st->execute([$id]);
$b = $st->fetch();
if (!$b) json_out(['ok'=>false,'error'=>'Non trovata'], 404);

if ($action === 'confirm') {
  $date = $b['booking_date'];
  $cap = capacity_for_date($pdo,$date);
  $used = seats_used($pdo,$date);
  if ($cap > 0 && ($used + (int)$b['people']) > $cap) {
    json_out(['ok'=>false,'error'=>'Non ci sono abbastanza coperti disponibili.'], 409);
  }

  $u = $pdo->prepare("UPDATE bookings SET status='confirmed' WHERE id=? AND status='pending'");
  $u->execute([$id]);
  push_event($pdo,'booking_confirmed',['id'=>$id]);
  json_out(['ok'=>true]);
}

if ($action === 'reject') {
  $u = $pdo->prepare("UPDATE bookings SET status='rejected' WHERE id=? AND status='pending'");
  $u->execute([$id]);
  push_event($pdo,'booking_rejected',['id'=>$id]);
  json_out(['ok'=>true]);
}

if ($action === 'cancel') {
  $u = $pdo->prepare("UPDATE bookings SET status='cancelled' WHERE id=? AND status IN ('pending','confirmed')");
  $u->execute([$id]);
  push_event($pdo,'booking_cancelled',['id'=>$id]);
  json_out(['ok'=>true]);
}

json_out(['ok'=>false,'error'=>'Azione non valida'], 400);
