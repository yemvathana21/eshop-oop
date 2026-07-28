<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Add/Edit Form -->
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h2 id="formTitle" class="text-lg font-bold text-gray-900 dark:text-white mb-4">Add New Color</h2>
            <form action="<?= BASE_URL ?>admin/color/save" method="POST" id="colorForm">
                <input type="hidden" name="id" id="colorId">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Color Name *</label>
                        <input type="text" name="name" id="colorName" required
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition"
                            placeholder="e.g. Red, Blue, Deep Black">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition shadow-md shadow-blue-500/20">
                            Save Color
                        </button>
                        <button type="button" onclick="resetForm()" id="cancelBtn" class="hidden px-4 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                            Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- List Table -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700 border-b border-gray-100 dark:border-gray-600 text-left">
                        <th class="py-3 px-4 font-semibold text-gray-600 dark:text-gray-300 w-16">#</th>
                        <th class="py-3 px-4 font-semibold text-gray-600 dark:text-gray-300">Color Name</th>
                        <th class="py-3 px-4 font-semibold text-gray-600 dark:text-gray-300 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php if (empty($colors)): ?>
                    <tr>
                        <td colspan="3" class="py-10 text-center text-gray-500">No colors found.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($colors as $idx => $c): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="py-3 px-4 text-gray-500"><?= $idx + 1 ?></td>
                            <td class="py-3 px-4">
                                <span class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($c['name']) ?></span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="editColor(<?= $c['id'] ?>, '<?= addslashes($c['name']) ?>')" class="text-blue-500 hover:text-blue-700 p-1">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="<?= BASE_URL ?>admin/color/delete?id=<?= $c['id'] ?>" onclick="return confirm('Are you sure?')" class="text-red-500 hover:text-red-700 p-1">
                                        <i class="fas fa-trash"></i>
                                    </a>
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

<script>
function editColor(id, name) {
    document.getElementById('formTitle').textContent = 'Edit Color';
    document.getElementById('colorId').value = id;
    document.getElementById('colorName').value = name;
    document.getElementById('cancelBtn').classList.remove('hidden');
    document.getElementById('colorName').focus();
}

function resetForm() {
    document.getElementById('formTitle').textContent = 'Add New Color';
    document.getElementById('colorId').value = '';
    document.getElementById('colorForm').reset();
    document.getElementById('cancelBtn').classList.add('hidden');
}
</script>
