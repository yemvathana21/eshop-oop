<?php $isEdit = !empty($product); ?>
<div class="max-w-3xl">
    <a href="<?= BASE_URL ?>admin/products" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 mb-6 text-sm font-medium">
        <i class="fas fa-arrow-left mr-2"></i><?= t('back_to_orders') ?>
    </a>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 transition-colors">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6"><?= $isEdit ? t('edit_product') : t('add_new_product') ?></h2>

        <form method="POST" action="<?= BASE_URL ?>admin/product/<?= $isEdit ? 'update' : 'save' ?>" enctype="multipart/form-data" class="space-y-5">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= $product['id'] ?>">
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
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($product['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?> (<?= $cat['product_count'] ?>)
                        </option>
                        <?php endforeach; ?>
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

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('product') ?> Image</label>
                <?php if ($isEdit && $product['image'] && file_exists(UPLOAD_PATH . $product['image'])): ?>
                <div class="mb-3">
                    <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($product['image']) ?>" class="w-24 h-24 object-cover rounded-lg border border-gray-200 dark:border-gray-600" alt="">
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1"><?= t('current_image') ?></p>
                </div>
                <?php endif; ?>
                <input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp"
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 dark:file:bg-blue-900/50 file:text-blue-600 dark:file:text-blue-400 hover:file:bg-blue-100 dark:hover:file:bg-blue-800/50">
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1"><?= t('accepted_formats') ?></p>
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
