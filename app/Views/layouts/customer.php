<?php
$navCategories = [];
$navTree = [];
if (class_exists('App\Models\Category')) {
    $catModel = new \App\Models\Category();
    $navCategories = $catModel->all();
    $navTree = $catModel->getTree();
}
$currentLang = \App\Core\Lang\Language::current();
?>
<!DOCTYPE html>
<html lang="<?= $currentLang ?>" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'E-Shop' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        gray: {
                            50: '#f9fafb', 100: '#f3f4f6', 200: '#e5e7eb', 300: '#d1d5db',
                            400: '#9ca3af', 500: '#6b7280', 600: '#4b5563', 700: '#374151',
                            800: '#1f2937', 900: '#111827', 950: '#030712'
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    <script>
        (function() {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (saved === 'dark' || (!saved && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 min-h-screen flex flex-col transition-colors duration-300">
    <!-- Top Bar -->
    <div class="bg-gray-900 dark:bg-gray-950 text-gray-400 text-xs border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-9">
            <div class="flex items-center gap-4">
                <span><i class="fas fa-truck mr-1"></i> <?= t('free_shipping') ?></span>
                <span class="hidden sm:inline"><i class="fas fa-headset mr-1"></i> <?= t('support_247') ?></span>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden sm:inline text-gray-500"><i class="fas fa-clock mr-1"></i> <?= t('delivery_time') ?></span>
            </div>
        </div>
    </div>

    <!-- Main Nav -->
    <nav class="bg-white dark:bg-gray-900 shadow-sm border-b border-gray-100 dark:border-gray-800 sticky top-0 z-50 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="<?= BASE_URL ?>" class="flex items-center space-x-2 flex-shrink-0">
                    <div class="bg-blue-600 text-white w-9 h-9 rounded-lg flex items-center justify-center font-bold text-lg">E</div>
                    <span class="text-xl font-bold text-gray-900 dark:text-white">E-Shop</span>
                </a>

                <!-- Desktop Nav Links -->
                <div class="hidden lg:flex items-center gap-1">
                    <a href="<?= BASE_URL ?>" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-lg transition"><?= t('home') ?></a>
                    <div class="relative group">
                        <a href="<?= BASE_URL ?>shop" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-lg transition inline-flex items-center gap-1">
                            <?= t('shop') ?> <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                        </a>
                        <div class="absolute top-full left-0 w-64 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <a href="<?= BASE_URL ?>shop" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                <i class="fas fa-grid-2 text-xs w-5 text-center text-gray-400"></i> <?= t('all_products') ?>
                            </a>
                            <?php foreach ($navTree as $cat): ?>
                            <div class="relative group/sub">
                                <a href="<?= BASE_URL ?>shop?category=<?= htmlspecialchars($cat['slug']) ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                    <i class="fas <?= htmlspecialchars($cat['icon']) ?> text-xs w-5 text-center text-gray-400"></i> <?= htmlspecialchars($cat['name']) ?>
                                    <?php if (!empty($cat['children'])): ?>
                                        <i class="fas fa-chevron-right text-[10px] text-gray-400 ml-auto"></i>
                                    <?php else: ?>
                                        <span class="ml-auto text-xs text-gray-400"><?= $cat['product_count'] ?></span>
                                    <?php endif; ?>
                                </a>
                                <?php if (!empty($cat['children'])): ?>
                                <div class="absolute top-full left-full w-48 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 py-2 opacity-0 invisible group-hover/sub:opacity-100 group-hover/sub:visible transition-all duration-200 z-50">
                                    <?php foreach ($cat['children'] as $child): ?>
                                    <a href="<?= BASE_URL ?>shop?category=<?= htmlspecialchars($child['slug']) ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                        <i class="fas <?= htmlspecialchars($child['icon']) ?> text-xs w-5 text-center text-gray-400"></i> <?= htmlspecialchars($child['name']) ?>
                                        <span class="ml-auto text-xs text-gray-400"><?= $child['product_count'] ?></span>
                                    </a>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Right Actions -->
                <div class="hidden lg:flex items-center gap-2">
                    <!-- Language Switcher -->
                    <div class="relative group">
                        <button class="flex items-center gap-1.5 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-lg transition" title="<?= t('language') ?>">
                            <i class="fas fa-globe text-sm"></i>
                            <?= strtoupper($currentLang) ?>
                            <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                        </button>
                        <div class="absolute top-full right-0 mt-1 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 py-1 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 min-w-[130px]">
                            <a href="<?= BASE_URL ?>lang?lang=en" class="flex items-center gap-2 px-3 py-2 text-sm <?= $currentLang === 'en' ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' ?> transition">
                                <span>🇺🇸</span> <?= t('english') ?>
                            </a>
                            <a href="<?= BASE_URL ?>lang?lang=km" class="flex items-center gap-2 px-3 py-2 text-sm <?= $currentLang === 'km' ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' ?> transition">
                                <span>🇰🇭</span> <?= t('khmer') ?>
                            </a>
                        </div>
                    </div>

                    <div class="w-px h-6 bg-gray-200 dark:bg-gray-700"></div>

                    <!-- Dark Mode Toggle -->
                    <button id="darkToggle" class="p-2.5 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-lg transition" title="<?= t('toggle_dark_mode') ?>">
                        <i class="fas fa-moon text-lg dark:hidden"></i>
                        <i class="fas fa-sun text-lg hidden dark:inline"></i>
                    </button>

                    <a href="<?= BASE_URL ?>cart" class="relative p-2.5 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-lg transition" title="<?= t('cart') ?>">
                        <i class="fas fa-shopping-cart text-lg"></i>
                        <?php
                        $cart = \App\Core\Session::get('cart', []);
                        $count = array_sum(array_column($cart, 'quantity'));
                        if ($count > 0):
                        ?>
                        <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold"><?= $count ?></span>
                        <?php endif; ?>
                    </a>

                    <?php if (\App\Core\Session::isLoggedIn('customer')): ?>
                        <div class="relative group">
                            <button class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition">
                                <div class="w-7 h-7 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center text-xs font-bold">
                                    <?= strtoupper(substr(\App\Core\Session::getUserName('customer'), 0, 1) ?: 'U') ?>
                                </div>
                                <span class="max-w-[100px] truncate"><?= htmlspecialchars(\App\Core\Session::getUserName('customer')) ?></span>
                                <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                            </button>
                            <div class="absolute top-full right-0 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <a href="<?= BASE_URL ?>my-orders" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                    <i class="fas fa-receipt w-4 text-center text-gray-400"></i> <?= t('my_orders') ?>
                                </a>
                                <hr class="my-1 border-gray-100 dark:border-gray-700">
                                <a href="<?= BASE_URL ?>logout" class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                                    <i class="fas fa-right-from-bracket w-4 text-center"></i> <?= t('logout') ?>
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>login" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 px-3 py-2 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-lg transition"><?= t('login') ?></a>
                        <a href="<?= BASE_URL ?>register" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition"><?= t('register') ?></a>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center gap-2 lg:hidden">
                    <button id="darkToggleMobile" class="p-2 text-gray-600 dark:text-gray-400">
                        <i class="fas fa-moon dark:hidden"></i>
                        <i class="fas fa-sun hidden dark:inline"></i>
                    </button>
                    <a href="<?= BASE_URL ?>cart" class="relative p-2 text-gray-600 dark:text-gray-400">
                        <i class="fas fa-shopping-cart"></i>
                        <?php if ($count > 0): ?>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center font-bold text-[10px]"><?= $count ?></span>
                        <?php endif; ?>
                    </a>
                    <button id="mobileMenuBtn" class="p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden lg:hidden border-t border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900">
            <div class="px-4 py-3 space-y-1">
                <a href="<?= BASE_URL ?>" class="block py-2.5 px-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg text-sm font-medium transition"><?= t('home') ?></a>
                <a href="<?= BASE_URL ?>shop" class="block py-2.5 px-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg text-sm font-medium transition"><?= t('shop_all') ?></a>
                <hr class="my-2 border-gray-100 dark:border-gray-800">
                <p class="text-xs text-gray-400 uppercase tracking-wider px-3 py-1"><?= t('categories') ?></p>
                <?php foreach ($navTree as $cat): ?>
                <div class="mobile-cat-group">
                    <div class="flex items-center justify-between">
                        <a href="<?= BASE_URL ?>shop?category=<?= htmlspecialchars($cat['slug']) ?>" class="flex items-center gap-3 py-2 px-3 text-gray-600 dark:text-gray-400 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg text-sm transition flex-1">
                            <i class="fas <?= htmlspecialchars($cat['icon']) ?> text-xs w-5 text-center text-gray-400"></i> <?= htmlspecialchars($cat['name']) ?>
                        </a>
                        <?php if (!empty($cat['children'])): ?>
                        <button onclick="this.parentElement.nextElementSibling.classList.toggle('hidden'); this.querySelector('i').classList.toggle('fa-chevron-down'); this.querySelector('i').classList.toggle('fa-chevron-up');" class="px-3 py-2 text-gray-400 hover:text-blue-500">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($cat['children'])): ?>
                    <div class="hidden pl-8 space-y-1 pb-1">
                        <?php foreach ($cat['children'] as $child): ?>
                        <a href="<?= BASE_URL ?>shop?category=<?= htmlspecialchars($child['slug']) ?>" class="flex items-center gap-3 py-1.5 px-3 text-gray-500 dark:text-gray-500 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg text-sm transition">
                            <i class="fas <?= htmlspecialchars($child['icon']) ?> text-xs w-5 text-center text-gray-400"></i> <?= htmlspecialchars($child['name']) ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
                <hr class="my-2 border-gray-100 dark:border-gray-800">
                <div class="flex items-center justify-between px-3 py-2">
                    <span class="text-sm text-gray-500 dark:text-gray-400"><?= t('language') ?></span>
                    <div class="flex gap-1">
                        <a href="<?= BASE_URL ?>lang?lang=en" class="px-2 py-1 text-xs rounded <?= $currentLang === 'en' ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-400 font-bold' : 'text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800' ?> transition">EN</a>
                        <a href="<?= BASE_URL ?>lang?lang=km" class="px-2 py-1 text-xs rounded <?= $currentLang === 'km' ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-400 font-bold' : 'text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800' ?> transition">KM</a>
                    </div>
                </div>
                <a href="<?= BASE_URL ?>cart" class="flex items-center justify-between py-2.5 px-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg text-sm font-medium transition">
                    <span><i class="fas fa-shopping-cart mr-2"></i><?= t('cart') ?></span>
                    <?php if ($count > 0): ?>
                    <span class="bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold"><?= $count ?></span>
                    <?php endif; ?>
                </a>
                <?php if (\App\Core\Session::isLoggedIn('customer')): ?>
                    <a href="<?= BASE_URL ?>my-orders" class="block py-2.5 px-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg text-sm font-medium transition"><i class="fas fa-receipt mr-2"></i><?= t('my_orders') ?></a>
                    <a href="<?= BASE_URL ?>logout" class="block py-2.5 px-3 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg text-sm font-medium transition"><i class="fas fa-right-from-bracket mr-2"></i><?= t('logout') ?></a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>login" class="block py-2.5 px-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg text-sm font-medium transition"><i class="fas fa-right-to-bracket mr-2"></i><?= t('login') ?></a>
                    <a href="<?= BASE_URL ?>register" class="block py-2.5 px-3 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-lg text-sm font-medium transition"><i class="fas fa-user-plus mr-2"></i><?= t('register') ?></a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Category Bar -->
    <div class="bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 transition-colors hidden lg:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-1 overflow-x-auto py-2 scrollbar-hide">
                <a href="<?= BASE_URL ?>shop" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-full transition whitespace-nowrap <?= empty($_GET['category']) ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' ?>">
                    <i class="fas fa-grid-2"></i> <?= t('all_products') ?>
                </a>
                <?php
                $activeParentId = null;
                if (!empty($_GET['category'])) {
                    foreach ($navTree as $pc) {
                        if ($pc['slug'] === $_GET['category']) {
                            $activeParentId = $pc['id'];
                            break;
                        }
                        if (!empty($pc['children'])) {
                            foreach ($pc['children'] as $ch) {
                                if ($ch['slug'] === $_GET['category']) {
                                    $activeParentId = $pc['id'];
                                    break 2;
                                }
                            }
                        }
                    }
                }
                foreach ($navTree as $cat):
                    $isActive = (isset($_GET['category']) && $_GET['category'] === $cat['slug']) || $activeParentId === $cat['id'];
                ?>
                <a href="<?= BASE_URL ?>shop?category=<?= htmlspecialchars($cat['slug']) ?>" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-full transition whitespace-nowrap <?= $isActive && !empty($cat['children']) ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-bold' : ((isset($_GET['category']) && $_GET['category'] === $cat['slug']) ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '') ?>">
                    <i class="fas <?= htmlspecialchars($cat['icon']) ?>"></i> <?= htmlspecialchars($cat['name']) ?>
                </a>
                <?php
                    if ($activeParentId === $cat['id'] && !empty($cat['children'])) {
                        foreach ($cat['children'] as $child) {
                            $childActive = isset($_GET['category']) && $_GET['category'] === $child['slug'];
                ?>
                <a href="<?= BASE_URL ?>shop?category=<?= htmlspecialchars($child['slug']) ?>" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-full transition whitespace-nowrap <?= $childActive ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400' : '' ?>">
                    <i class="fas <?= htmlspecialchars($child['icon']) ?>"></i> <?= htmlspecialchars($child['name']) ?>
                </a>
                <?php
                        }
                    }
                endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" class="fixed top-4 right-4 z-50 flex flex-col gap-3 max-w-sm"></div>

    <main class="flex-1">
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 dark:bg-gray-950 text-gray-300 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="col-span-2 md:col-span-1">
                    <a href="<?= BASE_URL ?>" class="flex items-center space-x-2 mb-4">
                        <div class="bg-blue-600 text-white w-9 h-9 rounded-lg flex items-center justify-center font-bold text-lg">E</div>
                        <span class="text-xl font-bold text-white">E-Shop</span>
                    </a>
                    <p class="text-sm text-gray-400 leading-relaxed"><?= t('footer_tagline') ?></p>
                    <div class="flex gap-3 mt-4">
                        <a href="#" class="w-9 h-9 bg-white/10 hover:bg-blue-600 rounded-lg flex items-center justify-center transition text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="w-9 h-9 bg-white/10 hover:bg-blue-400 rounded-lg flex items-center justify-center transition text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="w-9 h-9 bg-white/10 hover:bg-pink-600 rounded-lg flex items-center justify-center transition text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-semibold text-sm mb-4"><?= t('quick_links') ?></h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="<?= BASE_URL ?>" class="hover:text-white transition"><?= t('home') ?></a></li>
                        <li><a href="<?= BASE_URL ?>shop" class="hover:text-white transition"><?= t('shop') ?></a></li>
                        <li><a href="<?= BASE_URL ?>cart" class="hover:text-white transition"><?= t('cart') ?></a></li>
                        <li><a href="<?= BASE_URL ?>my-orders" class="hover:text-white transition"><?= t('my_orders') ?></a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold text-sm mb-4"><?= t('categories') ?></h4>
                    <ul class="space-y-2.5 text-sm">
                        <?php foreach (array_slice($navTree, 0, 5) as $cat): ?>
                        <li><a href="<?= BASE_URL ?>shop?category=<?= htmlspecialchars($cat['slug']) ?>" class="hover:text-white transition"><?= htmlspecialchars($cat['name']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold text-sm mb-4"><?= t('contact') ?></h4>
                    <ul class="space-y-2.5 text-sm">
                        <li class="flex items-center gap-2"><i class="fas fa-envelope text-gray-500 text-xs w-4"></i> support@eshop.com</li>
                        <li class="flex items-center gap-2"><i class="fas fa-phone text-gray-500 text-xs w-4"></i> +1 (555) 123-4567</li>
                        <li class="flex items-center gap-2"><i class="fas fa-location-dot text-gray-500 text-xs w-4"></i> <?= t('address') ?></li>
                    </ul>
                </div>
            </div>
            <hr class="border-gray-800 my-8">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 text-sm text-gray-500">
                <p>&copy; <?= date('Y') ?> E-Shop. <?= t('all_rights_reserved') ?></p>
                <div class="flex items-center gap-4">
                    <span class="flex items-center gap-1"><i class="fab fa-cc-visa text-lg"></i></span>
                    <span class="flex items-center gap-1"><i class="fab fa-cc-mastercard text-lg"></i></span>
                    <span class="flex items-center gap-1"><i class="fab fa-cc-paypal text-lg"></i></span>
                    <span class="flex items-center gap-1"><i class="fab fa-cc-stripe text-lg"></i></span>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu
        document.getElementById('mobileMenuBtn')?.addEventListener('click', function() {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        });

        // Dark mode toggle
        function toggleDark() {
            const html = document.documentElement;
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        }
        document.getElementById('darkToggle')?.addEventListener('click', toggleDark);
        document.getElementById('darkToggleMobile')?.addEventListener('click', toggleDark);

        // Toast Notification System
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            
            toast.className = `${bgColor} text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-3 transform translate-x-full opacity-0 transition-all duration-300`;
            toast.innerHTML = `
                <i class="fas ${icon} text-lg"></i>
                <span class="flex-1 font-medium">${message}</span>
                <button onclick="this.parentElement.remove()" class="text-white/80 hover:text-white ml-2">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            container.appendChild(toast);
            
            requestAnimationFrame(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
                toast.classList.add('translate-x-0', 'opacity-100');
            });
            
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        // Show flash messages as toasts
        <?php if (\App\Core\Session::hasFlash('success')): ?>
            showToast('<?= \App\Core\Session::getFlash('success') ?>', 'success');
        <?php endif; ?>
        <?php if (\App\Core\Session::hasFlash('error')): ?>
            showToast('<?= \App\Core\Session::getFlash('error') ?>', 'error');
        <?php endif; ?>
    </script>
</body>
</html>
