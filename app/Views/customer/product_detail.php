<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mb-6 overflow-x-auto">
        <a href="<?= BASE_URL ?>" class="hover:text-blue-600 dark:hover:text-blue-400 transition whitespace-nowrap"><?= t('home') ?></a>
        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="<?= BASE_URL ?>shop" class="hover:text-blue-600 dark:hover:text-blue-400 transition whitespace-nowrap"><?= t('shop') ?></a>
        <?php if (!empty($product['category_name'])): ?>
        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="<?= BASE_URL ?>shop?category=<?= htmlspecialchars($product['category_slug']) ?>" class="hover:text-blue-600 dark:hover:text-blue-400 transition whitespace-nowrap"><?= htmlspecialchars($product['category_name']) ?></a>
        <?php endif; ?>
        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-900 dark:text-white font-medium truncate"><?= htmlspecialchars($product['name']) ?></span>
    </nav>

    <!-- Main Product Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10">

        <!-- Image Gallery -->
        <?php
        $allImages = [];
        if (!empty($product['image'])) $allImages[] = $product['image'];
        if (!empty($galleryImages)) $allImages = array_merge($allImages, $galleryImages);
        $hasSale = !empty($product['compare_price']) && $product['compare_price'] > $product['price'];
        ?>
        <div class="relative" id="gallerySection">
            <div class="flex gap-2.5">
                <!-- Thumbnails -->
                <?php if (count($allImages) > 1): ?>
                <div class="flex flex-row md:flex-col gap-2 order-1 md:order-none overflow-x-auto md:overflow-x-visible shrink-0">
                    <?php foreach ($allImages as $idx => $img): ?>
                    <button onclick="switchImage(<?= $idx ?>)" id="thumb-<?= $idx ?>"
                            class="thumb-btn w-12 h-12 md:w-14 md:h-14 rounded-lg border-2 overflow-hidden shrink-0 bg-white dark:bg-gray-700 shadow-sm transition-all <?= $idx === 0 ? 'border-blue-500 ring-2 ring-blue-100 dark:ring-blue-900' : 'border-gray-200 dark:border-gray-600 hover:border-blue-300' ?>">
                        <img src="<?= BASE_URL . 'uploads/' . rawurlencode($img) ?>"
                             onerror="this.src='<?= BASE_URL . 'images/' . rawurlencode($img) ?>'; this.onerror=null;"
                             alt="<?= htmlspecialchars($product['name']) ?>"
                             class="w-full h-full object-cover">
                    </button>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Main Image -->
                <div class="flex-1 aspect-square bg-gray-50 dark:bg-gray-700 rounded-xl overflow-hidden relative cursor-crosshair"
                     onmouseenter="startZoom()" onmouseleave="stopZoom()" onmousemove="moveZoom(event)">
                    <?php if (!empty($allImages)): ?>
                    <img id="mainImage"
                         src="<?= BASE_URL . 'uploads/' . rawurlencode($allImages[0]) ?>"
                         onerror="this.src='<?= BASE_URL . 'images/' . rawurlencode($allImages[0]) ?>'; this.onerror=null;"
                         alt="<?= htmlspecialchars($product['name']) ?>"
                         class="w-full h-full object-cover select-none" onclick="openLightbox()">
                    <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-gray-300 dark:text-gray-600">
                        <i class="fas fa-image text-6xl"></i>
                    </div>
                    <?php endif; ?>
                    <div id="zoomOverlay" class="absolute inset-0 hidden md:block pointer-events-none rounded-xl" style="background-size: 0; background-repeat: no-repeat;"></div>

                    <!-- SALE Badge -->
                    <?php if ($hasSale): ?>
                    <span class="absolute top-3 left-3 bg-red-500 text-white text-[10px] uppercase font-extrabold px-2 py-1 rounded shadow-lg z-10"><?= t('sale') ?></span>
                    <?php endif; ?>

                    <!-- Wishlist Heart -->
                    <button onclick="event.stopPropagation(); toggleWishlist(<?= $product['id'] ?>)" id="wishlistBtn"
                            class="absolute top-3 right-3 w-8 h-8 flex items-center justify-center rounded-full <?= $isWishlisted ? 'bg-red-500 text-white' : 'bg-white/80 dark:bg-gray-800/80 text-gray-400 hover:text-red-500' ?> shadow-md transition-all hover:scale-110 backdrop-blur-sm z-10 text-sm">
                        <i class="<?= $isWishlisted ? 'fas' : 'far' ?> fa-heart" id="wishlistIcon"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Product Info -->
        <div class="md:pt-2">
            <?php if (!empty($product['category_name'])): ?>
            <a href="<?= BASE_URL ?>shop?category=<?= htmlspecialchars($product['category_slug']) ?>" class="text-[10px] text-blue-600 dark:text-blue-400 font-bold uppercase tracking-widest mb-1.5 inline-block hover:underline transition">
                <?= htmlspecialchars($product['category_name']) ?>
            </a>
            <?php endif; ?>

            <h1 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white leading-tight mb-2 md:mb-3"><?= htmlspecialchars($product['name']) ?></h1>

            <!-- Price -->
            <div class="flex items-center gap-2.5 mb-3">
                <span class="text-2xl font-extrabold text-blue-600 dark:text-blue-400">$<?= number_format($product['price'], 2) ?></span>
                <?php if ($hasSale): ?>
                    <span class="text-base text-gray-400 dark:text-gray-500 line-through">$<?= number_format($product['compare_price'], 2) ?></span>
                    <span class="text-[10px] bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 px-1.5 py-0.5 rounded font-bold uppercase"><?= t('save') ?> $<?= number_format($product['compare_price'] - $product['price'], 2) ?></span>
                <?php endif; ?>
            </div>

            <!-- Rating + Stock -->
            <div class="flex items-center gap-3 mb-5">
                <?php if ($ratingData['total_reviews'] > 0): ?>
                <div class="flex items-center gap-1">
                    <div class="flex">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <svg class="w-3.5 h-3.5 <?= $i <= round($ratingData['avg_rating']) ? 'text-yellow-400' : 'text-gray-200 dark:text-gray-600' ?>" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <?php endfor; ?>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">(<?= $ratingData['total_reviews'] ?> <?= $ratingData['total_reviews'] == 1 ? t('review') : t('review_count') ?>)</span>
                </div>
                <span class="text-gray-300 dark:text-gray-600">|</span>
                <?php endif; ?>
                <span class="text-xs flex items-center gap-1.5 <?= $product['stock'] > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500 dark:text-red-400 font-medium' ?>">
                    <span class="w-1.5 h-1.5 rounded-full <?= $product['stock'] > 0 ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.4)]' : 'bg-red-500' ?>"></span>
                    <?php if ($product['stock'] > 0): ?>
                        <span class="opacity-90"><?= t('in_stock_available') ?> (<?= $product['stock'] ?> <?= t('available') ?>)</span>
                    <?php else: ?>
                        <?= t('sold_out') ?>
                    <?php endif; ?>
                </span>
            </div>

            <!-- Description -->
            <?php if (!empty($product['description'])): ?>
            <div class="mb-6">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-2 uppercase tracking-wide"><?= t('details') ?></h3>
                <div class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed whitespace-pre-line"><?= htmlspecialchars($product['description']) ?></div>
            </div>
            <?php endif; ?>

            <!-- Add to Cart + Buy Now -->
            <div class="flex gap-2.5 mb-6">
                <?php if ($product['stock'] > 0): ?>
                <form method="POST" action="<?= BASE_URL ?>cart/add" class="flex-1">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="w-full px-5 py-2.5 bg-gray-900 dark:bg-gray-700 text-white text-sm font-bold rounded-lg hover:bg-blue-600 dark:hover:bg-blue-600 transition flex items-center justify-center gap-2 shadow-sm">
                        <i class="fas fa-shopping-cart text-xs"></i>
                        <?= t('add_to_cart') ?>
                    </button>
                </form>
                <a href="<?= BASE_URL ?>cart/buy?product_id=<?= $product['id'] ?>&qty=1"
                   class="flex-1 w-full px-5 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2 shadow-sm">
                    <i class="fas fa-bolt text-xs"></i>
                    <?= t('buy_now') ?>
                </a>
                <?php else: ?>
                <button disabled class="w-full px-5 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 text-sm font-bold rounded-lg cursor-not-allowed flex items-center justify-center gap-2">
                    <i class="fas fa-ban text-xs"></i>
                    <?= t('out_of_stock_msg') ?>
                </button>
                <?php endif; ?>
            </div>

            <!-- Specifications -->
            <?php
            $customSpecs = [];
            if (!empty($product['specifications'])) {
                $customSpecs = json_decode($product['specifications'], true) ?? [];
            }
            ?>
            <?php if (!empty($customSpecs)): ?>
            <div class="bg-gray-50 dark:bg-gray-800/40 rounded-xl p-4 border border-gray-100 dark:border-gray-700/50">
                <h3 class="text-xs font-bold text-gray-900 dark:text-white mb-3 uppercase tracking-wider"><?= t('specifications') ?></h3>
                <dl class="space-y-2">
                    <?php foreach ($customSpecs as $sk => $sv): ?>
                    <div class="flex justify-between items-center text-xs">
                        <dt class="text-gray-500 dark:text-gray-400 capitalize"><?= htmlspecialchars($sk) ?></dt>
                        <dd class="text-gray-900 dark:text-white font-semibold"><?= htmlspecialchars($sv) ?></dd>
                    </div>
                    <?php endforeach; ?>
                </dl>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Product Gallery
    <?php if (!empty($allImages)): ?>
    <section class="mb-12">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6"><i class="fas fa-images mr-2 text-blue-500"></i><?= t('image_gallery') ?></h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
            <?php foreach ($allImages as $gIdx => $gImg): ?>
            <button onclick="switchImage(<?= $gIdx ?>); window.scrollTo({top: 0, behavior: 'smooth'})"
                    class="aspect-square rounded-xl overflow-hidden border-2 border-gray-200 dark:border-gray-700 hover:border-blue-500 hover:ring-2 hover:ring-blue-200 dark:hover:ring-blue-800 transition-all group">
                <img src="<?= BASE_URL . 'uploads/' . rawurlencode($gImg) ?>"
                     onerror="this.src='<?= BASE_URL . 'images/' . rawurlencode($gImg) ?>'; this.onerror=null;"
                     alt="<?= htmlspecialchars($product['name']) ?> <?= $gIdx + 1 ?>"
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" loading="lazy">
            </button>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?> -->

    <!-- Customer Reviews -->
    <section class="mt-12">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6 uppercase tracking-wide"><?= t('reviews') ?></h2>

        <?php if ($ratingData['total_reviews'] > 0): ?>
        <!-- Rating Breakdown -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5 mb-5 shadow-sm">
            <div class="flex flex-col sm:flex-row gap-6">
                <div class="text-center sm:text-left sm:pr-6 sm:border-r border-gray-100 dark:border-gray-700">
                    <div class="text-4xl font-black text-gray-900 dark:text-white leading-none"><?= number_format($ratingData['avg_rating'], 1) ?></div>
                    <div class="flex justify-center sm:justify-start mt-2">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <svg class="w-4 h-4 <?= $i <= round($ratingData['avg_rating']) ? 'text-yellow-400' : 'text-gray-200 dark:text-gray-600' ?>" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <?php endfor; ?>
                    </div>
                    <div class="text-[10px] text-gray-400 dark:text-gray-500 mt-1 uppercase font-bold"><?= $ratingData['total_reviews'] ?> <?= $ratingData['total_reviews'] == 1 ? t('review') : t('review_count') ?></div>
                </div>
                <div class="flex-1 space-y-1.5">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <?php
                        $count = 0;
                        foreach ($distribution as $d) {
                            if ($d['rating'] == $i) { $count = $d['count']; break; }
                        }
                        $pct = $ratingData['total_reviews'] > 0 ? ($count / $ratingData['total_reviews']) * 100 : 0;
                        ?>
                        <div class="flex items-center gap-3">
                            <span class="text-[10px] text-gray-500 font-bold w-2"><?= $i ?></span>
                            <div class="flex-1 h-1.5 bg-gray-100 dark:bg-gray-700/50 rounded-full overflow-hidden">
                                <div class="h-full bg-yellow-400 rounded-full" style="width: <?= $pct ?>%"></div>
                            </div>
                            <span class="text-[10px] text-gray-400 dark:text-gray-500 w-4 text-right"><?= $count ?></span>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Reviews List -->
        <?php if (!empty($reviews)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <?php foreach ($reviews as $review): ?>
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-4 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="text-xs font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($review['user_name']) ?></p>
                            <div class="flex items-center gap-0.5 mt-0.5">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <svg class="w-3 h-3 <?= $i <= $review['rating'] ? 'text-yellow-400' : 'text-gray-200 dark:text-gray-600' ?>" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] text-gray-400"><?= date('M d, Y', strtotime($review['created_at'])) ?></span>
                            <?php if ($review['user_id'] == \App\Core\Session::getUserId()): ?>
                                <a href="<?= BASE_URL ?>review/delete?id=<?= $review['id'] ?>&product_id=<?= $product['id'] ?>"
                                   onclick="return confirm('<?= t('confirm_delete_review') ?>')"
                                   class="text-[10px] text-red-400 hover:text-red-500"><i class="fas fa-trash"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (!empty($review['comment'])): ?>
                        <p class="text-xs text-gray-600 dark:text-gray-400 leading-normal line-clamp-3 italic">"<?= htmlspecialchars($review['comment']) ?>"</p>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-10 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700">
                <i class="fas fa-comment-dots text-4xl text-gray-200 dark:text-gray-700 mb-3"></i>
                <p class="text-xs text-gray-400"><?= t('no_reviews_yet') ?></p>
            </div>
        <?php endif; ?>
    </section>

    <!-- Write a Review -->
    <section class="mt-10 max-w-2xl">
        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-5 uppercase tracking-wide"><?= t('write_review') ?></h3>
        <?php if (\App\Core\Session::isLoggedIn()): ?>
            <form method="POST" action="<?= BASE_URL ?>review/submit" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2"><?= t('your_rating') ?></label>
                    <div class="flex gap-0.5">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <button type="button" onclick="setRating(<?= $i ?>)" class="star-btn focus:outline-none" data-star="<?= $i ?>">
                            <svg class="w-6 h-6 transition-colors star-icon text-gray-200 dark:text-gray-700" data-star="<?= $i ?>" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </button>
                        <?php endfor; ?>
                        <input type="hidden" name="rating" id="ratingInput" value="0">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2"><?= t('your_review') ?></label>
                    <textarea name="comment" rows="3" required
                              class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50 dark:bg-gray-900/50 text-gray-900 dark:text-white transition"
                              placeholder="<?= t('your_review') ?>"></textarea>
                </div>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-gray-900 dark:bg-gray-700 text-white text-xs font-bold rounded-lg hover:bg-blue-600 transition shadow-sm">
                    <i class="fas fa-paper-plane mr-2 text-[10px]"></i><?= t('submit_review') ?>
                </button>
            </form>
        <?php else: ?>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6 text-center shadow-sm">
            <p class="text-xs text-gray-500 dark:text-gray-400"><?= t('login_to_review') ?></p>
            <a href="<?= BASE_URL ?>login" class="inline-flex items-center gap-2 mt-3 px-5 py-2 bg-gray-900 dark:bg-gray-700 text-white text-xs font-bold rounded-lg hover:bg-blue-600 transition">
                <i class="fas fa-sign-in-alt text-[10px]"></i><?= t('login') ?>
            </a>
        </div>
        <?php endif; ?>
    </section>

    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
    <section class="mt-14">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6 uppercase tracking-wide"><?= t('related_products') ?></h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            <?php foreach ($relatedProducts as $rp): ?>
            <a href="<?= BASE_URL ?>product?id=<?= $rp['id'] ?>"
               class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg transition group">
                <div class="h-36 overflow-hidden bg-gray-50 dark:bg-gray-700">
                    <?php if (!empty($rp['image'])): ?>
                        <img src="<?= BASE_URL . 'uploads/' . rawurlencode($rp['image']) ?>"
                             onerror="this.src='<?= BASE_URL . 'images/' . rawurlencode($rp['image']) ?>'; this.onerror=null;"
                             alt="<?= htmlspecialchars($rp['name']) ?>"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center"><i class="fas fa-image text-2xl text-gray-300 dark:text-gray-700"></i></div>
                    <?php endif; ?>
                </div>
                <div class="p-3">
                    <h3 class="text-[11px] font-bold text-gray-900 dark:text-white truncate group-hover:text-blue-600 transition"><?= htmlspecialchars($rp['name']) ?></h3>
                    <span class="text-blue-600 dark:text-blue-400 font-extrabold text-[11px] mt-1 inline-block">$<?= number_format($rp['price'], 2) ?></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</div>

