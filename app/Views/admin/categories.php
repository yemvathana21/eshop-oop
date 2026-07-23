<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= t('manage_categories') ?></h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1"><?= count($categories) ?> <?= t('categories_total') ?></p>
    </div>
    <a href="<?= BASE_URL ?>admin/category/create" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium transition inline-flex items-center gap-2">
        <i class="fas fa-plus"></i> <?= t('add_category') ?>
    </a>
</div>

<?php if (empty($categories)): ?>
<div class="text-center py-20 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
    <i class="fas fa-tags text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
    <p class="text-gray-500 dark:text-gray-400 text-lg mb-4"><?= t('no_categories_yet') ?></p>
    <a href="<?= BASE_URL ?>admin/category/create" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium transition">
        <i class="fas fa-plus mr-2"></i> <?= t('add_first_category') ?>
    </a>
</div>
<?php else: ?>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                    <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300">#</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('category_name') ?></th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300">Slug</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300">Icon</th>
                    <th class="text-center py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('sort_order') ?></th>
                    <th class="text-center py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('products') ?></th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('actions') ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php foreach ($categories as $cat): ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                    <td class="py-3 px-4 text-gray-500 dark:text-gray-400"><?= $cat['id'] ?></td>
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-blue-50 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                <i class="fas <?= htmlspecialchars($cat['icon']) ?> text-blue-600 dark:text-blue-400 text-sm"></i>
                            </div>
                            <span class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($cat['name']) ?></span>
                        </div>
                    </td>
                    <td class="py-3 px-4">
                        <code class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-1 rounded"><?= htmlspecialchars($cat['slug']) ?></code>
                    </td>
                    <td class="py-3 px-4 text-gray-500 dark:text-gray-400"><code class="text-xs"><?= htmlspecialchars($cat['icon']) ?></code></td>
                    <td class="py-3 px-4 text-center text-gray-500 dark:text-gray-400"><?= $cat['sort_order'] ?></td>
                    <td class="py-3 px-4 text-center">
                        <span class="inline-block bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs font-semibold px-2.5 py-1 rounded-full"><?= $cat['product_count'] ?></span>
                    </td>
                    <td class="py-3 px-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="<?= BASE_URL ?>admin/category/edit?id=<?= $cat['id'] ?>" class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-600 flex items-center justify-center transition" title="<?= t('edit') ?>">
                                <i class="fas fa-pen text-xs"></i>
                            </a>
                            <a href="<?= BASE_URL ?>admin/category/delete?id=<?= $cat['id'] ?>" onclick="return confirm('<?= t('confirm_delete_category') ?>')" class="text-gray-400 hover:text-red-600 dark:hover:text-red-400 w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-600 flex items-center justify-center transition" title="<?= t('delete') ?>">
                                <i class="fas fa-trash text-xs"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
