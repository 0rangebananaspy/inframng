<?php
session_start();
require_once __DIR__ . '/../../includes/Database.php';
require_once __DIR__ . '/../../includes/Auth.php';

$config = require __DIR__ . '/../../config/config.php';
$basePath = $config['base_path'] ?? '';

$auth = new Auth();
$db = Database::getInstance();

// Initialize default admin user if not exists
$users = $db->read('users');
if (empty($users)) {
    $authInstance = new Auth();
    $db->insert('users', [
        'username' => 'admin',
        'password' => $authInstance->hashPassword('admin123'),
        'role' => 'admin',
        'full_name' => 'Administrator'
    ]);
}

requireAuth();

$pageTitle = 'Dashboard - InfraData Manager';
$contentFile = __DIR__ . '/dashboard-content.php';
ob_start();

if (file_exists($contentFile)) {
    include $contentFile;
}

$content = ob_get_clean();
include __DIR__ . '/../../templates/layout.php';
?>
