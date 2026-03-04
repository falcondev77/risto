<?php
require __DIR__.'/../config.php';
require __DIR__.'/../functions.php';

$first = trim($_POST['first_name'] ?? '');
$last  = trim($_POST['last_name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$date  = trim($_POST['booking_date'] ?? '');
$time  = trim($_POST['booking_time'] ?? '');
$people = (int)($_POST['people'] ?? 0);

if ($first==='' || $last==='' || $phone==='' || $email==='' || $date==='' || $time==='' || $people<1) {
  json_out(['ok'=>false,'error'=>'Compila tutti i campi.'], 400);
}

if (!preg_match('/^\d{2}:\d{2}$/', $time)) {
  json_out(['ok'=>false,'error'=>'Orario non valido.'], 400);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  json_out(['ok'=>false,'error'=>'Email non valida.'], 400);
}

$mode = setting($pdo,'mode') ?? 'auto';

if (is_closure_day($pdo, $date)) {
  json_out(['ok'=>false,'error'=>'Il locale è chiuso nella data selezionata.'], 409);
}

$slotSt = $pdo->prepare("SELECT capacity FROM time_slots WHERE slot_time=? AND is_active=1");
$slotSt->execute([$time]);
$slotRow = $slotSt->fetch();

if (!$slotRow) {
  json_out(['ok'=>false,'error'=>'Orario non disponibile.'], 400);
}

$slotUsed = seats_used_for_slot($pdo, $date, $time);
if ((int)$slotRow['capacity'] > 0 && ($slotUsed + $people) > (int)$slotRow['capacity']) {
  json_out(['ok'=>false,'error'=>"Posti esauriti per l'orario selezionato."], 409);
}

$status = ($mode === 'manual') ? 'pending' : 'confirmed';

$st = $pdo->prepare("INSERT INTO bookings(first_name,last_name,phone,email,booking_date,booking_time,people,status)
                     VALUES(?,?,?,?,?,?,?,?)");
$st->execute([$first,$last,$phone,$email,$date,$time,$people,$status]);

push_event($pdo, 'booking_created', ['date'=>$date]);

$msg = ($status === 'pending')
  ? 'Prenotazione inviata. In attesa di conferma.'
  : 'Prenotazione confermata!';

json_out(['ok'=>true,'message'=>$msg]);
