<?php
session_start();
require_once __DIR__ . '/../../includes/Database.php';
require_once __DIR__ . '/../../includes/Auth.php';

$auth = new Auth();
$db = Database::getInstance();

requireAuth();

$pageTitle = 'Proxmox Management - InfraData Manager';
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'create':
            $data = [
                'host_name' => $_POST['host_name'] ?? '',
                'ip_address' => $_POST['ip_address'] ?? '',
                'cluster_name' => $_POST['cluster_name'] ?? '',
                'is_cluster_member' => isset($_POST['is_cluster_member']) ? 1 : 0,
                'username' => $_POST['username'] ?? '',
                'password' => $_POST['password'] ?? '',
                'notes' => $_POST['notes'] ?? '',
            ];
            $db->insert('proxmox', $data);
            $message = 'Proxmox host added successfully!';
            $messageType = 'success';
            break;
            
        case 'update':
            $id = $_POST['id'] ?? '';
            $data = [
                'host_name' => $_POST['host_name'] ?? '',
                'ip_address' => $_POST['ip_address'] ?? '',
                'cluster_name' => $_POST['cluster_name'] ?? '',
                'is_cluster_member' => isset($_POST['is_cluster_member']) ? 1 : 0,
                'username' => $_POST['username'] ?? '',
                'password' => $_POST['password'] ?? '',
                'notes' => $_POST['notes'] ?? '',
            ];
            $db->update('proxmox', $id, $data);
            $message = 'Proxmox host updated successfully!';
            $messageType = 'success';
            break;
            
        case 'delete':
            $id = $_POST['id'] ?? '';
            $db->delete('proxmox', $id);
            $message = 'Proxmox host deleted successfully!';
            $messageType = 'success';
            break;
    }
}

$proxmoxHosts = $db->read('proxmox');

ob_start();
?>

<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">
                <i class="fa-duotone fa-cloud mr-3 text-accent"></i>Proxmox Management
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Manage Proxmox hosts and clusters</p>
        </div>
        <?php if ($auth->isAdmin() || $auth->isOperator()): ?>
        <button onclick="openModal('create')" 
                class="px-6 py-3 bg-accent hover:bg-yellow-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
            <i class="fa-duotone fa-plus mr-2"></i>Add Proxmox Host
        </button>
        <?php endif; ?>
    </div>

    <?php if ($message): ?>
    <div class="p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 rounded-lg flex items-center">
        <i class="fa-duotone fa-check-circle mr-2"></i>
        <?= htmlspecialchars($message) ?>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <?php if (empty($proxmoxHosts)): ?>
        <div class="col-span-full glass-card rounded-2xl p-12 text-center">
            <i class="fa-duotone fa-cloud text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
            <p class="text-gray-500 dark:text-gray-400 text-lg">No Proxmox hosts registered yet.</p>
        </div>
        <?php else: ?>
            <?php foreach ($proxmoxHosts as $host): ?>
            <div class="glass-card rounded-2xl p-6 hover:shadow-xl transition-all">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= htmlspecialchars($host['host_name']) ?></h3>
                        <?php if ($host['is_cluster_member']): ?>
                        <span class="inline-block px-2 py-1 bg-accent text-white text-xs rounded-full mt-1">
                            <i class="fa-duotone fa-sitemap mr-1"></i>Cluster Member
                        </span>
                        <?php endif; ?>
                    </div>
                    <div class="flex gap-2">
                        <?php if ($auth->isAdmin() || $auth->isOperator()): ?>
                        <button onclick='openModal("edit", <?= json_encode($host) ?>)' 
                                class="p-2 text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900 rounded-lg transition-colors">
                            <i class="fa-duotone fa-pen-to-square"></i>
                        </button>
                        <?php endif; ?>
                        <?php if ($auth->isAdmin()): ?>
                        <form method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $host['id'] ?>">
                            <button type="submit" class="p-2 text-red-600 hover:bg-red-100 dark:hover:bg-red-900 rounded-lg transition-colors">
                                <i class="fa-duotone fa-trash"></i>
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="space-y-2 text-sm">
                    <div class="flex items-center text-gray-600 dark:text-gray-400">
                        <i class="fa-duotone fa-network-wired w-6 text-primary"></i>
                        <span>IP: <code class="bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded"><?= htmlspecialchars($host['ip_address']) ?></code></span>
                    </div>
                    <div class="flex items-center text-gray-600 dark:text-gray-400">
                        <i class="fa-duotone fa-sitemap w-6 text-purple-600"></i>
                        <span>Cluster: <?= htmlspecialchars($host['cluster_name']) ?></span>
                    </div>
                    <div class="flex items-center text-gray-600 dark:text-gray-400">
                        <i class="fa-duotone fa-user w-6 text-green-600"></i>
                        <span>Username: <?= htmlspecialchars($host['username']) ?></span>
                    </div>
                    <?php if (!empty($host['notes'])): ?>
                    <div class="pt-2 mt-2 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-gray-600 dark:text-gray-400 text-xs"><?= nl2br(htmlspecialchars($host['notes'])) ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Modal -->
