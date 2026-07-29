<div class="max-w-2xl mx-auto pb-10">
    <div class="mb-6">
        <a href="<?= BASE_URL ?>account/dashboard" class="text-sm text-blue-600 dark:text-blue-400 hover:underline"><i class="fas fa-chevron-left mr-1"></i> Settings</a>
    </div>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">My Orders</h1>

    <!-- Filter tabs -->
    <div class="flex gap-2 mb-6 overflow-x-auto scrollbar-hide">
        <?php
        $statuses = ['all' => 'All', 'pending' => 'Pending', 'confirmed' => 'Confirmed', 'shipping' => 'Shipping', 'delivery' => 'Delivery', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'];
        foreach ($statuses as $key => $label):
            $active = $currentStatus === $key;
        ?>
        <a href="<?= BASE_URL ?>account/orders?status=<?= $key ?>"
           class="px-4 py-2 rounded-full text-sm font-medium transition whitespace-nowrap
           <?= $active ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600' ?>">
            <?= $label ?>
        </a>
        <?php endforeach; ?>
    </div>

    <?php if (empty($orders)): ?>
    <div class="text-center py-16">
        <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mx-auto mb-4"><i class="fas fa-receipt text-2xl text-gray-400"></i></div>
        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">No Orders</h3>
        <p class="text-sm text-gray-500 mb-5">You haven't placed any orders yet.</p>
        <a href="<?= BASE_URL ?>shop" class="inline-flex px-5 py-2.5 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition">Start Shopping</a>
    </div>
    <?php else: ?>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700">
        <?php foreach ($orders as $order): ?>
        <?php $badgeMap = ['pending' => 'bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600', 'confirmed' => 'bg-blue-50 dark:bg-blue-900/20 text-blue-600', 'shipping' => 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600', 'delivery' => 'bg-purple-50 dark:bg-purple-900/20 text-purple-600', 'delivered' => 'bg-green-50 dark:bg-green-900/20 text-green-600', 'completed' => 'bg-green-50 dark:bg-green-900/20 text-green-600', 'cancelled' => 'bg-red-50 dark:bg-red-900/20 text-red-600']; ?>
        <div class="flex items-center justify-between px-4 py-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition group">
            <a href="<?= BASE_URL ?>account/order?id=<?= (int)$order['id'] ?>" class="flex items-center gap-3 min-w-0 flex-1">
                <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 shrink-0"><i class="fas fa-receipt"></i></div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">#<?= htmlspecialchars($order['invoice_number']) ?></span>
                        <span class="text-xs px-2 py-0.5 rounded <?= $badgeMap[$order['status']] ?? 'bg-gray-100 text-gray-600' ?>"><?= t($order['status']) ?></span>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5"><?= date('M d, Y', strtotime($order['created_at'])) ?></p>
                </div>
            </a>
            <div class="flex items-center gap-3 shrink-0">
                <?php if ($order['status'] === 'pending'): ?>
                <form method="post" action="<?= BASE_URL ?>account/order/cancel" onsubmit="return confirm('<?= t('cancel_order_confirm') ?>')" class="opacity-0 group-hover:opacity-100 transition">
                    <input type="hidden" name="id" value="<?= (int)$order['id'] ?>">
                    <button type="submit" class="text-xs bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-800 px-2 py-1 rounded font-medium whitespace-nowrap">
                        <?= t('cancel_order') ?>
                    </button>
                </form>
                <?php endif; ?>
                <span class="text-sm font-bold text-gray-900 dark:text-white">$<?= number_format((float)$order['total_price'], 2) ?></span>
                <i class="fas fa-chevron-right text-xs text-gray-400"></i>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
