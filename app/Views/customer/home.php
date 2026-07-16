<!-- Hero Banner -->
<section class="relative bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-800 text-white overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 -left-20 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-80 h-80 bg-white rounded-full blur-3xl"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28 relative z-10">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <span class="inline-block bg-white/20 text-white text-sm font-medium px-4 py-1.5 rounded-full mb-4"><?= t('hero_badge') ?></span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-6"><?= t('hero_title') ?></h1>
                <p class="text-blue-100 text-lg mb-8 max-w-lg"><?= t('hero_subtitle') ?></p>
                <div class="flex flex-wrap gap-4">
                    <a href="<?= BASE_URL ?>shop" class="bg-white text-blue-700 hover:bg-blue-50 px-8 py-3.5 rounded-lg font-bold transition shadow-lg">
                        <i class="fas fa-shopping-bag mr-2"></i><?= t('shop_now') ?>
                    </a>
                    <a href="#categories" class="border-2 border-white/40 hover:border-white text-white px-8 py-3.5 rounded-lg font-bold transition">
                        <?= t('browse_categories') ?>
                    </a>
                </div>
            </div>
            <div class="hidden md:flex justify-center">
                <div class="relative">
                    <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20">
                        <div class="grid grid-cols-2 gap-4">
                            <?php foreach (array_slice($featured, 0, 4) as $fp): ?>
                            <div class="bg-white rounded-xl overflow-hidden shadow-lg w-36 h-36">
                                <?php if ($fp['image'] && file_exists(UPLOAD_PATH . $fp['image'])): ?>
                                    <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($fp['image']) ?>" alt="<?= htmlspecialchars($fp['name']) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center bg-gray-100"><i class="fas fa-image text-gray-300 text-2xl"></i></div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="absolute -bottom-4 -right-4 bg-yellow-400 text-yellow-900 rounded-xl px-5 py-3 shadow-xl font-bold text-sm">
                        <i class="fas fa-truck mr-1"></i> <?= t('free_shipping_badge') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trust Badges -->
<section class="bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center text-sm">
            <div class="flex items-center justify-center gap-3 text-gray-600 dark:text-gray-400">
                <i class="fas fa-truck text-blue-600 dark:text-blue-400 text-xl"></i>
                <div class="text-left"><span class="font-semibold text-gray-900 dark:text-white block"><?= t('free_shipping_title') ?></span><span class="text-xs text-gray-400 dark:text-gray-500"><?= t('free_shipping_desc') ?></span></div>
            </div>
            <div class="flex items-center justify-center gap-3 text-gray-600 dark:text-gray-400">
                <i class="fas fa-shield-halved text-blue-600 dark:text-blue-400 text-xl"></i>
                <div class="text-left"><span class="font-semibold text-gray-900 dark:text-white block"><?= t('secure_payment') ?></span><span class="text-xs text-gray-400 dark:text-gray-500"><?= t('secure_payment_desc') ?></span></div>
            </div>
            <div class="flex items-center justify-center gap-3 text-gray-600 dark:text-gray-400">
                <i class="fas fa-rotate-left text-blue-600 dark:text-blue-400 text-xl"></i>
                <div class="text-left"><span class="font-semibold text-gray-900 dark:text-white block"><?= t('easy_returns') ?></span><span class="text-xs text-gray-400 dark:text-gray-500"><?= t('easy_returns_desc') ?></span></div>
            </div>
            <div class="flex items-center justify-center gap-3 text-gray-600 dark:text-gray-400">
                <i class="fas fa-headset text-blue-600 dark:text-blue-400 text-xl"></i>
                <div class="text-left"><span class="font-semibold text-gray-900 dark:text-white block"><?= t('support_title') ?></span><span class="text-xs text-gray-400 dark:text-gray-500"><?= t('support_desc') ?></span></div>
            </div>
        </div>
    </div>
</section>

<!-- Categories -->
<section id="categories" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white"><?= t('shop_by_category') ?></h2>
        <p class="text-gray-500 dark:text-gray-400 mt-2"><?= t('find_what_looking') ?></p>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-5">
        <?php foreach ($categories as $cat): ?>
        <a href="<?= BASE_URL ?>shop?category=<?= htmlspecialchars($cat['slug']) ?>" class="group bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-6 text-center hover:shadow-lg hover:border-blue-200 dark:hover:border-blue-700 transition-all duration-300">
            <div class="w-16 h-16 mx-auto bg-blue-50 dark:bg-blue-900/30 group-hover:bg-blue-100 dark:group-hover:bg-blue-800/40 rounded-2xl flex items-center justify-center mb-4 transition">
                <i class="fas <?= htmlspecialchars($cat['icon']) ?> text-2xl text-blue-600 dark:text-blue-400"></i>
            </div>
            <h3 class="font-semibold text-gray-900 dark:text-white text-sm mb-1"><?= htmlspecialchars($cat['name']) ?></h3>
            <span class="text-xs text-gray-400 dark:text-gray-500"><?= $cat['product_count'] ?> <?= t('products_count') ?></span>
        </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- Featured Products -->
