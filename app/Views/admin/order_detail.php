<a href="<?= BASE_URL ?>admin/orders" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 mb-6 text-sm font-medium">
    <i class="fas fa-arrow-left mr-2"></i><?= t('back_to_orders') ?>
</a>

<div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 transition-colors">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white"><?= t('order') ?> #<?= htmlspecialchars($order['invoice_number']) ?></h2>
            <?php
                $statusColors = [
                    'pending'   => 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-400',
                    'confirmed' => 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-400',
                    'shipping'  => 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-400',
                    'delivery'  => 'bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-400',
                    'delivered' => 'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-400',
                    'cancelled' => 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-400',
                ];
                $badgeClass = $statusColors[$order['status']] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300';
            ?>
            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold <?= $badgeClass ?>">
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
            <?php if (!empty($order['shipping_name'])): ?>
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 sm:col-span-3">
                <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider mb-1">Shipping</p>
                <p class="font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($order['shipping_name']) ?></p>
                <p class="text-gray-600 dark:text-gray-300 text-sm"><?= htmlspecialchars($order['shipping_address']) ?></p>
                <?php if (!empty($order['shipping_method'])): ?>
                <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">via <?= htmlspecialchars($order['shipping_method']) ?> <?= !empty($order['shipping_cost']) ? '(+$' . number_format($order['shipping_cost'], 2) . ')' : '' ?></p>
                <?php endif; ?>
                <?php if (!empty($order['payment_method'])): ?>
                <p class="text-gray-500 dark:text-gray-400 text-xs">Payment: <?= htmlspecialchars($order['payment_method']) ?></p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
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
                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-lg flex-shrink-0 overflow-hidden flex items-center justify-center">
                                <?php if ($item['product_image'] && file_exists(UPLOAD_PATH . $item['product_image'])): ?>
                                    <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($item['product_image']) ?>" class="w-full h-full object-cover" alt="">
                                <?php else: ?>
                                    <i class="fas fa-image text-gray-300 dark:text-gray-500"></i>
                                <?php endif; ?>
                            </div>
                            <span class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($item['product_name']) ?></span>
                            <?php if (!empty($item['color_name']) || !empty($item['size_name'])): ?>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">
                                <?= !empty($item['color_name']) ? htmlspecialchars($item['color_name']) : '' ?>
                                <?= !empty($item['color_name']) && !empty($item['size_name']) ? ' / ' : '' ?>
                                <?= !empty($item['size_name']) ? htmlspecialchars($item['size_name']) : '' ?>
                            </p>
                            <?php endif; ?>
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

    <?php
        $allStatuses = ['pending', 'confirmed', 'shipping', 'delivery', 'delivered'];
        $currentIdx = array_search($order['status'], $allStatuses);
        $statusOptions = [
            'pending'   => ['label' => 'Pending',     'icon' => 'fa-clock',     'color' => 'bg-yellow-500'],
            'confirmed' => ['label' => 'Confirmed',   'icon' => 'fa-check',     'color' => 'bg-blue-500'],
            'shipping'  => ['label' => 'Shipping',    'icon' => 'fa-truck',     'color' => 'bg-indigo-500'],
            'delivery'  => ['label' => 'Delivery',    'icon' => 'fa-road',      'color' => 'bg-purple-500'],
            'delivered' => ['label' => 'Delivered',   'icon' => 'fa-home',      'color' => 'bg-green-500'],
            'cancelled' => ['label' => 'Cancelled',   'icon' => 'fa-times',     'color' => 'bg-red-500'],
        ];
    ?>
    <div class="flex flex-wrap items-center gap-2">
        <?php foreach ($allStatuses as $i => $s):
            $opt = $statusOptions[$s];
            $isCurrent = $s === $order['status'];
            $isPast = $currentIdx !== false && $i < $currentIdx;
            $isFuture = $currentIdx !== false && $i > $currentIdx;
        ?>
        <?php if ($isCurrent): ?>
            <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-semibold text-white <?= $opt['color'] ?> shadow-sm">
                <i class="fas <?= $opt['icon'] ?>"></i> <?= $opt['label'] ?>
            </span>
        <?php elseif ($isFuture && $currentIdx !== false && $currentIdx < count($allStatuses)): ?>
            <form method="POST" action="<?= BASE_URL ?>admin/order/update-status">
                <input type="hidden" name="id" value="<?= $order['id'] ?>">
                <input type="hidden" name="status" value="<?= $s ?>">
                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:<?= $opt['color'] ?> hover:text-white transition">
                    <i class="fas <?= $opt['icon'] ?>"></i> <?= $opt['label'] ?>
                </button>
            </form>
        <?php else: ?>
            <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-800/50 cursor-default">
                <i class="fas <?= $opt['icon'] ?>"></i> <?= $opt['label'] ?>
            </span>
        <?php endif; ?>
        <?php endforeach; ?>
        <?php if (!in_array($order['status'], ['cancelled', 'delivered'])): ?>
        <form method="POST" action="<?= BASE_URL ?>admin/order/update-status" class="ml-2">
            <input type="hidden" name="id" value="<?= $order['id'] ?>">
            <input type="hidden" name="status" value="cancelled">
            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 transition"
                    onclick="return confirm('Cancel this order?')">
                <i class="fas fa-times"></i> Cancel
            </button>
        </form>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>admin/order/invoice?inv=<?= htmlspecialchars($order['invoice_number']) ?>" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg font-medium transition text-sm">
            <i class="fas fa-file-invoice mr-2"></i><?= t('view_invoice') ?>
        </a>
        <a href="<?= BASE_URL ?>admin/orders" class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-5 py-2.5 rounded-lg font-medium transition text-sm">
            <?= t('back_to_orders') ?>
        </a>
    </div>
</div>
