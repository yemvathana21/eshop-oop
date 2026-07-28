<aside id="accountSidebar" class="w-[280px] flex-shrink-0 hidden lg:block fixed lg:static inset-y-0 left-0 z-50 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 overflow-y-auto custom-scrollbar transition-all duration-300">
    <div class="p-5">

        <!-- Settings Group (User Card) -->
        <div class="text-center mb-6">
            <div class="relative inline-block">
                <div class="w-20 h-20 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700 ring-4 ring-blue-100 dark:ring-blue-900/50 mx-auto shadow-lg">
                    <?php if (!empty($user['avatar'])): ?>
                        <img id="sidebarAvatar" src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($user['avatar']) ?>" alt="" class="w-full h-full object-cover" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                        <div class="w-full h-full hidden items-center justify-center bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 font-bold text-2xl"><?= strtoupper(substr($user['name'], 0, 1)) ?></div>
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 font-bold text-2xl"><?= strtoupper(substr($user['name'], 0, 1)) ?></div>
                    <?php endif; ?>
                </div>
                <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></div>
            </div>
            <h3 class="mt-3 font-semibold text-gray-900 dark:text-white text-sm"><?= htmlspecialchars($user['name']) ?></h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 truncate"><?= htmlspecialchars($user['email']) ?></p>
        </div>

        <nav class="space-y-1 text-sm">

            <!-- Dashboard -->
            <a href="<?= BASE_URL ?>account/dashboard"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 <?= $currentSection === 'dashboard' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-semibold shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-200' ?>">
                <i class="fas fa-chart-pie w-5 text-center text-base"></i>
                <span><?= t('dashboard') ?></span>
            </a>

            <!-- Profile group -->
            <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 px-3 pt-4 pb-1"><?= t('profile') ?></p>

            <a href="<?= BASE_URL ?>account/profile"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 <?= $currentSection === 'profile' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-semibold shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-200' ?>">
                <i class="fas fa-user-pen w-5 text-center"></i>
                <span><?= t('edit_profile') ?></span>
            </a>

            <a href="<?= BASE_URL ?>account/username"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 <?= $currentSection === 'username' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-semibold shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-200' ?>">
                <i class="fas fa-at w-5 text-center"></i>
                <span><?= t('username') ?></span>
            </a>

            <!-- Security group -->
            <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 px-3 pt-4 pb-1"><?= t('security') ?></p>

            <a href="<?= BASE_URL ?>account/security#phone"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 <?= $currentSection === 'security' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-semibold shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-200' ?>">
                <i class="fas fa-mobile-screen w-5 text-center"></i>
                <span><?= t('activate_phone') ?></span>
            </a>

            <a href="<?= BASE_URL ?>account/security#password"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 <?= $currentSection === 'security' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-semibold shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-200' ?>">
                <i class="fas fa-key w-5 text-center"></i>
                <span><?= t('change_password') ?></span>
            </a>

            <!-- Devices -->
            <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 px-3 pt-4 pb-1"><?= t('devices') ?></p>

            <a href="<?= BASE_URL ?>account/devices"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 <?= $currentSection === 'devices' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-semibold shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-200' ?>">
                <i class="fas fa-laptop w-5 text-center"></i>
                <span><?= t('devices') ?></span>
            </a>

            <a href="<?= BASE_URL ?>account/passkeys"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 <?= $currentSection === 'passkeys' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-semibold shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-200' ?>">
                <i class="fas fa-fingerprint w-5 text-center"></i>
                <span><?= t('passkeys') ?></span>
            </a>

            <!-- Connected Accounts -->
            <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 px-3 pt-4 pb-1"><?= t('connected_accounts') ?></p>

            <a href="<?= BASE_URL ?>account/connected"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 <?= $currentSection === 'connected' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-semibold shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-200' ?>">
                <i class="fab fa-facebook w-5 text-center"></i>
                <span>Facebook</span>
            </a>

            <a href="<?= BASE_URL ?>account/connected"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-200">
                <i class="fab fa-google w-5 text-center"></i>
                <span>Google</span>
            </a>

            <a href="<?= BASE_URL ?>account/connected"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-200">
                <i class="fab fa-telegram w-5 text-center"></i>
                <span>Telegram</span>
            </a>

            <a href="<?= BASE_URL ?>account/connected"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-200">
                <i class="fab fa-apple w-5 text-center"></i>
                <span>Apple</span>
            </a>

            <!-- Privacy & Data group -->
            <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 px-3 pt-4 pb-1"><?= t('privacy_data') ?></p>

            <a href="<?= BASE_URL ?>account/privacy"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 <?= $currentSection === 'privacy' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-semibold shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-200' ?>">
                <i class="fas fa-lock w-5 text-center"></i>
                <span><?= t('privacy') ?></span>
            </a>

            <a href="<?= BASE_URL ?>account/privacy#delete"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20">
                <i class="fas fa-trash-can w-5 text-center"></i>
                <span><?= t('delete_account') ?></span>
            </a>

            <!-- Billing & Membership group -->
            <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 px-3 pt-4 pb-1"><?= t('billing_membership') ?></p>

            <a href="<?= BASE_URL ?>account/membership"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 <?= $currentSection === 'membership' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-semibold shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-200' ?>">
                <i class="fas fa-circle-info w-5 text-center"></i>
                <span><?= t('membership_info') ?></span>
            </a>

            <a href="<?= BASE_URL ?>account/addresses"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 <?= $currentSection === 'addresses' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-semibold shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-200' ?>">
                <i class="fas fa-credit-card w-5 text-center"></i>
                <span><?= t('billing_address') ?></span>
            </a>

            <hr class="border-gray-100 dark:border-gray-700 my-3">

            <a href="<?= BASE_URL ?>"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-200 transition-all duration-200">
                <i class="fas fa-store w-5 text-center"></i>
                <span><?= t('visit_store') ?></span>
            </a>
            <a href="<?= BASE_URL ?>logout"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200">
                <i class="fas fa-right-from-bracket w-5 text-center"></i>
                <span><?= t('logout') ?></span>
            </a>
        </nav>

        <button id="sidebarClose" class="lg:hidden mt-4 w-full py-2 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition">
            <i class="fas fa-times mr-1"></i> <?= t('close') ?>
        </button>
    </div>
</aside>
