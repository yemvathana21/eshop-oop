<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Add/Edit Form -->
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h2 id="formTitle" class="text-lg font-bold text-gray-900 dark:text-white mb-4">Add Shipping Method</h2>
            <form action="<?= BASE_URL ?>admin/shipping-method/save" method="POST" id="methodForm">
                <input type="hidden" name="id" id="methodId">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Code *</label>
                        <input type="text" name="code" id="methodCode" required
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition"
                            placeholder="e.g. standard">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Label *</label>
                        <input type="text" name="label" id="methodLabel" required
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition"
                            placeholder="e.g. Standard Shipping">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Delivery Time</label>
                        <input type="text" name="days" id="methodDays"
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition"
                            placeholder="e.g. 5-7 business days">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cost ($) *</label>
                        <input type="number" step="0.01" min="0" name="cost" id="methodCost" required
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition"
                            placeholder="0.00">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Active</label>
                            <select name="is_active" id="methodActive"
                                class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sort Order</label>
                            <input type="number" min="0" name="sort_order" id="methodSort" value="0"
                                class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition">
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition shadow-md shadow-blue-500/20">
                            Save Method
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
                        <th class="py-3 px-4 font-semibold text-gray-600 dark:text-gray-300">Code</th>
                        <th class="py-3 px-4 font-semibold text-gray-600 dark:text-gray-300">Label</th>
                        <th class="py-3 px-4 font-semibold text-gray-600 dark:text-gray-300">Delivery</th>
                        <th class="py-3 px-4 font-semibold text-gray-600 dark:text-gray-300">Cost</th>
                        <th class="py-3 px-4 font-semibold text-gray-600 dark:text-gray-300">Active</th>
                        <th class="py-3 px-4 font-semibold text-gray-600 dark:text-gray-300 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php if (empty($methods)): ?>
                    <tr>
                        <td colspan="7" class="py-10 text-center text-gray-500">No shipping methods found.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($methods as $idx => $m): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="py-3 px-4 text-gray-500"><?= $idx + 1 ?></td>
                            <td class="py-3 px-4 font-mono text-xs text-gray-500"><?= htmlspecialchars($m['code']) ?></td>
                            <td class="py-3 px-4 font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($m['label']) ?></td>
                            <td class="py-3 px-4 text-gray-500"><?= htmlspecialchars($m['days'] ?? '—') ?></td>
                            <td class="py-3 px-4 text-gray-900 dark:text-white">$<?= number_format($m['cost'], 2) ?></td>
                            <td class="py-3 px-4">
                                <?php if ($m['is_active']): ?>
                                <span class="text-green-500 bg-green-100 dark:bg-green-900/30 px-2 py-0.5 rounded-full text-xs font-medium">Active</span>
                                <?php else: ?>
                                <span class="text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full text-xs font-medium">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="editMethod(<?= $m['id'] ?>, '<?= addslashes($m['code']) ?>', '<?= addslashes($m['label']) ?>', '<?= addslashes($m['days'] ?? '') ?>', <?= $m['cost'] ?>, <?= $m['is_active'] ?>, <?= $m['sort_order'] ?? 0 ?>)" class="text-blue-500 hover:text-blue-700 p-1">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="<?= BASE_URL ?>admin/shipping-method/delete?id=<?= $m['id'] ?>" onclick="return confirm('Are you sure?')" class="text-red-500 hover:text-red-700 p-1">
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
function editMethod(id, code, label, days, cost, active, sort) {
    document.getElementById('formTitle').textContent = 'Edit Shipping Method';
    document.getElementById('methodId').value = id;
    document.getElementById('methodCode').value = code;
    document.getElementById('methodLabel').value = label;
    document.getElementById('methodDays').value = days;
    document.getElementById('methodCost').value = cost;
    document.getElementById('methodActive').value = active;
    document.getElementById('methodSort').value = sort;
    document.getElementById('cancelBtn').classList.remove('hidden');
    document.getElementById('methodCode').focus();
}

function resetForm() {
    document.getElementById('formTitle').textContent = 'Add Shipping Method';
    document.getElementById('methodId').value = '';
    document.getElementById('methodForm').reset();
    document.getElementById('methodActive').value = '1';
    document.getElementById('methodSort').value = '0';
    document.getElementById('cancelBtn').classList.add('hidden');
}
</script>
