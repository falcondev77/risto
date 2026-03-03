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

$allowed_times = ['19:00','19:30','20:00','20:30','21:00','21:30','22:00','22:30'];

if ($first==='' || $last==='' || $phone==='' || $email==='' || $date==='' || $time==='' || $people<1) {
  json_out(['ok'=>false,'error'=>'Compila tutti i campi.'], 400);
}

if (!in_array($time, $allowed_times, true)) {
  json_out(['ok'=>false,'error'=>'Orario non valido.'], 400);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  json_out(['ok'=>false,'error'=>'Email non valida.'], 400);
}

$mode = setting($pdo,'mode') ?? 'auto';

$capacity = capacity_for_date($pdo, $date);
$used = seats_used($pdo, $date);

if ($capacity > 0 && ($used + $people) > $capacity) {
  json_out(['ok'=>false,'error'=>"Posti esauriti per la data selezionata."], 409);
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
