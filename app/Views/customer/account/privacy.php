<?php
$currentSection = 'privacy';
?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <?php include __DIR__ . '/partials/sidebar.php'; ?>

    <div class="lg:pl-[300px]">
        <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
            <a href="<?= BASE_URL ?>account/dashboard" class="hover:text-blue-600 dark:hover:text-blue-400 transition"><?= t('account_center') ?></a>
            <i class="fas fa-chevron-right text-[10px]"></i>
            <span class="text-gray-900 dark:text-gray-100 font-medium"><?= t('privacy') ?></span>
        </nav>

        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6"><?= t('privacy_settings') ?></h1>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white text-sm"><?= t('show_email_profile') ?></h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Show your email on your public profile</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" name="show_email" value="1" <?= ($user['show_email'] ?? 0) ? 'checked' : '' ?>>
                        <div class="w-11 h-6 bg-gray-200 dark:bg-gray-600 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
                <hr class="border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white text-sm"><?= t('show_order_history') ?></h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Allow others to see your order history</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" name="show_orders" value="1" <?= ($user['show_orders'] ?? 0) ? 'checked' : '' ?>>
                        <div class="w-11 h-6 bg-gray-200 dark:bg-gray-600 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2"><?= t('download_data') ?></h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Download a copy of your personal data and order history.</p>
            <a href="<?= BASE_URL ?>account/download-data" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-medium rounded-lg transition">
                <i class="fas fa-download"></i> <?= t('download_data') ?>
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-red-200 dark:border-red-900/50 p-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-red-500">
                    <i class="fas fa-triangle-exclamation"></i>
                </div>
                <h2 class="text-lg font-semibold text-red-600 dark:text-red-400"><?= t('delete_account') ?></h2>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4"><?= t('delete_account_warning') ?></p>
            <form action="<?= BASE_URL ?>account/delete-account" method="POST" onsubmit="return confirm('<?= t('delete_account') ?>?')">
                <div class="max-w-md">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('enter_password_confirm') ?></label>
                    <div class="flex gap-3">
                        <input type="password" name="password" required placeholder="<?= t('enter_password_confirm') ?>" class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none">
                        <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition whitespace-nowrap">
                            <?= t('confirm_delete') ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
