<?php
require __DIR__.'/../../config.php';
require __DIR__.'/../../functions.php';
require_admin();

$id = (int)($_POST['id'] ?? 0);
if ($id < 1) {
  json_out(['ok' => false, 'error' => 'ID non valido.'], 400);
}

$st = $pdo->prepare("DELETE FROM time_slots WHERE id=?");
$st->execute([$id]);

$slots = get_all_slots($pdo);
json_out(['ok' => true, 'slots' => $slots]);