<!-- Lightbox -->
<div id="lightbox" class="fixed inset-0 z-50 bg-black/95 flex items-center justify-center hidden" onclick="closeLightbox()">
    <button class="absolute top-4 right-4 text-white/70 hover:text-white z-20 p-2 transition" onclick="closeLightbox()">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <button class="absolute left-4 text-white/70 hover:text-white p-2 transition z-20" onclick="event.stopPropagation(); lightboxPrev();">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <img id="lightboxImage" src="" class="max-w-[90vw] max-h-[90vh] object-contain rounded-lg select-none" onclick="event.stopPropagation();">
    <button class="absolute right-4 text-white/70 hover:text-white p-2 transition z-20" onclick="event.stopPropagation(); lightboxNext();">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </button>
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 text-white/60 text-sm font-medium">
        <span id="lightboxCounter">1</span> / <span id="lightboxTotal"><?= count($allImages) ?></span>
    </div>
</div>

<script>
// Image data
var allImages = <?= json_encode(array_map(fn($img) => [
    'upload' => BASE_URL . 'uploads/' . rawurlencode($img),
    'fallback' => BASE_URL . 'images/' . rawurlencode($img)
], $allImages)) ?>;
var currentIdx = 0;
var mainImg = document.getElementById('mainImage');

function switchImage(idx) {
    currentIdx = idx;
    if (mainImg && allImages[idx]) {
        mainImg.src = allImages[idx].upload;
        mainImg.onerror = function() { this.src = allImages[idx].fallback; this.onerror = null; };
    }
    document.querySelectorAll('.thumb-btn').forEach(function(b, i) {
        if (i === idx) {
            b.classList.add('border-blue-500', 'ring-2', 'ring-blue-200', 'dark:ring-blue-800');
            b.classList.remove('border-gray-200', 'dark:border-gray-600');
        } else {
            b.classList.remove('border-blue-500', 'ring-2', 'ring-blue-200', 'dark:ring-blue-800');
            b.classList.add('border-gray-200', 'dark:border-gray-600');
        }
    });
}

