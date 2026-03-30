--- templates/layout.php (原始)
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'InfraData Manager' ?></title>

    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#0256ac',
                        accent: '#eeb60f',
                    },
                    fontFamily: {
                        sans: ['Roboto', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Font Awesome Duotone -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/rizmyabdulla/fontawesome-pro@main/releases/v7.2.0/css/fontawesome.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/rizmyabdulla/fontawesome-pro@main/releases/v7.2.0/css/duotone.css" />

    <!-- Google Fonts - Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .dark .glass {
            background: rgba(30, 30, 30, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .dark .glass-card {
            background: rgba(40, 40, 40, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #0256ac 0%, #033d7a 100%);
        }

        .sidebar-link {
            transition: all 0.3s ease;
        }

        .sidebar-link:hover {
            background: rgba(2, 86, 172, 0.1);
            transform: translateX(5px);
        }

        .dark .sidebar-link:hover {
            background: rgba(2, 86, 172, 0.2);
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 transition-colors duration-300">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 glass-card hidden md:flex flex-col z-20">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h1 class="text-2xl font-bold text-primary">
                    <i class="fa-duotone fa-server mr-2"></i>InfraData
                </h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Manager v1.0</p>
            </div>

            <nav class="flex-1 p-4 overflow-y-auto">
                <a href="/dashboard/index.php" class="sidebar-link flex items-center p-3 rounded-lg mb-2 <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'bg-primary text-white' : '' ?>">
                    <i class="fa-duotone fa-grid-2 w-6"></i>
                    <span>Dashboard</span>
                </a>

                <div class="pt-4 pb-2">
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-3 mb-2">Infrastructure</p>
                    <a href="/modules/devices/index.php" class="sidebar-link flex items-center p-3 rounded-lg mb-2 <?= strpos($_SERVER['PHP_SELF'], 'devices') ? 'bg-primary text-white' : '' ?>">
                        <i class="fa-duotone fa-microchip w-6"></i>
                        <span>Devices</span>
                    </a>
                    <a href="/modules/vms/proxmox.php" class="sidebar-link flex items-center p-3 rounded-lg mb-2 <?= strpos($_SERVER['PHP_SELF'], 'proxmox') ? 'bg-primary text-white' : '' ?>">
                        <i class="fa-duotone fa-cloud w-6"></i>
                        <span>Proxmox</span>
                    </a>
                    <a href="/modules/vms/virtual-machines.php" class="sidebar-link flex items-center p-3 rounded-lg mb-2 <?= strpos($_SERVER['PHP_SELF'], 'virtual') ? 'bg-primary text-white' : '' ?>">
                        <i class="fa-duotone fa-desktop w-6"></i>
                        <span>Virtual Machines</span>
                    </a>
                </div>

                <?php if ($auth->isAdmin()): ?>
                <div class="pt-4 pb-2">
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-3 mb-2">Administration</p>
                    <a href="/modules/users/index.php" class="sidebar-link flex items-center p-3 rounded-lg mb-2 <?= strpos($_SERVER['PHP_SELF'], 'users') ? 'bg-primary text-white' : '' ?>">
                        <i class="fa-duotone fa-users w-6"></i>
                        <span>Users</span>
                    </a>
                </div>
                <?php endif; ?>
            </nav>

            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium">Dark Mode</span>
                    <button id="darkModeToggle" class="relative inline-flex h-6 w-11 items-center rounded-full bg-gray-300 dark:bg-primary transition-colors">
                        <span class="translate-x-1 dark:translate-x-6 inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                    </button>
                </div>
                <div class="flex items-center p-3 rounded-lg bg-gray-100 dark:bg-gray-800">
                    <i class="fa-duotone fa-user-circle text-primary text-xl mr-2"></i>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate"><?= htmlspecialchars($_SESSION['username']) ?></p>
                        <p class="text-xs text-gray-500 capitalize"><?= htmlspecialchars($_SESSION['role']) ?></p>
                    </div>
                </div>
                <a href="/modules/auth/logout.php" class="mt-2 flex items-center justify-center w-full p-2 rounded-lg bg-red-500 hover:bg-red-600 text-white transition-colors">
                    <i class="fa-duotone fa-sign-out-alt mr-2"></i>Logout
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Mobile Header -->
            <header class="glass-card md:hidden p-4 flex items-center justify-between z-10">
                <h1 class="text-xl font-bold text-primary">InfraData</h1>
                <button id="mobileMenuBtn" class="text-gray-600 dark:text-gray-300">
                    <i class="fa-duotone fa-bars text-2xl"></i>
                </button>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6">
                <?= $content ?? '' ?>
            </main>
        </div>

        <!-- Mobile Menu Overlay -->
        <div id="mobileMenu" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden">
            <div class="w-64 h-full glass-card transform transition-transform translate-x-0">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-primary">Menu</h2>
                    <button id="closeMobileMenu" class="text-gray-600 dark:text-gray-300">
                        <i class="fa-duotone fa-times text-xl"></i>
                    </button>
                </div>
                <nav class="p-4">
                    <a href="/dashboard/index.php" class="block p-3 rounded-lg mb-2 hover:bg-primary hover:text-white">Dashboard</a>
                    <a href="/modules/devices/index.php" class="block p-3 rounded-lg mb-2 hover:bg-primary hover:text-white">Devices</a>
                    <a href="/modules/vms/proxmox.php" class="block p-3 rounded-lg mb-2 hover:bg-primary hover:text-white">Proxmox</a>
                    <a href="/modules/vms/virtual-machines.php" class="block p-3 rounded-lg mb-2 hover:bg-primary hover:text-white">Virtual Machines</a>
                    <?php if ($auth->isAdmin()): ?>
                    <a href="/modules/users/index.php" class="block p-3 rounded-lg mb-2 hover:bg-primary hover:text-white">Users</a>
                    <?php endif; ?>
                    <a href="/modules/auth/logout.php" class="block p-3 rounded-lg bg-red-500 text-white">Logout</a>
                </nav>
            </div>
        </div>
    </div>

    <script>
        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const html = document.documentElement;

        if (localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
        }

        darkModeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.setItem('darkMode', html.classList.contains('dark'));
        });

        // Mobile Menu
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const closeMobileMenu = document.getElementById('closeMobileMenu');
        const mobileMenu = document.getElementById('mobileMenu');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.remove('hidden');
        });

        closeMobileMenu.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
        });

        mobileMenu.addEventListener('click', (e) => {
            if (e.target === mobileMenu) {
                mobileMenu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>

+++ templates/layout.php (修改后)
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'InfraData Manager' ?></title>

    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#0256ac',
                        accent: '#eeb60f',
                    },
                    fontFamily: {
                        sans: ['Roboto', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Font Awesome Duotone -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/rizmyabdulla/fontawesome-pro@main/releases/v7.2.0/css/fontawesome.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/rizmyabdulla/fontawesome-pro@main/releases/v7.2.0/css/duotone.css" />

    <!-- Google Fonts - Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .dark .glass {
            background: rgba(30, 30, 30, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .dark .glass-card {
            background: rgba(40, 40, 40, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #0256ac 0%, #033d7a 100%);
        }

        .sidebar-link {
            transition: all 0.3s ease;
        }

        .sidebar-link:hover {
            background: rgba(2, 86, 172, 0.1);
            transform: translateX(5px);
        }

        .dark .sidebar-link:hover {
            background: rgba(2, 86, 172, 0.2);
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 transition-colors duration-300">
    <?php
    $config = require __DIR__ . '/../config/config.php';
    $basePath = $config['base_path'] ?? '';
    ?>
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 glass-card hidden md:flex flex-col z-20">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h1 class="text-2xl font-bold text-primary">
                    <i class="fa-duotone fa-server mr-2"></i>InfraData
                </h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Manager v1.0</p>
            </div>

            <nav class="flex-1 p-4 overflow-y-auto">
                <a href="<?= $basePath ?>/dashboard/index.php" class="sidebar-link flex items-center p-3 rounded-lg mb-2 <?= basename($_SERVER['PHP_SELF']) == 'index.php' && strpos($_SERVER['PHP_SELF'], 'dashboard') ? 'bg-primary text-white' : '' ?>">
                    <i class="fa-duotone fa-grid-2 w-6"></i>
                    <span>Dashboard</span>
                </a>

                <div class="pt-4 pb-2">
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-3 mb-2">Infrastructure</p>
                    <a href="<?= $basePath ?>/modules/devices/index.php" class="sidebar-link flex items-center p-3 rounded-lg mb-2 <?= strpos($_SERVER['PHP_SELF'], 'devices') ? 'bg-primary text-white' : '' ?>">
                        <i class="fa-duotone fa-microchip w-6"></i>
                        <span>Devices</span>
                    </a>
                    <a href="<?= $basePath ?>/modules/vms/proxmox.php" class="sidebar-link flex items-center p-3 rounded-lg mb-2 <?= strpos($_SERVER['PHP_SELF'], 'proxmox') ? 'bg-primary text-white' : '' ?>">
                        <i class="fa-duotone fa-cloud w-6"></i>
                        <span>Proxmox</span>
                    </a>
                    <a href="<?= $basePath ?>/modules/vms/virtual-machines.php" class="sidebar-link flex items-center p-3 rounded-lg mb-2 <?= strpos($_SERVER['PHP_SELF'], 'virtual') ? 'bg-primary text-white' : '' ?>">
                        <i class="fa-duotone fa-desktop w-6"></i>
                        <span>Virtual Machines</span>
                    </a>
                </div>

                <?php if ($auth->isAdmin()): ?>
                <div class="pt-4 pb-2">
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-3 mb-2">Administration</p>
                    <a href="<?= $basePath ?>/modules/users/index.php" class="sidebar-link flex items-center p-3 rounded-lg mb-2 <?= strpos($_SERVER['PHP_SELF'], 'users') && !strpos($_SERVER['PHP_SELF'], 'fields') ? 'bg-primary text-white' : '' ?>">
                        <i class="fa-duotone fa-users w-6"></i>
                        <span>Users</span>
                    </a>
                    <a href="<?= $basePath ?>/modules/users/fields.php" class="sidebar-link flex items-center p-3 rounded-lg mb-2 <?= strpos($_SERVER['PHP_SELF'], 'fields') ? 'bg-primary text-white' : '' ?>">
                        <i class="fa-duotone fa-list-check w-6"></i>
                        <span>Custom Fields</span>
                    </a>
                </div>
                <?php endif; ?>
            </nav>

            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium">Dark Mode</span>
                    <button id="darkModeToggle" class="relative inline-flex h-6 w-11 items-center rounded-full bg-gray-300 dark:bg-primary transition-colors">
                        <span class="translate-x-1 dark:translate-x-6 inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                    </button>
                </div>
                <div class="flex items-center p-3 rounded-lg bg-gray-100 dark:bg-gray-800">
                    <i class="fa-duotone fa-user-circle text-primary text-xl mr-2"></i>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate"><?= htmlspecialchars($_SESSION['username']) ?></p>
                        <p class="text-xs text-gray-500 capitalize"><?= htmlspecialchars($_SESSION['role']) ?></p>
                    </div>
                </div>
                <a href="<?= $basePath ?>/modules/auth/logout.php" class="mt-2 flex items-center justify-center w-full p-2 rounded-lg bg-red-500 hover:bg-red-600 text-white transition-colors">
                    <i class="fa-duotone fa-sign-out-alt mr-2"></i>Logout
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Mobile Header -->
            <header class="glass-card md:hidden p-4 flex items-center justify-between z-10">
                <h1 class="text-xl font-bold text-primary">InfraData</h1>
                <button id="mobileMenuBtn" class="text-gray-600 dark:text-gray-300">
                    <i class="fa-duotone fa-bars text-2xl"></i>
                </button>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6">
                <?= $content ?? '' ?>
            </main>
        </div>

        <!-- Mobile Menu Overlay -->
        <div id="mobileMenu" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden">
            <div class="w-64 h-full glass-card transform transition-transform translate-x-0">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-primary">Menu</h2>
                    <button id="closeMobileMenu" class="text-gray-600 dark:text-gray-300">
                        <i class="fa-duotone fa-times text-xl"></i>
                    </button>
                </div>
                <nav class="p-4">
                    <a href="<?= $basePath ?>/dashboard/index.php" class="block p-3 rounded-lg mb-2 hover:bg-primary hover:text-white">Dashboard</a>
                    <a href="<?= $basePath ?>/modules/devices/index.php" class="block p-3 rounded-lg mb-2 hover:bg-primary hover:text-white">Devices</a>
                    <a href="<?= $basePath ?>/modules/vms/proxmox.php" class="block p-3 rounded-lg mb-2 hover:bg-primary hover:text-white">Proxmox</a>
                    <a href="<?= $basePath ?>/modules/vms/virtual-machines.php" class="block p-3 rounded-lg mb-2 hover:bg-primary hover:text-white">Virtual Machines</a>
                    <?php if ($auth->isAdmin()): ?>
                    <a href="<?= $basePath ?>/modules/users/index.php" class="block p-3 rounded-lg mb-2 hover:bg-primary hover:text-white">Users</a>
                    <a href="<?= $basePath ?>/modules/users/fields.php" class="block p-3 rounded-lg mb-2 hover:bg-primary hover:text-white">Custom Fields</a>
                    <?php endif; ?>
                    <a href="<?= $basePath ?>/modules/auth/logout.php" class="block p-3 rounded-lg bg-red-500 text-white">Logout</a>
                </nav>
            </div>
        </div>
    </div>

    <script>
        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const html = document.documentElement;

        if (localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
        }

        darkModeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.setItem('darkMode', html.classList.contains('dark'));
        });

        // Mobile Menu
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const closeMobileMenu = document.getElementById('closeMobileMenu');
        const mobileMenu = document.getElementById('mobileMenu');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.remove('hidden');
        });

        closeMobileMenu.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
        });

        mobileMenu.addEventListener('click', (e) => {
            if (e.target === mobileMenu) {
                mobileMenu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
