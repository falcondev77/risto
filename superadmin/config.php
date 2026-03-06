<?php
define('SA_USERNAME', 'devadmin');
define('SA_PASSWORD', 'Dev@SuperAdmin2024!');

function require_superadmin(): void {
  if (empty($_SESSION['superadmin'])) {
    header('Location: /superadmin/login.php');
    exit;
  }
}
