<?php $isEdit = !empty($product); ?>
<div class="max-w-3xl">
    <a href="<?= BASE_URL ?>admin/products" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 mb-6 text-sm font-medium">
        <i class="fas fa-arrow-left mr-2"></i><?= t('back_to_products') ?>
    </a>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 transition-colors">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6"><?= $isEdit ? t('edit_product') : t('add_new_product') ?></h2>

        <form method="POST" action="<?= BASE_URL ?>admin/product/<?= $isEdit ? 'update' : 'save' ?>" enctype="multipart/form-data" class="space-y-5">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                <?php
                $existingGallery = [];
                if (!empty($product['gallery_images'])) {
                    $existingGallery = json_decode($product['gallery_images'], true) ?? [];
                }
                ?>
                <input type="hidden" name="existing_gallery" value="<?= htmlspecialchars($product['gallery_images'] ?? '') ?>">
            <?php endif; ?>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('product_name') ?> <span class="text-red-500"><?= t('required') ?></span></label>
                <input type="text" name="name" required value="<?= htmlspecialchars($product['name'] ?? '') ?>"
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                    placeholder="<?= t('enter_product_name') ?>">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('description') ?></label>
                <textarea name="description" rows="4"
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition resize-y bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                    placeholder="<?= t('enter_product_description') ?>"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('category') ?></label>
                    <select name="category_id"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="0"><?= t('no_category') ?></option>
                        <?php
                        $catTreeLocal = $categoryTree ?? [];
                        $catSelected = $product['category_id'] ?? '';
                        $renderCatOpts = function($cats, $selected, $depth = 0) use (&$renderCatOpts) {
                            foreach ($cats as $cat) {
                                $indent = str_repeat('&nbsp;&nbsp;', $depth);
                                $prefix = $depth > 0 ? '└ ' : '';
                                $selectedAttr = $selected == $cat['id'] ? 'selected' : '';
                                echo "<option value=\"{$cat['id']}\" {$selectedAttr}>{$indent}{$prefix}" . htmlspecialchars($cat['name']) . " ({$cat['product_count']})</option>";
                                if (!empty($cat['children'])) {
                                    $renderCatOpts($cat['children'], $selected, $depth + 1);
                                }
                            }
                        };
                        $renderCatOpts($catTreeLocal, $catSelected);
                        ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('price') ?> ($) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" required min="0.01" step="0.01" value="<?= $product['price'] ?? '' ?>"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                        placeholder="0.00">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('compare_price') ?> ($)</label>
                    <input type="number" name="compare_price" min="0" step="0.01" value="<?= $product['compare_price'] ?? '' ?>"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                        placeholder="<?= t('optional') ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('stock') ?> <span class="text-red-500">*</span></label>
                    <input type="number" name="stock" required min="0" value="<?= $product['stock'] ?? '' ?>"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                        placeholder="0">
                </div>
            </div>

            <!-- Main Image -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('product') ?> Image</label>
                <?php
                $currentImgPath = '';
                if ($isEdit && $product['image']) {
                    if (file_exists(UPLOAD_PATH . $product['image'])) {
                        $currentImgPath = BASE_URL . 'uploads/' . $product['image'];
                    } elseif (file_exists(ROOT_PATH . 'images/' . $product['image'])) {
                        $currentImgPath = BASE_URL . 'images/' . $product['image'];
                    }
                }
                ?>
                <?php if ($currentImgPath): ?>
                <div class="mb-3">
                    <img src="<?= $currentImgPath ?>" class="w-24 h-24 object-cover rounded-lg border border-gray-200 dark:border-gray-600" alt="">
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1"><?= t('current_image') ?></p>
                </div>
                <?php endif; ?>
                <input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp"
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 dark:file:bg-blue-900/50 file:text-blue-600 dark:file:text-blue-400 hover:file:bg-blue-100 dark:hover:file:bg-blue-800/50">
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1"><?= t('accepted_formats') ?></p>
            </div>

            <!-- Gallery Images -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    <?= t('image_gallery') ?> 
                    <span class="text-gray-400 dark:text-gray-500 font-normal">(<?= t('optional') ?> - <?= t('max_gallery_images') ?>)</span>
                </label>

                <?php if ($isEdit && !empty($existingGallery)): ?>
                <div class="grid grid-cols-4 sm:grid-cols-6 gap-3 mb-3" id="existingGallery">
                    <?php foreach ($existingGallery as $gIdx => $gImg): ?>
                    <div class="relative group" id="gallery-item-<?= $gIdx ?>">
                        <img src="<?= BASE_URL ?>uploads/<?= rawurlencode($gImg) ?>" 
                             onerror="this.src='<?= BASE_URL ?>images/<?= rawurlencode($gImg) ?>'; this.onerror=null;"
                             class="w-full h-20 object-cover rounded-lg border border-gray-200 dark:border-gray-600" alt="">
                        <label class="absolute inset-0 bg-black/50 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition cursor-pointer">
                            <input type="checkbox" name="remove_gallery[]" value="<?= $gIdx ?>" class="hidden" onchange="toggleRemoveGallery(this)">
                            <i class="fas fa-trash text-white text-sm"></i>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <input type="file" name="gallery_images[]" accept="image/jpeg,image/png,image/gif,image/webp" multiple
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 dark:file:bg-purple-900/50 file:text-purple-600 dark:file:text-purple-400 hover:file:bg-purple-100 dark:hover:file:bg-purple-800/50">
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1"><?= t('gallery_help') ?></p>
            </div>

            <!-- Specifications -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-list-ul mr-1"></i><?= t('specifications') ?>
                    <span class="text-gray-400 dark:text-gray-500 font-normal">(<?= t('optional') ?>)</span>
                </label>
                <?php
                $existingSpecs = [];
                if ($isEdit && !empty($product['specifications'])) {
                    $existingSpecs = json_decode($product['specifications'], true) ?? [];
                }
                ?>
                <div id="specsContainer" class="space-y-2">
                    <?php if (!empty($existingSpecs)): ?>
                        <?php foreach ($existingSpecs as $sk => $sv): ?>
                        <div class="flex items-center gap-2 spec-row">
                            <input type="text" name="spec_key[]" value="<?= htmlspecialchars($sk) ?>" placeholder="<?= t('spec_name_placeholder') ?>"
                                class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            <input type="text" name="spec_value[]" value="<?= htmlspecialchars($sv) ?>" placeholder="<?= t('spec_value_placeholder') ?>"
                                class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            <button type="button" onclick="this.closest('.spec-row').remove()" class="text-red-400 hover:text-red-600 px-2"><i class="fas fa-times"></i></button>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="mt-2 flex items-center gap-3">
                    <button type="button" onclick="addSpec()" class="text-sm text-blue-600 dark:text-blue-400 hover:underline"><i class="fas fa-plus mr-1"></i><?= t('add_specification') ?></button>
                    <div class="flex-1"></div>
                    <div class="text-xs text-gray-400 dark:text-gray-500">
                        <span class="hidden sm:inline"><?= t('spec_examples') ?></span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium transition">
                    <i class="fas fa-save mr-2"></i><?= $isEdit ? t('update_product') : t('create_product') ?>
                </button>
                <a href="<?= BASE_URL ?>admin/products" class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-6 py-2.5 rounded-lg font-medium transition"><?= t('cancel') ?></a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleRemoveGallery(checkbox) {
    const item = checkbox.closest('.relative');
    if (checkbox.checked) {
        item.style.opacity = '0.4';
        item.style.transform = 'scale(0.95)';
    } else {
        item.style.opacity = '1';
        item.style.transform = 'scale(1)';
    }
}
function addSpec() {
    const container = document.getElementById('specsContainer');
    const row = document.createElement('div');
    row.className = 'flex items-center gap-2 spec-row';
    row.innerHTML = `
        <input type="text" name="spec_key[]" placeholder="<?= t('spec_name_placeholder') ?>"
            class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
        <input type="text" name="spec_value[]" placeholder="<?= t('spec_value_placeholder') ?>"
            class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
        <button type="button" onclick="this.closest('.spec-row').remove()" class="text-red-400 hover:text-red-600 px-2"><i class="fas fa-times"></i></button>
    `;
    container.appendChild(row);
}
</script>
