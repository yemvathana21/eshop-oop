<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-600 text-white rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                <i class="fas fa-comments text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Customer Inquiries</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Manage and respond to customer support tickets.</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-700 text-sm font-bold text-gray-600 dark:text-gray-400">
                <?= count($messages) ?> Messages
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-900/50 text-gray-400 text-[10px] uppercase tracking-[0.15em] font-black">
                        <th class="px-8 py-5">Ticket Status</th>
                        <th class="px-8 py-5">Sender Details</th>
                        <th class="px-8 py-5">Inquiry Subject</th>
                        <th class="px-8 py-5">Received Date</th>
                        <th class="px-8 py-5 text-right">Control</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                    <?php if (empty($messages)): ?>
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center opacity-20">
                                    <i class="fas fa-inbox text-6xl mb-4 text-gray-400"></i>
                                    <p class="text-lg font-bold text-gray-500">No messages yet.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($messages as $msg): ?>
                            <tr class="hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-colors group">
                                <td class="px-8 py-5">
                                    <?php if ($msg['status'] === 'unread'): ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black bg-blue-600 text-white uppercase shadow-sm">
                                            New
                                        </span>
                                    <?php elseif ($msg['status'] === 'read'): ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400 uppercase">
                                            Read
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black bg-green-500 text-white uppercase shadow-sm">
                                            Replied
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-400 font-bold text-xs">
                                            <?= strtoupper(substr($msg['name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-1.5">
                                                <div class="text-sm font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($msg['name']) ?></div>
                                                <?php if($msg['user_id']): ?>
                                                    <i class="fas fa-circle-check text-blue-500 text-[10px]" title="Registered Customer"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div class="text-[11px] text-gray-400"><?= htmlspecialchars($msg['email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="text-sm text-gray-700 dark:text-gray-300 max-w-xs truncate font-medium">
                                        <?= htmlspecialchars($msg['subject'] ?: '(No Subject)') ?>
                                    </div>
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap text-xs text-gray-400 font-medium">
                                    <?= date('M d, Y', strtotime($msg['created_at'])) ?>
                                    <span class="block text-[10px] opacity-60"><?= date('h:i A', strtotime($msg['created_at'])) ?></span>
                                </td>
                                <td class="px-8 py-5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="<?= BASE_URL ?>admin/message?id=<?= $msg['id'] ?>"
                                           class="w-9 h-9 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-500 hover:text-blue-600 hover:border-blue-200 dark:hover:border-blue-800 flex items-center justify-center transition shadow-sm"
                                           title="Open Ticket">
                                            <i class="fas fa-arrow-right-to-bracket text-xs"></i>
                                        </a>
                                        <button onclick="confirmDelete(<?= $msg['id'] ?>)"
                                                class="w-9 h-9 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-400 hover:text-red-600 hover:border-red-200 flex items-center justify-center transition shadow-sm">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    confirmAction('Are you sure you want to permanently delete this message? This cannot be undone.', function() {
        window.location.href = '<?= BASE_URL ?>admin/message/delete?id=' + id;
    });
}
</script>
