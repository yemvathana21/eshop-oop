<nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
    <a href="<?= BASE_URL ?>" class="hover:text-blue-600 transition"><?= t('home') ?></a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <a href="<?= BASE_URL ?>account/dashboard" class="hover:text-blue-600 transition"><?= t('my_account') ?></a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span class="text-gray-800 dark:text-gray-200 font-medium"><?= t('passkeys') ?></span>
</nav>

<div class="card p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1 flex items-center gap-2">
        <i class="fas fa-fingerprint text-blue-500"></i> <?= t('passkeys') ?>
    </h3>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Use passkeys for a faster and more secure sign-in. No password needed.</p>

    <div class="text-center py-12">
        <i class="fas fa-fingerprint text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No passkeys yet</h4>
        <p class="text-gray-500 dark:text-gray-400 text-sm mb-6 max-w-md mx-auto">Create a passkey to sign in with your face, fingerprint, or device PIN.</p>
        <button disabled class="btn btn-primary opacity-60 cursor-not-allowed"><i class="fas fa-plus mr-1"></i> Create Passkey</button>
        <p class="text-xs text-gray-400 mt-3">Coming soon</p>
    </div>
</div>
