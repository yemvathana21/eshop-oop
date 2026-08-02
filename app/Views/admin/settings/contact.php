<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center text-xl">
                <i class="fas fa-address-book"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Contact & Store Info</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Manage your store's contact details, location, and social media links.</p>
            </div>
        </div>

        <form action="<?= BASE_URL ?>admin/settings/contact/save" method="POST" class="space-y-8">
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Store Name</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fas fa-store"></i>
                        </span>
                        <input type="text" name="store_name" value="<?= htmlspecialchars($settings['store_name'] ?? '') ?>"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-blue-500 outline-none transition"
                            placeholder="e.g. General Online Store">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Contact Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" name="store_email" value="<?= htmlspecialchars($settings['store_email'] ?? '') ?>"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-blue-500 outline-none transition"
                            placeholder="contact@example.com">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Phone Number</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fas fa-phone"></i>
                        </span>
                        <input type="text" name="store_phone" value="<?= htmlspecialchars($settings['store_phone'] ?? '') ?>"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-blue-500 outline-none transition"
                            placeholder="+855 12 345 678">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Store Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fas fa-map-marker-alt"></i>
                        </span>
                        <input type="text" name="store_address" value="<?= htmlspecialchars($settings['store_address'] ?? '') ?>"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-blue-500 outline-none transition"
                            placeholder="123 Street, Phnom Penh">
                    </div>
                </div>
            </div>

            <!-- Map Iframe -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Google Maps Embed URL</label>
                <div class="relative">
                    <span class="absolute top-3 left-3 text-gray-400">
                        <i class="fas fa-map"></i>
                    </span>
                    <textarea name="store_map" rows="3"
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-blue-500 outline-none transition"
                        placeholder="Paste your Google Maps iframe 'src' URL here..."><?= htmlspecialchars($settings['store_map'] ?? '') ?></textarea>
                </div>
                <p class="text-xs text-gray-500">Go to Google Maps -> Share -> Embed a map -> Copy the 'src' attribute value.</p>
            </div>

            <!-- Social Media -->
            <div class="pt-6 border-t border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Social Media Links</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 text-blue-600"><i class="fab fa-facebook mr-1"></i> Facebook</label>
                        <input type="text" name="facebook_url" value="<?= htmlspecialchars($settings['facebook_url'] ?? '') ?>"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-blue-500 outline-none transition"
                            placeholder="https://facebook.com/yourpage">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 text-sky-500"><i class="fab fa-telegram mr-1"></i> Telegram</label>
                        <input type="text" name="telegram_url" value="<?= htmlspecialchars($settings['telegram_url'] ?? '') ?>"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-blue-500 outline-none transition"
                            placeholder="https://t.me/yourusername">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 text-pink-600"><i class="fab fa-tiktok mr-1"></i> TikTok</label>
                        <input type="text" name="tiktok_url" value="<?= htmlspecialchars($settings['tiktok_url'] ?? '') ?>"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-2 focus:ring-blue-500 outline-none transition"
                            placeholder="https://tiktok.com/@yourusername">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-10 rounded-xl transition shadow-lg shadow-blue-500/20">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
