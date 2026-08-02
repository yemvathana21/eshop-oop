<div class="max-w-4xl mx-auto space-y-8 pb-10">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight"><?= t('customer_messages') ?></h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Track your support tickets and responses from our team.</p>
        </div>
        <a href="<?= BASE_URL ?>contact" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition-all shadow-lg shadow-blue-500/25 group">
            <i class="fas fa-plus mr-2 group-hover:rotate-90 transition-transform duration-300"></i>
            New Inquiry
        </a>
    </div>

    <?php if (empty($messages)): ?>
        <!-- Empty State -->
        <div class="bg-white dark:bg-gray-900 rounded-[2rem] p-16 text-center border border-gray-100 dark:border-gray-800 shadow-sm">
            <div class="relative w-24 h-24 mx-auto mb-8">
                <div class="absolute inset-0 bg-blue-100 dark:bg-blue-900/30 rounded-full animate-pulse"></div>
                <div class="relative flex items-center justify-center w-full h-full text-blue-600 dark:text-blue-400 text-4xl">
                    <i class="fas fa-comment-dots"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">No Conversations Found</h3>
            <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto leading-relaxed mb-10">
                You haven't reached out to our support team yet. If you have any questions about your orders or products, feel free to contact us!
            </p>
            <a href="<?= BASE_URL ?>contact" class="inline-flex items-center px-8 py-3.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold rounded-2xl hover:scale-105 transition shadow-xl">
                Contact Support Now
            </a>
        </div>
    <?php else: ?>
        <!-- Messages List -->
        <div class="space-y-6">
            <?php foreach ($messages as $msg): ?>
                <div class="group bg-white dark:bg-gray-900 rounded-[1.5rem] shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden hover:shadow-md hover:border-blue-100 dark:hover:border-blue-900/30 transition-all duration-300">
                    <!-- Card Header / Meta -->
                    <div class="px-6 py-5 border-b border-gray-50 dark:border-gray-800/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-50/50 dark:bg-gray-800/20">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white dark:bg-gray-800 rounded-xl flex items-center justify-center shadow-sm border border-gray-100 dark:border-gray-700 text-blue-600 dark:text-blue-400">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white line-clamp-1"><?= htmlspecialchars($msg['subject'] ?: 'Support Inquiry') ?></h4>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest"><?= date('M d, Y', strtotime($msg['created_at'])) ?></span>
                                    <span class="text-[10px] text-gray-300">•</span>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest"><?= date('h:i A', strtotime($msg['created_at'])) ?></span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <?php if ($msg['status'] === 'replied'): ?>
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-extrabold bg-green-500/10 text-green-600 dark:text-green-400 border border-green-500/20 uppercase tracking-widest">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-2 animate-pulse"></span> Replied
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-extrabold bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20 uppercase tracking-widest">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2 animate-pulse"></span> Processing
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Conversation Thread -->
                    <div class="p-6 md:p-8 space-y-8">
                        <!-- Customer Message (Right Aligned style but contained) -->
                        <div class="flex flex-col items-start max-w-[90%]">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Your Message</span>
                            </div>
                            <div class="relative bg-gray-50 dark:bg-gray-800/50 p-6 rounded-2xl rounded-tl-none border border-gray-100 dark:border-gray-700 shadow-inner">
                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                    <?= nl2br(htmlspecialchars($msg['message'])) ?>
                                </p>
                            </div>
                        </div>

                        <!-- Support Response (Indented / Different Color) -->
                        <?php if (!empty($msg['reply_message'])): ?>
                            <div class="flex flex-col items-end ml-auto max-w-[90%]">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-[10px] font-bold text-blue-500 uppercase tracking-widest">Support Team</span>
                                    <div class="w-5 h-5 bg-blue-600 text-white rounded-full flex items-center justify-center text-[8px]">
                                        <i class="fas fa-headset"></i>
                                    </div>
                                </div>
                                <div class="relative bg-blue-600 text-white p-6 rounded-2xl rounded-tr-none shadow-xl shadow-blue-500/10">
                                    <p class="text-sm leading-relaxed font-medium">
                                        <?= nl2br(htmlspecialchars($msg['reply_message'])) ?>
                                    </p>
                                    <!-- Decoration -->
                                    <div class="absolute -right-2 top-0 w-4 h-4 bg-blue-600 rotate-45 -z-10"></div>
                                </div>
                                <p class="text-[9px] text-gray-400 font-bold mt-2 uppercase tracking-tighter">Replied on <?= date('M d, Y', strtotime($msg['updated_at'])) ?></p>
                            </div>
                        <?php else: ?>
                            <!-- Pending State -->
                            <div class="flex flex-col items-center justify-center py-6 bg-gray-50/30 dark:bg-gray-800/10 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                                <div class="flex items-center gap-3 text-gray-400 dark:text-gray-600">
                                    <i class="fas fa-hourglass-start animate-bounce"></i>
                                    <span class="text-xs font-bold uppercase tracking-widest">Waiting for Support Team</span>
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1">Our team typically responds within 24 hours.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
/* Custom Scrollbar for better look */
.custom-scrollbar::-webkit-scrollbar {
    width: 5px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(59, 130, 246, 0.2);
    border-radius: 10px;
}
</style>
