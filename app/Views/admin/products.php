<div class="space-y-6" id="product-list">
    <div class="flex items-center justify-between">
        <div class="flex flex-col">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= t('manage_products') ?></h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                <?php
                $start = ($currentPage - 1) * 10 + 1;
                $end = min($start + count($products) - 1, $totalProducts);
                ?>
                <?= t('showing') ?> <?= $start ?> <?= t('to') ?> <?= $end ?> <?= t('of') ?> <?= $totalProducts ?> <?= t('products') ?>
            </p>
        </div>
        <a href="<?= BASE_URL ?>admin/product/create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm">
            <i class="fas fa-plus mr-1"></i><?= t('add_product') ?>
        </a>
    </div>

    <?php if (empty($products)): ?>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 text-center py-16 transition-colors">
        <i class="fas fa-box-open text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
        <p class="text-gray-500 dark:text-gray-400 text-lg mb-4"><?= t('no_products_yet') ?></p>
        <a href="<?= BASE_URL ?>admin/product/create" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition text-sm"><?= t('add_first_product') ?></a>
    </div>
    <?php else: ?>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-100 dark:border-gray-600">
                    <tr>
                        <th class="py-4 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('product') ?></th>
                        <th class="py-4 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('price') ?></th>
                        <th class="py-4 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('compare_price') ?></th>
                        <th class="py-4 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('stock') ?></th>
                        <th class="py-4 px-4 font-semibold text-gray-600 dark:text-gray-300 text-right"><?= t('actions') ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php foreach ($products as $product): ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex-shrink-0 overflow-hidden flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                    <?php if (!empty($product['image'])): ?>
                                        <img src="<?= BASE_URL . 'uploads/' . rawurlencode($product['image']) ?>"
                                             onerror="this.src='<?= BASE_URL . 'images/' . rawurlencode($product['image']) ?>'; this.onerror=null;"
                                             class="w-full h-full object-cover"
                                             alt="<?= htmlspecialchars($product['name']) ?>">
                                    <?php else: ?>
                                        <i class="fas fa-image text-gray-300 dark:text-gray-500 text-lg"></i>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($product['name']) ?></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1 max-w-[250px]"><?= htmlspecialchars($product['description'] ?? '') ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4 font-bold text-gray-900 dark:text-white">$<?= number_format($product['price'], 2) ?></td>
                        <td class="py-4 px-4">
                            <?php if (!empty($product['compare_price'])): ?>
                                <span class="text-gray-400 dark:text-gray-500 line-through text-xs">$<?= number_format($product['compare_price'], 2) ?></span>
                                <span class="ml-1 text-xs text-green-600 dark:text-green-400 font-bold">-<?= round((1 - $product['price'] / $product['compare_price']) * 100) ?>%</span>
                            <?php else: ?>
                                <span class="text-gray-400 dark:text-gray-500 text-xs">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-4 px-4">
                            <?php
                                $stockStatusClass = 'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-400';
                                if ($product['stock'] <= 0) $stockStatusClass = 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-400';
                                elseif ($product['stock'] <= 5) $stockStatusClass = 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-400';
                            ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold <?= $stockStatusClass ?>">
                                <?= $product['stock'] ?> <?= t('units') ?>
                            </span>
                        </td>
                        <td class="py-4 px-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button type="button"
                                    onclick="quickView(<?= htmlspecialchars(json_encode($product)) ?>)"
                                    class="p-2 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition"
                                    title="<?= t('view') ?>">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="<?= BASE_URL ?>admin/product/edit?id=<?= $product['id'] ?>" class="p-2 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition" title="<?= t('edit') ?>">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= BASE_URL ?>admin/product/delete?id=<?= $product['id'] ?>" class="p-2 text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition" title="<?= t('delete') ?>" onclick="return confirm('<?= t('confirm_delete_product') ?>')">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-4 border-t border-gray-100 dark:border-gray-600 flex items-center justify-between">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                <?= t('page') ?> <?= $currentPage ?> <?= t('of') ?> <?= $totalPages ?>
            </div>
            <div class="flex items-center gap-1">
                <?php if ($currentPage > 1): ?>
                <a href="<?= BASE_URL ?>admin/products?page=<?= $currentPage - 1 ?>#product-list" class="pagination-link px-3 py-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <i class="fas fa-chevron-left mr-1 text-xs"></i> <?= t('previous') ?>
                </a>
                <?php endif; ?>

                <?php
                $startPage = max(1, $currentPage - 2);
                $endPage = min($totalPages, $currentPage + 2);

                if ($startPage > 1): ?>
                    <a href="<?= BASE_URL ?>admin/products?page=1#product-list" class="pagination-link px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-md text-sm transition <?= $currentPage == 1 ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' ?>">1</a>
                    <?php if ($startPage > 2): ?><span class="px-2 text-gray-400">...</span><?php endif; ?>
                <?php endif;

                for ($i = $startPage; $i <= $endPage; $i++): ?>
                    <a href="<?= BASE_URL ?>admin/products?page=<?= $i ?>#product-list" class="pagination-link px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-md text-sm transition <?= $currentPage == $i ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor;

                if ($endPage < $totalPages): ?>
                    <?php if ($endPage < $totalPages - 1): ?><span class="px-2 text-gray-400">...</span><?php endif; ?>
                    <a href="<?= BASE_URL ?>admin/products?page=<?= $totalPages ?>#product-list" class="pagination-link px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-md text-sm transition <?= $currentPage == $totalPages ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' ?>"><?= $totalPages ?></a>
                <?php endif; ?>

                <?php if ($currentPage < $totalPages): ?>
                <a href="<?= BASE_URL ?>admin/products?page=<?= $currentPage + 1 ?>#product-list" class="pagination-link px-3 py-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <?= t('next') ?> <i class="fas fa-chevron-right ml-1 text-xs"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Quick View Modal -->
