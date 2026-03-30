--- modules/auth/login.php (原始)
<?php
session_start();
require_once __DIR__ . '/../../includes/Database.php';
require_once __DIR__ . '/../../includes/Auth.php';

$auth = new Auth();

// Redirect if already logged in
if ($auth->isLoggedIn()) {
    header('Location: /dashboard/index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Username and password are required';
    } elseif ($auth->login($username, $password)) {
        header('Location: /dashboard/index.php');
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}

$pageTitle = 'Login - InfraData Manager';
ob_start();
?>

<div class="min-h-screen flex items-center justify-center gradient-bg p-4">
    <div class="glass-card w-full max-w-md p-8 rounded-2xl">
        <div class="text-center mb-8">
            <i class="fa-duotone fa-server text-6xl text-primary mb-4"></i>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">InfraData Manager</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Sign in to your account</p>
        </div>

        <?php if ($error): ?>
        <div class="mb-6 p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 rounded-lg flex items-center">
            <i class="fa-duotone fa-triangle-exclamation mr-2"></i>
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fa-duotone fa-user mr-2"></i>Username
                </label>
                <input type="text" name="username" required
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                       placeholder="Enter your username">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fa-duotone fa-lock mr-2"></i>Password
                </label>
                <input type="password" name="password" required
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                       placeholder="Enter your password">
            </div>

            <button type="submit"
                    class="w-full py-3 px-4 bg-primary hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                <i class="fa-duotone fa-sign-in-alt mr-2"></i>Sign In
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
            <p>Default credentials:</p>
            <p class="font-mono bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded mt-1 inline-block">admin / admin123</p>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../templates/layout.php';
?>

+++ modules/auth/login.php (修改后)
<?php
session_start();
require_once __DIR__ . '/../../includes/Database.php';
require_once __DIR__ . '/../../includes/Auth.php';

$config = require __DIR__ . '/../../config/config.php';
$basePath = $config['base_path'] ?? '';

$auth = new Auth();

// Redirect if already logged in
if ($auth->isLoggedIn()) {
    header('Location: ' . $basePath . '/dashboard/index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Username and password are required';
    } elseif ($auth->login($username, $password)) {
        header('Location: ' . $basePath . '/dashboard/index.php');
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}

$pageTitle = 'Login - InfraData Manager';
ob_start();
?>

<div class="min-h-screen flex items-center justify-center gradient-bg p-4">
    <div class="glass-card w-full max-w-md p-8 rounded-2xl">
        <div class="text-center mb-8">
            <i class="fa-duotone fa-server text-6xl text-primary mb-4"></i>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">InfraData Manager</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Sign in to your account</p>
        </div>

        <?php if ($error): ?>
        <div class="mb-6 p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 rounded-lg flex items-center">
            <i class="fa-duotone fa-triangle-exclamation mr-2"></i>
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fa-duotone fa-user mr-2"></i>Username
                </label>
                <input type="text" name="username" required
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                       placeholder="Enter your username">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fa-duotone fa-lock mr-2"></i>Password
                </label>
                <input type="password" name="password" required
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                       placeholder="Enter your password">
            </div>

            <button type="submit"
                    class="w-full py-3 px-4 bg-primary hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                <i class="fa-duotone fa-sign-in-alt mr-2"></i>Sign In
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
            <p>Default credentials:</p>
            <p class="font-mono bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded mt-1 inline-block">admin / admin123</p>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../templates/layout.php';
?>
