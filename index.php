<?php
// Root redirect to login
$config = require __DIR__ . '/config/config.php';
$basePath = $config['base_path'] ?? '';
header('Location: ' . $basePath . '/modules/auth/login.php');
exit;
?>
