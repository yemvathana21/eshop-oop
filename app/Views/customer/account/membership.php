<nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
    <a href="<?= BASE_URL ?>" class="hover:text-blue-600 transition"><?= t('home') ?></a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <a href="<?= BASE_URL ?>account/dashboard" class="hover:text-blue-600 transition"><?= t('my_account') ?></a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span class="text-gray-800 dark:text-gray-200 font-medium"><?= t('membership') ?></span>
</nav>

<div class="card p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
        <i class="fas fa-circle-info text-blue-500"></i> <?= t('membership_info') ?>
    </h3>

    <!-- Membership Level -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl p-6 mb-6 border border-blue-100 dark:border-blue-900/30">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-blue-600 dark:text-blue-400 font-medium uppercase tracking-wide"><?= t('membership') ?></p>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= t('free') ?></h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?= t('member_since') ?> <?= date('F Y', strtotime($user['created_at'])) ?></p>
            </div>
            <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center">
                <i class="fas fa-crown text-2xl text-blue-600 dark:text-blue-400"></i>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl text-center">
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400"><?= (int)$orderCount ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><?= t('total_orders') ?></p>
        </div>
        <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-xl text-center">
            <p class="text-2xl font-bold text-green-600 dark:text-green-400">$<?= number_format((float)$totalSpent, 2) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><?= t('total_spent') ?></p>
        </div>
        <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl text-center">
            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400"><?= t('free') ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><?= t('membership') ?></p>
        </div>
        <div class="p-4 bg-orange-50 dark:bg-orange-900/20 rounded-xl text-center">
            <p class="text-2xl font-bold text-orange-600 dark:text-orange-400"><?= date('M Y', strtotime($user['created_at'])) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><?= t('member_since') ?></p>
        </div>
    </div>
</div>
