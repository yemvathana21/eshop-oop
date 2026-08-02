<div class="bg-gray-50 dark:bg-gray-950 py-16 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-4"><?= t('faq') ?></h1>
            <p class="text-lg text-gray-500 dark:text-gray-400"><?= t('faq_subtitle') ?></p>
        </div>

        <div class="space-y-6">
            <!-- Category: Orders & Shipping -->
            <div class="space-y-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fas fa-truck text-blue-600"></i>
                    <?= t('faq_cat_orders') ?>
                </h2>
                <div class="space-y-4">
                    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden shadow-sm">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-800 transition" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('i').classList.toggle('rotate-180');">
                            <span class="font-semibold text-gray-900 dark:text-white"><?= t('faq_q1') ?></span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="px-6 py-4 text-gray-600 dark:text-gray-400 border-t border-gray-100 dark:border-gray-800 hidden">
                            <?= t('faq_a1') ?>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden shadow-sm">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-800 transition" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('i').classList.toggle('rotate-180');">
                            <span class="font-semibold text-gray-900 dark:text-white"><?= t('faq_q2') ?></span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="px-6 py-4 text-gray-600 dark:text-gray-400 border-t border-gray-100 dark:border-gray-800 hidden">
                            <?= t('faq_a2') ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category: Payments -->
            <div class="space-y-4 pt-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fas fa-credit-card text-blue-600"></i>
                    <?= t('faq_cat_payments') ?>
                </h2>
                <div class="space-y-4">
                    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden shadow-sm">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-800 transition" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('i').classList.toggle('rotate-180');">
                            <span class="font-semibold text-gray-900 dark:text-white"><?= t('faq_q3') ?></span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="px-6 py-4 text-gray-600 dark:text-gray-400 border-t border-gray-100 dark:border-gray-800 hidden">
                            <?= t('faq_a3') ?>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden shadow-sm">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-800 transition" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('i').classList.toggle('rotate-180');">
                            <span class="font-semibold text-gray-900 dark:text-white"><?= t('faq_q4') ?></span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="px-6 py-4 text-gray-600 dark:text-gray-400 border-t border-gray-100 dark:border-gray-800 hidden">
                            <?= t('faq_a4') ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category: Returns & Refunds -->
            <div class="space-y-4 pt-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fas fa-undo text-blue-600"></i>
                    <?= t('faq_cat_returns') ?>
                </h2>
                <div class="space-y-4">
                    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden shadow-sm">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-800 transition" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('i').classList.toggle('rotate-180');">
                            <span class="font-semibold text-gray-900 dark:text-white"><?= t('faq_q5') ?></span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="px-6 py-4 text-gray-600 dark:text-gray-400 border-t border-gray-100 dark:border-gray-800 hidden">
                            <?= t('faq_a5') ?>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden shadow-sm">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-800 transition" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('i').classList.toggle('rotate-180');">
                            <span class="font-semibold text-gray-900 dark:text-white"><?= t('faq_q6') ?></span>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                        </button>
                        <div class="px-6 py-4 text-gray-600 dark:text-gray-400 border-t border-gray-100 dark:border-gray-800 hidden">
                            <?= t('faq_a6') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-20 text-center bg-blue-600 rounded-3xl p-10 text-white shadow-xl shadow-blue-500/20">
            <h3 class="text-2xl font-bold mb-4"><?= t('faq_still_questions') ?></h3>
            <p class="text-blue-100 mb-8"><?= t('faq_contact_desc') ?></p>
            <a href="<?= BASE_URL ?>contact" class="inline-flex items-center gap-2 bg-white text-blue-600 px-8 py-3 rounded-xl font-bold hover:bg-blue-50 transition shadow-lg">
                <i class="fas fa-envelope"></i>
                <?= t('contact_support') ?>
            </a>
        </div>
    </div>
</div>
