<?php
$currentLang = \App\Core\Lang\Language::current();
$cart = \App\Core\Session::get('cart', []);
$count = array_sum(array_column($cart, 'quantity'));
$userId = \App\Core\Session::getUserId();
$userModel = new \App\Models\User();
$user = $userModel->findById($userId);
$prefModel = new \App\Models\UserPreference();
$prefs = $prefModel->findByUserId($userId);
$savedTheme = $prefs['theme'] ?? 'system';
$isDark = $savedTheme === 'dark' || $savedTheme === 'amoled';
?>
<!DOCTYPE html>
<html lang="<?= $currentLang ?>" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? t('my_account') . ' - E-Shop' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { colors: { gray: { 50: '#f9fafb', 100: '#f3f4f6', 200: '#e5e7eb', 300: '#d1d5db', 400: '#9ca3af', 500: '#6b7280', 600: '#4b5563', 700: '#374151', 800: '#1f2937', 900: '#111827', 950: '#030712' } } } }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        body.modal-open { overflow: hidden; }
    </style>
    <script>
        (function() {
            var saved = localStorage.getItem('theme');
            if (!saved) saved = '<?= $savedTheme ?>';
            if (saved === 'dark' || saved === 'amoled') {
                document.documentElement.classList.add('dark');
            } else if (saved === 'system') {
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.add('dark');
                }
            }
            if (saved === 'amoled') {
                document.documentElement.classList.add('bg-black');
            }
        })();
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 min-h-screen transition-colors duration-300">

    <!-- Top Bar -->
    <div class="bg-gray-900 dark:bg-gray-950 text-gray-400 text-xs border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-9">
            <div class="flex items-center gap-4">
                <span><i class="fas fa-phone mr-1"></i> +855 987654321</span>
                <span class="hidden sm:inline"><i class="fas fa-envelope mr-1"></i> kongsievmey8@gmail.com</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="#" class="hover:text-white transition"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="hover:text-white transition"><i class="fab fa-twitter"></i></a>
                <a href="#" class="hover:text-white transition"><i class="fab fa-instagram"></i></a>
                <a href="#" class="hover:text-white transition"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 gap-8">
                <div class="flex items-center gap-4">
                    <button id="sidebarToggle" class="lg:hidden p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <a href="<?= BASE_URL ?>" class="flex items-center space-x-2 flex-shrink-0">
                        <div class="bg-blue-600 text-white w-9 h-9 rounded-lg flex items-center justify-center font-bold text-lg shadow-lg shadow-blue-500/30">E</div>
                        <span class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">E-Shop</span>
                    </a>
                </div>
                <div class="flex items-center gap-2">
                    <a href="<?= BASE_URL ?>shop" class="hidden sm:flex items-center gap-1.5 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-lg transition">
                        <i class="fas fa-store"></i><span><?= t('shop') ?></span>
                    </a>
                    <a href="<?= BASE_URL ?>cart" class="relative p-2.5 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-lg transition" title="<?= t('cart') ?>">
                        <i class="fas fa-shopping-cart text-lg"></i>
                        <?php if ($count > 0): ?>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] w-4.5 h-4.5 rounded-full flex items-center justify-center font-bold ring-2 ring-white dark:ring-gray-900"><?= $count ?></span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="flex max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 gap-6">

        <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"></div>

        <?php
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $basePath = parse_url(BASE_URL, PHP_URL_PATH);
            if ($basePath && strpos($uri, $basePath) === 0) {
                $uri = substr($uri, strlen(rtrim($basePath, '/')));
            }
            $parts = explode('/', trim($uri, '/'));
            $currentSection = $parts[1] ?? 'dashboard';
        ?>
        <?php require APP_PATH . 'Views/customer/account/partials/sidebar.php'; ?>

        <main class="flex-1 min-w-0">
            <?= $content ?>
        </main>
    </div>

    <div id="toastContainer" class="fixed bottom-6 right-6 z-50 flex flex-col gap-2 max-w-sm w-full pointer-events-none px-4"></div>

    <script>
        function showToast(message, type) {
            type = type || 'success';
            var c = document.getElementById('toastContainer');
            if (!c) return;
            var t = document.createElement('div');
            t.className = 'flex items-center gap-2 px-4 py-3 rounded-xl shadow-lg text-sm font-medium text-white';
            t.style.background = type === 'error' ? '#ef4444' : type === 'info' ? '#3b82f6' : '#22c55e';
            t.innerHTML = '<i class="fas fa-' + (type === 'error' ? 'exclamation-circle' : type === 'info' ? 'info-circle' : 'check-circle') + '"></i> ' + message;
            c.appendChild(t);
            setTimeout(function() { t.style.opacity = '0'; t.style.transition = 'all 0.3s'; setTimeout(function() { t.remove(); }, 300); }, 2600);
        }
        <?php if (\App\Core\Session::hasFlash('success')): ?>
            showToast('<?= \App\Core\Session::getFlash('success') ?>', 'success');
        <?php endif; ?>
        <?php if (\App\Core\Session::hasFlash('error')): ?>
            showToast('<?= \App\Core\Session::getFlash('error') ?>', 'error');
        <?php endif; ?>
        <?php if (\App\Core\Session::hasFlash('info')): ?>
            showToast('<?= \App\Core\Session::getFlash('info') ?>', 'info');
        <?php endif; ?>
    </script>
</body>
</html>
