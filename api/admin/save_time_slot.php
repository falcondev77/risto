<?php
require __DIR__.'/../../config.php';
require __DIR__.'/../../functions.php';
require_admin();

$id       = isset($_POST['id']) && $_POST['id'] !== '' ? (int)$_POST['id'] : null;
$time     = trim($_POST['slot_time'] ?? '');
$capacity = isset($_POST['capacity']) ? (int)$_POST['capacity'] : -1;
$active   = isset($_POST['is_active']) ? (int)(bool)$_POST['is_active'] : 1;

if (!preg_match('/^\d{2}:\d{2}$/', $time)) {
  json_out(['ok' => false, 'error' => 'Formato orario non valido (HH:MM).'], 400);
}
if ($capacity < 1 || $capacity > 999) {
  json_out(['ok' => false, 'error' => 'Coperti non validi.'], 400);
}

if ($id) {
  $st = $pdo->prepare("UPDATE time_slots SET slot_time=?, capacity=?, is_active=? WHERE id=?");
  $st->execute([$time, $capacity, $active, $id]);
} else {
  $maxOrder = (int)$pdo->query("SELECT COALESCE(MAX(sort_order),0) FROM time_slots")->fetchColumn();
  $st = $pdo->prepare("INSERT INTO time_slots(slot_time, capacity, is_active, sort_order) VALUES(?,?,?,?)
                        ON DUPLICATE KEY UPDATE capacity=VALUES(capacity), is_active=VALUES(is_active)");
  $st->execute([$time, $capacity, $active, $maxOrder + 10]);
}

$slots = get_all_slots($pdo);
json_out(['ok' => true, 'slots' => $slots]);
