<nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6 flex-wrap">
    <a href="<?= BASE_URL ?>" class="hover:text-blue-600 transition"><?= t('home') ?></a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <a href="<?= BASE_URL ?>profile" class="hover:text-blue-600 transition"><?= t('my_account') ?></a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span class="text-gray-800 dark:text-gray-200 font-medium"><?= t('payment_methods') ?></span>
</nav>

<div class="card p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
            <i class="fas fa-credit-card text-blue-500"></i> <?= t('payment_methods') ?>
        </h3>
        <button onclick="document.getElementById('addMethodForm').classList.toggle('hidden')" class="btn btn-primary text-sm">
            <i class="fas fa-plus mr-1"></i> <?= t('add_payment_method') ?>
        </button>
    </div>

    <!-- Add Payment Method Form -->
    <div id="addMethodForm" class="hidden mb-6 p-5 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
        <h4 class="font-medium text-gray-900 dark:text-white mb-4"><?= t('new_payment_method') ?></h4>
        <form action="<?= BASE_URL ?>account/payment-method/save" method="POST" class="space-y-4 max-w-md">
            <div>
                <label class="form-label" for="pmType"><?= t('payment_type') ?></label>
                <select name="type" id="pmType" class="form-input">
                    <option value="visa"><?= t('visa') ?></option>
                    <option value="mastercard"><?= t('mastercard') ?></option>
                    <option value="aba"><?= t('aba') ?></option>
                    <option value="acleda"><?= t('acleda') ?></option>
                    <option value="khqr"><?= t('khqr') ?></option>
                </select>
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 cursor-pointer">
                <input type="checkbox" name="is_default" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <?= t('set_as_default') ?>
            </label>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn btn-primary"><?= t('save') ?></button>
                <button type="button" onclick="document.getElementById('addMethodForm').classList.add('hidden')" class="btn btn-outline"><?= t('cancel') ?></button>
            </div>
        </form>
    </div>

    <?php if (empty($methods)): ?>
        <div class="text-center py-12 text-gray-400">
            <i class="fas fa-credit-card text-4xl mb-3"></i>
            <p class="text-sm"><?= t('no_payment_methods') ?></p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <?php foreach ($methods as $method): ?>
                <div class="border border-gray-200 dark:border-gray-600 rounded-xl p-4 bg-white dark:bg-gray-800 hover:shadow-md transition-shadow">
                    <div class="flex items-start gap-3">
                        <div class="w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0
                            <?php
                            $iconBg = 'bg-gray-100 dark:bg-gray-700';
                            $icon = 'fa-credit-card';
                            if ($method['type'] === 'visa') { $iconBg = 'bg-blue-100 dark:bg-blue-900/50'; $icon = 'fa-cc-visa'; }
                            elseif ($method['type'] === 'mastercard') { $iconBg = 'bg-red-100 dark:bg-red-900/50'; $icon = 'fa-cc-mastercard'; }
                            elseif ($method['type'] === 'aba') { $iconBg = 'bg-blue-100 dark:bg-blue-900/50'; $icon = 'fa-university'; }
                            elseif ($method['type'] === 'acleda') { $iconBg = 'bg-green-100 dark:bg-green-900/50'; $icon = 'fa-university'; }
                            elseif ($method['type'] === 'khqr') { $iconBg = 'bg-purple-100 dark:bg-purple-900/50'; $icon = 'fa-qrcode'; }
                            ?> <?= $iconBg ?> text-blue-600 dark:text-blue-400 text-xl">
                            <i class="fab <?= $icon ?>"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-medium text-gray-900 dark:text-white uppercase"><?= htmlspecialchars($method['type']) ?></span>
                                <?php if (!empty($method['is_default'])): ?>
                                    <span class="badge"><?= t('default') ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($method['last_four'])): ?>
                                <p class="text-sm text-gray-600 dark:text-gray-400">•••• •••• •••• <?= htmlspecialchars($method['last_four']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($method['cardholder_name'])): ?>
                                <p class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($method['cardholder_name']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($method['expiry_month']) && !empty($method['expiry_year'])): ?>
                                <p class="text-xs text-gray-400 mt-1"><?= t('expires') ?>: <?= sprintf('%02d', $method['expiry_month']) ?>/<?= $method['expiry_year'] ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="flex items-center gap-1 flex-shrink-0">
                            <?php if (empty($method['is_default'])): ?>
                                <a href="<?= BASE_URL ?>account/payment-method/default?id=<?= $method['id'] ?>" class="btn btn-outline p-2 text-xs" title="<?= t('set_default') ?>">
                                    <i class="fas fa-check"></i>
                                </a>
                            <?php endif; ?>
                            <a href="<?= BASE_URL ?>account/payment-method/delete?id=<?= $method['id'] ?>" onclick="return confirm('<?= t('confirm_delete') ?>')" class="btn btn-danger p-2 text-xs" title="<?= t('delete') ?>">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
