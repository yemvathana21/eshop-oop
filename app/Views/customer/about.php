<div class="bg-white dark:bg-gray-950 transition-colors duration-300">
    <!-- Hero Section -->
    <section class="relative py-20 bg-blue-600 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white"></path>
            </svg>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6 tracking-tight">
                <?= t('about_us') ?>
            </h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto leading-relaxed">
                <?= t('about_hero_subtitle') ?>
            </p>
        </div>
    </section>

    <!-- Content Sections -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <!-- Trust Badges Section (from image) -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8 mb-24 py-12 border-y border-gray-100 dark:border-gray-800">
            <div class="text-center group">
                <div class="w-16 h-16 bg-blue-50 dark:bg-blue-900/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition duration-300">
                    <i class="fas fa-arrow-rotate-left text-blue-600 text-xl"></i>
                </div>
                <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1"><?= t('trust_easy_returns') ?></h4>
                <p class="text-[11px] text-gray-500 leading-tight"><?= t('trust_easy_returns_desc') ?></p>
            </div>

            <div class="text-center group">
                <div class="w-16 h-16 bg-green-50 dark:bg-green-900/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition duration-300">
                    <i class="fas fa-truck text-green-600 text-xl"></i>
                </div>
                <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1"><?= t('trust_free_shipping') ?></h4>
                <p class="text-[11px] text-gray-500 leading-tight"><?= t('trust_free_shipping_desc') ?></p>
            </div>

            <div class="text-center group">
                <div class="w-16 h-16 bg-orange-50 dark:bg-orange-900/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition duration-300">
                    <i class="fas fa-bolt-lightning text-orange-500 text-xl"></i>
                </div>
                <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1"><?= t('trust_fast_shipping') ?></h4>
                <p class="text-[11px] text-gray-500 leading-tight"><?= t('trust_fast_shipping_desc') ?></p>
            </div>

            <div class="text-center group">
                <div class="w-16 h-16 bg-purple-50 dark:bg-purple-900/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition duration-300">
                    <i class="fas fa-smile text-purple-600 text-xl"></i>
                </div>
                <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1"><?= t('trust_satisfaction') ?></h4>
                <p class="text-[11px] text-gray-500 leading-tight"><?= t('trust_satisfaction_desc') ?></p>
            </div>

            <div class="text-center group">
                <div class="w-16 h-16 bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition duration-300">
                    <i class="fas fa-shield-halved text-indigo-600 text-xl"></i>
                </div>
                <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1"><?= t('trust_secure_checkout') ?></h4>
                <p class="text-[11px] text-gray-500 leading-tight"><?= t('trust_secure_checkout_desc') ?></p>
            </div>

            <div class="text-center group">
                <div class="w-16 h-16 bg-red-50 dark:bg-red-900/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition duration-300">
                    <i class="fas fa-hand-holding-dollar text-red-600 text-xl"></i>
                </div>
                <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1"><?= t('trust_money_back') ?></h4>
                <p class="text-[11px] text-gray-500 leading-tight"><?= t('trust_money_back_desc') ?></p>
            </div>
        </div>

        <!-- Our Story -->
        <div class="grid md:grid-cols-2 gap-16 items-center mb-24">
            <div class="order-2 md:order-1">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6"><?= t('about_our_story') ?></h2>
                <div class="space-y-4 text-gray-600 dark:text-gray-400 leading-relaxed">
                    <p><?= t('about_story_p1') ?></p>
                    <p><?= t('about_story_p2') ?></p>
                </div>
                <div class="mt-8 grid grid-cols-2 gap-6">
                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-xl border border-gray-100 dark:border-gray-800">
                        <span class="block text-3xl font-bold text-blue-600 mb-1">10k+</span>
                        <span class="text-sm text-gray-500 uppercase tracking-wider font-semibold"><?= t('about_active_users') ?></span>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-xl border border-gray-100 dark:border-gray-800">
                        <span class="block text-3xl font-bold text-blue-600 mb-1">5k+</span>
                        <span class="text-sm text-gray-500 uppercase tracking-wider font-semibold"><?= t('about_products') ?></span>
                    </div>
                </div>
            </div>
            <div class="order-1 md:order-2">
                <div class="relative">
                    <div class="absolute -inset-4 bg-blue-600/10 rounded-3xl transform rotate-3"></div>
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1171&q=80"
                         alt="Our Team"
                         class="relative rounded-2xl shadow-2xl object-cover w-full h-[400px]">
                </div>
            </div>
        </div>

        <!-- Our Mission & Vision -->
        <div class="grid md:grid-cols-3 gap-8 mb-24">
            <div class="bg-white dark:bg-gray-900 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-md transition">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center mb-6 text-xl">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4"><?= t('about_mission') ?></h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                    <?= t('about_mission_desc') ?>
                </p>
            </div>
            <div class="bg-white dark:bg-gray-900 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-md transition">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-xl flex items-center justify-center mb-6 text-xl">
                    <i class="fas fa-eye"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4"><?= t('about_vision') ?></h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                    <?= t('about_vision_desc') ?>
                </p>
            </div>
            <div class="bg-white dark:bg-gray-900 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-md transition">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-xl flex items-center justify-center mb-6 text-xl">
                    <i class="fas fa-heart"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4"><?= t('about_values') ?></h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                    <?= t('about_values_desc') ?>
                </p>
            </div>
        </div>

        <!-- Team Section -->
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4"><?= t('about_team') ?></h2>
            <p class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto"><?= t('about_team_subtitle') ?></p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <!-- Team Member 1 -->
            <div class="text-center group">
                <div class="relative inline-block mb-4">
                    <div class="absolute inset-0 bg-blue-600 rounded-2xl transform rotate-6 group-hover:rotate-0 transition duration-300 opacity-20"></div>
                    <img src="https://ui-avatars.com/api/?name=John+Doe&background=random&size=200" alt="John Doe" class="relative rounded-2xl w-40 h-40 object-cover shadow-lg">
                </div>
                <h4 class="text-lg font-bold text-gray-900 dark:text-white">John Doe</h4>
                <p class="text-sm text-gray-500">Founder & CEO</p>
            </div>
            <!-- Team Member 2 -->
            <div class="text-center group">
                <div class="relative inline-block mb-4">
                    <div class="absolute inset-0 bg-blue-600 rounded-2xl transform rotate-6 group-hover:rotate-0 transition duration-300 opacity-20"></div>
                    <img src="https://ui-avatars.com/api/?name=Jane+Smith&background=random&size=200" alt="Jane Smith" class="relative rounded-2xl w-40 h-40 object-cover shadow-lg">
                </div>
                <h4 class="text-lg font-bold text-gray-900 dark:text-white">Jane Smith</h4>
                <p class="text-sm text-gray-500">Head of Operations</p>
            </div>
            <!-- Team Member 3 -->
            <div class="text-center group">
                <div class="relative inline-block mb-4">
                    <div class="absolute inset-0 bg-blue-600 rounded-2xl transform rotate-6 group-hover:rotate-0 transition duration-300 opacity-20"></div>
                    <img src="https://ui-avatars.com/api/?name=Sok+Mean&background=random&size=200" alt="Sok Mean" class="relative rounded-2xl w-40 h-40 object-cover shadow-lg">
                </div>
                <h4 class="text-lg font-bold text-gray-900 dark:text-white">Sok Mean</h4>
                <p class="text-sm text-gray-500">Lead Developer</p>
            </div>
            <!-- Team Member 4 -->
            <div class="text-center group">
                <div class="relative inline-block mb-4">
                    <div class="absolute inset-0 bg-blue-600 rounded-2xl transform rotate-6 group-hover:rotate-0 transition duration-300 opacity-20"></div>
                    <img src="https://ui-avatars.com/api/?name=Chan+Vithyea&background=random&size=200" alt="Chan Vithyea" class="relative rounded-2xl w-40 h-40 object-cover shadow-lg">
                </div>
                <h4 class="text-lg font-bold text-gray-900 dark:text-white">Chan Vithyea</h4>
                <p class="text-sm text-gray-500">Customer Success</p>
            </div>
        </div>
    </div>
</div>