<section class="bg-white dark:bg-gray-900 border-y border-gray-100 dark:border-gray-800 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white"><?= t('new_arrivals') ?></h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1"><?= t('check_latest_products') ?></p>
            </div>
            <a href="<?= BASE_URL ?>shop" class="hidden sm:inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-semibold text-sm transition">
                <?= t('view_all_products') ?> <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($featured as $product): ?>
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-300 group">
                <div class="relative bg-gray-50 dark:bg-gray-700 h-56 flex items-center justify-center overflow-hidden">
                    <?php if ($product['image'] && file_exists(UPLOAD_PATH . $product['image'])): ?>
                        <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <?php else: ?>
                        <div class="text-gray-300 dark:text-gray-600"><i class="fas fa-image text-5xl"></i></div>
                    <?php endif; ?>
                    <?php if ($product['stock'] <= 0): ?>
                        <div class="absolute top-3 left-3 bg-red-500 text-white text-xs px-2.5 py-1 rounded-full font-medium"><?= t('sold_out') ?></div>
                    <?php elseif ($product['stock'] <= 10): ?>
                        <div class="absolute top-3 left-3 bg-orange-500 text-white text-xs px-2.5 py-1 rounded-full font-medium"><?= $product['stock'] ?> <?= t('low_stock_left') ?></div>
                    <?php elseif (!empty($product['compare_price']) && $product['compare_price'] > $product['price']): ?>
                        <div class="absolute top-3 left-3 bg-red-500 text-white text-xs px-2.5 py-1 rounded-full font-medium">-<?= round((1 - $product['price'] / $product['compare_price']) * 100) ?>%</div>
                    <?php endif; ?>
                    <?php if ($product['category_name']): ?>
                        <div class="absolute top-3 right-3 bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm text-gray-700 dark:text-gray-300 text-xs px-2.5 py-1 rounded-full font-medium shadow-sm"><?= htmlspecialchars($product['category_name']) ?></div>
                    <?php endif; ?>
                </div>
                <div class="p-5">
                    <h3 class="font-semibold text-gray-900 dark:text-white leading-tight mb-2 truncate" title="<?= htmlspecialchars($product['name']) ?>"><?= htmlspecialchars($product['name']) ?></h3>
                    <p class="text-gray-400 dark:text-gray-500 text-sm mb-4 line-clamp-2"><?= htmlspecialchars($product['description'] ?? '') ?></p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-xl font-bold text-blue-600 dark:text-blue-400">$<?= number_format($product['price'], 2) ?></span>
                            <?php if ($product['stock'] > 10 && !empty($product['compare_price']) && $product['compare_price'] > $product['price']): ?>
                                <span class="text-sm text-gray-400 dark:text-gray-500 line-through">$<?= number_format($product['compare_price'], 2) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="flex gap-2">
                            <a href="<?= BASE_URL ?>product?id=<?= $product['id'] ?>" class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 w-10 h-10 rounded-xl border border-gray-200 dark:border-gray-600 flex items-center justify-center transition">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <?php if ($product['stock'] > 0): ?>
                            <a href="<?= BASE_URL ?>cart/add?id=<?= $product['id'] ?>" class="bg-blue-600 hover:bg-blue-700 text-white w-10 h-10 rounded-xl flex items-center justify-center transition shadow-sm">
                                <i class="fas fa-cart-plus text-sm"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="sm:hidden mt-8 text-center">
            <a href="<?= BASE_URL ?>shop" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-semibold transition">
                <?= t('view_all_products') ?> <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Promo Banner -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid md:grid-cols-2 gap-6">
        <div class="relative bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl p-8 md:p-10 text-white overflow-hidden">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-10 -mt-10"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-8 -mb-8"></div>
            <div class="relative z-10">
                <span class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide"><?= t('limited_time') ?></span>
                <h3 class="text-2xl md:text-3xl font-bold mt-4 mb-2"><?= t('up_to_40_off') ?></h3>
                <p class="text-emerald-100 mb-6 max-w-xs"><?= t('promo_electronics_desc') ?></p>
                <a href="<?= BASE_URL ?>shop" class="inline-flex items-center bg-white text-emerald-700 hover:bg-emerald-50 px-6 py-3 rounded-lg font-bold transition shadow-lg">
                    <?= t('shop_deals') ?> <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
        <div class="relative bg-gradient-to-br from-violet-500 to-purple-600 rounded-3xl p-8 md:p-10 text-white overflow-hidden">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-10 -mt-10"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-8 -mb-8"></div>
            <div class="relative z-10">
                <span class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide"><?= t('new_collection') ?></span>
                <h3 class="text-2xl md:text-3xl font-bold mt-4 mb-2"><?= t('premium_accessories') ?></h3>
                <p class="text-purple-100 mb-6 max-w-xs"><?= t('promo_accessories_desc') ?></p>
                <a href="<?= BASE_URL ?>shop?category=accessories" class="inline-flex items-center bg-white text-purple-700 hover:bg-purple-50 px-6 py-3 rounded-lg font-bold transition shadow-lg">
                    <?= t('explore_now') ?> <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- All Products -->
