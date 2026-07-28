<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Add/Edit Form -->
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h2 id="formTitle" class="text-lg font-bold text-gray-900 dark:text-white mb-4">Add Top Category</h2>
            <form action="<?= BASE_URL ?>admin/top-category/save" method="POST" id="topCatForm">
                <input type="hidden" name="id" id="catId">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category Name *</label>
                        <input type="text" name="name" id="catName" required
                            onkeyup="document.getElementById('catSlug').value = this.value.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '')"
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition"
                            placeholder="e.g. Electronics">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Slug *</label>
                        <input type="text" name="slug" id="catSlug" required
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">FontAwesome Icon</label>
                        <input type="text" name="icon" id="catIcon" value="fa-tag"
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition"
                            placeholder="fa-laptop">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition shadow-md shadow-blue-500/20">
                            Save Category
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
                        <th class="py-3 px-4 font-semibold text-gray-600 dark:text-gray-300">Category Name</th>
                        <th class="py-3 px-4 font-semibold text-gray-600 dark:text-gray-300">Icon</th>
                        <th class="py-3 px-4 font-semibold text-gray-600 dark:text-gray-300 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="4" class="py-10 text-center text-gray-500">No categories found.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($categories as $idx => $c): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="py-3 px-4 text-gray-500"><?= $idx + 1 ?></td>
                            <td class="py-3 px-4 font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($c['name']) ?></td>
                            <td class="py-3 px-4"><i class="fas <?= htmlspecialchars($c['icon']) ?> text-gray-400"></i></td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="editCat(<?= $c['id'] ?>, '<?= addslashes($c['name']) ?>', '<?= addslashes($c['slug']) ?>', '<?= addslashes($c['icon']) ?>')" class="text-blue-500 hover:text-blue-700 p-1">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="<?= BASE_URL ?>admin/top-category/delete?id=<?= $c['id'] ?>" onclick="return confirm('Are you sure? This will affect subcategories.')" class="text-red-500 hover:text-red-700 p-1">
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
function editCat(id, name, slug, icon) {
    document.getElementById('formTitle').textContent = 'Edit Top Category';
    document.getElementById('catId').value = id;
    document.getElementById('catName').value = name;
    document.getElementById('catSlug').value = slug;
    document.getElementById('catIcon').value = icon;
    document.getElementById('cancelBtn').classList.remove('hidden');
    document.getElementById('catName').focus();
}

function resetForm() {
    document.getElementById('formTitle').textContent = 'Add Top Category';
    document.getElementById('catId').value = '';
    document.getElementById('topCatForm').reset();
    document.getElementById('cancelBtn').classList.add('hidden');
}
</script>
