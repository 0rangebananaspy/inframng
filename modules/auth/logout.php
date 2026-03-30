--- modules/auth/logout.php (原始)
<?php
session_start();
require_once __DIR__ . '/../../includes/Database.php';
require_once __DIR__ . '/../../includes/Auth.php';

$auth = new Auth();

$auth->logout();
header('Location: /modules/auth/login.php');
exit;
?>

+++ modules/auth/logout.php (修改后)
<?php
session_start();
require_once __DIR__ . '/../../includes/Database.php';
require_once __DIR__ . '/../../includes/Auth.php';

$config = require __DIR__ . '/../../config/config.php';
$basePath = $config['base_path'] ?? '';

$auth = new Auth();

$auth->logout();
header('Location: ' . $basePath . '/modules/auth/login.php');
exit;
?>
