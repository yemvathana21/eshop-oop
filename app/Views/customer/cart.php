<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-8 overflow-x-auto whitespace-nowrap">
        <a href="<?= BASE_URL ?>" class="hover:text-blue-600 transition"><?= t('home') ?></a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <span class="text-gray-900 dark:text-white font-medium"><?= t('shopping_cart') ?></span>
    </nav>

    <div class="flex flex-col lg:flex-row justify-between items-end gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight"><?= t('shopping_cart') ?></h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1"><?= count($cart) ?> item(s) in your cart</p>
        </div>
        <?php if (!empty($cart)): ?>
        <a href="<?= BASE_URL ?>cart/clear" onclick="return confirm('Are you sure you want to clear your cart?')" class="text-sm text-red-500 hover:text-red-600 font-medium transition flex items-center gap-2">
            <i class="fas fa-trash-can"></i> <?= t('clear_cart') ?? 'Clear Cart' ?>
        </a>
        <?php endif; ?>
    </div>

    <?php if (empty($cart)): ?>
    <div class="text-center py-24 bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors">
        <div class="w-24 h-24 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-shopping-basket text-4xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2"><?= t('cart_empty') ?></h2>
        <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-sm mx-auto">Looks like you haven't added anything to your cart yet. Explore our products and find something you love!</p>
        <a href="<?= BASE_URL ?>shop" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold transition shadow-lg shadow-blue-500/30">
            <i class="fas fa-shopping-bag mr-2"></i><?= t('continue_shopping') ?>
        </a>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items Table -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                            <tr>
                                <th class="py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-widest"><?= t('product') ?></th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-widest text-center"><?= t('quantity') ?></th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-widest text-right"><?= t('total') ?></th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-widest w-16"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            <?php foreach ($cart as $productId => $item): ?>
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition">
                                <td class="py-6 px-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-20 h-24 bg-gray-50 dark:bg-gray-700 rounded-xl overflow-hidden shrink-0 flex items-center justify-center border border-gray-100 dark:border-gray-600">
                                            <?php if (!empty($item['image'])): ?>
                                                <img src="<?= BASE_URL . 'uploads/' . rawurlencode($item['image']) ?>"
                                                     onerror="this.src='<?= BASE_URL . 'images/' . rawurlencode($item['image']) ?>'; this.onerror=null;"
                                                     class="w-full h-full object-cover" alt="<?= htmlspecialchars($item['name']) ?>">
                                            <?php else: ?>
                                                <i class="fas fa-image text-gray-300 dark:text-gray-600 text-xl"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div class="min-w-0">
                                            <a href="<?= BASE_URL ?>product?id=<?= $productId ?>" class="font-bold text-gray-900 dark:text-white hover:text-blue-600 transition block truncate mb-1">
                                                <?= htmlspecialchars($item['name']) ?>
                                            </a>
                                            <p class="text-sm text-blue-600 dark:text-blue-400 font-bold">$<?= number_format($item['price'], 2) ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 px-6">
                                    <div class="flex flex-col items-center gap-2">
                                        <form method="POST" action="<?= BASE_URL ?>cart/update" class="flex items-center bg-gray-100 dark:bg-gray-700 rounded-xl p-1">
                                            <input type="hidden" name="product_id" value="<?= $productId ?>">
                                            <button type="submit" name="quantity" value="<?= $item['quantity'] - 1 ?>"
                                                class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-white dark:hover:bg-gray-600 rounded-lg transition shadow-sm">-</button>
                                            <span class="w-10 text-center font-bold text-sm text-gray-900 dark:text-white"><?= $item['quantity'] ?></span>
                                            <button type="submit" name="quantity" value="<?= $item['quantity'] + 1 ?>"
                                                class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-white dark:hover:bg-gray-600 rounded-lg transition shadow-sm">+</button>
                                        </form>
                                    </div>
                                </td>
                                <td class="py-6 px-6 text-right font-black text-gray-900 dark:text-white text-base">
                                    $<?= number_format($item['price'] * $item['quantity'], 2) ?>
                                </td>
                                <td class="py-6 px-6 text-right">
                                    <a href="<?= BASE_URL ?>cart/remove?id=<?= $productId ?>"
                                       class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition"
                                       title="<?= t('remove') ?>">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-[#232f3e] text-white rounded-3xl p-8 sticky top-24 shadow-2xl overflow-hidden">
                <!-- Background Decoration -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-600/10 rounded-full -mr-16 -mt-16"></div>

                <h2 class="text-xl font-bold mb-6 flex items-center gap-3">
                    <i class="fas fa-receipt text-blue-400"></i>
                    <?= t('order_summary') ?>
                </h2>

                <div class="space-y-4 mb-8">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400"><?= t('items') ?> (<?= array_sum(array_column($cart, 'quantity')) ?>)</span>
                        <span class="font-bold">$<?= number_format(array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart)), 2) ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400"><?= t('shipping') ?></span>
                        <span class="text-emerald-400 font-bold uppercase text-[10px] tracking-widest bg-emerald-400/10 px-2 py-0.5 rounded"><?= t('free') ?></span>
                    </div>
                    <div class="pt-4 border-t border-gray-700">
                        <div class="flex justify-between items-end">
                            <span class="text-gray-400 text-sm font-medium"><?= t('total') ?></span>
                            <span class="text-3xl font-black text-white leading-none">
                                <span class="text-sm text-blue-400 mr-1">$</span><?= number_format(array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart)), 2) ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <?php if (App\Core\Session::isLoggedIn()): ?>
                    <a href="<?= BASE_URL ?>checkout" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-2xl font-bold transition transform hover:scale-[1.02] shadow-xl shadow-blue-500/20">
                        <?= t('proceed_to_checkout') ?>
                    </a>
                    <?php else: ?>
                    <a href="<?= BASE_URL ?>login" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-2xl font-bold transition transform hover:scale-[1.02] shadow-xl shadow-blue-500/20">
                        <?= t('login_to_checkout') ?>
                    </a>
                    <?php endif; ?>

                    <a href="<?= BASE_URL ?>shop" class="block w-full text-center bg-white/5 hover:bg-white/10 text-gray-300 py-3 rounded-2xl font-bold transition text-sm">
                        <?= t('continue_shopping') ?>
                    </a>
                </div>

                <!-- Trust Badges -->
                <div class="mt-8 pt-8 border-t border-gray-700 grid grid-cols-3 gap-4 opacity-50">
                    <div class="text-center"><i class="fas fa-shield-alt text-xl mb-1"></i><p class="text-[8px] uppercase font-bold tracking-tighter">Secure</p></div>
                    <div class="text-center"><i class="fas fa-undo text-xl mb-1"></i><p class="text-[8px] uppercase font-bold tracking-tighter">Easy Return</p></div>
                    <div class="text-center"><i class="fas fa-truck text-xl mb-1"></i><p class="text-[8px] uppercase font-bold tracking-tighter">Fast Del</p></div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
