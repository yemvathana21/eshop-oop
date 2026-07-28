<nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6 flex-wrap">
    <a href="<?= BASE_URL ?>" class="hover:text-blue-600 transition"><?= t('home') ?></a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <a href="<?= BASE_URL ?>profile" class="hover:text-blue-600 transition"><?= t('my_account') ?></a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span class="text-gray-800 dark:text-gray-200 font-medium"><?= t('notifications') ?></span>
</nav>

<div class="card p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
        <i class="fas fa-bell text-blue-500"></i> <?= t('notification_preferences') ?>
    </h3>

    <form action="<?= BASE_URL ?>account/notifications/save" method="POST" class="space-y-6">
        <!-- General Settings -->
        <div>
            <h4 class="font-medium text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                <i class="fas fa-cog text-gray-400 text-sm"></i> <?= t('general_notifications') ?>
            </h4>
            <div class="space-y-3">
                <label class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl cursor-pointer">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white"><?= t('email_notifications') ?></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400"><?= t('email_notifications_desc') ?></p>
                    </div>
                    <label class="toggle">
                        <input type="checkbox" name="email_notifications" value="1" <?= !empty($prefs['email_notifications']) ? 'checked' : '' ?>>
                        <div class="toggle-slider"></div>
                    </label>
                </label>
                <label class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl cursor-pointer">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white"><?= t('sms_notifications') ?></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400"><?= t('sms_notifications_desc') ?></p>
                    </div>
                    <label class="toggle">
                        <input type="checkbox" name="sms_notifications" value="1" <?= !empty($prefs['sms_notifications']) ? 'checked' : '' ?>>
                        <div class="toggle-slider"></div>
                    </label>
                </label>
            </div>
        </div>

        <!-- Order Notifications -->
        <div>
            <h4 class="font-medium text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                <i class="fas fa-receipt text-gray-400 text-sm"></i> <?= t('order_notifications') ?>
            </h4>
            <div class="space-y-3">
                <label class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl cursor-pointer">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white"><?= t('order_updates') ?></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400"><?= t('order_updates_desc') ?></p>
                    </div>
                    <label class="toggle">
                        <input type="checkbox" name="order_updates" value="1" <?= !empty($prefs['order_updates']) ? 'checked' : '' ?>>
                        <div class="toggle-slider"></div>
                    </label>
                </label>
            </div>
        </div>

        <!-- Marketing -->
        <div>
            <h4 class="font-medium text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                <i class="fas fa-bullhorn text-gray-400 text-sm"></i> <?= t('marketing') ?>
            </h4>
            <div class="space-y-3">
                <label class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl cursor-pointer">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white"><?= t('promotions') ?></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400"><?= t('promotions_desc') ?></p>
                    </div>
                    <label class="toggle">
                        <input type="checkbox" name="promotions" value="1" <?= !empty($prefs['promotions']) ? 'checked' : '' ?>>
                        <div class="toggle-slider"></div>
                    </label>
                </label>
                <label class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl cursor-pointer">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white"><?= t('newsletter') ?></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400"><?= t('newsletter_desc') ?></p>
                    </div>
                    <label class="toggle">
                        <input type="checkbox" name="newsletter" value="1" <?= !empty($prefs['newsletter']) ? 'checked' : '' ?>>
                        <div class="toggle-slider"></div>
                    </label>
                </label>
            </div>
        </div>

        <!-- Alerts -->
        <div>
            <h4 class="font-medium text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                <i class="fas fa-bell-exclamation text-gray-400 text-sm"></i> <?= t('alerts') ?>
            </h4>
            <div class="space-y-3">
                <label class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl cursor-pointer">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white"><?= t('price_drop_alerts') ?></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400"><?= t('price_drop_alerts_desc') ?></p>
                    </div>
                    <label class="toggle">
                        <input type="checkbox" name="price_drop_alerts" value="1" <?= !empty($prefs['price_drop_alerts']) ? 'checked' : '' ?>>
                        <div class="toggle-slider"></div>
                    </label>
                </label>
                <label class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl cursor-pointer">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white"><?= t('back_in_stock') ?></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400"><?= t('back_in_stock_desc') ?></p>
                    </div>
                    <label class="toggle">
                        <input type="checkbox" name="back_in_stock" value="1" <?= !empty($prefs['back_in_stock']) ? 'checked' : '' ?>>
                        <div class="toggle-slider"></div>
                    </label>
                </label>
            </div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> <?= t('save_preferences') ?>
            </button>
        </div>
    </form>
</div>
