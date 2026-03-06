<?php
require __DIR__.'/../config.php';
unset($_SESSION['superadmin']);
header('Location: /superadmin/login.php');
exit;
