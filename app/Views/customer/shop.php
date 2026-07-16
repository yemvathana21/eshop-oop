<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-gray-400 dark:text-gray-500 mb-3">
            <a href="<?= BASE_URL ?>" class="hover:text-blue-600 dark:hover:text-blue-400 transition"><?= t('home') ?></a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-700 dark:text-gray-300 font-medium"><?= t('shop') ?></span>
            <?php if (!empty($currentCategory)): ?>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-gray-700 dark:text-gray-300 font-medium"><?= htmlspecialchars($currentCategory['name']) ?></span>
            <?php endif; ?>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><?= !empty($currentCategory) ? htmlspecialchars($currentCategory['name']) : t('all_products') ?></h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1"><?= count($products) ?> <?= t('products_found') ?></p>
            </div>
            <form method="GET" action="<?= BASE_URL ?>shop" class="flex gap-2">
                <?php if (!empty($currentCategory)): ?>
                    <input type="hidden" name="category" value="<?= htmlspecialchars($currentCategory['slug']) ?>">
                <?php endif; ?>
                <div class="relative">
                    <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="<?= t('search_products') ?>"
                        class="pl-10 pr-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm w-64 bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 transition-colors">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition">
                    <?= t('search_btn') ?>
                </button>
            </form>
        </div>
    </div>

    <div class="flex gap-8">
        <aside class="hidden lg:block w-64 flex-shrink-0">
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 sticky top-4 transition-colors">
                <h3 class="font-bold text-gray-900 dark:text-white mb-4"><?= t('categories') ?></h3>
                <ul class="space-y-1">
                    <li>
                        <a href="<?= BASE_URL ?>shop"
                           class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm transition <?= empty($_GET['category']) ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' ?>">
                            <span><?= t('all_products') ?></span>
                            <span class="text-xs <?= empty($_GET['category']) ? 'text-blue-500' : 'text-gray-400' ?>"><?= $totalProducts ?></span>
                        </a>
                    </li>
                    <?php foreach ($categories as $cat): ?>
                    <li>
                        <a href="<?= BASE_URL ?>shop?category=<?= htmlspecialchars($cat['slug']) ?>"
                           class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm transition <?= (isset($_GET['category']) && $_GET['category'] === $cat['slug']) ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' ?>">
                            <span class="flex items-center gap-2.5">
                                <i class="fas <?= htmlspecialchars($cat['icon']) ?> text-xs w-4 text-center"></i>
                                <?= htmlspecialchars($cat['name']) ?>
                            </span>
                            <span class="text-xs <?= (isset($_GET['category']) && $_GET['category'] === $cat['slug']) ? 'text-blue-500' : 'text-gray-400' ?>"><?= $cat['product_count'] ?></span>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <hr class="my-5 border-gray-100 dark:border-gray-700">

                <h3 class="font-bold text-gray-900 dark:text-white mb-3"><?= t('price_range') ?></h3>
                <div class="space-y-2 text-sm">
                    <a href="<?= BASE_URL ?>shop<?= !empty($_GET['category']) ? '?category=' . htmlspecialchars($_GET['category']) : '' ?>" class="block px-3 py-2 rounded-lg <?= empty($_GET['price']) ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' ?>"><?= t('all_prices') ?></a>
                    <a href="<?= BASE_URL ?>shop?price=under50<?= !empty($_GET['category']) ? '&category=' . htmlspecialchars($_GET['category']) : '' ?>" class="block px-3 py-2 rounded-lg <?= (isset($_GET['price']) && $_GET['price'] === 'under50') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' ?>"><?= t('under_50') ?></a>
                    <a href="<?= BASE_URL ?>shop?price=50to100<?= !empty($_GET['category']) ? '&category=' . htmlspecialchars($_GET['category']) : '' ?>" class="block px-3 py-2 rounded-lg <?= (isset($_GET['price']) && $_GET['price'] === '50to100') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' ?>"><?= t('50_to_100') ?></a>
                    <a href="<?= BASE_URL ?>shop?price=over100<?= !empty($_GET['category']) ? '&category=' . htmlspecialchars($_GET['category']) : '' ?>" class="block px-3 py-2 rounded-lg <?= (isset($_GET['price']) && $_GET['price'] === 'over100') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' ?>"><?= t('over_100') ?></a>
                </div>
            </div>
        </aside>

        <div class="flex-1">
            <?php if (empty($products)): ?>
            <div class="text-center py-20 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 transition-colors">
                <i class="fas fa-box-open text-6xl text-gray-200 dark:text-gray-600 mb-4"></i>
                <p class="text-gray-500 dark:text-gray-400 text-lg mb-2"><?= t('no_products_found') ?></p>
                <p class="text-gray-400 dark:text-gray-500 text-sm mb-4"><?= t('try_adjusting') ?></p>
                <a href="<?= BASE_URL ?>shop" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition">
                    <?= t('clear_filters') ?>
                </a>
            </div>
            <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                <?php foreach ($products as $product): ?>
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
            <?php endif; ?>
        </div>
    </div>
</div>
