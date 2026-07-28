<?php
$currentSection = 'connected';
?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <?php include __DIR__ . '/partials/sidebar.php'; ?>

    <div class="lg:pl-[300px]">
        <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
            <a href="<?= BASE_URL ?>account/dashboard" class="hover:text-blue-600 dark:hover:text-blue-400 transition"><?= t('account_center') ?></a>
            <i class="fas fa-chevron-right text-[10px]"></i>
            <span class="text-gray-900 dark:text-gray-100 font-medium"><?= t('connected_accounts') ?></span>
        </nav>

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= t('connected_accounts') ?></h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Connect your social accounts for easier login.</p>
        </div>

        <?php
        $connectedMap = [];
        foreach ($connectedAccounts as $acct) {
            $connectedMap[$acct['provider']] = $acct;
        }
        $providers = [
            ['key' => 'google', 'icon' => 'fab fa-google', 'name' => 'Google'],
            ['key' => 'facebook', 'icon' => 'fab fa-facebook', 'name' => 'Facebook'],
            ['key' => 'telegram', 'icon' => 'fab fa-telegram', 'name' => 'Telegram'],
            ['key' => 'apple', 'icon' => 'fab fa-apple', 'name' => 'Apple'],
            ['key' => 'github', 'icon' => 'fab fa-github', 'name' => 'GitHub'],
        ];
        ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <?php foreach ($providers as $provider):
                $acct = $connectedMap[$provider['key']] ?? null;
                $connected = $acct !== null;
            ?>
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-xl <?= $connected ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400' ?>">
                        <i class="<?= $provider['icon'] ?>"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white text-sm"><?= $provider['name'] ?></h3>
                        <?php if ($connected): ?>
                            <div class="flex items-center gap-2 mt-1">
                                <?php if (!empty($acct['avatar_url'])): ?>
                                    <img src="<?= htmlspecialchars($acct['avatar_url']) ?>" alt="" class="w-5 h-5 rounded-full">
                                <?php endif; ?>
                                <span class="text-sm text-gray-500 dark:text-gray-400"><?= htmlspecialchars($acct['email']) ?></span>
                            </div>
                            <span class="text-xs text-green-500 font-medium"><?= t('connected') ?> &middot; <?= date('M j, Y', strtotime($acct['connected_at'])) ?></span>
                        <?php else: ?>
                            <span class="text-xs text-gray-400 dark:text-gray-500"><?= t('not_connected') ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if ($connected): ?>
                    <a href="<?= BASE_URL ?>account/connected/delete?id=<?= $acct['id'] ?>" onclick="return confirm('<?= t('disconnect') ?>?')" class="px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition whitespace-nowrap">
                        <?= t('disconnect') ?>
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>account/connect/<?= $provider['key'] ?>" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition whitespace-nowrap">
                        <?= t('connect') ?>
                    </a>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
