--- includes/helpers.php (原始)


+++ includes/helpers.php (修改后)
<?php
/**
 * Path Helper Functions
 * Use these functions to generate correct paths based on BASE_PATH configuration
 */

if (!function_exists('asset')) {
    function asset($path = '') {
        $config = require __DIR__ . '/../config/config.php';
        $basePath = $config['base_path'] ?? '';
        return $basePath . '/assets/' . ltrim($path, '/');
    }
}

if (!function_exists('url')) {
    function url($path = '') {
        $config = require __DIR__ . '/../config/config.php';
        $basePath = $config['base_path'] ?? '';
        return $basePath . '/' . ltrim($path, '/');
    }
}

if (!function_exists('moduleUrl')) {
    function moduleUrl($module, $file = '') {
        $config = require __DIR__ . '/../config/config.php';
        $basePath = $config['base_path'] ?? '';
        $path = 'modules/' . $module;
        if ($file) {
            $path .= '/' . $file;
        }
        return $basePath . '/' . ltrim($path, '/');
    }
}

if (!function_exists('dashboardUrl')) {
    function dashboardUrl() {
        $config = require __DIR__ . '/../config/config.php';
        $basePath = $config['base_path'] ?? '';
        return $basePath . '/dashboard/index.php';
    }
}

if (!function_exists('requireAuth')) {
    function requireAuth() {
        $auth = new Auth();
        if (!$auth->isLoggedIn()) {
            $config = require __DIR__ . '/../../config/config.php';
            $basePath = $config['base_path'] ?? '';
            header('Location: ' . $basePath . '/modules/auth/login.php');
            exit;
        }
    }
}

if (!function_exists('requireAdmin')) {
    function requireAdmin() {
        $auth = new Auth();
        if (!$auth->isAdmin()) {
            $config = require __DIR__ . '/../../config/config.php';
            $basePath = $config['base_path'] ?? '';
            header('Location: ' . $basePath . '/dashboard/index.php');
            exit;
        }
    }
}
