<?php
session_start();
require_once __DIR__ . '/../../includes/Database.php';
require_once __DIR__ . '/../../includes/Auth.php';

$config = require __DIR__ . '/../../config/config.php';
$basePath = $config['base_path'] ?? '';

$auth = new Auth();
$db = Database::getInstance();

requireAuth();
requireAdmin();

$pageTitle = 'User Management - InfraData Manager';
$message = '';
$messageType = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $username = $_POST['username'] ?? '';
                $password = $_POST['password'] ?? '';
                $role = $_POST['role'] ?? 'operator';
                $full_name = $_POST['full_name'] ?? '';
                
                if (empty($username) || empty($password)) {
                    $message = 'Username and password are required';
                    $messageType = 'error';
                } else {
                    // Check if username exists
                    $users = $db->read('users');
                    $exists = false;
                    foreach ($users as $user) {
                        if ($user['username'] === $username) {
                            $exists = true;
                            break;
                        }
                    }
                    
                    if ($exists) {
                        $message = 'Username already exists';
                        $messageType = 'error';
                    } else {
                        $db->insert('users', [
                            'username' => $username,
                            'password' => $auth->hashPassword($password),
                            'role' => $role,
                            'full_name' => $full_name
                        ]);
                        $message = 'User created successfully!';
                        $messageType = 'success';
                    }
                }
                break;
                
            case 'update':
                $id = $_POST['id'] ?? '';
                $data = [
                    'full_name' => $_POST['full_name'] ?? '',
                    'role' => $_POST['role'] ?? 'operator',
                ];
                
                if (!empty($_POST['password'])) {
                    $data['password'] = $auth->hashPassword($_POST['password']);
                }
                
                $db->update('users', $id, $data);
                $message = 'User updated successfully!';
                $messageType = 'success';
                break;
                
            case 'delete':
                $id = $_POST['id'] ?? '';
                // Prevent deleting yourself
                if ($id === $_SESSION['user_id']) {
                    $message = 'Cannot delete your own account';
                    $messageType = 'error';
                } else {
                    $db->delete('users', $id);
                    $message = 'User deleted successfully!';
                    $messageType = 'success';
                }
                break;
        }
    }
}

$users = $db->read('users');

ob_start();
?>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">
                <i class="fa-duotone fa-users mr-3 text-primary"></i>User Management
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Manage users and roles</p>
        </div>
        <button onclick="openModal('create')" 
                class="px-6 py-3 bg-primary hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
            <i class="fa-duotone fa-plus mr-2"></i>Add User
        </button>
    </div>

    <?php if ($message): ?>
    <div class="p-4 bg-<?= $messageType === 'success' ? 'green' : 'red' ?>-100 dark:bg-<?= $messageType === 'success' ? 'green' : 'red' ?>-900 border border-<?= $messageType === 'success' ? 'green' : 'red' ?>-400 dark:border-<?= $messageType === 'success' ? 'green' : 'red' ?>-700 text-<?= $messageType === 'success' ? 'green' : 'red' ?>-700 dark:text-<?= $messageType === 'success' ? 'green' : 'red' ?>-200 rounded-lg flex items-center">
        <i class="fa-duotone fa-<?= $messageType === 'success' ? 'check-circle' : 'triangle-exclamation' ?> mr-2"></i>
        <?= htmlspecialchars($message) ?>
    </div>
    <?php endif; ?>

    <!-- Users Table -->
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">User</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Username</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Role</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Created</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700 dark:text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            <i class="fa-duotone fa-users text-4xl mb-3"></i>
                            <p>No users found</p>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold">
                                        <?= strtoupper(substr($user['full_name'] ?: $user['username'], 0, 1)) ?>
                                    </div>
                                    <span class="ml-3 font-medium text-gray-800 dark:text-white"><?= htmlspecialchars($user['full_name'] ?: '-') ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400"><?= htmlspecialchars($user['username']) ?></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $user['role'] === 'admin' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-300' : 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' ?>">
                                    <?= ucfirst($user['role']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400"><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick='openModal("edit", <?= json_encode($user) ?>)' 
                                            class="p-2 text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900 rounded-lg transition-colors">
                                        <i class="fa-duotone fa-pen-to-square"></i>
                                    </button>
                                    <?php if ($user['id'] !== $_SESSION['user_id']): ?>
                                    <form method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-100 dark:hover:bg-red-900 rounded-lg transition-colors">
                                            <i class="fa-duotone fa-trash"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="userModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="glass-card rounded-2xl w-full max-w-md">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h2 id="modalTitle" class="text-2xl font-bold text-gray-800 dark:text-white">Add User</h2>
            <button onclick="closeModal()" class="text-gray-600 dark:text-gray-300 hover:text-red-600">
                <i class="fa-duotone fa-times text-2xl"></i>
            </button>
        </div>
        
        <form method="POST" class="p-6 space-y-4">
            <input type="hidden" name="action" id="formAction" value="create">
            <input type="hidden" name="id" id="userId">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                <input type="text" name="full_name" id="fullName" 
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Username *</label>
                <input type="text" name="username" id="username" required 
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password <span id="passwordNote" class="text-xs text-gray-500">*</span></label>
                <input type="password" name="password" id="password" 
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role</label>
                <select name="role" id="role" 
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="operator">Operator</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="submit" 
                        class="flex-1 py-3 px-4 bg-primary hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg transition-all">
                    <i class="fa-duotone fa-save mr-2"></i>Save
                </button>
                <button type="button" onclick="closeModal()" 
                        class="px-6 py-3 bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-semibold rounded-lg transition-all">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(mode, data = null) {
    const modal = document.getElementById('userModal');
    const title = document.getElementById('modalTitle');
    const action = document.getElementById('formAction');
    const userId = document.getElementById('userId');
    const passwordNote = document.getElementById('passwordNote');
    
    if (mode === 'edit' && data) {
        title.textContent = 'Edit User';
        action.value = 'update';
        userId.value = data.id;
        passwordNote.textContent = '(leave blank to keep current)';
        
        document.getElementById('fullName').value = data.full_name || '';
        document.getElementById('username').value = data.username || '';
        document.getElementById('password').value = '';
        document.getElementById('role').value = data.role || 'operator';
        document.getElementById('password').removeAttribute('required');
    } else {
        title.textContent = 'Add User';
        action.value = 'create';
        userId.value = '';
        passwordNote.textContent = '*';
        
        document.getElementById('fullName').value = '';
        document.getElementById('username').value = '';
        document.getElementById('password').value = '';
        document.getElementById('role').value = 'operator';
        document.getElementById('password').setAttribute('required', 'required');
    }
    
    modal.classList.remove('hidden');
}

function closeModal() {
    document.getElementById('userModal').classList.add('hidden');
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../templates/layout.php';
?>
