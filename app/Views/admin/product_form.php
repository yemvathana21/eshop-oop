<?php $isEdit = !empty($product); ?>
<div class="max-w-5xl">
    <a href="<?= BASE_URL ?>admin/products" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 mb-6 text-sm font-medium">
        <i class="fas fa-arrow-left mr-2"></i><?= t('back_to_products') ?>
    </a>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 transition-colors">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6"><?= $isEdit ? t('edit_product') : t('add_new_product') ?></h2>

        <form method="POST" action="<?= BASE_URL ?>admin/product/<?= $isEdit ? 'update' : 'save' ?>" enctype="multipart/form-data" class="space-y-6">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                <input type="hidden" name="existing_gallery" value="<?= htmlspecialchars($product['gallery_images'] ?? '') ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Categories and Basic Info -->
                <div class="lg:col-span-2 space-y-5">
                    <!-- 3-Level Categories -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-700">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">Top Category *</label>
                            <select name="tcat_id" id="tcat_id" required onchange="loadSubs(this.value, 'mcat_id')"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Top Category</option>
                                <?php foreach ($topCategories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= (isset($tcat_id) && $tcat_id == $cat['id']) ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">Mid Category *</label>
                            <select name="mcat_id" id="mcat_id" required onchange="loadSubs(this.value, 'ecat_id')"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Mid Category</option>
                                <?php if (isset($midCategories)): ?>
                                    <?php foreach ($midCategories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= (isset($mcat_id) && $mcat_id == $cat['id']) ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">End Category *</label>
                            <select name="ecat_id" id="ecat_id" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select End Category</option>
                                <?php if (isset($endCategories)): ?>
                                    <?php foreach ($endCategories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= (isset($ecat_id) && $ecat_id == $cat['id']) ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('product_name') ?> *</label>
                        <input type="text" name="name" required value="<?= htmlspecialchars($product['name'] ?? '') ?>"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            placeholder="<?= t('enter_product_name') ?>">
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('price') ?> ($) *</label>
                            <input type="number" name="price" required min="0.01" step="0.01" value="<?= $product['price'] ?? '' ?>"
                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('compare_price') ?> ($)</label>
                            <input type="number" name="compare_price" min="0" step="0.01" value="<?= $product['compare_price'] ?? '' ?>"
                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="<?= t('optional') ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('stock') ?> *</label>
                            <input type="number" name="stock" required min="0" value="<?= $product['stock'] ?? '' ?>"
                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="0">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Sizes</label>
                            <select name="size[]" multiple class="select2-multiple w-full">
                                <?php foreach ($sizes as $size): ?>
                                    <option value="<?= $size['id'] ?>" <?= (isset($productSizes) && in_array($size['id'], $productSizes)) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($size['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Colors</label>
                            <select name="color[]" multiple class="select2-multiple w-full">
                                <?php foreach ($colors as $color): ?>
                                    <option value="<?= $color['id'] ?>" <?= (isset($productColors) && in_array($color['id'], $productColors)) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($color['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Images -->
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Featured Photo *</label>
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-4 text-center bg-gray-50 dark:bg-gray-900/30">
                            <?php if ($isEdit && $product['image']): ?>
                                <img src="<?= BASE_URL . (file_exists(UPLOAD_PATH . $product['image']) ? 'uploads/' : 'images/') . $product['image'] ?>" class="w-full h-32 object-cover rounded-lg mb-3 mx-auto shadow-sm" id="mainImagePreview">
                            <?php else: ?>
                                <div class="w-full h-32 flex items-center justify-center text-gray-400 dark:text-gray-600 mb-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                                    <i class="fas fa-image text-3xl"></i>
                                </div>
                            <?php endif; ?>
                            <input type="file" name="image" id="featured_image" accept="image/*" class="text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 dark:file:bg-blue-900/50 file:text-blue-700 dark:file:text-blue-400 hover:file:bg-blue-100">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Other Photos</label>
                            <button type="button" id="btnAddNew" class="bg-[#f0ad4e] hover:bg-[#ec971f] text-white text-[11px] font-bold px-3 py-1.5 rounded transition shadow-sm">
                                Add Item
                            </button>
                        </div>
                        <div class="space-y-3">
                            <?php if ($isEdit && !empty($product['gallery_images'])): ?>
                                <div id="existingGallery" class="grid grid-cols-4 gap-2 mb-3">
                                    <?php $gall = json_decode($product['gallery_images'], true); ?>
                                    <?php foreach ($gall as $idx => $img): ?>
                                        <div class="relative group h-16 border rounded-lg overflow-hidden">
                                            <img src="<?= BASE_URL ?>uploads/<?= $img ?>" class="w-full h-full object-cover">
                                            <label class="absolute inset-0 bg-red-500/80 flex items-center justify-center opacity-0 group-hover:opacity-100 cursor-pointer transition">
                                                <input type="checkbox" name="remove_gallery[]" value="<?= $idx ?>" class="hidden">
                                                <i class="fas fa-trash text-white text-xs"></i>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <div id="ProductTable" class="space-y-2">
                                <div class="flex items-center gap-2 photo-row">
                                    <div class="w-10 h-10 border rounded overflow-hidden bg-gray-100 flex-shrink-0">
                                        <img src="" class="w-full h-full object-cover hidden gallery-row-preview">
                                        <div class="w-full h-full flex items-center justify-center text-gray-300 gallery-row-placeholder">
                                            <i class="fas fa-image text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" name="gallery_images[]" class="gallery-row-input text-xs text-gray-500 w-full file:mr-4 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-gray-100 dark:file:bg-gray-700 file:text-gray-700 dark:file:text-gray-300 hover:file:bg-gray-200">
                                    </div>
                                    <button type="button" class="btnDelete bg-[#d9534f] hover:bg-[#c9302c] text-white w-7 h-7 rounded flex items-center justify-center transition">
                                        <span class="text-xs font-bold">X</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Area: Description with Rich Text Editor -->
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                    <div class="bg-white rounded-lg">
                        <textarea name="description" id="summernote" class="w-full"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-6 border-t border-gray-100 dark:border-gray-700">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold transition shadow-lg shadow-blue-500/30">
                    <i class="fas fa-save mr-2"></i><?= $isEdit ? t('update_product') : t('create_product') ?>
                </button>
                <a href="<?= BASE_URL ?>admin/products" class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-8 py-3 rounded-xl font-bold transition"><?= t('cancel') ?></a>
            </div>
        </form>
    </div>
</div>

<style>
/* Custom Summernote Styling to match Dark Mode and Theme */
.note-editor.note-frame { border-radius: 0.5rem; border: 1px solid #d1d5db; overflow: hidden; }
.dark .note-editor.note-frame { border-color: #4b5563; }
.note-editor .note-toolbar { background: #f9fafb; border-bottom: 1px solid #d1d5db; }
.dark .note-editor .note-toolbar { background: #374151; border-color: #4b5563; }
.dark .note-editable { background: #1f2937; color: #f3f4f6; }
.note-btn { background: white; border: 1px solid #d1d5db !important; }
.dark .note-btn { background: #4b5563; border-color: #6b7280 !important; color: white !important; }

/* Custom Select2 Styling */
.select2-container--default .select2-selection--multiple {
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    padding: 2px;
    min-height: 44px;
}
.dark .select2-container--default .select2-selection--multiple {
    background-color: #374151;
    border-color: #4b5563;
}
.select2-container--default.select2-container--focus .select2-selection--multiple {
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    outline: none;
}
.select2-selection--multiple .select2-selection__rendered {
    display: flex !important;
    flex-wrap: wrap;
    gap: 8px !important;
    padding: 2px !important;
    margin: 0 !important;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #3b82f6;
    border: none;
    color: white;
    border-radius: 6px;
    padding: 0 !important;
    margin: 0 !important;
    display: flex;
    align-items: center;
    overflow: hidden;
    height: 28px;
    font-weight: 500;
    font-size: 13px;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    background: rgba(0, 0, 0, 0.15) !important;
    border: none !important;
    border-right: 1px solid rgba(255, 255, 255, 0.1) !important;
    color: white !important;
    padding: 0 8px !important;
    margin: 0 !important;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: static !important;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__display {
    padding: 0 10px !important;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
    background: rgba(255, 255, 255, 0.25) !important;
}
.select2-container--default .select2-search--inline {
    margin: 0 !important;
}
.select2-container--default .select2-search--inline .select2-search__field {
    margin: 0 !important;
    height: 28px;
    line-height: 28px;
    font-size: 13px;
}
.select2-dropdown {
    border-color: #d1d5db;
    border-radius: 0.75rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}
.dark .select2-dropdown {
    background-color: #1f2937;
    border-color: #4b5563;
}
.dark .select2-results__option {
    color: #d1d5db;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #3b82f6;
}
.select2-container--default .select2-selection--multiple .select2-selection__clear {
    margin-right: 10px;
    color: #9ca3af;
}
</style>

<script>
$(document).ready(function() {
    // Initialize Summernote
    $('#summernote').summernote({
        placeholder: 'Enter product description...',
        tabsize: 2,
        height: 300,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    // Initialize Select2 Multi-select
    $('.select2-multiple').select2({
        placeholder: "Select options",
        allowClear: true
    });

    // Featured Image Preview
    $('#featured_image').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = $('#mainImagePreview');
                if (preview.length) {
                    preview.attr('src', e.target.result);
                } else {
                    // If no preview exists yet (e.g. adding new product)
                    $('.fa-image').parent().replaceWith(`<img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg mb-3 mx-auto shadow-sm" id="mainImagePreview">`);
                }
            }
            reader.readAsDataURL(file);
        }
    });

    // Gallery Dynamic Inputs
    $('#btnAddNew').click(function() {
        const row = `
            <div class="flex items-center gap-2 photo-row">
                <div class="w-10 h-10 border rounded overflow-hidden bg-gray-100 flex-shrink-0">
                    <img src="" class="w-full h-full object-cover hidden gallery-row-preview">
                    <div class="w-full h-full flex items-center justify-center text-gray-300 gallery-row-placeholder">
                        <i class="fas fa-image text-xs"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <input type="file" name="gallery_images[]" class="gallery-row-input text-xs text-gray-500 w-full file:mr-4 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-gray-100 dark:file:bg-gray-700 file:text-gray-700 dark:file:text-gray-300 hover:file:bg-gray-200">
                </div>
                <button type="button" class="btnDelete bg-[#d9534f] hover:bg-[#c9302c] text-white w-7 h-7 rounded flex items-center justify-center transition">
                    <span class="text-xs font-bold">X</span>
                </button>
            </div>
        `;
        $('#ProductTable').append(row);
    });

    $(document).on('change', '.gallery-row-input', function() {
        const file = this.files[0];
        const row = $(this).closest('.photo-row');
        const preview = row.find('.gallery-row-preview');
        const placeholder = row.find('.gallery-row-placeholder');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.attr('src', e.target.result).removeClass('hidden');
                placeholder.addClass('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            preview.addClass('hidden').attr('src', '');
            placeholder.removeClass('hidden');
        }
    });

    $(document).on('click', '.btnDelete', function() {
        if ($('.photo-row').length > 1) {
            $(this).closest('.photo-row').remove();
        } else {
            $(this).closest('.photo-row').find('input').val('');
        }
    });
});
function loadSubs(parentId, targetId) {
    const target = document.getElementById(targetId);
    target.innerHTML = '<option value="">Loading...</option>';

    // Clear the end category if mid category changes
    if (targetId === 'mcat_id') {
        document.getElementById('ecat_id').innerHTML = '<option value="">Select End Category</option>';
    }

    if (!parentId) {
        target.innerHTML = `<option value="">Select ${targetId === 'mcat_id' ? 'Mid' : 'End'} Category</option>`;
        return;
    }

    fetch('<?= BASE_URL ?>admin/get-subcategories?parent_id=' + parentId)
        .then(r => r.json())
        .then(data => {
            let html = `<option value="">Select ${targetId === 'mcat_id' ? 'Mid' : 'End'} Category</option>`;
            data.forEach(cat => {
                html += `<option value="${cat.id}">${cat.name}</option>`;
            });
            target.innerHTML = html;
        });
}
</script>
