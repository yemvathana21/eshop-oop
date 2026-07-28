<div class="max-w-2xl mx-auto pb-10">
    <div class="mb-6">
        <a href="<?= BASE_URL ?>account/dashboard" class="text-sm text-blue-600 dark:text-blue-400 hover:underline"><i class="fas fa-chevron-left mr-1"></i> Settings</a>
    </div>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Wishlist</h1>

    <?php if (empty($wishlist)): ?>
    <div class="text-center py-16">
        <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mx-auto mb-4"><i class="fas fa-heart text-2xl text-gray-400"></i></div>
        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">No Items</h3>
        <p class="text-sm text-gray-500 mb-5">Your wishlist is empty.</p>
        <a href="<?= BASE_URL ?>shop" class="inline-flex px-5 py-2.5 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition">Browse Products</a>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <?php foreach ($wishlist as $item): ?>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center gap-3">
                <div class="w-16 h-16 rounded-lg overflow-hidden shrink-0 bg-gray-100 dark:bg-gray-700">
                    <?php if (!empty($item['image'])): ?>
                    <img src="<?= BASE_URL ?>uploads/products/<?= htmlspecialchars($item['image']) ?>" alt="" class="w-full h-full object-cover">
                    <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-gray-400"><i class="fas fa-box"></i></div>
                    <?php endif; ?>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate"><?= htmlspecialchars($item['product_name'] ?? $item['name'] ?? 'Product') ?></p>
                    <?php if (!empty($item['price'])): ?>
                    <p class="text-sm font-bold text-gray-900 dark:text-white mt-0.5">$<?= number_format((float)$item['price'], 2) ?></p>
                    <?php endif; ?>
                </div>
                <button onclick="removeWishlist(<?= $item['id'] ?>)" class="p-2 text-red-400 hover:text-red-600 transition" title="Remove"><i class="fas fa-trash-alt"></i></button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<script>
function removeWishlist(id) {
    if (!confirm('Remove from wishlist?')) return;
    window.location.href = '<?= BASE_URL ?>account/wishlist/remove?id=' + id;
}
</script>