<section class="bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white"><?= t('all_products_title') ?></h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1"><?= t('browse_complete_collection') ?></p>
        </div>
        <?php if (!empty($allProducts)): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($allProducts as $product): ?>
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-300 group">
                <div class="relative bg-gray-50 dark:bg-gray-700 h-56 flex items-center justify-center overflow-hidden">
                    <?php if ($product['image'] && file_exists(UPLOAD_PATH . $product['image'])): ?>
                        <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <?php else: ?>
                        <div class="text-gray-300 dark:text-gray-600"><i class="fas fa-image text-5xl"></i></div>
                    <?php endif; ?>
                    <?php if ($product['stock'] <= 0): ?>
                        <div class="absolute top-3 left-3 bg-red-500 text-white text-xs px-2.5 py-1 rounded-full font-medium"><?= t('sold_out') ?></div>
                    <?php elseif ($product['stock'] <= 10): ?>
                        <div class="absolute top-3 left-3 bg-orange-500 text-white text-xs px-2.5 py-1 rounded-full font-medium"><?= $product['stock'] ?> <?= t('low_stock_left') ?></div>
                    <?php elseif (!empty($product['compare_price']) && $product['compare_price'] > $product['price']): ?>
                        <div class="absolute top-3 left-3 bg-red-500 text-white text-xs px-2.5 py-1 rounded-full font-medium">-<?= round((1 - $product['price'] / $product['compare_price']) * 100) ?>%</div>
                    <?php endif; ?>
                </div>
                <div class="p-5">
                    <h3 class="font-semibold text-gray-900 dark:text-white leading-tight mb-2 truncate" title="<?= htmlspecialchars($product['name']) ?>"><?= htmlspecialchars($product['name']) ?></h3>
                    <p class="text-gray-400 dark:text-gray-500 text-sm mb-4 line-clamp-2"><?= htmlspecialchars($product['description'] ?? '') ?></p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-xl font-bold text-blue-600 dark:text-blue-400">$<?= number_format($product['price'], 2) ?></span>
                            <?php if ($product['stock'] > 10 && !empty($product['compare_price']) && $product['compare_price'] > $product['price']): ?>
                                <span class="text-sm text-gray-400 dark:text-gray-500 line-through">$<?= number_format($product['compare_price'], 2) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="flex gap-2">
                            <a href="<?= BASE_URL ?>product?id=<?= $product['id'] ?>" class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 w-10 h-10 rounded-xl border border-gray-200 dark:border-gray-600 flex items-center justify-center transition">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <?php if ($product['stock'] > 0): ?>
                            <a href="<?= BASE_URL ?>cart/add?id=<?= $product['id'] ?>" class="bg-blue-600 hover:bg-blue-700 text-white w-10 h-10 rounded-xl flex items-center justify-center transition shadow-sm">
                                <i class="fas fa-cart-plus text-sm"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-16">
            <i class="fas fa-box-open text-6xl text-gray-200 dark:text-gray-700 mb-4"></i>
            <p class="text-gray-400 dark:text-gray-500 text-lg"><?= t('no_products_found') ?></p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Newsletter CTA -->
<section class="bg-gradient-to-r from-gray-900 to-gray-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <h2 class="text-3xl font-bold mb-3"><?= t('stay_in_loop') ?></h2>
        <p class="text-gray-400 mb-8 max-w-md mx-auto"><?= t('newsletter_desc') ?></p>
        <form class="flex max-w-md mx-auto" onsubmit="return false;">
            <input type="email" placeholder="<?= t('email_placeholder') ?>" class="flex-1 px-5 py-3 rounded-l-lg bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-r-lg font-semibold transition">
                <?= t('subscribe') ?>
            </button>
        </form>
    </div>
</section>
