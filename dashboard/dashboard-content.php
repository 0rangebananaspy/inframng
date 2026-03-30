<?php $config = require __DIR__ . '/../../config/config.php'; $basePath = $config['base_path'] ?? ''; ?>
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="glass-card rounded-2xl p-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">
            Welcome back, <?= htmlspecialchars($_SESSION['username']) ?>! 👋
        </h2>
        <p class="text-gray-600 dark:text-gray-400">Manage your infrastructure data all in one place.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php
        $devices = $db->read('devices');
        $proxmox = $db->read('proxmox');
        $vms = $db->read('virtual_machines');
        $users = $db->read('users');
        ?>
        
        <div class="glass-card rounded-2xl p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Devices</p>
                    <p class="text-3xl font-bold text-primary"><?= count($devices) ?></p>
                </div>
                <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                    <i class="fa-duotone fa-server text-2xl text-primary"></i>
                </div>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Proxmox Hosts</p>
                    <p class="text-3xl font-bold text-accent"><?= count($proxmox) ?></p>
                </div>
                <div class="w-14 h-14 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                    <i class="fa-duotone fa-cloud text-2xl text-accent"></i>
                </div>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Virtual Machines</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400"><?= count($vms) ?></p>
                </div>
                <div class="w-14 h-14 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                    <i class="fa-duotone fa-desktop text-2xl text-green-600 dark:text-green-400"></i>
                </div>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6 hover:shadow-xl transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Users</p>
                    <p class="text-3xl font-bold text-purple-600 dark:text-purple-400"><?= count($users) ?></p>
                </div>
                <div class="w-14 h-14 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                    <i class="fa-duotone fa-users text-2xl text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Modules Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Devices Module -->
        <a href="<?= $basePath ?>/modules/devices/index.php" class="glass-card rounded-2xl p-6 hover:shadow-xl transition-all transform hover:-translate-y-1 group">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="fa-duotone fa-microchip text-3xl text-white"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Infrastructure Devices</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm">Manage physical devices, machines, vendors, and network information.</p>
            <div class="mt-4 flex items-center text-primary font-semibold">
                <span>Manage Devices</span>
                <i class="fa-duotone fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
            </div>
        </a>

        <!-- Proxmox Module -->
        <a href="<?= $basePath ?>/modules/vms/proxmox.php" class="glass-card rounded-2xl p-6 hover:shadow-xl transition-all transform hover:-translate-y-1 group">
            <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-700 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="fa-duotone fa-cloud text-3xl text-white"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Proxmox Management</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm">Configure Proxmox hosts, clusters, IPs, and authentication details.</p>
            <div class="mt-4 flex items-center text-primary font-semibold">
                <span>Manage Proxmox</span>
                <i class="fa-duotone fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
            </div>
        </a>

        <!-- Virtual Machines Module -->
        <a href="<?= $basePath ?>/modules/vms/virtual-machines.php" class="glass-card rounded-2xl p-6 hover:shadow-xl transition-all transform hover:-translate-y-1 group">
            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-700 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="fa-duotone fa-desktop text-3xl text-white"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Virtual Machines</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm">Track VMs, their purposes, credentials, and network configurations.</p>
            <div class="mt-4 flex items-center text-primary font-semibold">
                <span>Manage VMs</span>
                <i class="fa-duotone fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
            </div>
        </a>

        <?php if ($auth->isAdmin()): ?>
        <!-- Users Module -->
        <a href="<?= $basePath ?>/modules/users/index.php" class="glass-card rounded-2xl p-6 hover:shadow-xl transition-all transform hover:-translate-y-1 group">
            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="fa-duotone fa-users text-3xl text-white"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">User Management</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm">Create and manage users, roles, and permissions.</p>
            <div class="mt-4 flex items-center text-primary font-semibold">
                <span>Manage Users</span>
                <i class="fa-duotone fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
            </div>
        </a>

        <!-- Custom Fields Module -->
        <a href="<?= $basePath ?>/modules/users/fields.php" class="glass-card rounded-2xl p-6 hover:shadow-xl transition-all transform hover:-translate-y-1 group">
            <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-pink-700 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="fa-duotone fa-list-check text-3xl text-white"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Custom Fields</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm">Define custom fields for device data entry forms.</p>
            <div class="mt-4 flex items-center text-primary font-semibold">
                <span>Configure Fields</span>
                <i class="fa-duotone fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
            </div>
        </a>
        <?php endif; ?>
    </div>

    <!-- Recent Activity -->
    <div class="glass-card rounded-2xl p-6">
        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">
            <i class="fa-duotone fa-clock-rotate-left mr-2 text-primary"></i>Quick Reference
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <h4 class="font-semibold text-primary mb-2">
                    <i class="fa-duotone fa-circle-info mr-2"></i>Getting Started
                </h4>
                <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                    <li>• Add infrastructure devices in the Devices module</li>
                    <li>• Configure Proxmox hosts and clusters</li>
                    <li>• Register virtual machines and their details</li>
                    <li>• Admin can create custom fields for data entry</li>
                </ul>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <h4 class="font-semibold text-accent mb-2">
                    <i class="fa-duotone fa-shield-halved mr-2"></i>Role Information
                </h4>
                <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                    <li>• <strong>Admin:</strong> Full access to all modules</li>
                    <li>• <strong>Operator:</strong> Can enter and view data</li>
                    <li>• Only Admin can manage users and custom fields</li>
                </ul>
            </div>
        </div>
    </div>
</div>
