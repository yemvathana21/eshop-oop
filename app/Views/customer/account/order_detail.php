<div class="max-w-2xl mx-auto pb-10">
    <div class="mb-6">
        <a href="<?= BASE_URL ?>account/orders" class="text-sm text-blue-600 dark:text-blue-400 hover:underline"><i class="fas fa-chevron-left mr-1"></i> Orders</a>
    </div>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">#<?= htmlspecialchars($order['invoice_number']) ?></h1>
        <?php $badgeMap = ['pending' => 'bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600', 'processing' => 'bg-blue-50 dark:bg-blue-900/20 text-blue-600', 'shipped' => 'bg-blue-50 dark:bg-blue-900/20 text-blue-600', 'delivered' => 'bg-green-50 dark:bg-green-900/20 text-green-600', 'completed' => 'bg-green-50 dark:bg-green-900/20 text-green-600', 'cancelled' => 'bg-red-50 dark:bg-red-900/20 text-red-600']; ?>
        <span class="text-xs px-2.5 py-1 rounded <?= $badgeMap[$order['status']] ?? 'bg-gray-100 text-gray-600' ?>"><?= t($order['status']) ?></span>
    </div>

    <!-- Total Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 mb-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500"><?= t('total_amount') ?></p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">$<?= number_format((float)$order['total_price'], 2) ?></p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500"><?= date('M d, Y', strtotime($order['created_at'])) ?></p>
                <?php if (!empty($order['updated_at'])): ?>
                <p class="text-xs text-gray-400 mt-0.5"><?= t('updated') ?> <?= date('M d, Y', strtotime($order['updated_at'])) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Items -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700 mb-4">
        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white"><?= t('order_items') ?></h2>
        </div>
        <?php if (!empty($items)): ?>
            <?php foreach ($items as $item): ?>
            <div class="flex items-center gap-3.5 px-4 py-3.5">
                <div class="w-12 h-12 rounded-lg overflow-hidden shrink-0 bg-gray-100 dark:bg-gray-700">
                    <?php if (!empty($item['product_image'])): ?>
                        <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($item['product_image']) ?>" alt="" class="w-full h-full object-cover" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                        <div class="w-full h-full hidden items-center justify-center text-gray-400"><i class="fas fa-box"></i></div>
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-gray-400"><i class="fas fa-box"></i></div>
                    <?php endif; ?>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate"><?= htmlspecialchars($item['product_name']) ?></p>
                    <p class="text-xs text-gray-500"><?= (int)$item['quantity'] ?> x $<?= number_format((float)$item['price'], 2) ?></p>
                </div>
                <p class="text-sm font-semibold text-gray-900 dark:text-white">$<?= number_format((float)$item['price'] * (int)$item['quantity'], 2) ?></p>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Summary -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <div class="space-y-2.5 text-sm">
            <div class="flex justify-between text-gray-500"><span><?= t('subtotal') ?></span><span class="text-gray-900 dark:text-white">$<?= number_format((float)($order['subtotal'] ?? $order['total_price']), 2) ?></span></div>
            <?php if (!empty($order['discount'])): ?>
            <div class="flex justify-between text-green-600"><span><?= t('discount') ?></span><span>-$<?= number_format((float)$order['discount'], 2) ?></span></div>
            <?php endif; ?>
            <?php if (!empty($order['shipping_fee'])): ?>
            <div class="flex justify-between text-gray-500"><span><?= t('shipping') ?></span><span class="text-gray-900 dark:text-white">$<?= number_format((float)$order['shipping_fee'], 2) ?></span></div>
            <?php endif; ?>
            <hr class="border-gray-100 dark:border-gray-700">
            <div class="flex justify-between font-semibold text-gray-900 dark:text-white"><span><?= t('total') ?></span><span class="text-lg">$<?= number_format((float)$order['total_price'], 2) ?></span></div>
        </div>
        <?php if (!empty($order['shipping_address'])): ?>
        <div class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-700">
            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-1"><?= t('shipping_address') ?></h3>
            <p class="text-sm text-gray-500"><?= nl2br(htmlspecialchars($order['shipping_address'])) ?></p>
        </div>
        <?php endif; ?>
    </div>
</div>
