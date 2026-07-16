<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Admin - E-Shop' ?></title>
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
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen flex transition-colors duration-300">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 dark:bg-gray-950 text-white flex flex-col min-h-screen fixed z-30">
        <div class="p-6 border-b border-gray-700">
            <a href="<?= BASE_URL ?>admin/dashboard" class="flex items-center space-x-2">
                <div class="bg-blue-600 text-white w-9 h-9 rounded-lg flex items-center justify-center font-bold text-lg">E</div>
                <span class="text-lg font-bold"><?= t('admin_panel') ?></span>
            </a>
        </div>
        <nav class="flex-1 p-4 space-y-1">
            <a href="<?= BASE_URL ?>admin/dashboard" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition <?= (strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false) ? 'bg-gray-700' : '' ?>">
                <i class="fas fa-tachometer-alt w-5"></i><span><?= t('dashboard') ?></span>
            </a>
            <a href="<?= BASE_URL ?>admin/products" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition <?= (strpos($_SERVER['REQUEST_URI'], 'product') !== false && strpos($_SERVER['REQUEST_URI'], 'dashboard') === false && strpos($_SERVER['REQUEST_URI'], 'inventory') === false) ? 'bg-gray-700' : '' ?>">
                <i class="fas fa-box w-5"></i><span><?= t('products') ?></span>
            </a>
            <a href="<?= BASE_URL ?>admin/categories" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition <?= (strpos($_SERVER['REQUEST_URI'], 'categor') !== false) ? 'bg-gray-700' : '' ?>">
                <i class="fas fa-tags w-5"></i><span><?= t('categories') ?></span>
            </a>
            <a href="<?= BASE_URL ?>admin/inventory" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition <?= (strpos($_SERVER['REQUEST_URI'], 'inventory') !== false) ? 'bg-gray-700' : '' ?>">
                <i class="fas fa-warehouse w-5"></i><span><?= t('inventory') ?></span>
            </a>
            <a href="<?= BASE_URL ?>admin/orders" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition <?= (strpos($_SERVER['REQUEST_URI'], 'order') !== false && strpos($_SERVER['REQUEST_URI'], 'product') === false && strpos($_SERVER['REQUEST_URI'], 'inventory') === false && strpos($_SERVER['REQUEST_URI'], 'user') === false) ? 'bg-gray-700' : '' ?>">
                <i class="fas fa-file-invoice w-5"></i><span><?= t('orders') ?></span>
            </a>
            <a href="<?= BASE_URL ?>admin/users" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition <?= (strpos($_SERVER['REQUEST_URI'], 'user') !== false && strpos($_SERVER['REQUEST_URI'], 'users') !== false || preg_match('#/admin/users?#', $_SERVER['REQUEST_URI'])) ? 'bg-gray-700' : '' ?>">
                <i class="fas fa-users w-5"></i><span><?= t('users') ?></span>
            </a>
            <hr class="border-gray-700 my-3">
            <a href="<?= BASE_URL ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                <i class="fas fa-store w-5"></i><span><?= t('view_store') ?></span>
            </a>
            <a href="<?= BASE_URL ?>logout" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-red-600 transition">
                <i class="fas fa-sign-out-alt w-5"></i><span><?= t('logout') ?></span>
            </a>
        </nav>
        <div class="p-4 border-t border-gray-700 text-xs text-gray-500">
            <?= t('logged_in_as') ?> <?= htmlspecialchars(App\Core\Session::get('user_name', '')) ?>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 ml-64">
        <!-- Header -->
        <header class="bg-white dark:bg-gray-800 shadow-sm p-4 flex items-center justify-between transition-colors duration-300">
            <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-100"><?= $data['title'] ?? 'Admin Dashboard' ?></h1>

            <div class="flex items-center gap-3">
                <!-- Search -->
                <div class="relative hidden md:block">
                    <input type="text" id="globalSearch" placeholder="<?= t('search') ?>"
                        class="pl-9 pr-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition w-56">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <!-- Search Results Dropdown -->
                    <div id="searchResults" class="absolute top-full left-0 right-0 mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg shadow-lg z-50 hidden max-h-80 overflow-y-auto"></div>
                </div>

                <!-- Dark/Light Mode Toggle -->
                <button id="themeToggle" class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition text-gray-600 dark:text-yellow-400" title="<?= t('dark_mode') ?>">
                    <i class="fas fa-moon dark:hidden"></i>
                    <i class="fas fa-sun hidden dark:inline"></i>
                </button>

                <!-- Language Switcher -->
                <div class="relative">
                    <button id="langToggle" class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition text-sm font-bold text-gray-600 dark:text-gray-300" title="<?= t('language') ?>">
                        <?= \App\Core\Lang\Language::current() === 'km' ? 'ខ្មែរ' : 'EN' ?>
                    </button>
                    <div id="langDropdown" class="hidden absolute right-0 top-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg shadow-lg z-50 py-1 min-w-[120px]">
                        <a href="<?= BASE_URL ?>lang?lang=en" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition <?= \App\Core\Lang\Language::current() === 'en' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600' : '' ?>">
                            <span>🇺🇸</span> English
                        </a>
                        <a href="<?= BASE_URL ?>lang?lang=km" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition <?= \App\Core\Lang\Language::current() === 'km' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600' : '' ?>">
                            <span>🇰🇭</span> ខ្មែរ
                        </a>
                    </div>
                </div>

                <!-- Calendar + Clock -->
                <div class="relative">
                    <button id="calendarToggle" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                        <i class="fas fa-calendar-days"></i>
                        <span class="hidden lg:block" id="calendarDate"><?= date('M d, Y') ?></span>
                        <span class="hidden lg:block text-gray-400 dark:text-gray-500">|</span>
                        <i class="fas fa-clock text-xs"></i>
                        <span id="liveClock" class="font-mono text-xs tabular-nums"></span>
                    </button>
                    <div id="calendarDropdown" class="hidden absolute right-0 top-full mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl shadow-xl z-50 p-4 w-72">
                        <div class="flex items-center justify-between mb-3">
                            <button id="calPrev" class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition"><i class="fas fa-chevron-left text-sm"></i></button>
                            <span id="calMonthYear" class="font-semibold text-gray-900 dark:text-white text-sm"></span>
                            <button id="calNext" class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition"><i class="fas fa-chevron-right text-sm"></i></button>
                        </div>
                        <div class="grid grid-cols-7 gap-1 mb-1">
                            <?php foreach (['Su','Mo','Tu','We','Th','Fr','Sa'] as $d): ?>
                            <div class="text-center text-xs font-semibold text-gray-400 dark:text-gray-500 py-1"><?= $d ?></div>
                            <?php endforeach; ?>
                        </div>
                        <div id="calDays" class="grid grid-cols-7 gap-1"></div>
                        <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700 text-center">
                            <button id="calToday" class="text-xs text-blue-600 dark:text-blue-400 hover:underline font-medium"><?= t('today') ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Toast Container -->
        <div id="toastContainer" class="fixed top-4 right-4 z-50 flex flex-col gap-3 max-w-sm"></div>

        <main class="p-6">
            <?= $content ?>
        </main>
    </div>

    <script>
    // === Toast Notification System ===
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
        
        // Animate in
        requestAnimationFrame(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
            toast.classList.add('translate-x-0', 'opacity-100');
        });
        
        // Auto dismiss after 4 seconds
        setTimeout(() => {
            toast.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }

    // Show flash messages as toasts
    <?php if (App\Core\Session::hasFlash('success')): ?>
        showToast('<?= App\Core\Session::getFlash('success') ?>', 'success');
    <?php endif; ?>
    <?php if (App\Core\Session::hasFlash('error')): ?>
        showToast('<?= App\Core\Session::getFlash('error') ?>', 'error');
    <?php endif; ?>

    // === Dark/Light Mode ===
    const themeToggle = document.getElementById('themeToggle');
    const html = document.documentElement;

    // Load saved theme
    const savedTheme = localStorage.getItem('theme') || 'light';
    if (savedTheme === 'dark') {
        html.classList.add('dark');
    }

    themeToggle.addEventListener('click', () => {
        html.classList.toggle('dark');
        const isDark = html.classList.contains('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        themeToggle.querySelector('.fa-moon').classList.toggle('hidden', isDark);
        themeToggle.querySelector('.fa-sun').classList.toggle('hidden', !isDark);
    });

    // === Language Dropdown ===
    const langToggle = document.getElementById('langToggle');
    const langDropdown = document.getElementById('langDropdown');

    langToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        langDropdown.classList.toggle('hidden');
    });

    document.addEventListener('click', () => langDropdown.classList.add('hidden'));

    // === Calendar Widget ===
    const calToggle = document.getElementById('calendarToggle');
    const calDropdown = document.getElementById('calendarDropdown');
    const calDays = document.getElementById('calDays');
    const calMonthYear = document.getElementById('calMonthYear');
    const calPrev = document.getElementById('calPrev');
    const calNext = document.getElementById('calNext');
    const calToday = document.getElementById('calToday');
    let calDate = new Date();

    const monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];

    function renderCalendar() {
        const year = calDate.getFullYear();
        const month = calDate.getMonth();
        calMonthYear.textContent = monthNames[month] + ' ' + year;
        
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const today = new Date();
        
        let html = '';
        for (let i = 0; i < firstDay; i++) {
            html += '<div></div>';
        }
        for (let d = 1; d <= daysInMonth; d++) {
            const isToday = d === today.getDate() && month === today.getMonth() && year === today.getFullYear();
            const classes = isToday 
                ? 'bg-blue-600 text-white font-bold' 
                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700';
            html += `<div class="text-center text-sm py-1.5 rounded-lg cursor-pointer transition ${classes}">${d}</div>`;
        }
        calDays.innerHTML = html;
    }

    calToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        calDropdown.classList.toggle('hidden');
        if (!calDropdown.classList.contains('hidden')) renderCalendar();
    });

    calPrev.addEventListener('click', () => { calDate.setMonth(calDate.getMonth() - 1); renderCalendar(); });
    calNext.addEventListener('click', () => { calDate.setMonth(calDate.getMonth() + 1); renderCalendar(); });
    calToday.addEventListener('click', () => { calDate = new Date(); renderCalendar(); });

    document.addEventListener('click', () => calDropdown.classList.add('hidden'));
    calDropdown.addEventListener('click', (e) => e.stopPropagation());

    // === Live Clock ===
    const liveClock = document.getElementById('liveClock');
    function updateClock() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        liveClock.textContent = h + ':' + m + ':' + s;
    }
    updateClock();
    setInterval(updateClock, 1000);

    // === Global Search ===
    const searchInput = document.getElementById('globalSearch');
    const searchResults = document.getElementById('searchResults');
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        if (query.length < 2) {
            searchResults.classList.add('hidden');
            return;
        }
        searchTimeout = setTimeout(() => {
            fetch('<?= BASE_URL ?>admin/search?q=' + encodeURIComponent(query))
                .then(r => r.json())
                .then(data => {
                    if (data.length === 0) {
                        searchResults.innerHTML = '<div class="px-4 py-3 text-sm text-gray-500">No results found</div>';
                    } else {
                        searchResults.innerHTML = data.map(item => `
                            <a href="${item.url}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700 transition border-b border-gray-100 dark:border-gray-600 last:border-0">
                                <div class="w-8 h-8 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-${item.icon} text-gray-400 text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">${item.title}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">${item.subtitle}</p>
                                </div>
                                <span class="text-xs text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-600 px-2 py-0.5 rounded">${item.type}</span>
                            </a>
                        `).join('');
                    }
                    searchResults.classList.remove('hidden');
                });
        }, 300);
    });

    searchInput.addEventListener('blur', () => {
        setTimeout(() => searchResults.classList.add('hidden'), 200);
    });

    searchInput.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') searchResults.classList.add('hidden');
    });
    </script>
</body>
</html>
