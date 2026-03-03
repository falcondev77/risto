<?php
require __DIR__.'/../../config.php';
require __DIR__.'/../../functions.php';
require_admin();

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

$lastId = isset($_GET['last_id']) ? (int)$_GET['last_id'] : 0;
$start = time();

while (true) {
  if (time() - $start > 25) break;

  $st = $pdo->prepare("SELECT id, type FROM events WHERE id > ? ORDER BY id ASC LIMIT 10");
  $st->execute([$lastId]);
  $evs = $st->fetchAll();

  if ($evs) {
    foreach ($evs as $ev) {
      $lastId = (int)$ev['id'];
      echo "id: {$lastId}\n";
      echo "data: ".json_encode(['type'=>$ev['type']])."\n\n";
    }
    @ob_flush(); @flush();
  } else {
    echo ": ping\n\n";
    @ob_flush(); @flush();
    usleep(300000);
  }
}
