<nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
    <a href="<?= BASE_URL ?>" class="hover:text-blue-600 transition"><?= t('home') ?></a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <a href="<?= BASE_URL ?>account/dashboard" class="hover:text-blue-600 transition"><?= t('my_account') ?></a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span class="text-gray-800 dark:text-gray-200 font-medium"><?= t('devices') ?></span>
</nav>

<div class="card p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1 flex items-center gap-2">
        <i class="fas fa-laptop text-blue-500"></i> <?= t('devices') ?>
    </h3>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6"><?= t('devices_desc') ?></p>

    <?php if (!empty($devices)): ?>
    <div class="space-y-3">
        <?php foreach ($devices as $device): ?>
        <div class="flex items-center gap-4 p-4 rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700">
            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center text-blue-600 dark:text-blue-400 flex-shrink-0">
                <i class="fas <?= $device['device_type'] === 'mobile' ? 'fa-mobile' : 'fa-laptop' ?>"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($device['device_name'] ?? 'Unknown Device') ?>
                    <?php if (!empty($device['is_current'])): ?>
                    <span class="text-xs text-green-600 dark:text-green-400 font-medium ml-2"><?= t('current_session') ?></span>
                    <?php endif; ?>
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    <?= htmlspecialchars($device['browser'] ?? '') ?> <?= !empty($device['os']) ? '&middot; ' . htmlspecialchars($device['os']) : '' ?>
                </p>
                <p class="text-xs text-gray-400"><?= t('last_active') ?>: <?= date('M d, Y g:i A', strtotime($device['last_active'])) ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-12">
        <i class="fas fa-laptop text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
        <p class="text-gray-500 dark:text-gray-400 text-sm"><?= t('no_devices') ?></p>
    </div>
    <?php endif; ?>
</div>
