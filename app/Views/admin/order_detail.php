<a href="<?= BASE_URL ?>admin/orders" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 mb-6 text-sm font-medium">
    <i class="fas fa-arrow-left mr-2"></i><?= t('back_to_orders') ?>
</a>

<div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 transition-colors">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white"><?= t('order') ?> #<?= htmlspecialchars($order['invoice_number']) ?></h2>
            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                <?= $order['status'] === 'completed' ? 'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-400' : ($order['status'] === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-400' : 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-400') ?>">
                <?= t($order['status']) ?>
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider mb-1"><?= t('customer') ?></p>
                <p class="font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($order['user_name']) ?></p>
                <p class="text-gray-600 dark:text-gray-300 text-sm"><?= htmlspecialchars($order['user_email']) ?></p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider mb-1"><?= t('date') ?></p>
                <p class="font-bold text-gray-900 dark:text-white"><?= date('M d, Y', strtotime($order['created_at'])) ?></p>
                <p class="text-gray-600 dark:text-gray-300 text-sm"><?= date('g:i A', strtotime($order['created_at'])) ?></p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider mb-1"><?= t('total') ?></p>
                <p class="font-bold text-blue-600 dark:text-blue-400 text-xl">$<?= number_format($order['total_price'], 2) ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors">
        <div class="p-5 border-b border-gray-100 dark:border-gray-700">
            <h3 class="font-bold text-gray-900 dark:text-white"><?= t('order_items') ?></h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('product') ?></th>
                    <th class="text-center py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('unit_price') ?></th>
                    <th class="text-center py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('qty') ?></th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('subtotal') ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php foreach ($items as $item): ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-lg flex-shrink-0 overflow-hidden flex items-center justify-center">
                                <?php if ($item['product_image'] && file_exists(UPLOAD_PATH . $item['product_image'])): ?>
                                    <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($item['product_image']) ?>" class="w-full h-full object-cover" alt="">
                                <?php else: ?>
                                    <i class="fas fa-image text-gray-300 dark:text-gray-500 text-xs"></i>
                                <?php endif; ?>
                            </div>
                            <span class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($item['product_name']) ?></span>
                        </div>
                    </td>
                    <td class="py-3 px-4 text-center text-gray-600 dark:text-gray-300">$<?= number_format($item['price'], 2) ?></td>
                    <td class="py-3 px-4 text-center text-gray-600 dark:text-gray-300"><?= $item['quantity'] ?></td>
                    <td class="py-3 px-4 text-right font-bold text-gray-900 dark:text-white">$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                <tr>
                    <td colspan="3" class="py-3 px-4 text-right font-bold text-gray-900 dark:text-white"><?= t('total') ?></td>
                    <td class="py-3 px-4 text-right font-bold text-blue-600 dark:text-blue-400 text-lg">$<?= number_format($order['total_price'], 2) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="flex gap-3">
        <a href="<?= BASE_URL ?>admin/order/invoice?inv=<?= htmlspecialchars($order['invoice_number']) ?>" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg font-medium transition text-sm">
            <i class="fas fa-file-invoice mr-2"></i><?= t('view_invoice') ?>
        </a>
        <a href="<?= BASE_URL ?>admin/orders" class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-5 py-2.5 rounded-lg font-medium transition text-sm">
            <?= t('back_to_orders') ?>
        </a>
    </div>
</div>
