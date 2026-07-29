<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-center">
    <div class="w-20 h-20 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center mx-auto mb-6">
        <i class="fas fa-clock text-3xl text-yellow-600 dark:text-yellow-400"></i>
    </div>

    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Order Placed!</h1>
    <p class="text-gray-500 dark:text-gray-400 mb-6">Your order has been submitted. Please wait for the seller to confirm it.</p>

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 text-left mb-8">
        <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-100 dark:border-gray-700">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Order</p>
                <p class="font-bold text-gray-900 dark:text-white">#<?= htmlspecialchars($order['invoice_number']) ?></p>
            </div>
            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-400">
                <?= t($order['status']) ?>
            </span>
        </div>

        <div class="divide-y divide-gray-100 dark:divide-gray-700 mb-4">
            <?php foreach ($items as $item): ?>
            <div class="flex items-center justify-between py-2">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded flex-shrink-0 overflow-hidden flex items-center justify-center">
                        <?php if ($item['product_image'] && file_exists(UPLOAD_PATH . $item['product_image'])): ?>
                        <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($item['product_image']) ?>" class="w-full h-full object-cover" alt="">
                        <?php else: ?>
                        <i class="fas fa-image text-gray-300 dark:text-gray-600 text-xs"></i>
                        <?php endif; ?>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($item['product_name']) ?></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">x<?= $item['quantity'] ?></p>
                    </div>
                </div>
                <p class="text-sm font-medium text-gray-900 dark:text-white">$<?= number_format($item['price'] * $item['quantity'], 2) ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
            <span class="font-bold text-gray-900 dark:text-white">Total</span>
            <span class="font-bold text-blue-600 dark:text-blue-400 text-lg">$<?= number_format($order['total_price'], 2) ?></span>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
        <?php if ($order['status'] !== 'pending'): ?>
        <a href="<?= BASE_URL ?>invoice?inv=<?= htmlspecialchars($order['invoice_number']) ?>" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg font-medium transition text-sm">
            <i class="fas fa-file-invoice mr-2"></i>View Invoice
        </a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>account/orders" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium transition text-sm">
            <i class="fas fa-receipt mr-2"></i>My Orders
        </a>
        <a href="<?= BASE_URL ?>" class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-6 py-2.5 rounded-lg font-medium transition text-sm">
            <i class="fas fa-home mr-2"></i>Home
        </a>
    </div>
</div>