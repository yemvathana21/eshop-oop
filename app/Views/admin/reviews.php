<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= t('manage_reviews') ?></h1>
            <p class="text-sm text-gray-500 dark:text-gray-400"><?= count($reviews) ?> <?= t('total_reviews') ?></p>
        </div>
    </div>

    <?php if (empty($reviews)): ?>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 text-center py-16 transition-colors">
        <i class="fas fa-star text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
        <p class="text-gray-500 dark:text-gray-400 text-lg"><?= t('no_reviews_yet_admin') ?></p>
    </div>
    <?php else: ?>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-100 dark:border-gray-600">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('product') ?></th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('user') ?></th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('rating') ?></th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('reviews') ?></th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('date') ?></th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-600 dark:text-gray-300"><?= t('actions') ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php foreach ($reviews as $review): ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="py-3 px-4">
                            <p class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($review['product_name']) ?></p>
                            <a href="<?= BASE_URL ?>admin/product/edit?id=<?= $review['product_id'] ?>" class="text-xs text-blue-600 dark:text-blue-400 hover:underline"><?= t('view') ?> <i class="fas fa-edit text-[10px]"></i></a>
                        </td>
                        <td class="py-3 px-4">
                            <p class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($review['user_name']) ?></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($review['user_email']) ?></p>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex justify-center gap-0.5">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?= $i <= $review['rating'] ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' ?> text-xs"></i>
                                <?php endfor; ?>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 max-w-xs"><?= htmlspecialchars($review['comment'] ?? '') ?></p>
                        </td>
                        <td class="py-3 px-4 text-xs text-gray-500 dark:text-gray-400"><?= date('M d, Y', strtotime($review['created_at'])) ?></td>
                        <td class="py-3 px-4 text-right">
                            <a href="<?= BASE_URL ?>admin/review/delete?id=<?= $review['id'] ?>" 
                               class="text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition px-2 py-1" 
                               title="<?= t('delete') ?>"
                               onclick="return confirm('<?= t('confirm_delete_review') ?>')">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>
