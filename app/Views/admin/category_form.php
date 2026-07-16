<?php $isEdit = !empty($category); ?>
<div class="max-w-2xl">
    <a href="<?= BASE_URL ?>admin/categories" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 mb-6 text-sm font-medium">
        <i class="fas fa-arrow-left mr-2"></i><?= t('back_to_categories') ?>
    </a>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 transition-colors">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6"><?= $isEdit ? t('edit_category') : t('add_new_category') ?></h2>

        <form method="POST" action="<?= BASE_URL ?>admin/category/<?= $isEdit ? 'update' : 'save' ?>" class="space-y-5">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= $category['id'] ?>">
            <?php endif; ?>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('category_name') ?> <span class="text-red-500">*</span></label>
                <input type="text" name="name" required value="<?= htmlspecialchars($category['name'] ?? '') ?>"
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                    placeholder="<?= t('enter_category_name') ?>">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Slug <span class="text-red-500">*</span></label>
                <input type="text" name="slug" required value="<?= htmlspecialchars($category['slug'] ?? '') ?>"
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                    placeholder="e.g. electronics">
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1"><?= t('slug_help') ?></p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Icon</label>
                    <div class="flex gap-2">
                        <input type="text" name="icon" value="<?= htmlspecialchars($category['icon'] ?? 'fa-tag') ?>"
                            class="flex-1 px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            placeholder="fa-tag" id="iconInput">
                        <div class="w-11 h-11 border border-gray-300 dark:border-gray-600 rounded-lg flex items-center justify-center bg-gray-50 dark:bg-gray-700">
                            <i class="fas <?= htmlspecialchars($category['icon'] ?? 'fa-tag') ?>" id="iconPreview"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1"><?= t('fontawesome_icon_help') ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('sort_order') ?></label>
                    <input type="number" name="sort_order" min="0" value="<?= $category['sort_order'] ?? 0 ?>"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2"><?= t('popular_icons') ?></p>
                <div class="flex flex-wrap gap-2">
                    <?php
                    $icons = ['fa-laptop', 'fa-headphones', 'fa-shirt', 'fa-couch', 'fa-gamepad', 'fa-bag-shopping', 'fa-book', 'fa-utensils', 'fa-car', 'fa-heart', 'fa-gem', 'fa-spa', 'fa-baby', 'fa-dumbbell', 'fa-paw', 'fa-camera'];
                    foreach ($icons as $ic):
                    ?>
                    <button type="button" onclick="setIcon('<?= $ic ?>')" class="w-9 h-9 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 flex items-center justify-center transition text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400" title="<?= $ic ?>">
                        <i class="fas <?= $ic ?> text-sm"></i>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium transition">
                    <i class="fas fa-save mr-2"></i><?= $isEdit ? t('update_category') : t('create_category') ?>
                </button>
                <a href="<?= BASE_URL ?>admin/categories" class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-6 py-2.5 rounded-lg font-medium transition"><?= t('cancel') ?></a>
            </div>
        </form>
    </div>
</div>

<script>
const iconInput = document.getElementById('iconInput');
const iconPreview = document.getElementById('iconPreview');

iconInput.addEventListener('input', function() {
    iconPreview.className = 'fas ' + (this.value || 'fa-tag');
});

function setIcon(icon) {
    iconInput.value = icon;
    iconPreview.className = 'fas ' + icon;
}
</script>
