<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center gap-4">
        <a href="<?= BASE_URL ?>admin/messages" class="w-10 h-10 bg-white dark:bg-gray-800 rounded-xl flex items-center justify-center text-gray-500 hover:text-blue-600 shadow-sm border border-gray-100 dark:border-gray-700 transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Message from <?= htmlspecialchars($message['name']) ?></h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Message Content -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">
                <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-50 dark:border-gray-700">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center font-bold text-lg">
                            <?= strtoupper(substr($message['name'], 0, 1)) ?>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <div class="font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($message['name']) ?></div>
                                <?php if($message['user_id']): ?>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-400 uppercase tracking-wider">Customer</span>
                                <?php endif; ?>
                            </div>
                            <div class="text-xs text-gray-500"><?= htmlspecialchars($message['email']) ?></div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-gray-400 mb-1"><?= date('F d, Y', strtotime($message['created_at'])) ?></div>
                        <div class="text-[10px] text-gray-400 font-mono"><?= date('h:i A', strtotime($message['created_at'])) ?></div>
                    </div>
                </div>

                <div class="mb-8">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Subject</h3>
                    <div class="text-lg font-bold text-gray-800 dark:text-white">
                        <?= htmlspecialchars($message['subject'] ?: '(No Subject)') ?>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Message</h3>
                    <div class="text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap bg-gray-50 dark:bg-gray-900/50 p-6 rounded-2xl border border-gray-100 dark:border-gray-700">
                        <?= htmlspecialchars($message['message']) ?>
                    </div>
                </div>
            </div>

            <!-- Reply Form -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <i class="fas fa-reply text-blue-500"></i>
                    Reply to Customer
                </h3>

                <form action="<?= BASE_URL ?>admin/message/reply" method="POST" class="space-y-6">
                    <input type="hidden" name="id" value="<?= $message['id'] ?>">

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Your Response</label>
                        <textarea name="reply_message" rows="6" required
                            class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl py-4 px-5 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all resize-none"
                            placeholder="Type your reply here..."><?= htmlspecialchars($message['reply_message'] ?? '') ?></textarea>
                        <p class="text-[10px] text-gray-500 italic mt-1">Note: This response will be saved for internal record. (External email sending requires SMTP configuration)</p>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-10 rounded-xl transition shadow-lg shadow-blue-500/25 flex items-center gap-2">
                            <i class="fas fa-paper-plane"></i>
                            Save & Mark as Replied
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar / Status -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6">Message Status</h3>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Current Status:</span>
                        <?php if ($message['status'] === 'unread'): ?>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-400 uppercase">Unread</span>
                        <?php elseif ($message['status'] === 'read'): ?>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400 uppercase">Read</span>
                        <?php else: ?>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-600 dark:bg-green-900/40 dark:text-green-400 uppercase">Replied</span>
                        <?php endif; ?>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Received:</span>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300"><?= date('M d, Y', strtotime($message['created_at'])) ?></span>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-50 dark:border-gray-700">
                    <button onclick="confirmDelete(<?= $message['id'] ?>)" class="w-full py-3 rounded-xl border border-red-100 dark:border-red-900/30 text-red-500 hover:bg-red-500 hover:text-white transition-all text-sm font-bold flex items-center justify-center gap-2">
                        <i class="fas fa-trash-alt"></i>
                        Delete Message
                    </button>
                </div>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/10 rounded-2xl p-6 border border-blue-100 dark:border-blue-900/30">
                <h4 class="text-blue-600 dark:text-blue-400 font-bold text-sm mb-2 flex items-center gap-2">
                    <i class="fas fa-info-circle"></i> Quick Tip
                </h4>
                <p class="text-[12px] text-blue-600/70 dark:text-blue-400/60 leading-relaxed">
                    Once you save a reply, the message status will automatically change to <span class="font-bold">Replied</span>. You can edit your reply at any time.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    confirmAction('Are you sure you want to permanently delete this message?', function() {
        window.location.href = '<?= BASE_URL ?>admin/message/delete?id=' + id;
    });
}
</script>
