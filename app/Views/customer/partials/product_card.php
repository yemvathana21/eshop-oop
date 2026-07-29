<div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-2xl transition-all duration-500 group flex flex-col h-full relative">
    <!-- Image Area -->
    <div class="relative bg-gray-50 dark:bg-gray-700 h-64 overflow-hidden shrink-0">
        <a href="<?= BASE_URL ?>product?id=<?= $product['id'] ?>" class="block w-full h-full">
            <?php if (!empty($product['image'])): ?>
                <img src="<?= BASE_URL . 'uploads/' . rawurlencode($product['image']) ?>"
                     onerror="this.src='<?= BASE_URL . 'images/' . rawurlencode($product['image']) ?>'; this.onerror=null;"
                     alt="<?= htmlspecialchars($product['name']) ?>"
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
            <?php else: ?>
                <div class="w-full h-full flex items-center justify-center text-gray-300 dark:text-gray-600">
                    <i class="fas fa-image text-5xl"></i>
                </div>
            <?php endif; ?>
        </a>

        <!-- Floating Actions -->
        <!-- Wishlist Button (Top Right) -->
        <?php $isWishlisted = !empty($wishlistedIds) && in_array($product['id'], $wishlistedIds); ?>
        <div class="absolute top-3 right-3 z-10 translate-x-12 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-300">
            <button onclick="event.preventDefault(); toggleWishlist(<?= $product['id'] ?>, this)"
                    class="w-9 h-9 bg-white dark:bg-gray-700 rounded-full flex items-center justify-center shadow-lg transition-colors <?= $isWishlisted ? 'bg-red-500 text-white' : 'text-gray-900 dark:text-white hover:bg-red-500 hover:text-white dark:hover:bg-red-500' ?>">
                <i class="<?= $isWishlisted ? 'fas' : 'far' ?> fa-heart"></i>
            </button>
        </div>

        <!-- Quick View Link (Bottom Slide Up) -->
        <div class="absolute inset-x-0 bottom-0 z-10 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
            <a href="<?= BASE_URL ?>product?id=<?= $product['id'] ?>"
               class="block w-full bg-blue-600/90 backdrop-blur-sm text-white text-[10px] font-bold uppercase tracking-widest text-center py-2.5 hover:bg-blue-700 transition-colors">
                View Details
            </a>
        </div>

        <!-- Badges -->
        <div class="absolute top-3 left-3 flex flex-col gap-2 z-20">
            <?php if ($product['stock'] <= 0): ?>
                <div class="bg-red-500 text-white text-[10px] uppercase font-bold px-2 py-1 rounded-md shadow-lg"><?= t('sold_out') ?></div>
            <?php elseif (!empty($product['compare_price']) && $product['compare_price'] > $product['price']): ?>
                <div class="bg-blue-600 text-white text-[10px] uppercase font-bold px-2 py-1 rounded-md shadow-lg">
                    -<?= round((1 - $product['price'] / $product['compare_price']) * 100) ?>% Off
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Content Area -->
    <div class="p-5 flex flex-col flex-1">
        <div class="mb-2">
            <a href="<?= BASE_URL ?>shop?category=<?= $product['category_slug'] ?>" class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest hover:underline">
                <?= htmlspecialchars($product['category_name']) ?>
            </a>
        </div>
        <a href="<?= BASE_URL ?>product?id=<?= $product['id'] ?>" class="block mb-2 flex-1">
            <h3 class="font-bold text-gray-900 dark:text-white leading-snug line-clamp-2 group-hover:text-blue-600 transition" title="<?= htmlspecialchars($product['name']) ?>">
                <?= htmlspecialchars($product['name']) ?>
            </h3>
        </a>

        <!-- Pricing -->
        <div class="flex items-center gap-2 mb-4">
            <span class="text-lg font-extrabold text-blue-600 dark:text-blue-400">$<?= number_format($product['price'], 2) ?></span>
            <?php if (!empty($product['compare_price']) && $product['compare_price'] > $product['price']): ?>
                <span class="text-sm text-gray-400 dark:text-gray-500 line-through">$<?= number_format($product['compare_price'], 2) ?></span>
            <?php endif; ?>
        </div>

        <!-- Add to Cart Button -->
        <div class="mt-auto">
            <?php if ($product['stock'] > 0): ?>
            <a href="<?= BASE_URL ?>product?id=<?= $product['id'] ?>"
               class="w-full bg-gray-900 dark:bg-gray-700 hover:bg-blue-600 dark:hover:bg-blue-600 text-white text-xs font-bold py-3 rounded-xl flex items-center justify-center gap-2 transition shadow-md">
                <i class="fas fa-shopping-cart"></i>
                Add to Cart
            </a>
            <?php else: ?>
            <button disabled class="w-full bg-gray-100 dark:bg-gray-800 text-gray-400 text-xs font-bold py-3 rounded-xl cursor-not-allowed">
                Out of Stock
            </button>
            <?php endif; ?>
        </div>
    </div>
</div>
