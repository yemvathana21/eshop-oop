<div class="space-y-6">
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500 dark:text-gray-400"><?= count($products) ?> <?= t('product_total') ?></p>
        <a href="<?= BASE_URL ?>admin/product/create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
            <i class="fas fa-plus mr-1"></i><?= t('add_product') ?>
        </a>
    </div>

    <?php if (empty($products)): ?>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 text-center py-16 transition-colors">
        <i class="fas fa-box-open text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
        <p class="text-gray-500 dark:text-gray-400 text-lg mb-4"><?= t('no_products_yet') ?></p>
        <a href="<?= BASE_URL ?>admin/product/create" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition text-sm"><?= t('add_first_product') ?></a>
    </div>
    <?php else: ?>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-100 dark:border-gray-600">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('product') ?></th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('price') ?></th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('compare_price') ?></th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('stock') ?></th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('actions') ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php foreach ($products as $product): ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-lg flex-shrink-0 overflow-hidden flex items-center justify-center">
                                    <?php if ($product['image'] && file_exists(UPLOAD_PATH . $product['image'])): ?>
                                        <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($product['image']) ?>" class="w-full h-full object-cover" alt="">
                                    <?php else: ?>
                                        <i class="fas fa-image text-gray-300 dark:text-gray-500 text-xs"></i>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($product['name']) ?></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[200px]"><?= htmlspecialchars($product['description'] ?? '') ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 font-bold text-gray-900 dark:text-white">$<?= number_format($product['price'], 2) ?></td>
                        <td class="py-3 px-4">
                            <?php if (!empty($product['compare_price'])): ?>
                                <span class="text-gray-400 dark:text-gray-500 line-through">$<?= number_format($product['compare_price'], 2) ?></span>
                                <span class="ml-1 text-xs text-green-600 dark:text-green-400 font-semibold">-<?= round((1 - $product['price'] / $product['compare_price']) * 100) ?>%</span>
                            <?php else: ?>
                                <span class="text-gray-400 dark:text-gray-500">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold
                                <?= $product['stock'] > 5 ? 'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-400' : ($product['stock'] > 0 ? 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-400' : 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-400') ?>">
                                <?= $product['stock'] ?> <?= t('units') ?>
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="<?= BASE_URL ?>product?id=<?= $product['id'] ?>" target="_blank" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition px-2 py-1" title="<?= t('view') ?>">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?= BASE_URL ?>admin/product/edit?id=<?= $product['id'] ?>" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition px-2 py-1" title="<?= t('edit') ?>">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= BASE_URL ?>admin/product/delete?id=<?= $product['id'] ?>" class="text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition px-2 py-1" title="<?= t('delete') ?>" onclick="return confirm('<?= t('confirm_delete_product') ?>')">
                                    <i class="fas fa-trash-alt"></i>
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
</div>