// Zoom on hover
var zoomOverlay = document.getElementById('zoomOverlay');
function startZoom() {
    if (window.innerWidth < 768 || !mainImg || !allImages[currentIdx]) return;

    // Use the current src of the main image which already has fallback logic handled
    var currentSrc = mainImg.src;
    var img = new Image();
    img.onload = function() {
        var w = mainImg.offsetWidth * 2;
        var h = mainImg.offsetHeight * 2;
        zoomOverlay.style.backgroundImage = 'url(' + currentSrc + ')';
        zoomOverlay.style.backgroundSize = w + 'px ' + h + 'px';
    };
    img.src = currentSrc;
}
function stopZoom() {
    if (zoomOverlay) zoomOverlay.style.backgroundImage = '';
}
function moveZoom(e) {
    if (window.innerWidth < 768 || !zoomOverlay) return;
    var rect = e.currentTarget.getBoundingClientRect();
    var x = ((e.clientX - rect.left) / rect.width) * 100;
    var y = ((e.clientY - rect.top) / rect.height) * 100;
    zoomOverlay.style.backgroundPosition = x + '% ' + y + '%';
}

// Lightbox
function openLightbox() {
    if (!allImages.length) return;
    document.getElementById('lightboxImage').src = allImages[currentIdx].upload;
    document.getElementById('lightboxCounter').textContent = currentIdx + 1;
    document.getElementById('lightbox').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('lightbox').classList.add('hidden');
    document.body.style.overflow = '';
}
function lightboxPrev() {
    currentIdx = currentIdx > 0 ? currentIdx - 1 : allImages.length - 1;
    switchImage(currentIdx);
    document.getElementById('lightboxImage').src = allImages[currentIdx].upload;
    document.getElementById('lightboxCounter').textContent = currentIdx + 1;
}
function lightboxNext() {
    currentIdx = currentIdx < allImages.length - 1 ? currentIdx + 1 : 0;
    switchImage(currentIdx);
    document.getElementById('lightboxImage').src = allImages[currentIdx].upload;
    document.getElementById('lightboxCounter').textContent = currentIdx + 1;
}
document.addEventListener('keydown', function(e) {
    if (document.getElementById('lightbox').classList.contains('hidden')) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft') lightboxPrev();
    if (e.key === 'ArrowRight') lightboxNext();
});

