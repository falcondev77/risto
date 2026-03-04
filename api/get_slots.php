<?php
require __DIR__.'/../config.php';
require __DIR__.'/../functions.php';

$slots = get_active_slots($pdo);
$out = array_map(fn($s) => ['time' => $s['slot_time'], 'capacity' => (int)$s['capacity']], $slots);
json_out(['ok' => true, 'slots' => $out]);
