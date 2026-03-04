<?php
declare(strict_types=1);

function h(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

function json_out(array $data, int $code=200): void {
  http_response_code($code);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($data);
  exit;
}

function setting(PDO $pdo, string $key): ?string {
  $st = $pdo->prepare("SELECT value FROM settings WHERE `key`=?");
  $st->execute([$key]);
  $row = $st->fetch();
  return $row ? (string)$row['value'] : null;
}

function set_setting(PDO $pdo, string $key, string $value): void {
  $st = $pdo->prepare("INSERT INTO settings(`key`,`value`) VALUES(?,?)
                       ON DUPLICATE KEY UPDATE value=VALUES(value)");
  $st->execute([$key,$value]);
}

function capacity_for_date(PDO $pdo, string $dateYmd): int {
  $st = $pdo->prepare("SELECT capacity FROM capacity_by_date WHERE `date`=?");
  $st->execute([$dateYmd]);
  $row = $st->fetch();
  if ($row) return (int)$row['capacity'];
  return (int)(setting($pdo,'default_capacity') ?? '0');
}

function seats_used(PDO $pdo, string $dateYmd): int {
  // In modalità manuale, blocchiamo posti anche per "pending" per evitare overbooking.
  $mode = setting($pdo,'mode') ?? 'auto';
  $statuses = ($mode === 'manual') ? ['confirmed','pending'] : ['confirmed'];

  $in = implode(',', array_fill(0, count($statuses), '?'));
  $params = array_merge([$dateYmd], $statuses);

  $st = $pdo->prepare("SELECT COALESCE(SUM(people),0) AS s
                       FROM bookings
                       WHERE booking_date=? AND status IN ($in)");
  $st->execute($params);
  return (int)$st->fetch()['s'];
}

function push_event(PDO $pdo, string $type, array $payload=[]): void {
  $st = $pdo->prepare("INSERT INTO events(type,payload) VALUES(?,?)");
  $st->execute([$type, json_encode($payload)]);
}

function require_admin(): void {
  if (empty($_SESSION['admin_id'])) {
    header('Location: /admin/login.php');
    exit;
  }
}

function get_active_slots(PDO $pdo): array {
  $st = $pdo->query("SELECT slot_time, capacity FROM time_slots WHERE is_active=1 ORDER BY sort_order ASC, slot_time ASC");
  return $st->fetchAll();
}

function get_all_slots(PDO $pdo): array {
  $st = $pdo->query("SELECT id, slot_time, capacity, is_active, sort_order FROM time_slots ORDER BY sort_order ASC, slot_time ASC");
  return $st->fetchAll();
}

function seats_used_for_slot(PDO $pdo, string $dateYmd, string $slotTime): int {
  $mode = setting($pdo,'mode') ?? 'auto';
  $statuses = ($mode === 'manual') ? ['confirmed','pending'] : ['confirmed'];
  $in = implode(',', array_fill(0, count($statuses), '?'));
  $params = array_merge([$dateYmd, $slotTime], $statuses);
  $st = $pdo->prepare("SELECT COALESCE(SUM(people),0) AS s FROM bookings WHERE booking_date=? AND booking_time=? AND status IN ($in)");
  $st->execute($params);
  return (int)$st->fetch()['s'];
}

function is_closure_day(PDO $pdo, string $dateYmd): bool {
  $st = $pdo->prepare("SELECT 1 FROM closure_days WHERE date=?");
  $st->execute([$dateYmd]);
  return (bool)$st->fetch();
}

function get_closure_days_range(PDO $pdo, string $from, string $to): array {
  $st = $pdo->prepare("SELECT date, reason FROM closure_days WHERE date BETWEEN ? AND ? ORDER BY date ASC");
  $st->execute([$from, $to]);
  return $st->fetchAll();
}
