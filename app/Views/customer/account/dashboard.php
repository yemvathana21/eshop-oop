<?php
$userId = \App\Core\Session::getUserId();
$connectedAccounts = $connectedAccounts ?? [];
$devicesList = $devicesList ?? [];
$prefs = $prefs ?? [];
$currentLang = \App\Core\Lang\Language::current();
$accountProviders = [
    ['id' => 'facebook', 'name' => 'Facebook'],
    ['id' => 'google', 'name' => 'Google'],
    ['id' => 'telegram', 'name' => 'Telegram'],
    ['id' => 'apple', 'name' => 'Apple'],
];
function accountConnected($provider, $accounts) {
    foreach ($accounts as $a) {
        if ($a['provider'] === $provider && !empty($a['connected'])) return true;
    }
    return false;
}
$accountStatus = fn($p) => accountConnected($p, $connectedAccounts) ? 'Connected' : 'Not Linked';
?>
<div class="max-w-2xl mx-auto space-y-6 pb-10">

    <!-- Search -->
    <div class="relative">
        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
        <input id="settingsSearch" type="text" placeholder="Search settings..."
               class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-gray-100 outline-none focus:border-blue-500">
    </div>

    <!-- Profile Card -->
    <div onclick="openModal('profile')" class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="relative">
                <?php if (!empty($user['avatar'])): ?>
                    <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($user['avatar']) ?>" alt="" class="w-14 h-14 rounded-full object-cover">
                <?php else: ?>
                    <div class="w-14 h-14 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-xl">
                        <?= strtoupper(substr($user['name'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
            </div>
            <div>
                <h2 class="font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($user['name']) ?></h2>
                <p class="text-sm text-gray-500 dark:text-gray-400"><?= htmlspecialchars($user['email']) ?></p>
            </div>
        </div>
        <i class="fas fa-chevron-right text-gray-400"></i>
    </div>

    <!-- Profile Section -->
    <div>
        <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 px-1 mb-1">Profile</h3>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700">
            <div onclick="openModal('profile')" class="flex items-center justify-between px-4 py-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700" data-search="edit profile name email avatar phone address gender birth location">
                <div class="flex items-center gap-3">
                    <i class="fas fa-user-gear w-5 text-center text-gray-500"></i>
                    <span class="text-sm font-medium text-gray-900 dark:text-white"><?= t('edit_profile') ?></span>
                </div>
                <i class="fas fa-chevron-right text-xs text-gray-400"></i>
            </div>
            <a href="<?= BASE_URL ?>account/orders" class="flex items-center justify-between px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700" data-search="orders history">
                <div class="flex items-center gap-3">
                    <i class="fas fa-receipt w-5 text-center text-gray-500"></i>
                    <span class="text-sm font-medium text-gray-900 dark:text-white"><?= t('my_orders') ?></span>
                </div>
                <i class="fas fa-chevron-right text-xs text-gray-400"></i>
            </a>
            <a href="<?= BASE_URL ?>account/addresses" class="flex items-center justify-between px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700" data-search="address shipping billing">
                <div class="flex items-center gap-3">
                    <i class="fas fa-map-location-dot w-5 text-center text-gray-500"></i>
                    <span class="text-sm font-medium text-gray-900 dark:text-white"><?= t('my_addresses') ?></span>
                </div>
                <i class="fas fa-chevron-right text-xs text-gray-400"></i>
            </a>
        </div>
    </div>

    <!-- Preferences & Security -->
    <div>
        <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 px-1 mb-1"><?= t('settings_title') ?></h3>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700">
            <div onclick="openModal('appearance')" class="flex items-center justify-between px-4 py-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700" data-search="theme dark mode light language">
                <div class="flex items-center gap-3">
                    <i class="fas fa-palette w-5 text-center text-gray-500"></i>
                    <span class="text-sm font-medium text-gray-900 dark:text-white"><?= t('appearance') ?></span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500 capitalize"><?= $prefs['theme'] ?? 'System' ?></span>
                    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                </div>
            </div>
            <div onclick="openModal('security')" class="flex items-center justify-between px-4 py-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700" data-search="password security devices">
                <div class="flex items-center gap-3">
                    <i class="fas fa-shield-halved w-5 text-center text-gray-500"></i>
                    <span class="text-sm font-medium text-gray-900 dark:text-white"><?= t('security') ?></span>
                </div>
                <i class="fas fa-chevron-right text-xs text-gray-400"></i>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700">
            <div onclick="openModal('delete_account')" class="flex items-center justify-between px-4 py-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700" data-search="delete account permanently">
                <div class="flex items-center gap-3">
                    <i class="fas fa-user-xmark w-5 text-center text-red-500"></i>
                    <span class="text-sm font-medium text-red-500"><?= t('delete_account') ?></span>
                </div>
                <i class="fas fa-chevron-right text-xs text-gray-400"></i>
            </div>
        </div>
    </div>

    <!-- Logout -->
    <button onclick="openModal('logout')" class="w-full py-3 text-red-500 font-medium text-sm rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
        <i class="fas fa-right-from-bracket mr-2"></i> Sign Out
    </button>
</div>

<!-- Modal Overlay -->
<div id="modalOverlay" class="fixed top-0 left-0 w-full h-full z-50 hidden flex items-center justify-center p-4" style="background: rgba(0,0,0,0.4);" onclick="if(event.target===this)closeModal()">
    <div id="modalSheet" class="w-full sm:max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-h-[90vh] flex flex-col overflow-hidden mx-auto">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700 shrink-0">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-900 dark:text-white"></h3>
            <button onclick="closeModal()" class="p-1 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300"><i class="fas fa-times text-xl"></i></button>
        </div>
        <div id="modalBody" class="p-5 overflow-y-auto"></div>
    </div>
</div>

<script>
let currentModal = null;
function openModal(type) {
    currentModal = type;
    var overlay = document.getElementById('modalOverlay');
    var title = document.getElementById('modalTitle');
    var body = document.getElementById('modalBody');
    overlay.classList.remove('hidden');
    document.body.classList.add('modal-open');
    var c = modalContents[type];
    if (c) { title.textContent = c.title; body.innerHTML = c.html; }
    body.querySelectorAll('script').forEach(function(s) {
        var ns = document.createElement('script');
        Array.from(s.attributes).forEach(function(a) { ns.setAttribute(a.name, a.value); });
        ns.textContent = s.textContent;
        s.parentNode.replaceChild(ns, s);
    });
}
function closeModal() {
    document.getElementById('modalOverlay').classList.add('hidden');
    document.body.classList.remove('modal-open');
    currentModal = null;
}
document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeModal(); });

document.getElementById('settingsSearch').addEventListener('input', function() {
    var q = this.value.toLowerCase().trim();
    document.querySelectorAll('[data-search]').forEach(function(el) {
        var text = (el.getAttribute('data-search') || el.textContent).toLowerCase();
        el.style.display = (!q || text.includes(q)) ? '' : 'none';
    });
});

var modalContents = {};

modalContents.profile = {
    title: 'Edit Profile',
    html: '<form id="profileForm" onsubmit="return submitProfileForm(event)">' +
        '<div class="flex flex-col items-center py-4 mb-4">' +
            '<div class="relative mb-2">' +
                '<?php if (!empty($user['avatar'])): ?>' +
                '<img id="profilePreview" src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($user['avatar']) ?>" alt="" class="w-20 h-20 rounded-full object-cover border-2 border-blue-500">' +
                '<?php else: ?>' +
                '<div id="profilePreview" class="w-20 h-20 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-2xl font-bold text-blue-600 dark:text-blue-400 border-2 border-blue-500"><?= strtoupper(substr($user['first_name'] ?: $user['name'], 0, 1)) ?></div>' +
                '<?php endif; ?>' +
                '<label for="modalAvatarInput" class="absolute bottom-0 right-0 w-7 h-7 bg-blue-600 text-white rounded-full flex items-center justify-center cursor-pointer text-xs shadow"><i class="fas fa-camera"></i></label>' +
                '<input id="modalAvatarInput" type="file" name="avatar" accept="image/*" class="hidden" onchange="previewModalAvatar(this)">' +
            '</div>' +
        '</div>' +
        '<div class="space-y-4">' +
            '<div class="grid grid-cols-2 gap-4">' +
                '<div><label class="block text-xs font-medium text-gray-500 mb-1">First Name <span class="text-red-500">*</span></label><input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" required minlength="2" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500"><p class="text-xs text-gray-400 mt-1">The first name must be at least 2 characters long.</p></div>' +
                '<div><label class="block text-xs font-medium text-gray-500 mb-1">Last Name <span class="text-red-500">*</span></label><input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" required minlength="2" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500"><p class="text-xs text-gray-400 mt-1">The last name must be at least 2 characters long.</p></div>' +
            '</div>' +
            '<div class="grid grid-cols-2 gap-4">' +
                '<div><label class="block text-xs font-medium text-gray-500 mb-1">Gender</label><select name="gender" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500"><option value="">Select Gender</option><option value="male" <?= ($user['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Male</option><option value="female" <?= ($user['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Female</option><option value="other" <?= ($user['gender'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option></select></div>' +
                '<div><label class="block text-xs font-medium text-gray-500 mb-1">Date Of Birth</label><input type="date" name="date_of_birth" value="<?= htmlspecialchars($user['date_of_birth'] ?? '') ?>" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500"></div>' +
            '</div>' +
            '<div><label class="block text-xs font-medium text-gray-500 mb-1">Email</label><input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500"></div>' +
            '<div class="grid grid-cols-2 gap-4">' +
                '<div><label class="block text-xs font-medium text-gray-500 mb-1">Company</label><input type="text" name="company" value="<?= htmlspecialchars($user['company'] ?? '') ?>" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500"></div>' +
                '<div><label class="block text-xs font-medium text-gray-500 mb-1">Phone Number</label><input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500"></div>' +
            '</div>' +
            '<div><label class="block text-xs font-medium text-gray-500 mb-1">Location</label><select name="location" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500"><option value="">Choose Location</option><?php foreach ($provinces as $p): ?><option value="<?= htmlspecialchars($p['name_en'] ?? $p['code']) ?>" <?= ($user['location'] ?? '') === ($p['name_en'] ?? $p['code']) ? 'selected' : '' ?>><?= htmlspecialchars($p['name_en'] ?? $p['code']) ?></option><?php endforeach; ?></select></div>' +
            '<div><label class="block text-xs font-medium text-gray-500 mb-1">Address</label><textarea name="address" rows="3" placeholder="Enter the address (e.g., House No. 225, Serey Vuth Street, Phnom Penh)" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500 resize-none"><?= htmlspecialchars($user['address'] ?? '') ?></textarea><button type="button" onclick="document.getElementsByName(\'location\')[0].focus()" class="text-xs text-blue-600 hover:text-blue-700 mt-1">Change Location</button></div>' +
        '</div>' +
        '<div class="flex gap-3 mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">' +
            '<button type="button" onclick="closeModal()" class="flex-1 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition">Cancel</button>' +
            '<button type="submit" class="flex-1 py-2.5 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition">Save</button>' +
        '</div>' +
    '</form>'
};

function previewModalAvatar(input) {
    var file = input.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var preview = document.getElementById('profilePreview');
        var img = document.createElement('img');
        img.id = 'profilePreview';
        img.className = 'w-20 h-20 rounded-full object-cover border-2 border-blue-500';
        img.src = e.target.result;
        if (preview.tagName === 'IMG') preview.src = e.target.result;
        else preview.parentNode.replaceChild(img, preview);
    };
    reader.readAsDataURL(file);
}

function submitProfileForm(e) {
    e.preventDefault();
    var form = e.target;
    var fd = new FormData(form);
    fd.append('ajax', '1');
    fetch('<?= BASE_URL ?>account/profile/update', { method: 'POST', body: fd })
    .then(function(r) { return r.json(); }).then(function(d) {
        if (d.success) { showToast(d.message || 'Profile updated'); closeModal(); setTimeout(function() { location.reload(); }, 500); }
        else showToast(d.message || 'Error', 'error');
    }).catch(function() { form.submit(); });
    return false;
}

modalContents.username = {
    title: 'Username',
    html: '<form id="usernameForm" onsubmit="return submitUsernameForm(event)">' +
        '<p class="text-sm text-gray-500 mb-4">Your unique handle used across all mini apps.</p>' +
        '<div><label class="block text-xs font-medium text-gray-500 mb-1">Handle</label><input type="text" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500"></div>' +
        '<div class="flex gap-3 mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">' +
            '<button type="button" onclick="closeModal()" class="flex-1 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition">Cancel</button>' +
            '<button type="submit" class="flex-1 py-2.5 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition">Save</button>' +
        '</div>' +
    '</form>'
};

function submitUsernameForm(e) {
    e.preventDefault();
    var fd = new FormData(e.target);
    fd.append('ajax', '1');
    fetch('<?= BASE_URL ?>account/username/save', { method: 'POST', body: fd })
    .then(function(r) { return r.json(); }).then(function(d) {
        if (d.success) { showToast(d.message || 'Username updated'); closeModal(); setTimeout(function() { location.reload(); }, 500); }
        else showToast(d.message || 'Error', 'error');
    }).catch(function() { e.target.submit(); });
    return false;
}

modalContents.phone = {
    title: 'Phone Number',
    html: '<form id="phoneForm" onsubmit="return submitPhoneForm(event)">' +
        '<p class="text-sm text-gray-500 mb-4">Link your phone for account security and two-factor authentication.</p>' +
        '<input type="hidden" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>">' +
        '<input type="hidden" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>">' +
        '<input type="hidden" name="email" value="<?= htmlspecialchars($user['email']) ?>">' +
        '<div><label class="block text-xs font-medium text-gray-500 mb-1">Phone Number</label><input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500"></div>' +
        '<div class="flex gap-3 mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">' +
            '<button type="button" onclick="closeModal()" class="flex-1 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition">Cancel</button>' +
            '<button type="submit" class="flex-1 py-2.5 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition">Save</button>' +
        '</div>' +
    '</form>'
};

function submitPhoneForm(e) {
    e.preventDefault();
    var fd = new FormData(e.target);
    fd.append('ajax', '1');
    fetch('<?= BASE_URL ?>account/profile/update', { method: 'POST', body: fd })
    .then(function(r) { return r.json(); }).then(function(d) {
        if (d.success) { showToast(d.message || 'Phone updated'); closeModal(); setTimeout(function() { location.reload(); }, 500); }
        else showToast(d.message || 'Error', 'error');
    }).catch(function() { e.target.submit(); });
    return false;
}

modalContents.password = {
    title: 'Change Password',
    html: '<form id="passwordForm" onsubmit="return submitPasswordForm(event)">' +
        '<div id="passwordError" class="hidden text-sm p-3 rounded-lg mb-4 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400"></div>' +
        '<div class="space-y-4">' +
            '<div><label class="block text-xs font-medium text-gray-500 mb-1">Current Password</label><input type="password" name="current_password" required class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500"></div>' +
            '<div><label class="block text-xs font-medium text-gray-500 mb-1">New Password</label><input type="password" name="new_password" required minlength="6" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500"></div>' +
            '<div><label class="block text-xs font-medium text-gray-500 mb-1">Confirm New Password</label><input type="password" name="confirm_password" required minlength="6" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500"></div>' +
        '</div>' +
        '<div class="flex gap-3 mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">' +
            '<button type="button" onclick="closeModal()" class="flex-1 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition">Cancel</button>' +
            '<button type="submit" class="flex-1 py-2.5 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition">Update</button>' +
        '</div>' +
    '</form>'
};

function submitPasswordForm(e) {
    e.preventDefault();
    var form = e.target;
    var np = form.querySelector('[name="new_password"]').value;
    var cp = form.querySelector('[name="confirm_password"]').value;
    var err = document.getElementById('passwordError');
    if (np !== cp) { err.classList.remove('hidden'); err.textContent = 'Passwords do not match.'; return false; }
    if (np.length < 6) { err.classList.remove('hidden'); err.textContent = 'Minimum 6 characters.'; return false; }
    err.classList.add('hidden');
    var fd = new FormData(form);
    fd.append('ajax', '1');
    fetch('<?= BASE_URL ?>account/security/password', { method: 'POST', body: fd })
    .then(function(r) { return r.json(); }).then(function(d) {
        if (d.success) { showToast('Password changed'); closeModal(); }
        else { err.classList.remove('hidden'); err.textContent = d.message || 'Error'; }
    }).catch(function() { form.submit(); });
    return false;
}

modalContents.devices = {
    title: 'Devices',
    html: '<div>' +
        '<p class="text-sm text-gray-500 mb-4">Devices currently logged into your account.</p>' +
        '<div class="space-y-2">' +
            '<?php if (empty($devicesList)): ?>' +
            '<p class="text-center py-8 text-gray-500">No devices found</p>' +
            '<?php else: ?>' +
            '<?php foreach ($devicesList as $dev): ?>' +
            '<div class="flex items-center justify-between p-3 rounded-lg border border-gray-200 dark:border-gray-700">' +
                '<div class="flex items-center gap-3">' +
                    '<div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500"><i class="fas fa-<?= ($dev['device_type'] ?? 'laptop') === 'Mobile' ? 'mobile' : ($dev['device_type'] === 'Tablet' ? 'tablet' : 'laptop') ?>"></i></div>' +
                    '<div>' +
                        '<p class="text-sm font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($dev['device_name'] ?: 'Unknown') ?></p>' +
                        '<p class="text-xs text-gray-500"><?= htmlspecialchars($dev['location'] ?? 'Unknown') ?></p>' +
                    '</div>' +
                '</div>' +
                '<?php if (!empty($dev['is_current'])): ?>' +
                '<span class="text-xs px-2 py-0.5 rounded bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400">This Device</span>' +
                '<?php else: ?>' +
                '<button onclick="revokeDevice(<?= $dev['id'] ?? 0 ?>)" class="text-red-400 hover:text-red-600 p-1"><i class="fas fa-minus-circle"></i></button>' +
                '<?php endif; ?>' +
            '</div>' +
            '<?php endforeach; ?>' +
            '<?php endif; ?>' +
        '</div>' +
    '</div>'
};

function revokeDevice(id) {
    if (!confirm('Revoke this device?')) return;
    fetch('<?= BASE_URL ?>account/device/revoke?id=' + id, { method: 'POST' })
    .then(function(r) { return r.json(); }).then(function(d) { showToast(d.message || 'Revoked'); setTimeout(function() { location.reload(); }, 500); })
    .catch(function() { location.reload(); });
}

modalContents.appearance = {
    title: '<?= t('appearance') ?>',
    html: '<form id="appearanceForm" onsubmit="return submitAppearanceForm(event)">' +
        '<div class="space-y-4">' +
            '<div>' +
                '<label class="block text-xs font-medium text-gray-500 mb-2"><?= t('theme') ?></label>' +
                '<div class="grid grid-cols-3 gap-2">' +
                    '<label class="cursor-pointer">' +
                        '<input type="radio" name="theme" value="light" <?= ($prefs['theme'] ?? 'light') === 'light' ? 'checked' : '' ?> class="hidden peer">' +
                        '<div class="p-2 text-center rounded-lg border border-gray-200 dark:border-gray-700 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 transition-all">' +
                            '<i class="fas fa-sun text-lg mb-1 block"></i>' +
                            '<span class="text-[10px] font-medium"><?= t('light') ?></span>' +
                        '</div>' +
                    '</label>' +
                    '<label class="cursor-pointer">' +
                        '<input type="radio" name="theme" value="dark" <?= ($prefs['theme'] ?? '') === 'dark' ? 'checked' : '' ?> class="hidden peer">' +
                        '<div class="p-2 text-center rounded-lg border border-gray-200 dark:border-gray-700 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 transition-all">' +
                            '<i class="fas fa-moon text-lg mb-1 block"></i>' +
                            '<span class="text-[10px] font-medium"><?= t('dark') ?></span>' +
                        '</div>' +
                    '</label>' +
                    '<label class="cursor-pointer">' +
                        '<input type="radio" name="theme" value="system" <?= ($prefs['theme'] ?? '') === 'system' || !isset($prefs['theme']) ? 'checked' : '' ?> class="hidden peer">' +
                        '<div class="p-2 text-center rounded-lg border border-gray-200 dark:border-gray-700 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 transition-all">' +
                            '<i class="fas fa-circle-half-stroke text-lg mb-1 block"></i>' +
                            '<span class="text-[10px] font-medium"><?= t('system') ?></span>' +
                        '</div>' +
                    '</label>' +
                '</div>' +
            '</div>' +
            '<div>' +
                '<label class="block text-xs font-medium text-gray-500 mb-2"><?= t('language') ?></label>' +
                '<div class="grid grid-cols-2 gap-2">' +
                    '<label class="cursor-pointer">' +
                        '<input type="radio" name="language" value="en" <?= ($prefs['language'] ?? 'en') === 'en' ? 'checked' : '' ?> class="hidden peer">' +
                        '<div class="p-3 flex items-center gap-3 rounded-xl border border-gray-200 dark:border-gray-700 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 transition-all">' +
                            '<span>🇺🇸</span>' +
                            '<span class="text-sm font-medium">English</span>' +
                        '</div>' +
                    '</label>' +
                    '<label class="cursor-pointer">' +
                        '<input type="radio" name="language" value="km" <?= ($prefs['language'] ?? '') === 'km' ? 'checked' : '' ?> class="hidden peer">' +
                        '<div class="p-3 flex items-center gap-3 rounded-xl border border-gray-200 dark:border-gray-700 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 transition-all">' +
                            '<span>🇰🇭</span>' +
                            '<span class="text-sm font-medium">ភាសាខ្មែរ</span>' +
                        '</div>' +
                    '</label>' +
                '</div>' +
            '</div>' +
        '</div>' +
        '<div class="flex gap-3 mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">' +
            '<button type="button" onclick="closeModal()" class="flex-1 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition">Cancel</button>' +
            '<button type="submit" class="flex-1 py-2.5 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition">Apply</button>' +
        '</div>' +
    '</form>'
};

function submitAppearanceForm(e) {
    e.preventDefault();
    var fd = new FormData(e.target);
    fetch('<?= BASE_URL ?>account/appearance/save', { method: 'POST', body: fd })
    .then(function() {
        var lang = e.target.querySelector('input[name="language"]:checked').value;
        window.location.href = '<?= BASE_URL ?>lang?lang=' + lang;
    });
    return false;
}

modalContents.security = {
    title: '<?= t('security') ?>',
    html: '<div class="space-y-6">' +
        '<div>' +
            '<h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Password</h4>' +
            '<button onclick="openModal(\'password\')" class="w-full flex items-center justify-between p-3 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all">' +
                '<div class="flex items-center gap-3"><i class="fas fa-key text-gray-400"></i><span class="text-sm font-medium"><?= t('change_password') ?></span></div>' +
                '<i class="fas fa-chevron-right text-xs text-gray-400"></i>' +
            '</button>' +
        '</div>' +
        '<div>' +
            '<h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Active Sessions</h4>' +
            '<div class="space-y-2">' +
                '<?php foreach (array_slice($devicesList, 0, 3) as $dev): ?>' +
                '<div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-transparent dark:border-gray-800">' +
                    '<div class="flex items-center gap-3">' +
                        '<i class="fas fa-<?= ($dev['device_type'] ?? 'laptop') === 'Mobile' ? 'mobile-screen' : 'laptop' ?> text-gray-400"></i>' +
                        '<div>' +
                            '<p class="text-xs font-medium"><?= htmlspecialchars($dev['device_name'] ?: 'Browser') ?></p>' +
                            '<p class="text-[10px] text-gray-500"><?= htmlspecialchars($dev['browser'] ?? 'Unknown') ?> • <?= htmlspecialchars($dev['ip_address'] ?? '') ?></p>' +
                        '</div>' +
                    '</div>' +
                    '<?php if (!empty($dev['is_current'])): ?><span class="text-[9px] font-bold text-green-500 uppercase tracking-tighter">Current</span><?php endif; ?>' +
                '</div>' +
                '<?php endforeach; ?>' +
            '</div>' +
            '<?php if(count($devicesList) > 3): ?><button onclick="openModal(\'devices\')" class="w-full mt-3 text-center py-2 text-xs font-medium text-blue-600 hover:text-blue-700 transition">View all devices</button><?php endif; ?>' +
        '</div>' +
    '</div>'
};

modalContents.delete_account = {
    title: '<?= t('delete_account') ?>',
    html: '<div>' +
        '<div class="p-3 rounded-lg mb-4 text-sm bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400"><strong>Warning:</strong> This permanently removes all your profile data and orders.</div>' +
        '<form id="deleteAccountForm" onsubmit="return submitDeleteAccount(event)">' +
            '<div class="mb-4"><label class="block text-xs font-medium text-gray-500 mb-1">Type <strong class="text-red-500">DELETE</strong> to confirm:</label><input type="text" id="deleteConfirmInput" placeholder="DELETE" class="w-full px-3.5 py-2.5 rounded-lg border border-red-300 dark:border-red-700 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-red-500"></div>' +
            '<button type="submit" id="deleteAccountBtn" disabled class="w-full py-2.5 rounded-lg text-sm font-medium text-white bg-red-500 opacity-40 cursor-not-allowed">Delete Permanently</button>' +
        '</form>' +
    '</div>'
};

document.addEventListener('click', function() {
    if (currentModal === 'delete_account') {
        var input = document.getElementById('deleteConfirmInput');
        var btn = document.getElementById('deleteAccountBtn');
        if (input && btn) {
            input.removeEventListener('input', window._delHandler);
            window._delHandler = function() {
                var enabled = this.value === 'DELETE';
                btn.disabled = !enabled;
                btn.style.opacity = enabled ? '1' : '0.4';
                btn.style.cursor = enabled ? 'pointer' : 'not-allowed';
            };
            input.addEventListener('input', window._delHandler);
        }
    }
});

function submitDeleteAccount(e) {
    e.preventDefault();
    var input = document.getElementById('deleteConfirmInput');
    if (input.value !== 'DELETE') return false;
    fetch('<?= BASE_URL ?>account/privacy/delete', {
        method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'confirm=1&ajax=1'
    }).then(function(r) { return r.json(); }).then(function(d) {
        if (d.success) { showToast('Account deletion scheduled', 'error'); closeModal(); setTimeout(function() { window.location.href = '<?= BASE_URL ?>'; }, 1000); }
        else showToast(d.message || 'Error', 'error');
    }).catch(function() { document.getElementById('deleteAccountForm').submit(); });
    return false;
}


modalContents.logout = {
    title: 'Sign Out',
    html: '<p class="text-sm text-gray-500 mb-6">Are you sure you want to sign out?</p>' +
        '<div class="flex gap-3">' +
            '<button onclick="closeModal()" class="flex-1 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition">Cancel</button>' +
            '<a href="<?= BASE_URL ?>logout" class="flex-1 py-2.5 rounded-lg text-sm font-medium text-white bg-red-500 hover:bg-red-600 transition text-center">Sign Out</a>' +
        '</div>'
};
</script>
