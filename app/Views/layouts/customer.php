<?php
$navCategories = [];
$navTree = [];
$catModel = new \App\Models\Product\Category();
$navCategories = $catModel->all();
$navTree = $catModel->getTree();
$currentLang = \App\Core\Lang\Language::current();
$userId = \App\Core\Session::getUserId();
$cartKey = $userId ? 'cart_' . $userId : 'cart_guest';
$cart = \App\Core\Session::get($cartKey, []);
$count = array_sum(array_column($cart, 'quantity'));

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

    <!-- Header Section -->
    <header class="sticky top-0 z-50 transition-colors duration-300 border-none">
        <div class="bg-white dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20 gap-8">
                <!-- Logo -->
                <a href="<?= BASE_URL ?>" class="flex items-center space-x-2 flex-shrink-0">
                    <div class="bg-blue-600 text-white w-10 h-10 rounded-lg flex items-center justify-center font-bold text-xl">G</div>
                    <span class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight"><?= htmlspecialchars($siteSettings['store_name'] ?? 'General Online Store') ?></span>
                </a>

                <!-- Search Bar -->
                <div class="hidden md:flex flex-1 max-w-xl mx-4">
                    <form action="<?= BASE_URL ?>shop" method="GET" class="relative w-full group">
                        <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                            placeholder="Search Product"
                            class="w-full bg-gray-100 dark:bg-gray-800 border-none rounded-xl py-2.5 pl-4 pr-12 text-sm focus:ring-2 focus:ring-blue-500 transition-all">
                        <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-blue-600 text-white p-1.5 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-search text-xs"></i>
                        </button>
                    </form>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-2">
                    <!-- Language Switcher -->
                    <div class="relative group hidden sm:block">
                        <button class="flex items-center gap-1.5 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-lg transition" title="<?= t('language') ?>">
                            <i class="fas fa-globe text-sm"></i>
                            <?= strtoupper($currentLang) ?>
                            <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                        </button>
                        <div class="absolute top-full right-0 mt-1 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 py-1 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 min-w-[130px]">
                            <a href="<?= BASE_URL ?>lang?lang=en" class="flex items-center gap-2 px-3 py-2 text-sm <?= $currentLang === 'en' ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' ?> transition">
                                <span>🇺🇸</span> <?= t('english') ?>
                            </a>
                            <a href="<?= BASE_URL ?>lang?lang=km" class="flex items-center gap-2 px-3 py-2 text-sm <?= $currentLang === 'km' ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' ?> transition">
                                <span>🇰🇭</span> <?= t('khmer') ?>
                            </a>
                        </div>
                    </div>

                    <div class="hidden sm:block w-px h-6 bg-gray-200 dark:bg-gray-700 mx-1"></div>

                    <!-- Dark Mode Toggle -->
                    <button id="darkToggle" class="p-2.5 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-lg transition" title="<?= t('toggle_dark_mode') ?>">
                        <i class="fas fa-moon text-lg dark:hidden"></i>
                        <i class="fas fa-sun text-lg hidden dark:inline"></i>
                    </button>

                    <a href="<?= BASE_URL ?>cart" class="relative p-2.5 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-lg transition flex items-center gap-2" title="<?= t('cart') ?>">
                        <i class="fas fa-shopping-cart text-lg"></i>
                        <span class="text-sm font-bold hidden sm:inline">Cart ($<?= number_format(array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart)), 2) ?>)</span>
                        <?php if ($count > 0): ?>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] w-4 h-4 rounded-full flex items-center justify-center ring-2 ring-white dark:ring-gray-900"><?= $count ?></span>
                        <?php endif; ?>
                    </a>

                    <?php if (\App\Core\Session::isLoggedIn('customer')): ?>
                        <?php
                        $cmModel = new \App\Models\Contact\ContactMessage();
                        $unreadReplies = $cmModel->countUnreadRepliesForCustomer($userId, \App\Core\Session::get('customer_user_email'));
                        ?>
                        <div class="relative group">
                            <button class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition relative">
                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center text-xs font-bold">
                                    <?= strtoupper(substr(\App\Core\Session::getUserName('customer'), 0, 1) ?: 'U') ?>
                                </div>
                                <span class="max-w-[100px] truncate hidden lg:inline"><?= htmlspecialchars(\App\Core\Session::getUserName('customer')) ?></span>
                                <?php if ($unreadReplies > 0): ?>
                                    <span class="absolute top-1 right-1 w-2 h-2 bg-blue-600 rounded-full ring-2 ring-white dark:ring-gray-900"></span>
                                <?php endif; ?>
                            </button>
                            <div class="absolute top-full right-0 w-48 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <a href="<?= BASE_URL ?>account/dashboard" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                    <i class="fas fa-user-circle w-4 text-center text-gray-400"></i> <?= t('my_account') ?>
                                </a>
                                <a href="<?= BASE_URL ?>account/messages" class="flex items-center justify-between px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-envelope w-4 text-center text-gray-400"></i> <?= t('customer_messages') ?>
                                    </div>
                                    <?php if ($unreadReplies > 0): ?>
                                        <span class="bg-blue-600 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full"><?= $unreadReplies ?></span>
                                    <?php endif; ?>
                                </a>
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
                        <div class="hidden lg:flex items-center gap-2 ml-2">
                            <a href="<?= BASE_URL ?>login" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 px-3 py-2 transition"><?= t('login') ?></a>
                            <a href="<?= BASE_URL ?>register" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition shadow-sm shadow-blue-500/20"><?= t('register') ?></a>
                        </div>
                    <?php endif; ?>

                    <!-- Mobile Menu Button -->
                    <button id="mobileMenuBtn" class="lg:hidden p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Navigation Bar (Desktop) -->
        <div class="bg-slate-900 text-white hidden lg:block border-none relative z-10 mt-[-1px]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-12">
                    <div class="flex items-center gap-0">
                        <a href="<?= BASE_URL ?>" class="px-5 py-3.5 text-[11px] font-bold uppercase tracking-widest hover:bg-slate-800 transition duration-300"><?= t('home') ?></a>

                        <?php foreach ($navTree as $tcat): ?>
                        <div class="group static">
                            <a href="<?= BASE_URL ?>shop?category=<?= htmlspecialchars($tcat['slug']) ?>"
                               class="px-5 py-3.5 text-[11px] font-bold uppercase tracking-widest hover:bg-slate-800 transition duration-300 inline-flex items-center gap-1.5">
                                <?= htmlspecialchars($tcat['name']) ?>
                            </a>

                            <?php if (!empty($tcat['children'])): ?>
                            <div class="absolute left-0 right-0 w-full bg-[#f9f9f9] text-gray-800 py-10 px-8 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                                <div class="max-w-7xl mx-auto grid grid-cols-4 xl:grid-cols-5 gap-y-10 gap-x-12">
                                    <?php foreach ($tcat['children'] as $mcat): ?>
                                    <div class="space-y-4">
                                        <a href="<?= BASE_URL ?>shop?category=<?= htmlspecialchars($mcat['slug']) ?>" class="block font-bold text-gray-900 uppercase tracking-widest text-[11px] pb-2 mb-2 hover:text-blue-600 transition">
                                            <?= htmlspecialchars($mcat['name']) ?>
                                        </a>
                                        <ul class="space-y-2">
                                            <?php foreach ($mcat['children'] as $ecat): ?>
                                            <li>
                                                <a href="<?= BASE_URL ?>shop?category=<?= htmlspecialchars($ecat['slug']) ?>" class="text-[13px] text-gray-600 hover:text-blue-600 transition block">
                                                    <?= htmlspecialchars($ecat['name']) ?>
                                                </a>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>

                        <a href="<?= BASE_URL ?>about" class="px-5 py-3.5 text-[11px] font-bold uppercase tracking-widest hover:bg-slate-800 transition duration-300"><?= t('about_us') ?></a>
                        <a href="<?= BASE_URL ?>faq" class="px-5 py-3.5 text-[11px] font-bold uppercase tracking-widest hover:bg-slate-800 transition duration-300"><?= t('faq') ?></a>
                        <a href="<?= BASE_URL ?>contact" class="px-5 py-3.5 text-[11px] font-bold uppercase tracking-widest hover:bg-slate-800 transition duration-300"><?= t('contact_us') ?></a>
                    </div>

                    <div class="flex items-center text-[10px] text-slate-400 font-bold uppercase tracking-widest italic">
                        <i class="fas fa-truck-fast mr-2 text-blue-500 text-sm"></i>
                        <?= t('free_shipping') ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden lg:hidden bg-white dark:bg-gray-900 overflow-y-auto max-h-[calc(100vh-80px)]">
            <div class="px-4 py-4 space-y-4">
                <form action="<?= BASE_URL ?>shop" method="GET" class="relative">
                    <input type="text" name="search" placeholder="<?= t('search_products') ?>" class="w-full bg-gray-100 dark:bg-gray-800 border-none rounded-lg py-2 pl-3 pr-10 text-sm">
                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                <div class="space-y-1">
                    <a href="<?= BASE_URL ?>" class="block py-2 px-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg text-sm font-semibold"><?= t('home') ?></a>

                    <?php foreach ($navTree as $tcat): ?>
                    <div class="mobile-cat-group">
                        <div class="flex items-center justify-between">
                            <a href="<?= BASE_URL ?>shop?category=<?= htmlspecialchars($tcat['slug']) ?>" class="block py-2 px-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg text-sm font-semibold flex-1">
                                <?= htmlspecialchars($tcat['name']) ?>
                            </a>
                            <?php if (!empty($tcat['children'])): ?>
                                <button onclick="this.parentElement.nextElementSibling.classList.toggle('hidden'); this.querySelector('i').classList.toggle('rotate-180');" class="px-4 py-2 text-gray-400">
                                    <i class="fas fa-chevron-down text-[10px] transition-transform"></i>
                                </button>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($tcat['children'])): ?>
                        <div class="hidden pl-4 space-y-1 mt-1 border-l-2 border-gray-100 dark:border-gray-800 ml-3">
                            <?php foreach ($tcat['children'] as $mcat): ?>
                                <div class="space-y-1">
                                    <div class="flex items-center justify-between">
                                        <a href="<?= BASE_URL ?>shop?category=<?= htmlspecialchars($mcat['slug']) ?>" class="block py-1.5 px-3 text-gray-600 dark:text-gray-400 text-sm"><?= htmlspecialchars($mcat['name']) ?></a>
                                        <?php if (!empty($mcat['children'])): ?>
                                            <button onclick="this.parentElement.nextElementSibling.classList.toggle('hidden'); this.querySelector('i').classList.toggle('rotate-180');" class="px-4 py-1 text-gray-400">
                                                <i class="fas fa-chevron-down text-[10px] transition-transform"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($mcat['children'])): ?>
                                        <div class="hidden pl-4 space-y-1 border-l border-gray-100 dark:border-gray-800 ml-3">
                                            <?php foreach ($mcat['children'] as $ecat): ?>
                                                <a href="<?= BASE_URL ?>shop?category=<?= htmlspecialchars($ecat['slug']) ?>" class="block py-1 px-3 text-gray-400 dark:text-gray-600 text-[13px] hover:text-blue-600 transition"><?= htmlspecialchars($ecat['name']) ?></a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>

                    <a href="<?= BASE_URL ?>about" class="block py-2 px-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg text-sm font-semibold"><?= t('about_us') ?></a>
                    <a href="<?= BASE_URL ?>faq" class="block py-2 px-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg text-sm font-semibold"><?= t('faq') ?></a>
                    <a href="<?= BASE_URL ?>contact" class="block py-2 px-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg text-sm font-semibold"><?= t('contact_us') ?></a>
                </div>

                <hr class="border-gray-100 dark:border-gray-800">

                <div class="grid grid-cols-2 gap-2">
                    <?php if (!\App\Core\Session::isLoggedIn('customer')): ?>
                        <a href="<?= BASE_URL ?>login" class="flex items-center justify-center py-2.5 px-4 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-semibold"><?= t('login') ?></a>
                        <a href="<?= BASE_URL ?>register" class="flex items-center justify-center py-2.5 px-4 bg-blue-600 text-white rounded-lg text-sm font-semibold"><?= t('register') ?></a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>account/dashboard" class="flex items-center justify-center py-2.5 px-4 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-semibold"><?= t('my_account') ?></a>
                        <a href="<?= BASE_URL ?>account/messages" class="flex items-center justify-center py-2.5 px-4 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-semibold"><?= t('customer_messages') ?></a>
                        <a href="<?= BASE_URL ?>my-orders" class="col-span-2 flex items-center justify-center py-2.5 px-4 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-semibold"><?= t('my_orders') ?></a>
                        <a href="<?= BASE_URL ?>logout" class="col-span-2 flex items-center justify-center py-2.5 px-4 text-red-600 font-semibold"><?= t('logout') ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Toast Container -->
    <div id="toastContainer" class="fixed top-4 right-4 z-50 flex flex-col gap-3 max-w-sm"></div>

    <!-- Custom Confirmation Modal -->
    <div id="confirmModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white dark:bg-gray-900 w-full max-w-sm rounded-2xl shadow-2xl transform scale-95 transition-transform duration-300 overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fas fa-question-circle"></i>
                </div>
                <h3 id="confirmTitle" class="text-xl font-bold text-gray-900 dark:text-white mb-2"><?= t('confirm_action') ?></h3>
                <p id="confirmMessage" class="text-gray-500 dark:text-gray-400 text-sm mb-6"></p>
                <div class="flex gap-3">
                    <button id="confirmCancel" class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-bold hover:bg-gray-50 dark:hover:bg-gray-800 transition"><?= t('cancel') ?></button>
                    <button id="confirmOk" class="flex-1 px-4 py-2.5 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-500/25"><?= t('ok') ?></button>
                </div>
            </div>
        </div>
    </div>

    <main class="flex-1">
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 dark:bg-gray-950 text-gray-300 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="col-span-2 md:col-span-1">
                    <a href="<?= BASE_URL ?>" class="flex items-center space-x-2 mb-4">
                        <div class="bg-blue-600 text-white w-9 h-9 rounded-lg flex items-center justify-center font-bold text-lg">G</div>
                        <span class="text-xl font-bold text-white"><?= htmlspecialchars($siteSettings['store_name'] ?? 'General Online Store') ?></span>
                    </a>
                    <p class="text-sm text-gray-400 leading-relaxed"><?= t('footer_tagline') ?></p>
                    <div class="flex gap-3 mt-4">
                        <?php if(!empty($siteSettings['facebook_url'])): ?>
                            <a href="<?= $siteSettings['facebook_url'] ?>" target="_blank" class="w-9 h-9 bg-white/10 hover:bg-blue-600 rounded-lg flex items-center justify-center transition text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                        <?php endif; ?>
                        <?php if(!empty($siteSettings['telegram_url'])): ?>
                            <a href="<?= $siteSettings['telegram_url'] ?>" target="_blank" class="w-9 h-9 bg-white/10 hover:bg-sky-500 rounded-lg flex items-center justify-center transition text-gray-400 hover:text-white"><i class="fab fa-telegram-plane"></i></a>
                        <?php endif; ?>
                        <?php if(!empty($siteSettings['tiktok_url'])): ?>
                            <a href="<?= $siteSettings['tiktok_url'] ?>" target="_blank" class="w-9 h-9 bg-white/10 hover:bg-pink-600 rounded-lg flex items-center justify-center transition text-gray-400 hover:text-white"><i class="fab fa-tiktok"></i></a>
                        <?php endif; ?>
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
                        <li class="flex items-center gap-2"><i class="fas fa-envelope text-gray-500 text-xs w-4"></i> <?= htmlspecialchars($siteSettings['store_email'] ?? 'support@generalstore.com') ?></li>
                        <li class="flex items-center gap-2"><i class="fas fa-phone text-gray-500 text-xs w-4"></i> <?= htmlspecialchars($siteSettings['store_phone'] ?? '+1 (555) 123-4567') ?></li>
                        <li class="flex items-center gap-2"><i class="fas fa-location-dot text-gray-500 text-xs w-4"></i> <?= htmlspecialchars($siteSettings['store_address'] ?? t('address')) ?></li>
                    </ul>
                </div>
            </div>
            <hr class="border-gray-800 my-8">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 text-sm text-gray-500">
                <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($siteSettings['store_name'] ?? 'General Online Store') ?> - Developed By Group-D</p>
                <div class="flex items-center gap-4">
                    <a href="#" class="hover:text-blue-500 transition"><i class="fab fa-facebook-f text-lg"></i></a>
                    <a href="#" class="hover:text-blue-400 transition"><i class="fab fa-twitter text-lg"></i></a>
                    <a href="#" class="hover:text-red-500 transition"><i class="fab fa-pinterest text-lg"></i></a>
                    <a href="#" class="hover:text-gray-300 transition"><i class="fas fa-envelope text-lg"></i></a>
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

        // Wishlist Toggle
        function toggleWishlist(productId, btn) {
            fetch('<?= BASE_URL ?>wishlist/toggle', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'product_id=' + productId
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (!data.success) {
                    if (data.message.indexOf('login') !== -1) {
                        window.location.href = '<?= BASE_URL ?>login';
                    }
                    return;
                }
                if (!btn) {
                    btn = document.getElementById('wishlistBtn');
                }
                if (btn) {
                    var icon = btn.querySelector('.fa-heart') || document.getElementById('wishlistIcon');
                    if (data.wishlisted) {
                        btn.classList.add('bg-red-500', 'text-white');
                        btn.classList.remove('bg-white/80', 'dark:bg-gray-800/80', 'text-gray-400', 'hover:bg-red-500');
                        icon.classList.add('fas');
                        icon.classList.remove('far');
                    } else {
                        btn.classList.remove('bg-red-500', 'text-white');
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                    }
                }
                showToast(data.message, data.wishlisted ? 'success' : 'error');
            });
        }

        // Show flash messages as toasts
        <?php if (\App\Core\Session::hasFlash('success')): ?>
            showToast('<?= addslashes(\App\Core\Session::getFlash('success')) ?>', 'success');
        <?php endif; ?>
        <?php if (\App\Core\Session::hasFlash('error')): ?>
            showToast('<?= addslashes(\App\Core\Session::getFlash('error')) ?>', 'error');
        <?php endif; ?>

        // Global Confirmation System
        let confirmCallback = null;
        const confirmModal = document.getElementById('confirmModal');
        const confirmMsg = document.getElementById('confirmMessage');
        const confirmOk = document.getElementById('confirmOk');
        const confirmCancel = document.getElementById('confirmCancel');

        window.confirmAction = function(message, callback) {
            confirmMsg.textContent = message;
            confirmCallback = callback;
            confirmModal.classList.remove('hidden');
            document.body.classList.add('modal-open');
            requestAnimationFrame(() => {
                confirmModal.classList.remove('opacity-0');
                confirmModal.querySelector('div').classList.remove('scale-95');
                confirmModal.querySelector('div').classList.add('scale-100');
            });
        };

        function closeConfirm() {
            confirmModal.classList.add('opacity-0');
            confirmModal.querySelector('div').classList.remove('scale-100');
            confirmModal.querySelector('div').classList.add('scale-95');
            setTimeout(() => {
                confirmModal.classList.add('hidden');
                document.body.classList.remove('modal-open');
            }, 300);
        }

        confirmCancel.onclick = closeConfirm;
        confirmOk.onclick = function() {
            if (confirmCallback) confirmCallback();
            closeConfirm();
        };

        confirmModal.onclick = function(e) {
            if (e.target === confirmModal) closeConfirm();
        };
    </script>
</body>
</html>
