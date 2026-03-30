--- config/config.php (原始)
<?php
return [
    'app_name' => 'InfraData Manager',
    'version' => '1.0.0',
    'data_path' => __DIR__ . '/../data/',
    'encryption_key' => 'YourSecretKey2025ChangeThisInProduction!',
    'session_timeout' => 3600,
];

+++ config/config.php (修改后)
<?php
// Base Configuration for InfraData Manager
// Adjust BASE_PATH according to your deployment directory
// For /htdocs/namadomain/infra/, set BASE_PATH to '/infra'
// For root domain, set BASE_PATH to ''

define('BASE_PATH', '/infra'); // Change this based on your installation path
define('APP_NAME', 'InfraData Manager');
define('APP_VERSION', '1.0.0');

return [
    'app_name' => APP_NAME,
    'version' => APP_VERSION,
    'base_path' => BASE_PATH,
    'data_path' => __DIR__ . '/../data/',
    'encryption_key' => 'YourSecretKey2025ChangeThisInProduction!',
    'session_timeout' => 3600,
];
