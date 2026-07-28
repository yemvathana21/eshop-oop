<div class="max-w-2xl mx-auto pb-10">
    <div class="mb-6">
        <a href="<?= BASE_URL ?>account/dashboard" class="text-sm text-blue-600 dark:text-blue-400 hover:underline"><i class="fas fa-chevron-left mr-1"></i> Settings</a>
    </div>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Edit Profile</h1>

    <form action="<?= BASE_URL ?>account/profile/update" method="POST" enctype="multipart/form-data">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex flex-col items-center py-4 mb-6">
                <div class="relative mb-3">
                    <?php if (!empty($user['avatar'])): ?>
                        <img id="avatarPreview" src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($user['avatar']) ?>" alt="" class="w-20 h-20 rounded-full object-cover border-2 border-blue-500">
                    <?php else: ?>
                        <div id="avatarPreview" class="w-20 h-20 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-2xl font-bold text-blue-600 dark:text-blue-400 border-2 border-blue-500"><?= strtoupper(substr($user['name'], 0, 1)) ?></div>
                    <?php endif; ?>
                    <label for="avatarInput" class="absolute bottom-0 right-0 w-7 h-7 bg-blue-600 text-white rounded-full flex items-center justify-center cursor-pointer text-xs shadow"><i class="fas fa-camera"></i></label>
                    <input id="avatarInput" type="file" name="avatar" accept="image/*" class="hidden" onchange="const f=this.files[0];if(f){const r=new FileReader();r.onload=e=>{const p=document.getElementById('avatarPreview');if(p.tagName==='IMG')p.src=e.target.result;else{const img=document.createElement('img');img.className='w-20 h-20 rounded-full object-cover';img.src=e.target.result;p.parentNode.replaceChild(img,p)}};r.readAsDataURL(f)}">
                </div>
                <span class="font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($user['name']) ?></span>
                <span class="text-sm text-gray-500"><?= t('member_since') ?> <?= date('M Y', strtotime($user['created_at'])) ?></span>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1"><?= t('full_name') ?></label>
                    <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1"><?= t('email') ?></label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1"><?= t('phone') ?></label>
                    <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition"><i class="fas fa-check mr-1"></i> <?= t('save_changes') ?></button>
            </div>
        </div>
    </form>
</div>