// Star Rating
function setRating(rating) {
    document.getElementById('ratingInput').value = rating;
    document.querySelectorAll('.star-icon').forEach(function(svg) {
        var star = parseInt(svg.dataset.star);
        if (star <= rating) {
            svg.classList.add('text-yellow-400');
            svg.classList.remove('text-gray-200', 'dark:text-gray-600');
        } else {
            svg.classList.remove('text-yellow-400');
            svg.classList.add('text-gray-200', 'dark:text-gray-600');
        }
    });
}

// Wishlist
function toggleWishlist(productId) {
    fetch('<?= BASE_URL ?>wishlist/toggle', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'product_id=' + productId
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (!data.success) {
            if (data.message.indexOf('login') !== -1) {
                window.location.href = '<?= BASE_URL ?>login';
            }
            return;
        }
        var btn = document.getElementById('wishlistBtn');
        var icon = document.getElementById('wishlistIcon');
        if (data.wishlisted) {
            btn.classList.add('bg-red-500', 'text-white');
            btn.classList.remove('bg-white/80', 'dark:bg-gray-800/80', 'text-gray-400');
            icon.classList.add('fas');
            icon.classList.remove('far');
        } else {
            btn.classList.remove('bg-red-500', 'text-white');
            btn.classList.add('bg-white/80', 'dark:bg-gray-800/80', 'text-gray-400');
            icon.classList.remove('fas');
            icon.classList.add('far');
        }
    });
}
</script>
