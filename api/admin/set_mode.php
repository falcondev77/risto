<?php
require __DIR__.'/../../config.php';
require __DIR__.'/../../functions.php';
require_admin();

$mode = setting($pdo,'mode') ?? 'auto';
$new = ($mode === 'auto') ? 'manual' : 'auto';
set_setting($pdo,'mode',$new);
push_event($pdo,'mode_changed',['mode'=>$new]);

json_out(['ok'=>true,'mode'=>$new]);
