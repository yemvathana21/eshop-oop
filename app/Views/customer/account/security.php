<nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6 flex-wrap">
    <a href="<?= BASE_URL ?>" class="hover:text-blue-600 transition"><?= t('home') ?></a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <a href="<?= BASE_URL ?>profile" class="hover:text-blue-600 transition"><?= t('my_account') ?></a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span class="text-gray-800 dark:text-gray-200 font-medium"><?= t('security') ?></span>
</nav>

<div class="space-y-6">
    <!-- Change Password -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
            <i class="fas fa-key text-blue-500"></i> <?= t('change_password') ?>
        </h3>

        <form action="<?= BASE_URL ?>account/security/password" method="POST" class="max-w-md space-y-4">
            <div>
                <label class="form-label" for="currentPassword"><?= t('current_password') ?></label>
                <input type="password" name="current_password" id="currentPassword" required class="form-input">
            </div>
            <div>
                <label class="form-label" for="newPassword"><?= t('new_password') ?></label>
                <input type="password" name="new_password" id="newPassword" required minlength="6"
                       onkeyup="checkPasswordStrength(this)" class="form-input">
                <div id="passwordStrength" class="mt-2">
                    <div class="h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div id="strengthBar" class="h-full rounded-full transition-all duration-300 w-0"></div>
                    </div>
                    <p id="strengthText" class="text-xs text-gray-500 dark:text-gray-400 mt-1"><?= t('enter_password') ?></p>
                </div>
            </div>
            <div>
                <label class="form-label" for="confirmPassword"><?= t('confirm_new_password') ?></label>
                <input type="password" name="confirm_password" id="confirmPassword" required minlength="6" class="form-input">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn btn-primary"><?= t('update_password') ?></button>
            </div>
        </form>
    </div>

    <!-- Login History -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
            <i class="fas fa-clock-rotate text-blue-500"></i> <?= t('login_history') ?>
        </h3>

        <?php if (empty($loginHistory)): ?>
            <div class="text-center py-8 text-gray-400">
                <i class="fas fa-history text-3xl mb-2"></i>
                <p class="text-sm"><?= t('no_login_history') ?></p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700 text-left text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <th class="px-3 py-3 font-medium"><?= t('date_time') ?></th>
                            <th class="px-3 py-3 font-medium"><?= t('ip_address') ?></th>
                            <th class="px-3 py-3 font-medium hidden sm:table-cell"><?= t('browser') ?></th>
                            <th class="px-3 py-3 font-medium hidden md:table-cell"><?= t('os') ?></th>
                            <th class="px-3 py-3 font-medium hidden lg:table-cell"><?= t('device') ?></th>
                            <th class="px-3 py-3 font-medium hidden lg:table-cell"><?= t('location') ?></th>
                            <th class="px-3 py-3 font-medium"><?= t('status') ?></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        <?php foreach ($loginHistory as $login): ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                <td class="px-3 py-3 text-gray-900 dark:text-gray-200 whitespace-nowrap"><?= htmlspecialchars(date('M j, Y g:i A', strtotime($login['logged_at']))) ?></td>
                                <td class="px-3 py-3 text-gray-600 dark:text-gray-400 font-mono text-xs"><?= htmlspecialchars($login['ip_address']) ?></td>
                                <td class="px-3 py-3 text-gray-600 dark:text-gray-400 hidden sm:table-cell"><?= htmlspecialchars($login['browser']) ?></td>
                                <td class="px-3 py-3 text-gray-600 dark:text-gray-400 hidden md:table-cell"><?= htmlspecialchars($login['os']) ?></td>
                                <td class="px-3 py-3 text-gray-600 dark:text-gray-400 hidden lg:table-cell"><?= htmlspecialchars($login['device_type']) ?></td>
                                <td class="px-3 py-3 text-gray-600 dark:text-gray-400 hidden lg:table-cell"><?= htmlspecialchars($login['location']) ?></td>
                                <td class="px-3 py-3">
                                    <?php if (!empty($login['success'])): ?>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                            <i class="fas fa-check-circle"></i> <?= t('success') ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                                            <i class="fas fa-times-circle"></i> <?= t('failed') ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Active Devices -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
            <i class="fas fa-laptop text-blue-500"></i> <?= t('active_devices') ?>
        </h3>

        <?php if (empty($devices)): ?>
            <div class="text-center py-8 text-gray-400">
                <i class="fas fa-laptop text-3xl mb-2"></i>
                <p class="text-sm"><?= t('no_devices') ?></p>
            </div>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($devices as $device): ?>
                    <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 flex-shrink-0">
                            <i class="fas <?= $device['device_type'] === 'Mobile' ? 'fa-mobile-screen' : ($device['device_type'] === 'Tablet' ? 'fa-tablet' : 'fa-laptop') ?>"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($device['device_name'] ?: $device['browser'] . ' on ' . $device['os']) ?></p>
                                <?php if (!empty($device['is_current'])): ?>
                                    <span class="text-[10px] bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-2 py-0.5 rounded-full font-medium"><?= t('current') ?></span>
                                <?php endif; ?>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                <?= htmlspecialchars($device['browser']) ?> &middot; <?= htmlspecialchars($device['os']) ?> &middot; <?= htmlspecialchars($device['ip_address']) ?>
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5"><?= t('last_active') ?>: <?= htmlspecialchars(date('M j, Y g:i A', strtotime($device['last_active']))) ?></p>
                        </div>
                        <?php if (empty($device['is_current'])): ?>
                            <a href="<?= BASE_URL ?>account/security/revoke-device?id=<?= $device['id'] ?? '' ?>" onclick="return confirm('<?= t('confirm_revoke_device') ?>')" class="btn btn-outline p-2 text-xs text-red-500 hover:text-red-700" title="<?= t('revoke') ?>">
                                <i class="fas fa-right-from-bracket"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function checkPasswordStrength(input) {
    const bar = document.getElementById('strengthBar');
    const text = document.getElementById('strengthText');
    const val = input.value;

    if (!val) {
        bar.style.width = '0%';
        bar.className = 'h-full rounded-full transition-all duration-300 w-0';
        text.textContent = '<?= t('enter_password') ?>';
        text.className = 'text-xs text-gray-500 dark:text-gray-400 mt-1';
        return;
    }

    let score = 0;
    if (val.length >= 6) score++;
    if (val.length >= 10) score++;
    if (/[a-z]/.test(val) && /[A-Z]/.test(val)) score++;
    if (/\d/.test(val)) score++;
    if (/[^a-zA-Z0-9]/.test(val)) score++;

    const configs = [
        { width: '20%', color: 'bg-red-500', label: '<?= t('weak') ?>', labelClass: 'text-red-500' },
        { width: '40%', color: 'bg-orange-500', label: '<?= t('fair') ?>', labelClass: 'text-orange-500' },
        { width: '60%', color: 'bg-yellow-500', label: '<?= t('good') ?>', labelClass: 'text-yellow-500' },
        { width: '80%', color: 'bg-blue-500', label: '<?= t('strong') ?>', labelClass: 'text-blue-500' },
        { width: '100%', color: 'bg-green-500', label: '<?= t('very_strong') ?>', labelClass: 'text-green-500' },
    ];

    const cfg = configs[Math.min(score, configs.length - 1)];
    bar.style.width = cfg.width;
    bar.className = 'h-full rounded-full transition-all duration-300 ' + cfg.color;
    text.textContent = cfg.label;
    text.className = 'text-xs mt-1 ' + cfg.labelClass;
}
</script>
