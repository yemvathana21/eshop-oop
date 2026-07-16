<div class="space-y-6">
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500 dark:text-gray-400"><?= count($users) ?> <?= t('users_total') ?></p>
        <a href="<?= BASE_URL ?>admin/user/create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
            <i class="fas fa-plus mr-1"></i><?= t('add_user') ?>
        </a>
    </div>

    <?php if (empty($users)): ?>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 text-center py-16 transition-colors">
        <i class="fas fa-users text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
        <p class="text-gray-500 dark:text-gray-400 text-lg mb-4"><?= t('no_users_yet') ?></p>
        <a href="<?= BASE_URL ?>admin/user/create" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition text-sm"><?= t('add_first_user') ?></a>
    </div>
    <?php else: ?>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-100 dark:border-gray-600">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300">#</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('user') ?></th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('email') ?></th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('role') ?></th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('orders') ?></th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('joined') ?></th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('actions') ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php foreach ($users as $u): ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="py-3 px-4 text-gray-500 dark:text-gray-400"><?= $u['id'] ?></td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 <?= $u['role'] === 'admin' ? 'bg-purple-100 dark:bg-purple-900/50 text-purple-600 dark:text-purple-400' : 'bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400' ?> rounded-full flex items-center justify-center text-xs font-bold">
                                    <?= strtoupper(substr($u['name'], 0, 1)) ?>
                                </div>
                                <span class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($u['name']) ?></span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-gray-500 dark:text-gray-400"><?= htmlspecialchars($u['email']) ?></td>
                        <td class="py-3 px-4">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold <?= $u['role'] === 'admin' ? 'bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-400' : 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-400' ?>">
                                <?= ucfirst($u['role']) ?>
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="text-gray-500 dark:text-gray-400"><?= $u['order_count'] ?? 0 ?></span>
                        </td>
                        <td class="py-3 px-4 text-gray-500 dark:text-gray-400 text-xs"><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="<?= BASE_URL ?>admin/user/edit?id=<?= $u['id'] ?>" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition px-2 py-1" title="<?= t('edit') ?>">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if ($u['id'] != \App\Core\Session::get('user_id')): ?>
                                <a href="<?= BASE_URL ?>admin/user/delete?id=<?= $u['id'] ?>" class="text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition px-2 py-1" title="<?= t('delete') ?>" onclick="return confirm('<?= t('confirm_delete_user') ?>')">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>
