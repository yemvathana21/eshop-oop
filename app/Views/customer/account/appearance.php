<?php
$currentSection = 'appearance';
?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <?php include __DIR__ . '/partials/sidebar.php'; ?>

    <div class="lg:pl-[300px]">
        <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
            <a href="<?= BASE_URL ?>account/dashboard" class="hover:text-blue-600 dark:hover:text-blue-400 transition"><?= t('account_center') ?></a>
            <i class="fas fa-chevron-right text-[10px]"></i>
            <span class="text-gray-900 dark:text-gray-100 font-medium"><?= t('appearance') ?></span>
        </nav>

        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6"><?= t('appearance') ?></h1>

        <form action="<?= BASE_URL ?>account/appearance/save" method="POST" class="space-y-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4"><?= t('theme') ?></h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <?php
                    $themes = [
                        'light' => ['icon' => 'fa-sun', 'label' => t('light'), 'desc' => 'Light mode'],
                        'dark' => ['icon' => 'fa-moon', 'label' => t('dark'), 'desc' => 'Dark mode'],
                        'system' => ['icon' => 'fa-circle-half-stroke', 'label' => t('system'), 'desc' => 'Follow system'],
                    ];
                    foreach ($themes as $val => $tData):
                        $checked = ($prefs['theme'] ?? 'light') === $val;
                    ?>
                    <label class="relative cursor-pointer">
                        <input type="radio" name="theme" value="<?= $val ?>" <?= $checked ? 'checked' : '' ?> class="sr-only peer">
                        <div class="p-4 rounded-xl border-2 transition-all <?= $checked ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600' ?>">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-lg <?= $checked ? 'text-blue-600' : 'text-gray-400' ?>">
                                    <i class="fas <?= $tData['icon'] ?>"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-sm text-gray-900 dark:text-white"><?= $tData['label'] ?></p>
                                    <p class="text-xs text-gray-500"><?= $tData['desc'] ?></p>
                                </div>
                            </div>
                            <div class="flex gap-1.5">
                                <div class="h-2 flex-1 rounded-full bg-gray-200 dark:bg-gray-600"></div>
                                <div class="h-2 flex-1 rounded-full bg-gray-300 dark:bg-gray-500"></div>
                                <div class="h-2 flex-1 rounded-full bg-gray-400 dark:bg-gray-400"></div>
                            </div>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4"><?= t('language') ?></h2>
                <div class="flex flex-wrap gap-4">
                    <?php
                    $langs = [
                        'en' => ['label' => 'English', 'flag' => '🇺🇸'],
                        'km' => ['label' => 'ភាសាខ្មែរ', 'flag' => '🇰🇭'],
                    ];
                    foreach ($langs as $val => $lData):
                        $checked = ($prefs['language'] ?? 'en') === $val;
                    ?>
                    <label class="relative cursor-pointer flex-1 min-w-[150px]">
                        <input type="radio" name="language" value="<?= $val ?>" <?= $checked ? 'checked' : '' ?> class="sr-only peer">
                        <div class="p-4 rounded-xl border-2 transition-all flex items-center gap-3 <?= $checked ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600' ?>">
                            <span class="text-2xl"><?= $lData['flag'] ?></span>
                            <span class="font-semibold text-sm text-gray-900 dark:text-white"><?= $lData['label'] ?></span>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4"><?= t('currency') ?></h2>
                <div class="flex flex-wrap gap-4">
                    <?php
                    $currencies = [
                        'USD' => ['symbol' => '$', 'label' => 'US Dollar'],
                        'KHR' => ['symbol' => '៛', 'label' => 'Cambodian Riel'],
                    ];
                    foreach ($currencies as $val => $cData):
                        $checked = ($prefs['currency'] ?? 'USD') === $val;
                    ?>
                    <label class="relative cursor-pointer flex-1 min-w-[150px]">
                        <input type="radio" name="currency" value="<?= $val ?>" <?= $checked ? 'checked' : '' ?> class="sr-only peer">
                        <div class="p-4 rounded-xl border-2 transition-all flex items-center gap-3 <?= $checked ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600' ?>">
                            <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-lg font-bold text-gray-600 dark:text-gray-300">
                                <?= $cData['symbol'] ?>
                            </div>
                            <div>
                                <p class="font-semibold text-sm text-gray-900 dark:text-white"><?= $cData['label'] ?></p>
                                <p class="text-xs text-gray-500"><?= $val ?></p>
                            </div>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition shadow-sm">
                    <?= t('save') ?>
                </button>
            </div>
        </form>
    </div>
</div>
