<?php
$settingModel = new \App\Models\Setting\Setting();
$siteSettings = $settingModel->all();
?>
<div class="bg-gray-50 dark:bg-gray-950 py-16 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-4"><?= t('contact_us') ?></h1>
            <p class="text-lg text-gray-500 dark:text-gray-400 max-w-2xl mx-auto"><?= t('contact_subtitle') ?></p>
        </div>

        <div class="grid lg:grid-cols-3 gap-10">
            <!-- Contact Info -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white dark:bg-gray-900 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6"><?= t('contact_info') ?></h3>

                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white"><?= t('contact_location') ?></h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?= htmlspecialchars($siteSettings['store_address'] ?? '123 Russian Blvd, Phnom Penh, Cambodia') ?></p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white"><?= t('contact_phone') ?></h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?= htmlspecialchars($siteSettings['store_phone'] ?? '+855 (0) 23 456 789') ?></p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white"><?= t('contact_email') ?></h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?= htmlspecialchars($siteSettings['store_email'] ?? 'support@generalstore.com') ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-4"><?= t('contact_follow') ?></h4>
                        <div class="flex gap-3">
                            <?php if(!empty($siteSettings['facebook_url'])): ?>
                            <a href="<?= $siteSettings['facebook_url'] ?>" target="_blank" class="w-10 h-10 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 rounded-xl flex items-center justify-center hover:bg-blue-600 hover:text-white transition duration-300">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <?php endif; ?>
                            <?php if(!empty($siteSettings['telegram_url'])): ?>
                            <a href="<?= $siteSettings['telegram_url'] ?>" target="_blank" class="w-10 h-10 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 rounded-xl flex items-center justify-center hover:bg-sky-500 hover:text-white transition duration-300">
                                <i class="fab fa-telegram-plane"></i>
                            </a>
                            <?php endif; ?>
                            <?php if(!empty($siteSettings['tiktok_url'])): ?>
                            <a href="<?= $siteSettings['tiktok_url'] ?>" target="_blank" class="w-10 h-10 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 rounded-xl flex items-center justify-center hover:bg-pink-600 hover:text-white transition duration-300">
                                <i class="fab fa-tiktok"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-900 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-8"><?= t('contact_send_message') ?></h3>

                    <form action="<?= BASE_URL ?>contact/submit" method="POST" class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="name" class="text-sm font-semibold text-gray-700 dark:text-gray-300"><?= t('contact_form_name') ?> <span class="text-red-500">*</span></label>
                                <input type="text" id="name" name="name" required
                                    class="w-full bg-gray-50 dark:bg-gray-800 border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-blue-500 transition-all"
                                    placeholder="<?= t('contact_form_name_placeholder') ?>">
                            </div>
                            <div class="space-y-2">
                                <label for="email" class="text-sm font-semibold text-gray-700 dark:text-gray-300"><?= t('contact_form_email') ?> <span class="text-red-500">*</span></label>
                                <input type="email" id="email" name="email" required
                                    class="w-full bg-gray-50 dark:bg-gray-800 border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-blue-500 transition-all"
                                    placeholder="<?= t('contact_form_email_placeholder') ?>">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="subject" class="text-sm font-semibold text-gray-700 dark:text-gray-300"><?= t('contact_form_subject') ?></label>
                            <input type="text" id="subject" name="subject"
                                class="w-full bg-gray-50 dark:bg-gray-800 border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-blue-500 transition-all"
                                placeholder="<?= t('contact_form_subject_placeholder') ?>">
                        </div>

                        <div class="space-y-2">
                            <label for="message" class="text-sm font-semibold text-gray-700 dark:text-gray-300"><?= t('contact_form_message') ?> <span class="text-red-500">*</span></label>
                            <textarea id="message" name="message" rows="5" required
                                class="w-full bg-gray-50 dark:bg-gray-800 border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-blue-500 transition-all resize-none"
                                placeholder="<?= t('contact_form_message_placeholder') ?>"></textarea>
                        </div>

                        <button type="submit"
                            class="w-full md:w-auto px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-500/25 flex items-center justify-center gap-2">
                            <i class="fas fa-paper-plane"></i>
                            <?= t('contact_form_submit') ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Google Maps Placeholder -->
        <div class="mt-16 bg-white dark:bg-gray-900 p-2 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 h-96 overflow-hidden relative">
            <?php
            $mapUrl = $siteSettings['store_map'] ?? '';

            // Clean up the URL if it contains HTML or extra characters
            if (!empty($mapUrl)) {
                // If it's a full iframe tag, extract the src
                if (strpos($mapUrl, '<iframe') !== false) {
                    if (preg_match('/src=["\']([^"\' ]+)["\']/', $mapUrl, $matches)) {
                        $mapUrl = $matches[1];
                    }
                }
                // Strip tags just in case
                $mapUrl = strip_tags($mapUrl);
                // Ensure it doesn't have stray quotes or spaces
                $mapUrl = trim($mapUrl, " \t\n\r\0\x0B\"'");
            }

            // Final check: if it doesn't look like a URL, use default
            if (empty($mapUrl) || (strpos($mapUrl, 'http') !== 0 && strpos($mapUrl, '//') !== 0)) {
                $mapUrl = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d125063.12356534246!2d104.82144889726563!3d11.562108000000004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3109513dc76a6d4b%3A0x12c98889139591fa!2sPhnom%20Penh!5e0!3m2!1sen!2skh!4v1689254848245!5m2!1sen!2skh';
            }
            ?>
            <iframe
                src="<?= htmlspecialchars($mapUrl) ?>"
                class="w-full h-full relative z-10 border-0"
                allowfullscreen=""
                loading="lazy">
            </iframe>
        </div>
    </div>
</div>
