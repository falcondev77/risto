<?php
require __DIR__.'/../../config.php';
require __DIR__.'/../../functions.php';
require_admin();

$st = $pdo->query("CREATE TABLE IF NOT EXISTS weekly_closures (
  day_of_week TINYINT NOT NULL PRIMARY KEY COMMENT '0=Dom,1=Lun,...,6=Sab',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB");

$st = $pdo->query("SELECT day_of_week FROM weekly_closures ORDER BY day_of_week ASC");
json_out(['ok' => true, 'days' => array_column($st->fetchAll(), 'day_of_week')]);
