<div class="space-y-6">
    <p class="text-sm text-gray-500 dark:text-gray-400"><?= count($orders) ?> <?= t('orders_total') ?></p>

    <?php if (empty($orders)): ?>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 text-center py-16 transition-colors">
        <i class="fas fa-file-invoice text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
        <p class="text-gray-500 dark:text-gray-400 text-lg"><?= t('no_orders_found') ?></p>
    </div>
    <?php else: ?>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-100 dark:border-gray-600">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('invoice') ?></th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('customer') ?></th>
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
                        <td class="py-3 px-4 text-gray-600 dark:text-gray-300"><?= date('M d, Y g:i A', strtotime($order['created_at'])) ?></td>
                        <td class="py-3 px-4">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold
                                <?= $order['status'] === 'completed' ? 'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-400' : ($order['status'] === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-400' : 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-400') ?>">
                                <?= t($order['status']) ?>
                            </span>
                        </td>
                        <td class="py-3 px-4 font-bold text-gray-900 dark:text-white">$<?= number_format($order['total_price'], 2) ?></td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="<?= BASE_URL ?>admin/order?id=<?= $order['id'] ?>" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition px-2 py-1" title="<?= t('order_details') ?>">
                                    <i class="fas fa-eye"></i>
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
