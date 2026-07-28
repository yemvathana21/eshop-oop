<?php
$section = $_GET['section'] ?? 'profile';
$activeTab = fn($s) => $section === $s ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700';
?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
        <a href="<?= BASE_URL ?>" class="hover:text-blue-600 transition"><?= t('home') ?></a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium"><?= t('my_profile') ?></span>
    </nav>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Navigation -->
        <div class="lg:w-72 flex-shrink-0">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden sticky top-28">
                <!-- User Mini Profile -->
                <div class="p-5 text-center border-b border-gray-100 dark:border-gray-700">
                    <div class="w-16 h-16 rounded-full overflow-hidden mx-auto bg-gray-100 dark:bg-gray-700 ring-3 ring-blue-100 dark:ring-blue-900/50">
                        <?php if (!empty($user['avatar'])): ?>
                            <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($user['avatar']) ?>" alt="" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($user['name']) ?>&background=3b82f6&color=fff'; this.onerror=null;">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 font-bold text-xl"><?= strtoupper(substr($user['name'], 0, 1)) ?></div>
                        <?php endif; ?>
                    </div>
                    <h2 class="mt-3 font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($user['name']) ?></h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($user['email']) ?></p>
                </div>

                <!-- Nav Items -->
                <nav class="p-3 space-y-1">
                    <a href="<?= BASE_URL ?>profile?section=profile" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition <?= $activeTab('profile') ?>">
                        <i class="fas fa-user w-4 text-center"></i> <?= t('edit_profile') ?>
                    </a>
                    <a href="<?= BASE_URL ?>profile?section=security" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition <?= $activeTab('security') ?>">
                        <i class="fas fa-shield-alt w-4 text-center"></i> <?= t('security') ?>
                    </a>
                    <a href="<?= BASE_URL ?>profile?section=billing" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition <?= $activeTab('billing') ?>">
                        <i class="fas fa-credit-card w-4 text-center"></i> <?= t('billing_membership') ?>
                    </a>
                    <a href="<?= BASE_URL ?>profile?section=connected" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition <?= $activeTab('connected') ?>">
                        <i class="fas fa-link w-4 text-center"></i> <?= t('connected_accounts') ?>
                    </a>
                    <a href="<?= BASE_URL ?>profile?section=privacy" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition <?= $activeTab('privacy') ?>">
                        <i class="fas fa-lock w-4 text-center"></i> <?= t('privacy_data') ?>
                    </a>
                    <hr class="border-gray-100 dark:border-gray-700 my-2">
                    <a href="<?= BASE_URL ?>my-orders" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <i class="fas fa-receipt w-4 text-center"></i> <?= t('my_orders') ?>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 min-w-0 space-y-6">

            <?php if ($section === 'profile'): ?>
            <!-- ====== EDIT PROFILE ====== -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <i class="fas fa-user-pen text-blue-500"></i> <?= t('edit_profile') ?>
                </h3>
                <form action="<?= BASE_URL ?>profile/update" method="POST" enctype="multipart/form-data" class="space-y-5">
                    <div class="flex items-center gap-6 mb-2">
                        <div class="relative">
                            <div class="w-20 h-20 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700 ring-4 ring-gray-100 dark:ring-gray-700">
                                <?php if (!empty($user['avatar'])): ?>
                                    <img id="avatarPreview" src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($user['avatar']) ?>" alt="" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div id="avatarPreview" class="w-full h-full flex items-center justify-center bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 font-bold text-2xl"><?= strtoupper(substr($user['name'], 0, 1)) ?></div>
                                <?php endif; ?>
                            </div>
                            <label class="absolute bottom-0 right-0 w-7 h-7 bg-blue-600 text-white rounded-full flex items-center justify-center cursor-pointer hover:bg-blue-700 transition shadow-sm text-xs">
                                <i class="fas fa-camera"></i>
                                <input type="file" name="avatar" accept="image/*" class="hidden" onchange="const f=this.files[0];if(f){const r=new FileReader();r.onload=e=>{const p=document.getElementById('avatarPreview');if(p.tagName==='IMG')p.src=e.target.result;else{const img=document.createElement('img');img.className='w-full h-full object-cover';img.src=e.target.result;p.parentNode.replaceChild(img,p)}};r.readAsDataURL(f)}">
                            </label>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($user['name']) ?></p>
                            <p class="text-sm text-gray-500"><?= t('member_since') ?> <?= date('M Y', strtotime($user['created_at'])) ?></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5"><?= t('full_name') ?></label>
                            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5"><?= t('email') ?></label>
                            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5"><?= t('phone') ?></label>
                            <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5"><?= t('address') ?></label>
                        <textarea name="address" rows="2" class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none text-sm"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                    </div>
                    <div class="flex justify-end pt-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition shadow-sm"><i class="fas fa-save mr-2"></i><?= t('save') ?></button>
                    </div>
                </form>
            </div>

            <?php elseif ($section === 'security'): ?>
            <!-- ====== SECURITY ====== -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <i class="fas fa-shield-alt text-blue-500"></i> <?= t('security') ?>
                </h3>

                <!-- Change Password -->
                <div class="pb-6 mb-6 border-b border-gray-100 dark:border-gray-700">
                    <h4 class="font-medium text-gray-900 dark:text-white mb-1"><?= t('change_password') ?></h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4"><?= t('password_recommendation') ?></p>
                    <form action="<?= BASE_URL ?>profile/password" method="POST" class="max-w-md space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('current_password') ?></label>
                            <input type="password" name="current_password" required class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('new_password') ?></label>
                                <input type="password" name="new_password" required minlength="6" class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('confirm_new_password') ?></label>
                                <input type="password" name="confirm_password" required minlength="6" class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                            </div>
                        </div>
                        <button type="submit" class="bg-gray-800 dark:bg-gray-700 hover:bg-gray-900 dark:hover:bg-gray-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition"><i class="fas fa-key mr-2"></i><?= t('update_password') ?></button>
                    </form>
                </div>

                <!-- Activate Phone -->
                <div class="pb-6 mb-6 border-b border-gray-100 dark:border-gray-700">
                    <h4 class="font-medium text-gray-900 dark:text-white mb-1"><?= t('activate_phone') ?></h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4"><?= t('phone_verification_desc') ?></p>
                    <div class="flex items-center gap-3">
                        <div class="flex-1 max-w-xs">
                            <input type="tel" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" readonly placeholder="<?= t('no_phone_set') ?>" class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm">
                        </div>
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-medium <?= empty($user['phone']) ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' : 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' ?>">
                            <i class="fas <?= empty($user['phone']) ? 'fa-exclamation-triangle' : 'fa-check-circle' ?>"></i>
                            <?= empty($user['phone']) ? t('not_activated') : t('activated') ?>
                        </span>
                    </div>
                </div>

                <!-- Devices / Active Sessions -->
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-white mb-1"><?= t('active_devices') ?></h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4"><?= t('devices_desc') ?></p>
                    <div class="space-y-3">
                        <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center text-blue-600 dark:text-blue-400 flex-shrink-0">
                                <i class="fas fa-laptop"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white"><?= t('current_session') ?></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400"><?= $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown' ?></p>
                            </div>
                            <span class="text-xs text-green-600 dark:text-green-400 font-medium"><?= t('active_now') ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <?php elseif ($section === 'billing'): ?>
            <!-- ====== BILLING & MEMBERSHIP ====== -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <i class="fas fa-credit-card text-blue-500"></i> <?= t('billing_membership') ?>
                </h3>

                <!-- Membership Info -->
                <div class="pb-6 mb-6 border-b border-gray-100 dark:border-gray-700">
                    <h4 class="font-medium text-gray-900 dark:text-white mb-1"><?= t('membership_info') ?></h4>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-4">
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-center">
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400"><?= $orderCount ?></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400"><?= t('total_orders') ?></p>
                        </div>
                        <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg text-center">
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">$<?= number_format($totalSpent, 2) ?></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400"><?= t('total_spent') ?></p>
                        </div>
                        <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg text-center">
                            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400"><?= t('free') ?></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400"><?= t('membership') ?></p>
                        </div>
                        <div class="p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg text-center">
                            <p class="text-2xl font-bold text-orange-600 dark:text-orange-400"><?= date('M Y', strtotime($user['created_at'])) ?></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400"><?= t('member_since') ?></p>
                        </div>
                    </div>
                </div>

                <!-- Billing Addresses -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-medium text-gray-900 dark:text-white"><?= t('billing_address') ?></h4>
                        <button onclick="document.getElementById('addressForm').classList.toggle('hidden')" class="text-sm text-blue-600 dark:text-blue-400 hover:underline font-medium">
                            <i class="fas fa-plus mr-1"></i><?= t('add_address') ?>
                        </button>
                    </div>

                    <!-- Address Form -->
                    <div id="addressForm" class="hidden p-5 bg-gray-50 dark:bg-gray-700/50 rounded-xl mb-4 border border-gray-200 dark:border-gray-600">
                        <form action="<?= BASE_URL ?>profile/address/save" method="POST" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('full_name') ?></label>
                                    <input type="text" name="full_name" value="<?= htmlspecialchars($user['name']) ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('phone') ?></label>
                                    <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                            </div>

                            <!-- Cambodia Address Hierarchy -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('province') ?></label>
                                    <select name="province_code" id="selProvince" onchange="loadDistricts(this.value)" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                                        <option value=""><?= t('select_province') ?></option>
                                        <?php foreach ($provinces as $p): ?>
                                            <option value="<?= $p['code'] ?>"><?= htmlspecialchars($p['name_en']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('district') ?></label>
                                    <select name="district_code" id="selDistrict" onchange="loadCommunes(this.value)" disabled class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                                        <option value=""><?= t('select_district') ?></option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('commune') ?></label>
                                    <select name="commune_code" id="selCommune" onchange="loadVillages(this.value)" disabled class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                                        <option value=""><?= t('select_commune') ?></option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('village') ?></label>
                                    <select name="village_code" id="selVillage" disabled class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                                        <option value=""><?= t('select_village') ?></option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('street') ?></label>
                                <input type="text" name="street" placeholder="House #, Street name" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>

                            <div class="flex items-center gap-4">
                                <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                    <input type="checkbox" name="is_default" value="1"> <?= t('set_as_default') ?>
                                </label>
                            </div>

                            <div class="flex gap-3 pt-2">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition"><i class="fas fa-save mr-1"></i><?= t('save') ?></button>
                                <button type="button" onclick="this.closest('#addressForm').classList.add('hidden')" class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 px-5 py-2 rounded-lg text-sm transition"><?= t('cancel') ?></button>
                            </div>
                        </form>
                    </div>

                    <!-- Existing Addresses -->
                    <?php if (empty($addresses)): ?>
                        <div class="text-center py-8 text-gray-400">
                            <i class="fas fa-map-marker-alt text-3xl mb-2"></i>
                            <p class="text-sm"><?= t('no_addresses') ?></p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach ($addresses as $addr): ?>
                                <div class="flex items-start gap-4 p-4 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700/30">
                                    <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 flex-shrink-0">
                                        <i class="fas fa-map-pin"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-sm font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($addr['label']) ?></span>
                                            <?php if ($addr['is_default']): ?>
                                                <span class="text-[10px] bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 px-2 py-0.5 rounded-full font-medium"><?= t('default') ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400"><?= htmlspecialchars($this->addressModel->getFullAddress($addr)) ?></p>
                                        <p class="text-xs text-gray-400 mt-1"><?= htmlspecialchars($addr['phone'] ?? '') ?></p>
                                    </div>
                                    <a href="<?= BASE_URL ?>profile/address/delete?id=<?= $addr['id'] ?>" onclick="return confirm('<?= t('confirm_delete') ?>')" class="text-red-400 hover:text-red-600 transition p-1"><i class="fas fa-trash-alt text-sm"></i></a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php elseif ($section === 'connected'): ?>
            <!-- ====== CONNECTED ACCOUNTS ====== -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <i class="fas fa-link text-blue-500"></i> <?= t('connected_accounts') ?>
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white"><i class="fab fa-facebook-f"></i></div>
                            <div><p class="text-sm font-medium text-gray-900 dark:text-white">Facebook</p><p class="text-xs text-gray-500"><?= t('not_connected') ?></p></div>
                        </div>
                        <button class="text-sm text-blue-600 dark:text-blue-400 hover:underline font-medium"><?= t('connect') ?></button>
                    </div>
                    <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white"><i class="fab fa-telegram-plane"></i></div>
                            <div><p class="text-sm font-medium text-gray-900 dark:text-white">Telegram</p><p class="text-xs text-gray-500"><?= t('not_connected') ?></p></div>
                        </div>
                        <button class="text-sm text-blue-600 dark:text-blue-400 hover:underline font-medium"><?= t('connect') ?></button>
                    </div>
                    <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-black rounded-lg flex items-center justify-center text-white"><i class="fab fa-github"></i></div>
                            <div><p class="text-sm font-medium text-gray-900 dark:text-white">Google</p><p class="text-xs text-gray-500"><?= t('not_connected') ?></p></div>
                        </div>
                        <button class="text-sm text-blue-600 dark:text-blue-400 hover:underline font-medium"><?= t('connect') ?></button>
                    </div>
                </div>
            </div>

            <?php elseif ($section === 'privacy'): ?>
            <!-- ====== PRIVACY & DATA ====== -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <i class="fas fa-lock text-blue-500"></i> <?= t('privacy_data') ?>
                </h3>

                <!-- Privacy Settings -->
                <div class="pb-6 mb-6 border-b border-gray-100 dark:border-gray-700">
                    <h4 class="font-medium text-gray-900 dark:text-white mb-1"><?= t('privacy_settings') ?></h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4"><?= t('privacy_settings_desc') ?></p>
                    <div class="space-y-3">
                        <label class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg cursor-pointer">
                            <div><p class="text-sm font-medium text-gray-900 dark:text-white"><?= t('show_email_profile') ?></p><p class="text-xs text-gray-500"><?= t('show_email_profile_desc') ?></p></div>
                            <input type="checkbox" checked class="rounded text-blue-600 focus:ring-blue-500">
                        </label>
                        <label class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg cursor-pointer">
                            <div><p class="text-sm font-medium text-gray-900 dark:text-white"><?= t('show_order_history') ?></p><p class="text-xs text-gray-500"><?= t('show_order_history_desc') ?></p></div>
                            <input type="checkbox" checked class="rounded text-blue-600 focus:ring-blue-500">
                        </label>
                    </div>
                </div>

                <!-- Delete Account -->
                <div>
                    <h4 class="font-medium text-red-600 dark:text-red-400 mb-1"><?= t('delete_account') ?></h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4"><?= t('delete_account_desc') ?></p>
                    <button onclick="document.getElementById('deleteForm').classList.toggle('hidden')" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition"><i class="fas fa-trash-alt mr-2"></i><?= t('delete_account') ?></button>
                    <div id="deleteForm" class="hidden mt-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <p class="text-sm text-red-600 dark:text-red-400 font-medium mb-3"><?= t('delete_account_warning') ?></p>
                        <form action="<?= BASE_URL ?>profile/delete-account" method="POST" class="flex items-center gap-3">
                            <input type="password" name="password" required placeholder="<?= t('enter_password_confirm') ?>" class="flex-1 px-4 py-2 border border-red-200 dark:border-red-800 rounded-lg text-sm focus:ring-2 focus:ring-red-500 outline-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition whitespace-nowrap"><i class="fas fa-trash-alt mr-1"></i><?= t('confirm_delete') ?></button>
                        </form>
                    </div>
                </div>
            </div>

            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Cascading address dropdowns
function loadDistricts(provinceCode) {
    const sel = document.getElementById('selDistrict');
    const commune = document.getElementById('selCommune');
    const village = document.getElementById('selVillage');
    sel.innerHTML = '<option value="">Loading...</option>';
    sel.disabled = true;
    commune.innerHTML = '<option value=""><?= t('select_commune') ?></option>';
    commune.disabled = true;
    village.innerHTML = '<option value=""><?= t('select_village') ?></option>';
    village.disabled = true;
    if (!provinceCode) { sel.innerHTML = '<option value=""><?= t('select_district') ?></option>'; return; }
    fetch('<?= BASE_URL ?>api/districts?province_code=' + provinceCode)
        .then(r => r.json()).then(data => {
            sel.innerHTML = '<option value=""><?= t('select_district') ?></option>' + data.map(d => '<option value="' + d.code + '">' + d.name_en + '</option>').join('');
            sel.disabled = false;
        });
}

function loadCommunes(districtCode) {
    const sel = document.getElementById('selCommune');
    const village = document.getElementById('selVillage');
    sel.innerHTML = '<option value="">Loading...</option>';
    sel.disabled = true;
    village.innerHTML = '<option value=""><?= t('select_village') ?></option>';
    village.disabled = true;
    if (!districtCode) { sel.innerHTML = '<option value=""><?= t('select_commune') ?></option>'; return; }
    fetch('<?= BASE_URL ?>api/communes?district_code=' + districtCode)
        .then(r => r.json()).then(data => {
            sel.innerHTML = '<option value=""><?= t('select_commune') ?></option>' + data.map(c => '<option value="' + c.code + '">' + c.name_en + '</option>').join('');
            sel.disabled = false;
        });
}

function loadVillages(communeCode) {
    const sel = document.getElementById('selVillage');
    sel.innerHTML = '<option value="">Loading...</option>';
    sel.disabled = true;
    if (!communeCode) { sel.innerHTML = '<option value=""><?= t('select_village') ?></option>'; return; }
    fetch('<?= BASE_URL ?>api/villages?commune_code=' + communeCode)
        .then(r => r.json()).then(data => {
            sel.innerHTML = '<option value=""><?= t('select_village') ?></option>' + data.map(v => '<option value="' + v.code + '">' + v.name_en + '</option>').join('');
            sel.disabled = false;
        });
}
</script>
