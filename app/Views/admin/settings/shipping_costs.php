<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Add/Edit Form -->
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h2 id="formTitle" class="text-lg font-bold text-gray-900 dark:text-white mb-4">Add Shipping Cost</h2>
            <form action="<?= BASE_URL ?>admin/shipping-cost/save" method="POST" id="scForm">
                <input type="hidden" name="id" id="scId">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Country *</label>
                        <select name="country_id" id="scCountryId" required
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition">
                            <option value="">Select Country</option>
                            <?php foreach ($countries as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount ($) *</label>
                        <input type="number" step="0.01" name="amount" id="scAmount" required
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition"
                            placeholder="0.00">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition shadow-md shadow-blue-500/20">
                            Save Cost
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
                        <th class="py-3 px-4 font-semibold text-gray-600 dark:text-gray-300">Country</th>
                        <th class="py-3 px-4 font-semibold text-gray-600 dark:text-gray-300">Amount</th>
                        <th class="py-3 px-4 font-semibold text-gray-600 dark:text-gray-300 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php if (empty($shippingCosts)): ?>
                    <tr>
                        <td colspan="4" class="py-10 text-center text-gray-500">No shipping costs defined.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($shippingCosts as $idx => $sc): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="py-3 px-4 text-gray-500"><?= $idx + 1 ?></td>
                            <td class="py-3 px-4 font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($sc['country_name']) ?></td>
                            <td class="py-3 px-4 text-gray-700 dark:text-gray-300">$<?= number_format($sc['amount'], 2) ?></td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="editSC(<?= $sc['id'] ?>, <?= $sc['country_id'] ?>, <?= $sc['amount'] ?>)" class="text-blue-500 hover:text-blue-700 p-1">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="<?= BASE_URL ?>admin/shipping-cost/delete?id=<?= $sc['id'] ?>" onclick="return confirm('Are you sure?')" class="text-red-500 hover:text-red-700 p-1">
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
function editSC(id, countryId, amount) {
    document.getElementById('formTitle').textContent = 'Edit Shipping Cost';
    document.getElementById('scId').value = id;
    document.getElementById('scCountryId').value = countryId;
    document.getElementById('scAmount').value = amount;
    document.getElementById('cancelBtn').classList.remove('hidden');
    document.getElementById('scCountryId').focus();
}

function resetForm() {
    document.getElementById('formTitle').textContent = 'Add Shipping Cost';
    document.getElementById('scId').value = '';
    document.getElementById('scForm').reset();
    document.getElementById('cancelBtn').classList.add('hidden');
}
</script>
