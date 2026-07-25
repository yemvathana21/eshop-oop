<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400"><?= t('total_sales') ?></p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">$<?= number_format($totalSales, 2) ?></p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400"><?= t('total_orders') ?></p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= $ordersCount ?></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-file-invoice text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400"><?= t('total_products') ?></p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= $totalProducts ?></p>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-box text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400"><?= t('total_users') ?></p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= $totalUsers ?></p>
                </div>
                <div class="w-12 h-12 bg-cyan-100 dark:bg-cyan-900/50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-cyan-600 dark:text-cyan-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400"><?= t('low_stock_items') ?></p>
                    <p class="text-2xl font-bold <?= count($lowStockProducts) > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' ?> mt-1"><?= count($lowStockProducts) ?></p>
                </div>
                <div class="w-12 h-12 <?= count($lowStockProducts) > 0 ? 'bg-red-100 dark:bg-red-900/50' : 'bg-gray-100 dark:bg-gray-700' ?> rounded-xl flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle <?= count($lowStockProducts) > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-400 dark:text-gray-500' ?> text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400"><?= t('reviews') ?></p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= $totalReviews ?></p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-star text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400"><?= t('revenue_avg') ?></p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">$<?= $ordersCount > 0 ? number_format($totalSales / $ordersCount, 2) : '0.00' ?></p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-indigo-600 dark:text-indigo-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sales Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors">
            <div class="p-5 border-b border-gray-100 dark:border-gray-700">
                <h2 class="font-bold text-gray-900 dark:text-white"><?= t('sales_last_7_days') ?></h2>
            </div>
            <div class="p-5">
                <div class="flex items-end gap-2 h-48">
                    <?php foreach ($salesChart as $day): ?>
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">$<?= $day['amount'] >= 1000 ? number_format($day['amount'] / 1000, 1) . 'k' : number_format($day['amount'], 0) ?></span>
                        <div class="w-full rounded-t-lg transition-all duration-500 <?= $day['amount'] > 0 ? 'bg-blue-500 dark:bg-blue-400 hover:bg-blue-600 dark:hover:bg-blue-300' : 'bg-gray-200 dark:bg-gray-700' ?>" 
                             style="height: <?= $day['amount'] > 0 ? max(8, ($day['amount'] / $maxSales) * 100) : 4 ?>%"></div>
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-medium"><?= $day['label'] ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Order Status Breakdown -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors">
            <div class="p-5 border-b border-gray-100 dark:border-gray-700">
                <h2 class="font-bold text-gray-900 dark:text-white"><?= t('order_status') ?></h2>
            </div>
            <div class="p-5 space-y-4">
                <?php
                $statusConfig = [
                    'completed' => ['color' => 'green', 'icon' => 'fa-check-circle'],
                    'pending' => ['color' => 'yellow', 'icon' => 'fa-clock'],
                    'cancelled' => ['color' => 'red', 'icon' => 'fa-times-circle']
                ];
                $totalOrdersAll = max(array_sum($ordersByStatus), 1);
                foreach ($statusConfig as $sKey => $sConf):
                    $count = $ordersByStatus[$sKey] ?? 0;
                    $pct = ($count / $totalOrdersAll) * 100;
                ?>
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-2">
                            <i class="fas <?= $sConf['icon'] ?> text-<?= $sConf['color'] ?>-500 text-sm"></i>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300"><?= t($sKey) ?></span>
                        </div>
                        <span class="text-sm font-bold text-gray-900 dark:text-white"><?= $count ?></span>
                    </div>
                    <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full bg-<?= $sConf['color'] ?>-500 rounded-full transition-all duration-500" style="width: <?= $pct ?>%"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Orders -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors">
            <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h2 class="font-bold text-gray-900 dark:text-white"><?= t('recent_orders') ?></h2>
                <a href="<?= BASE_URL ?>admin/orders" class="text-blue-600 dark:text-blue-400 text-sm hover:underline"><?= t('view_all') ?></a>
            </div>
            <div class="p-5">
                <?php if (empty($recentOrders)): ?>
                    <p class="text-gray-400 dark:text-gray-500 text-sm text-center py-4"><?= t('no_orders_yet') ?></p>
                <?php else: ?>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php foreach ($recentOrders as $order): ?>
                    <div class="flex items-center justify-between py-3">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">#<?= htmlspecialchars($order['invoice_number']) ?></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($order['user_name']) ?> &middot; <?= date('M d, g:i A', strtotime($order['created_at'])) ?></p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gray-900 dark:text-white text-sm">$<?= number_format($order['total_price'], 2) ?></p>
                            <span class="inline-block text-xs px-2 py-0.5 rounded-full font-medium <?= $order['status'] === 'completed' ? 'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-400' : ($order['status'] === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-400' : 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-400') ?>"><?= t($order['status']) ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors">
            <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h2 class="font-bold text-gray-900 dark:text-white"><?= t('low_stock_alert') ?></h2>
                <a href="<?= BASE_URL ?>admin/inventory" class="text-blue-600 dark:text-blue-400 text-sm hover:underline"><?= t('manage') ?></a>
            </div>
            <div class="p-5">
                <?php if (empty($lowStockProducts)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle text-3xl text-green-300 dark:text-green-500 mb-2"></i>
                        <p class="text-gray-400 dark:text-gray-500 text-sm"><?= t('all_products_well_stocked') ?></p>
                    </div>
                <?php else: ?>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php foreach ($lowStockProducts as $product): ?>
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center overflow-hidden">
                                <?php if ($product['image'] && file_exists(UPLOAD_PATH . $product['image'])): ?>
                                    <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($product['image']) ?>" class="w-full h-full object-cover" alt="">
                                <?php else: ?>
                                    <i class="fas fa-image text-gray-300 dark:text-gray-500 text-xs"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($product['name']) ?></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">$<?= number_format($product['price'], 2) ?></p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1 text-sm font-bold <?= $product['stock'] === 0 ? 'text-red-600 dark:text-red-400' : 'text-yellow-600 dark:text-yellow-400' ?>">
                            <?php if ($product['stock'] === 0): ?>
                                <i class="fas fa-times-circle"></i> <?= t('out') ?>
                            <?php else: ?>
                                <i class="fas fa-exclamation-circle"></i> <?= $product['stock'] ?> <?= t('left') ?>
                            <?php endif; ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
