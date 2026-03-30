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

$pageTitle = 'Custom Fields - InfraData Manager';
$message = '';
$messageType = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $name = $_POST['name'] ?? '';
                if (!empty($name)) {
                    $db->insert('custom_fields', ['name' => $name]);
                    $message = 'Field created successfully!';
                    $messageType = 'success';
                }
                break;
                
            case 'delete':
                $id = $_POST['id'] ?? '';
                $db->delete('custom_fields', $id);
                $message = 'Field deleted successfully!';
                $messageType = 'success';
                break;
        }
    }
}

$fields = $db->read('custom_fields');

ob_start();
?>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">
                <i class="fa-duotone fa-list-check mr-3 text-primary"></i>Custom Fields
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Define custom fields for device data entry</p>
        </div>
        <button onclick="openModal()" 
                class="px-6 py-3 bg-primary hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
            <i class="fa-duotone fa-plus mr-2"></i>Add Field
        </button>
    </div>

    <?php if ($message): ?>
    <div class="p-4 bg-<?= $messageType === 'success' ? 'green' : 'red' ?>-100 dark:bg-<?= $messageType === 'success' ? 'green' : 'red' ?>-900 border border-<?= $messageType === 'success' ? 'green' : 'red' ?>-400 dark:border-<?= $messageType === 'success' ? 'green' : 'red' ?>-700 text-<?= $messageType === 'success' ? 'green' : 'red' ?>-700 dark:text-<?= $messageType === 'success' ? 'green' : 'red' ?>-200 rounded-lg flex items-center">
        <i class="fa-duotone fa-<?= $messageType === 'success' ? 'check-circle' : 'triangle-exclamation' ?> mr-2"></i>
        <?= htmlspecialchars($message) ?>
    </div>
    <?php endif; ?>

    <!-- Fields Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (empty($fields)): ?>
        <div class="col-span-full glass-card rounded-2xl p-12 text-center">
            <i class="fa-duotone fa-list-check text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
            <p class="text-gray-500 dark:text-gray-400 text-lg">No custom fields defined yet.</p>
            <button onclick="openModal()" class="mt-4 px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-700">
                <i class="fa-duotone fa-plus mr-2"></i>Add First Field
            </button>
        </div>
        <?php else: ?>
            <?php foreach ($fields as $field): ?>
            <div class="glass-card rounded-2xl p-6 hover:shadow-xl transition-all">
                <div class="flex items-start justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-500 to-pink-700 flex items-center justify-center">
                            <i class="fa-duotone fa-field text-white"></i>
                        </div>
                        <span class="ml-3 font-medium text-gray-800 dark:text-white"><?= htmlspecialchars($field['name']) ?></span>
                    </div>
                    <form method="POST" onsubmit="return confirm('Are you sure?')">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $field['id'] ?>">
                        <button type="submit" class="p-2 text-red-600 hover:bg-red-100 dark:hover:bg-red-900 rounded-lg transition-colors">
                            <i class="fa-duotone fa-trash"></i>
                        </button>
                    </form>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">Created: <?= date('M d, Y', strtotime($field['created_at'])) ?></p>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Modal -->
<div id="fieldModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="glass-card rounded-2xl w-full max-w-md">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Add Custom Field</h2>
            <button onclick="closeModal()" class="text-gray-600 dark:text-gray-300 hover:text-red-600">
                <i class="fa-duotone fa-times text-2xl"></i>
            </button>
        </div>
        
        <form method="POST" class="p-6 space-y-4">
            <input type="hidden" name="action" value="create">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Field Name *</label>
                <input type="text" name="name" required 
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                       placeholder="e.g., Serial Number, Location">
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
function openModal() {
    document.getElementById('fieldModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('fieldModal').classList.add('hidden');
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../templates/layout.php';
?>
