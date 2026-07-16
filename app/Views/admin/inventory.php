<div class="space-y-6">

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400"><?= t('total_products') ?></p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= count($products) ?></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-box text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400"><?= t('total_units_in_stock') ?></p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= number_format($totalStock) ?></p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-cubes text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400"><?= t('inventory_value') ?></p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">$<?= number_format($totalValue, 2) ?></p>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400"><?= t('low_stock_items') ?></p>
                    <p class="text-2xl font-bold <?= count($lowStock) > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' ?> mt-1"><?= count($lowStock) ?></p>
                </div>
                <div class="w-12 h-12 <?= count($lowStock) > 0 ? 'bg-red-100 dark:bg-red-900/50' : 'bg-gray-100 dark:bg-gray-700' ?> rounded-xl flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle <?= count($lowStock) > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-400 dark:text-gray-500' ?> text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Alert Banner -->
    <?php if (!empty($outOfStock)): ?>
    <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-red-100 dark:bg-red-800 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400"></i>
            </div>
            <div>
                <p class="font-semibold text-red-800 dark:text-red-300"><?= count($outOfStock) ?> <?= t('product_out_of_stock') ?></p>
                <p class="text-sm text-red-600 dark:text-red-400">
                    <?php
                    $names = array_column($outOfStock, 'name');
                    echo htmlspecialchars(implode(', ', array_slice($names, 0, 5)));
                    if (count($names) > 5) echo ' +' . (count($names) - 5);
                    ?>
                </p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($lowStock) && empty($outOfStock)): ?>
    <div class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-800 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400"></i>
            </div>
            <div>
                <p class="font-semibold text-yellow-800 dark:text-yellow-300"><?= count($lowStock) ?> <?= t('products_low_stock') ?></p>
                <p class="text-sm text-yellow-600 dark:text-yellow-400">
                    <?php
                    $names = array_column($lowStock, 'name');
                    echo htmlspecialchars(implode(', ', array_slice($names, 0, 5)));
                    if (count($names) > 5) echo ' +' . (count($names) - 5);
                    ?>
                </p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Inventory Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors">
        <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <h2 class="font-bold text-gray-900 dark:text-white"><?= t('stock_levels') ?></h2>
            <a href="<?= BASE_URL ?>admin/products" class="text-blue-600 dark:text-blue-400 text-sm hover:underline"><i class="fas fa-plus mr-1"></i><?= t('add_product_btn') ?></a>
        </div>

        <?php if (empty($products)): ?>
        <div class="text-center py-16">
            <i class="fas fa-box-open text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
            <p class="text-gray-500 dark:text-gray-400 text-lg"><?= t('no_products_yet') ?></p>
        </div>
        <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('product') ?></th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('price') ?></th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('stock') ?></th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-600 dark:text-gray-300">Value</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('status') ?></th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('quick_adjust') ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php foreach ($products as $product): ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition <?= $product['stock'] === 0 ? 'bg-red-50 dark:bg-red-900/20' : '' ?>">
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
                                    <p class="text-xs text-gray-500 dark:text-gray-400">ID: <?= $product['id'] ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-gray-600 dark:text-gray-300">$<?= number_format($product['price'], 2) ?></td>
                        <td class="py-3 px-4 text-center">
                            <span class="font-bold <?= $product['stock'] === 0 ? 'text-red-600 dark:text-red-400' : ($product['stock'] <= 10 ? 'text-yellow-600 dark:text-yellow-400' : 'text-gray-900 dark:text-white') ?>">
                                <?= $product['stock'] ?>
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center text-gray-600 dark:text-gray-300">$<?= number_format($product['price'] * $product['stock'], 2) ?></td>
                        <td class="py-3 px-4">
                            <?php if ($product['stock'] === 0): ?>
                                <span class="inline-flex items-center gap-1 bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-400 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                    <i class="fas fa-times-circle"></i> <?= t('out_of_stock') ?>
                                </span>
                            <?php elseif ($product['stock'] <= 10): ?>
                                <span class="inline-flex items-center gap-1 bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-400 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                    <i class="fas fa-exclamation-circle"></i> <?= t('low_stock') ?>
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1 bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-400 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                    <i class="fas fa-check-circle"></i> <?= t('in_stock') ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center justify-end gap-1">
                                <form method="POST" action="<?= BASE_URL ?>admin/inventory/adjust" class="inline-flex items-center">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <input type="hidden" name="adjust_action" value="add">
                                    <div class="flex items-center border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
                                        <input type="number" name="quantity" value="1" min="1" max="9999" class="w-14 text-center border-none text-xs py-1.5 focus:ring-0 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" title="<?= t('add_stock') ?>">
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-2.5 py-1.5 text-xs transition" title="<?= t('add_stock') ?>">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </form>
                                <form method="POST" action="<?= BASE_URL ?>admin/inventory/adjust" class="inline-flex items-center">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <input type="hidden" name="adjust_action" value="remove">
                                    <div class="flex items-center border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
                                        <input type="number" name="quantity" value="1" min="1" max="<?= $product['stock'] ?>" class="w-14 text-center border-none text-xs py-1.5 focus:ring-0 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" title="<?= t('remove_stock') ?>">
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2.5 py-1.5 text-xs transition <?= $product['stock'] === 0 ? 'opacity-50 cursor-not-allowed' : '' ?>" title="<?= t('remove_stock') ?>" <?= $product['stock'] === 0 ? 'disabled' : '' ?>>
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </form>
                                <form method="POST" action="<?= BASE_URL ?>admin/inventory/adjust" class="inline-flex items-center ml-1">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <input type="hidden" name="adjust_action" value="set">
                                    <input type="number" name="quantity" value="<?= $product['stock'] ?>" min="0" max="99999" class="w-16 text-center border border-gray-200 dark:border-gray-600 rounded-lg text-xs py-1.5 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" title="<?= t('set_stock_level') ?>">
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-2.5 py-1.5 text-xs rounded-r-lg transition ml-[-1px]" title="<?= t('set_stock_level') ?>">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
