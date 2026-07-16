<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="<?= BASE_URL ?>cart" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 mb-6 text-sm font-medium">
        <i class="fas fa-arrow-left mr-2"></i><?= t('back_to_shop') ?>
    </a>

    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8"><?= t('checkout') ?></h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 transition-colors">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4"><i class="fas fa-user mr-2 text-gray-400"></i><?= t('billing_info') ?></h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                        <span class="text-gray-500 dark:text-gray-400 text-xs"><?= t('full_name') ?></span>
                        <p class="font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars(App\Core\Session::get('user_name', '')) ?></p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                        <span class="text-gray-500 dark:text-gray-400 text-xs"><?= t('email_address') ?></span>
                        <p class="font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars(App\Core\Session::get('user_email', '')) ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mt-4 transition-colors">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4"><i class="fas fa-box mr-2 text-gray-400"></i><?= t('order_items') ?></h2>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php foreach ($cart as $productId => $item): ?>
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex-shrink-0 overflow-hidden flex items-center justify-center">
                                <?php if ($item['image'] && file_exists(UPLOAD_PATH . $item['image'])): ?>
                                    <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($item['image']) ?>" class="w-full h-full object-cover" alt="">
                                <?php else: ?>
                                    <i class="fas fa-image text-gray-300 dark:text-gray-600"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white text-sm truncate max-w-[180px]" title="<?= htmlspecialchars($item['name']) ?>"><?= htmlspecialchars($item['name']) ?></p>
                                <p class="text-gray-500 dark:text-gray-400 text-xs"><?= t('qty') ?>: <?= $item['quantity'] ?> &times; $<?= number_format($item['price'], 2) ?></p>
                            </div>
                        </div>
                        <p class="font-bold text-gray-900 dark:text-white">$<?= number_format($item['price'] * $item['quantity'], 2) ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sticky top-4 transition-colors">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4"><?= t('payment_summary') ?></h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400"><?= t('subtotal') ?></span>
                        <span class="font-medium text-gray-900 dark:text-white">$<?= number_format($total, 2) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400"><?= t('shipping') ?></span>
                        <span class="text-green-600 dark:text-green-400 font-medium"><?= t('free') ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Tax</span>
                        <span class="text-gray-500 dark:text-gray-400">$0.00</span>
                    </div>
                    <hr class="border-gray-100 dark:border-gray-700">
                    <div class="flex justify-between text-lg">
                        <span class="font-bold text-gray-900 dark:text-white"><?= t('total') ?></span>
                        <span class="font-bold text-blue-600 dark:text-blue-400">$<?= number_format($total, 2) ?></span>
                    </div>
                </div>

                <form method="POST" action="<?= BASE_URL ?>checkout/process">
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-bold transition shadow-sm mt-6 text-lg">
                        <i class="fas fa-lock mr-2"></i><?= t('place_order') ?>
                    </button>
                </form>
                <p class="text-center text-xs text-gray-400 dark:text-gray-500 mt-3"><i class="fas fa-shield-alt mr-1"></i><?= t('secure_checkout') ?></p>
            </div>
        </div>
    </div>
</div>
