<?php
$currentLang = \App\Core\Lang\Language::current();
$settingModel = new \App\Models\Setting\Setting();
$siteSettings = $settingModel->all();
?>
<!DOCTYPE html>
<html lang="<?= $currentLang ?>" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'General Online Store' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        (function() {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (saved === 'dark' || (!saved && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-950 min-h-screen flex items-center justify-center transition-colors">
    <div class="w-full max-w-md p-8">
        <div class="text-center mb-8">
            <a href="<?= BASE_URL ?>" class="inline-flex items-center space-x-2">
                <div class="bg-blue-600 text-white w-12 h-12 rounded-xl flex items-center justify-center font-bold text-2xl">G</div>
                <span class="text-3xl font-bold text-gray-800 dark:text-white"><?= htmlspecialchars($siteSettings['store_name'] ?? 'General Online Store') ?></span>
            </a>
        </div>

        <?php if (App\Core\Session::hasFlash('success')): ?>
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 text-green-800 dark:text-green-300 px-4 py-3 rounded-lg mb-4 text-sm">
            <i class="fas fa-check-circle mr-2"></i><?= App\Core\Session::getFlash('success') ?>
        </div>
        <?php endif; ?>

        <?php if (App\Core\Session::hasFlash('error')): ?>
        <div class="bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-300 px-4 py-3 rounded-lg mb-4 text-sm">
            <i class="fas fa-exclamation-circle mr-2"></i><?= App\Core\Session::getFlash('error') ?>
        </div>
        <?php endif; ?>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 transition-colors">
            <?= $content ?>
        </div>

        <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-6">
            <a href="<?= BASE_URL ?>" class="text-blue-600 dark:text-blue-400 hover:underline"><i class="fas fa-arrow-left mr-1"></i><?= t('back_to_shop') ?></a>
        </p>
    </div>
</body>
</html>
