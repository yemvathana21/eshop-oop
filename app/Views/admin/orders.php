<div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 transition-colors">
        <form method="GET" action="<?= BASE_URL ?>admin/orders" class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-sm font-medium text-gray-600 dark:text-gray-300"><?= t('status') ?>:</span>
                <a href="<?= BASE_URL ?>admin/orders" class="px-3 py-1.5 rounded-lg text-sm font-medium transition <?= !$statusFilter ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' ?>">
                    <?= t('all') ?> (<?= $totalOrders ?>)
                </a>
                <?php
                foreach (['pending', 'confirmed', 'shipping', 'delivery', 'delivered', 'cancelled'] as $sf):
                ?>
                <a href="<?= BASE_URL ?>admin/orders?status=<?= $sf ?>" class="px-3 py-1.5 rounded-lg text-sm font-medium transition <?= $statusFilter === $sf ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' ?>">
                    <?= t($sf) ?> (<?= $statusCounts[$sf] ?? 0 ?>)
                </a>
                <?php endforeach; ?>
            </div>
            <div class="relative sm:ml-auto">
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="<?= t('search') ?>"
                    class="pl-9 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition w-64">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
        </form>
    </div>

    <p class="text-sm text-gray-500 dark:text-gray-400"><?= count($orders) ?> <?= t('orders_total') ?></p>

    <?php if (empty($orders)): ?>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 text-center py-16 transition-colors">
        <i class="fas fa-file-invoice text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
        <p class="text-gray-500 dark:text-gray-400 text-lg"><?= t('no_orders_found') ?></p>
        <?php if ($statusFilter || $search): ?>
        <a href="<?= BASE_URL ?>admin/orders" class="mt-3 text-blue-600 dark:text-blue-400 text-sm hover:underline"><?= t('clear_filters') ?></a>
        <?php endif; ?>
    </div>
    <?php else: ?>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-100 dark:border-gray-600">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('invoice') ?></th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('customer') ?></th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300">Items</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('date') ?></th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('status') ?></th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('total') ?></th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('actions') ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php foreach ($orders as $order): ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="py-3 px-4 font-bold text-gray-900 dark:text-white">#<?= htmlspecialchars($order['invoice_number']) ?></td>
                        <td class="py-3 px-4">
                            <p class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($order['user_name']) ?></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($order['user_email']) ?></p>
                        </td>
                        <td class="py-3 px-4">
                            <div class="text-gray-900 dark:text-white text-xs leading-relaxed">
                                <?php $itemCount = count($order['items']); ?>
                                <?php foreach (array_slice($order['items'], 0, 3) as $item): ?>
                                <div class="truncate max-w-[180px]"><?= htmlspecialchars($item['product_name']) ?> <span class="text-gray-400">x<?= $item['quantity'] ?></span></div>
                                <?php endforeach; ?>
                                <?php if ($itemCount > 3): ?>
                                <div class="text-gray-400 mt-0.5">+<?= $itemCount - 3 ?> more</div>
                                <?php endif; ?>
                                <?php if ($itemCount === 0): ?>
                                <span class="text-gray-400">—</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-gray-600 dark:text-gray-300 whitespace-nowrap"><?= date('M d, Y g:i A', strtotime($order['created_at'])) ?></td>
                        <td class="py-3 px-4">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold
                                <?php
                                    $sc = [
                                        'pending'   => 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-400',
                                        'confirmed' => 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-400',
                                        'shipping'  => 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-400',
                                        'delivery'  => 'bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-400',
                                        'delivered' => 'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-400',
                                        'cancelled' => 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-400',
                                    ];
                                    echo $sc[$order['status']] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300';
                                ?>">
                                <?= t($order['status']) ?>
                            </span>
                        </td>
                        <td class="py-3 px-4 font-bold text-gray-900 dark:text-white whitespace-nowrap">$<?= number_format($order['total_price'], 2) ?></td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="<?= BASE_URL ?>admin/order?id=<?= $order['id'] ?>" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition px-2 py-1" title="Edit Order">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <a href="<?= BASE_URL ?>admin/order/invoice?inv=<?= htmlspecialchars($order['invoice_number']) ?>" class="text-gray-500 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition px-2 py-1" title="<?= t('view_invoice') ?>">
                                    <i class="fas fa-file-invoice"></i>
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