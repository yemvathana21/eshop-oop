<?php $isEdit = !empty($user); ?>
<div class="max-w-2xl">
    <a href="<?= BASE_URL ?>admin/users" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 mb-6 text-sm font-medium">
        <i class="fas fa-arrow-left mr-2"></i><?= t('back_to_users') ?>
    </a>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 transition-colors">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6"><?= $isEdit ? t('edit_user') : t('add_new_user') ?></h2>

        <form method="POST" action="<?= BASE_URL ?>admin/user/<?= $isEdit ? 'update' : 'save' ?>" enctype="multipart/form-data" class="space-y-5">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= $user['id'] ?>">
            <?php endif; ?>

            <div class="flex items-center gap-6 mb-4">
                <div class="w-16 h-16 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700 flex-shrink-0">
                    <?php if ($isEdit && !empty($user['avatar'])): ?>
                        <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($user['avatar']) ?>" alt="" class="w-full h-full object-cover"
                             onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($user['name']) ?>&background=3b82f6&color=fff'; this.onerror=null;">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 font-bold text-lg">
                            <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if ($isEdit): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('avatar') ?></label>
                        <input type="file" name="avatar" accept="image/*"
                               class="text-sm text-gray-500 dark:text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-blue-50 dark:file:bg-blue-900/30 file:text-blue-600 dark:file:text-blue-400 hover:file:bg-blue-100">
                    </div>
                <?php endif; ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('full_name') ?> <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required value="<?= htmlspecialchars($user['name'] ?? '') ?>"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                        placeholder="<?= t('enter_full_name') ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('email') ?> <span class="text-red-500">*</span></label>
                    <input type="email" name="email" required value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                        placeholder="user@example.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('phone') ?></label>
                    <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('password') ?> <?= $isEdit ? '' : '<span class="text-red-500">*</span>' ?></label>
                    <input type="password" name="password" <?= $isEdit ? '' : 'required' ?> minlength="6"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                        placeholder="<?= $isEdit ? t('leave_blank_no_change') : t('min_6_chars') ?>">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('address') ?></label>
                <textarea name="address" rows="2"
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('role') ?></label>
                <select name="role"
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="customer" <?= ($user['role'] ?? 'customer') === 'customer' ? 'selected' : '' ?>><?= t('customer') ?></option>
                    <option value="admin" <?= ($user['role'] ?? '') === 'admin' ? 'selected' : '' ?>><?= t('admin') ?></option>
                </select>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium transition">
                    <i class="fas fa-save mr-2"></i><?= $isEdit ? t('update_user') : t('create_user') ?>
                </button>
                <a href="<?= BASE_URL ?>admin/users" class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-6 py-2.5 rounded-lg font-medium transition"><?= t('cancel') ?></a>
            </div>
        </form>
    </div>
</div>
