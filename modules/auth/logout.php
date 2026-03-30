<?php
session_start();
require_once __DIR__ . '/../../includes/Database.php';
require_once __DIR__ . '/../../includes/Auth.php';

$auth = new Auth();

$auth->logout();
header('Location: /modules/auth/login.php');
exit;
?>
