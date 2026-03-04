<?php
require __DIR__.'/../../config.php';
require __DIR__.'/../../functions.php';
require_admin();

$slots = get_all_slots($pdo);
json_out(['ok' => true, 'slots' => $slots]);