<div id="proxmoxModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="glass-card rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between sticky top-0 bg-inherit">
            <h2 id="modalTitle" class="text-2xl font-bold text-gray-800 dark:text-white">Add Proxmox Host</h2>
            <button onclick="closeModal()" class="text-gray-600 dark:text-gray-300 hover:text-red-600">
                <i class="fa-duotone fa-times text-2xl"></i>
            </button>
        </div>
        
        <form method="POST" class="p-6 space-y-4">
            <input type="hidden" name="action" id="formAction" value="create">
            <input type="hidden" name="id" id="hostId">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Host Name *</label>
                    <input type="text" name="host_name" id="hostName" required 
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-accent focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">IP Address *</label>
                    <input type="text" name="ip_address" id="ipAddress" required 
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-accent focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cluster Name *</label>
                    <input type="text" name="cluster_name" id="clusterName" required 
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-accent focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Username *</label>
                    <input type="text" name="username" id="username" required 
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-accent focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password *</label>
                    <input type="text" name="password" id="password" required 
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-accent focus:border-transparent">
                </div>
                <div class="md:col-span-2">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="is_cluster_member" id="isClusterMember" 
                               class="w-4 h-4 text-accent rounded focus:ring-accent">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Is Cluster Member</span>
                    </label>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes</label>
                    <textarea name="notes" id="notes" rows="3" 
                              class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-accent focus:border-transparent"></textarea>
                </div>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="submit" 
                        class="flex-1 py-3 px-4 bg-accent hover:bg-yellow-600 text-white font-semibold rounded-lg shadow-lg transition-all">
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
    const modal = document.getElementById('proxmoxModal');
    const title = document.getElementById('modalTitle');
    const action = document.getElementById('formAction');
    const hostId = document.getElementById('hostId');
    
    if (mode === 'edit' && data) {
        title.textContent = 'Edit Proxmox Host';
        action.value = 'update';
        hostId.value = data.id;
        
        document.getElementById('hostName').value = data.host_name || '';
        document.getElementById('ipAddress').value = data.ip_address || '';
        document.getElementById('clusterName').value = data.cluster_name || '';
        document.getElementById('username').value = data.username || '';
        document.getElementById('password').value = data.password || '';
        document.getElementById('notes').value = data.notes || '';
        document.getElementById('isClusterMember').checked = data.is_cluster_member == 1;
    } else {
        title.textContent = 'Add Proxmox Host';
        action.value = 'create';
        hostId.value = '';
        
        document.getElementById('hostName').value = '';
        document.getElementById('ipAddress').value = '';
        document.getElementById('clusterName').value = '';
        document.getElementById('username').value = '';
        document.getElementById('password').value = '';
        document.getElementById('notes').value = '';
        document.getElementById('isClusterMember').checked = false;
    }
    
    modal.classList.remove('hidden');
}

function closeModal() {
    document.getElementById('proxmoxModal').classList.add('hidden');
}

document.getElementById('proxmoxModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../templates/layout.php';
?>
