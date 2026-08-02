<div class="text-center mb-8">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white"><?= t('welcome_back') ?></h2>
    <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm"><?= t('sign_in_continue') ?></p>
</div>
<form method="POST" action="<?= BASE_URL ?>login" class="space-y-5">
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('email_address') ?></label>
        <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-envelope"></i></span>
            <input type="email" name="email" required placeholder="you@example.com"
                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('password') ?></label>
        <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-lock"></i></span>
            <input type="password" name="password" required placeholder="••••••••"
                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
        </div>
    </div>
    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition shadow-sm">
        <?= t('sign_in') ?>
    </button>
</form>
<p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-6">
    <?= t('no_account') ?> <a href="<?= BASE_URL ?>register" class="text-blue-600 dark:text-blue-400 hover:underline font-medium"><?= t('create_one') ?></a>
</p>
<p class="text-center text-xs text-gray-400 dark:text-gray-500 mt-3">Admin: admin@store.com / admin123</p>
