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
