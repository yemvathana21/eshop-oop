<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center text-xl">
                <i class="fas fa-qrcode"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white"><?= t('qr_code') ?> Management</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Manage the QR code image used for checkout payments.</p>
            </div>
        </div>

        <!-- Current QR Code -->
        <div class="mb-10 text-center">
            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Current QR Code</h3>
            <div class="relative inline-block p-4 bg-gray-50 dark:bg-gray-900/50 rounded-3xl border-2 border-dashed border-gray-200 dark:border-gray-700 group">
                <?php if ($hasQr): ?>
                    <img src="<?= $qrUrl ?>" alt="Current QR Code" class="w-64 h-64 object-contain rounded-2xl shadow-lg bg-white">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity rounded-3xl flex items-center justify-center gap-3">
                        <button onclick="confirmDelete()" class="w-10 h-10 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                <?php else: ?>
                    <div class="w-64 h-64 flex flex-col items-center justify-center text-gray-400 dark:text-gray-600 italic">
                        <i class="fas fa-image text-5xl mb-3 opacity-20"></i>
                        <span class="text-sm">No QR code uploaded yet</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Upload Form -->
        <form action="<?= BASE_URL ?>admin/qrcode/save" method="POST" enctype="multipart/form-data" class="space-y-6">
            <div class="bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-900/30 rounded-2xl p-6">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Upload New QR Code</label>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Recommended size: 500x500px. Format: PNG, JPG.</p>

                <div class="flex items-center gap-4">
                    <div class="flex-1 relative">
                        <input type="file" name="qr_code" id="qrInput" accept="image/*" required
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                            onchange="updateFileName(this)">
                        <div class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm text-gray-500 flex items-center justify-between">
                            <span id="fileName">Choose a file...</span>
                            <i class="fas fa-upload text-blue-500"></i>
                        </div>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl transition shadow-lg shadow-blue-500/20 whitespace-nowrap">
                        Upload & Save
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function updateFileName(input) {
    const fileName = input.files[0] ? input.files[0].name : 'Choose a file...';
    document.getElementById('fileName').textContent = fileName;
}

function confirmDelete() {
    confirmAction('Are you sure you want to delete the current QR code? This will remove it from the checkout page.', function() {
        window.location.href = '<?= BASE_URL ?>admin/qrcode/delete';
    });
}
</script>
