<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8"><?= t('shopping_cart') ?></h1>

    <?php if (empty($cart)): ?>
    <div class="text-center py-20 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors">
        <i class="fas fa-shopping-cart text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
        <p class="text-gray-500 dark:text-gray-400 text-lg mb-4"><?= t('cart_empty') ?></p>
        <a href="<?= BASE_URL ?>shop" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium transition">
            <i class="fas fa-arrow-left mr-2"></i><?= t('continue_shopping') ?>
        </a>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-4">
            <?php foreach ($cart as $productId => $item): ?>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 flex gap-5 transition-colors">
                <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-lg flex-shrink-0 overflow-hidden flex items-center justify-center">
                    <?php if (!empty($item['image'])): ?>
                        <img src="<?= BASE_URL . 'uploads/' . rawurlencode($item['image']) ?>"
                             onerror="this.src='<?= BASE_URL . 'images/' . rawurlencode($item['image']) ?>'; this.onerror=null;"
                             class="w-full h-full object-cover" alt="<?= htmlspecialchars($item['name']) ?>">
                    <?php else: ?>
                        <i class="fas fa-image text-gray-300 dark:text-gray-600 text-2xl"></i>
                    <?php endif; ?>
                </div>
                <div class="flex-1">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white truncate max-w-[200px]" title="<?= htmlspecialchars($item['name']) ?>"><?= htmlspecialchars($item['name']) ?></h3>
                            <p class="text-blue-600 dark:text-blue-400 font-bold mt-1">$<?= number_format($item['price'], 2) ?></p>
                        </div>
                        <a href="<?= BASE_URL ?>cart/remove?id=<?= $productId ?>" class="text-red-500 hover:text-red-700 dark:hover:text-red-400 transition" title="<?= t('remove') ?>">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                    <div class="flex items-center justify-between mt-3">
                        <form method="POST" action="<?= BASE_URL ?>cart/update" class="flex items-center">
                            <input type="hidden" name="product_id" value="<?= $productId ?>">
                            <div class="flex items-center border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
                                <button type="submit" name="quantity" value="<?= $item['quantity'] - 1 ?>" class="px-3 py-1.5 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition">-</button>
                                <span class="px-4 py-1.5 font-semibold text-sm text-gray-900 dark:text-white"><?= $item['quantity'] ?></span>
                                <button type="submit" name="quantity" value="<?= $item['quantity'] + 1 ?>" class="px-3 py-1.5 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition">+</button>
                            </div>
                        </form>
                        <p class="font-bold text-gray-900 dark:text-white">$<?= number_format($item['price'] * $item['quantity'], 2) ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sticky top-4 transition-colors">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4"><?= t('order_summary') ?></h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400"><?= t('items') ?></span>
                        <span class="font-medium text-gray-900 dark:text-white"><?= array_sum(array_column($cart, 'quantity')) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400"><?= t('subtotal') ?></span>
                        <span class="font-medium text-gray-900 dark:text-white">$<?= number_format(array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart)), 2) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400"><?= t('shipping') ?></span>
                        <span class="text-green-600 dark:text-green-400 font-medium"><?= t('free') ?></span>
                    </div>
                    <hr class="border-gray-100 dark:border-gray-700">
                    <div class="flex justify-between text-lg">
                        <span class="font-bold text-gray-900 dark:text-white"><?= t('total') ?></span>
                        <span class="font-bold text-blue-600 dark:text-blue-400">$<?= number_format(array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart)), 2) ?></span>
                    </div>
                </div>

                <?php if (App\Core\Session::isLoggedIn()): ?>
                <a href="<?= BASE_URL ?>checkout" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition mt-6">
                    <?= t('proceed_to_checkout') ?>
                </a>
                <?php else: ?>
                <a href="<?= BASE_URL ?>login" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition mt-6">
                    <?= t('login_to_checkout') ?>
                </a>
                <?php endif; ?>

                <a href="<?= BASE_URL ?>shop" class="block w-full text-center bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 py-2.5 rounded-lg font-medium transition mt-2 text-sm">
                    <?= t('continue_shopping') ?>
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
