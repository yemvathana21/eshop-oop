<nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
    <a href="<?= BASE_URL ?>" class="hover:text-blue-600 transition"><?= t('home') ?></a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <a href="<?= BASE_URL ?>account/dashboard" class="hover:text-blue-600 transition"><?= t('my_account') ?></a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span class="text-gray-800 dark:text-gray-200 font-medium"><?= t('username') ?></span>
</nav>

<div class="card p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1 flex items-center gap-2">
        <i class="fas fa-at text-blue-500"></i> <?= t('username') ?>
    </h3>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Choose a unique username for your profile.</p>

    <form action="<?= BASE_URL ?>account/username/save" method="POST" class="max-w-md space-y-4">
        <div>
            <label class="form-label"><?= t('username') ?></label>
            <input type="text" name="username" value="<?= htmlspecialchars($user['name']) ?>" required class="form-input" placeholder="Enter username">
        </div>
        <div class="flex justify-end pt-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> <?= t('save') ?></button>
        </div>
    </form>
</div>
