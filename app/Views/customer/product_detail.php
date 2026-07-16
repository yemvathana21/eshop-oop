<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="<?= BASE_URL ?>shop" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 mb-6 text-sm font-medium">
        <i class="fas fa-arrow-left mr-2"></i><?= t('back_to_shop') ?>
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex items-center justify-center h-96 transition-colors">
            <?php if ($product['image'] && file_exists(UPLOAD_PATH . $product['image'])): ?>
                <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-full object-cover">
            <?php else: ?>
                <div class="text-gray-300 dark:text-gray-600"><i class="fas fa-image text-7xl"></i></div>
            <?php endif; ?>
        </div>

        <div>
            <?php if ($product['stock'] <= 0): ?>
                <span class="inline-block bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-medium px-3 py-1 rounded-full mb-3"><?= t('sold_out') ?></span>
            <?php elseif ($product['stock'] <= 10): ?>
                <span class="inline-block bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 text-xs font-medium px-3 py-1 rounded-full mb-3"><?= $product['stock'] ?> <?= t('low_stock_left') ?></span>
            <?php else: ?>
                <span class="inline-block bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-medium px-3 py-1 rounded-full mb-3"><?= t('in_stock_available') ?> - <?= $product['stock'] ?> <?= t('available') ?></span>
            <?php endif; ?>

            <?php if (!empty($product['category_name'])): ?>
            <div class="mb-2">
                <a href="<?= BASE_URL ?>shop?category=<?= htmlspecialchars($product['category_slug']) ?>" class="inline-flex items-center gap-1 text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 bg-blue-50 dark:bg-blue-900/30 px-2.5 py-1 rounded-full transition">
                    <i class="fas fa-tag"></i> <?= htmlspecialchars($product['category_name']) ?>
                </a>
            </div>
            <?php endif; ?>

            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2"><?= htmlspecialchars($product['name']) ?></h1>
            <div class="flex items-center gap-3 mb-6">
                <span class="text-4xl font-bold text-blue-600 dark:text-blue-400">$<?= number_format($product['price'], 2) ?></span>
                <?php if (!empty($product['compare_price']) && $product['compare_price'] > $product['price']): ?>
                    <span class="text-2xl text-gray-400 dark:text-gray-500 line-through">$<?= number_format($product['compare_price'], 2) ?></span>
                    <span class="bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-sm font-semibold px-2.5 py-1 rounded-full">Save -<?= round((1 - $product['price'] / $product['compare_price']) * 100) ?>%</span>
                <?php endif; ?>
            </div>

            <div class="prose prose-sm text-gray-600 dark:text-gray-400 mb-8">
                <p class="whitespace-pre-line"><?= htmlspecialchars($product['description'] ?? 'No description available.') ?></p>
            </div>

            <?php if ($product['stock'] > 0): ?>
            <form method="POST" action="<?= BASE_URL ?>cart/add" class="flex items-center gap-4">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden">
                    <button type="button" onclick="decrementQty()" class="px-4 py-3 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition">-</button>
                    <input type="number" name="quantity" id="qty" value="1" min="1" max="<?= $product['stock'] ?>" readonly class="w-16 text-center border-none focus:ring-0 font-semibold bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                    <button type="button" onclick="incrementQty(<?= $product['stock'] ?>)" class="px-4 py-3 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition">+</button>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition shadow-sm">
                    <i class="fas fa-cart-plus mr-2"></i><?= t('add_to_cart') ?>
                </button>
            </form>
            <?php else: ?>
            <button disabled class="bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-500 px-8 py-3 rounded-lg font-semibold cursor-not-allowed">
                <i class="fas fa-ban mr-2"></i><?= t('out_of_stock_msg') ?>
            </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function incrementQty(max) {
    const input = document.getElementById('qty');
    if (parseInt(input.value) < max) input.value = parseInt(input.value) + 1;
}
function decrementQty() {
    const input = document.getElementById('qty');
    if (parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
}
</script>