<div id="quickViewModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closeQuickView()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden transform transition-all">
            <div class="absolute top-4 right-4 z-10">
                <button onclick="closeQuickView()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="flex flex-col md:flex-row">
                <div class="md:w-1/2 h-64 md:h-auto bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                    <img id="qv-image" src="" alt="" class="w-full h-full object-cover">
                    <div id="qv-no-image" class="hidden flex flex-col items-center text-gray-400">
                        <i class="fas fa-image text-5xl mb-2"></i>
                        <span>No image available</span>
                    </div>
                </div>
                <div class="md:w-1/2 p-6 flex flex-col justify-between">
                    <div>
                        <span id="qv-category" class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider"></span>
                        <h2 id="qv-name" class="text-2xl font-bold text-gray-900 dark:text-white mt-1"></h2>
                        <div class="flex items-center gap-2 mt-2">
                            <span id="qv-price" class="text-2xl font-bold text-gray-900 dark:text-white"></span>
                            <span id="qv-compare-price" class="text-sm text-gray-400 line-through"></span>
                        </div>
                        <p id="qv-description" class="text-gray-500 dark:text-gray-400 mt-4 text-sm leading-relaxed"></p>
                    </div>
                    <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Stock Status:</span>
                            <span id="qv-stock" class="px-3 py-1 rounded-full text-xs font-bold"></span>
                        </div>
                        <div class="mt-4 flex gap-2">
                            <a id="qv-edit-btn" href="#" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2.5 rounded-lg font-medium transition text-sm">
                                <i class="fas fa-edit mr-2"></i>Edit Product
                            </a>
                            <button onclick="closeQuickView()" class="flex-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 py-2.5 rounded-lg font-medium transition text-sm">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function quickView(product) {
        const modal = document.getElementById('quickViewModal');
        const img = document.getElementById('qv-image');
        const noImg = document.getElementById('qv-no-image');

        // Populate Data
        document.getElementById('qv-name').textContent = product.name;
        document.getElementById('qv-category').textContent = product.category_name || 'Uncategorized';
        document.getElementById('qv-description').textContent = product.description || 'No description provided.';
        document.getElementById('qv-price').textContent = '$' + parseFloat(product.price).toFixed(2);

        if (product.compare_price) {
            document.getElementById('qv-compare-price').textContent = '$' + parseFloat(product.compare_price).toFixed(2);
            document.getElementById('qv-compare-price').classList.remove('hidden');
        } else {
            document.getElementById('qv-compare-price').classList.add('hidden');
        }

        // Image Logic
        if (product.image) {
            // Try uploads folder first, then images folder
            const imgInUploads = '<?= BASE_URL ?>uploads/' + product.image;
            const imgInImages = '<?= BASE_URL ?>images/' + product.image;

            // We set the src to uploads by default, but we can add a small check
            img.src = imgInUploads;
            img.onerror = function() {
                this.src = imgInImages;
                this.onerror = null; // Prevent infinite loop
            };

            img.classList.remove('hidden');
            noImg.classList.add('hidden');
        } else {
            img.classList.add('hidden');
            noImg.classList.remove('hidden');
        }

        // Stock
        const stockEl = document.getElementById('qv-stock');
        stockEl.textContent = product.stock + ' units';
        stockEl.className = 'px-3 py-1 rounded-full text-xs font-bold ';
        if (product.stock <= 0) stockEl.classList.add('bg-red-100', 'text-red-700', 'dark:bg-red-900/50', 'dark:text-red-400');
        else if (product.stock <= 5) stockEl.classList.add('bg-yellow-100', 'text-yellow-700', 'dark:bg-yellow-900/50', 'dark:text-yellow-400');
        else stockEl.classList.add('bg-green-100', 'text-green-700', 'dark:bg-green-900/50', 'dark:text-green-400');

        // Edit Button
        document.getElementById('qv-edit-btn').href = '<?= BASE_URL ?>admin/product/edit?id=' + product.id;

        // Show Modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeQuickView() {
        document.getElementById('quickViewModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // AJAX Pagination to prevent "jump to top"
    document.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination-link');
        if (link) {
            e.preventDefault();
            const url = link.href;

            // Show a simple loading state (optional)
            const productList = document.getElementById('product-list');
            productList.style.opacity = '0.5';
            productList.style.pointerEvents = 'none';

            fetch(url)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContent = doc.getElementById('product-list');

                    if (newContent) {
                        productList.innerHTML = newContent.innerHTML;
                        // Update the URL in the browser without reloading
                        window.history.pushState({}, '', url);
                    }

                    productList.style.opacity = '1';
                    productList.style.pointerEvents = 'auto';
                })
                .catch(err => {
                    console.error('Pagination error:', err);
                    window.location.href = url; // Fallback to normal click on error
                });
        }
    });
</script>
