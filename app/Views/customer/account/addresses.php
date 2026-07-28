<div class="max-w-2xl mx-auto pb-10">
    <div class="mb-6">
        <a href="<?= BASE_URL ?>account/dashboard" class="text-sm text-blue-600 dark:text-blue-400 hover:underline"><i class="fas fa-chevron-left mr-1"></i> Settings</a>
    </div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= t('my_addresses') ?></h1>
        <button onclick="openAddressForm()" class="px-4 py-2 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition"><i class="fas fa-plus mr-1"></i> <?= t('add_address') ?></button>
    </div>

    <!-- Address Form -->
    <div id="addressFormContainer" class="hidden mb-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <h4 id="addressFormTitle" class="font-medium text-gray-900 dark:text-white mb-4"><?= t('add_address') ?></h4>
        <form id="addressForm" action="<?= BASE_URL ?>account/address/save" method="POST" class="space-y-4">
            <input type="hidden" name="id" id="addressId" value="">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><label class="block text-xs font-medium text-gray-500 mb-1"><?= t('address_label') ?></label><input type="text" name="label" id="addrLabel" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500" placeholder="Home / Office"></div>
                <div><label class="block text-xs font-medium text-gray-500 mb-1"><?= t('full_name') ?></label><input type="text" name="full_name" id="addrFullName" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500"></div>
                <div><label class="block text-xs font-medium text-gray-500 mb-1"><?= t('phone') ?></label><input type="tel" name="phone" id="addrPhone" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><label class="block text-xs font-medium text-gray-500 mb-1"><?= t('province') ?></label><select name="province_code" id="selProvince" onchange="loadDistricts(this.value)" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500"><option value=""><?= t('select_province') ?></option><?php foreach ($provinces as $p): ?><option value="<?= htmlspecialchars($p['code']) ?>"><?= htmlspecialchars($p['name_en']) ?></option><?php endforeach; ?></select></div>
                <div><label class="block text-xs font-medium text-gray-500 mb-1"><?= t('district') ?></label><select name="district_code" id="selDistrict" onchange="loadCommunes(this.value)" disabled class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500"><option value=""><?= t('select_district') ?></option></select></div>
                <div><label class="block text-xs font-medium text-gray-500 mb-1"><?= t('commune') ?></label><select name="commune_code" id="selCommune" onchange="loadVillages(this.value)" disabled class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500"><option value=""><?= t('select_commune') ?></option></select></div>
                <div><label class="block text-xs font-medium text-gray-500 mb-1"><?= t('village') ?></label><select name="village_code" id="selVillage" disabled class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500"><option value=""><?= t('select_village') ?></option></select></div>
            </div>
            <div><label class="block text-xs font-medium text-gray-500 mb-1"><?= t('street') ?></label><input type="text" name="street" id="addrStreet" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500"></div>
            <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 cursor-pointer"><input type="checkbox" name="is_default" value="1" id="addrDefault" class="accent-blue-600"> <?= t('set_as_default') ?></label>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-5 py-2.5 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition"><?= t('save') ?></button>
                <button type="button" onclick="closeAddressForm()" class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition"><?= t('cancel') ?></button>
            </div>
        </form>
    </div>

    <?php if (empty($addresses)): ?>
    <div class="text-center py-16">
        <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mx-auto mb-4"><i class="fas fa-map-marker-alt text-2xl text-gray-400"></i></div>
        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1"><?= t('no_addresses') ?></h3>
        <p class="text-sm text-gray-500 mb-5">Add a shipping address to get started.</p>
        <button onclick="openAddressForm()" class="inline-flex px-5 py-2.5 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition"><?= t('add_address') ?></button>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <?php foreach ($addresses as $addr): ?>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 hover:shadow-sm transition">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0"><i class="fas fa-map-pin"></i></div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-0.5">
                        <span class="text-sm font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($addr['label']) ?></span>
                        <?php if (!empty($addr['is_default'])): ?><span class="text-xs px-2 py-0.5 rounded bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400"><?= t('default') ?></span><?php endif; ?>
                    </div>
                    <p class="text-sm text-gray-500"><?= htmlspecialchars($addressModel->getFullAddress($addr)) ?></p>
                    <p class="text-xs text-gray-400 mt-0.5"><?= htmlspecialchars($addr['phone']) ?></p>
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <button onclick="editAddress(<?= $addr['id'] ?>)" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition" title="<?= t('edit') ?>"><i class="fas fa-pen text-xs"></i></button>
                    <a href="<?= BASE_URL ?>account/address/delete?id=<?= $addr['id'] ?>" onclick="return confirm('<?= t('confirm_delete') ?>')" class="p-2 text-red-400 hover:text-red-600 transition" title="<?= t('delete') ?>"><i class="fas fa-trash-alt text-xs"></i></a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<script>
function openAddressForm(data) {
    var c = document.getElementById('addressFormContainer');
    c.classList.remove('hidden');
    c.scrollIntoView({ behavior: 'smooth', block: 'start' });
    if (data) {
        document.getElementById('addressFormTitle').textContent = '<?= t('edit_address') ?>';
        document.getElementById('addressId').value = data.id || '';
        document.getElementById('addrLabel').value = data.label || '';
        document.getElementById('addrFullName').value = data.full_name || '';
        document.getElementById('addrPhone').value = data.phone || '';
        document.getElementById('addrStreet').value = data.street || '';
        document.getElementById('addrDefault').checked = !!data.is_default;
        if (data.province_code) { document.getElementById('selProvince').value = data.province_code; loadDistricts(data.province_code, data.district_code, data.commune_code, data.village_code); }
    } else {
        document.getElementById('addressFormTitle').textContent = '<?= t('add_address') ?>';
        document.getElementById('addressForm').reset();
        document.getElementById('addressId').value = '';
    }
}
function closeAddressForm() { document.getElementById('addressFormContainer').classList.add('hidden'); }
function editAddress(id) {
    <?php foreach ($addresses as $addr): ?>
    if (<?= $addr['id'] ?> === id) {
        openAddressForm({ id: <?= $addr['id'] ?>, label: '<?= htmlspecialchars(addslashes($addr['label'])) ?>', full_name: '<?= htmlspecialchars(addslashes($addr['full_name'])) ?>', phone: '<?= htmlspecialchars(addslashes($addr['phone'])) ?>', street: '<?= htmlspecialchars(addslashes($addr['street'])) ?>', province_code: '<?= $addr['province_code'] ?>', district_code: '<?= $addr['district_code'] ?>', commune_code: '<?= $addr['commune_code'] ?>', village_code: '<?= $addr['village_code'] ?>', is_default: <?= !empty($addr['is_default']) ? 'true' : 'false' ?> });
    }
    <?php endforeach; ?>
}
function loadDistricts(pc, sd, sc, sv) {
    var s = document.getElementById('selDistrict'), c = document.getElementById('selCommune'), v = document.getElementById('selVillage');
    s.innerHTML = '<option value=""><?= t('loading') ?></option>'; s.disabled = true;
    c.innerHTML = '<option value=""><?= t('select_commune') ?></option>'; c.disabled = true;
    v.innerHTML = '<option value=""><?= t('select_village') ?></option>'; v.disabled = true;
    if (!pc) { s.innerHTML = '<option value=""><?= t('select_district') ?></option>'; return; }
    fetch('<?= BASE_URL ?>api/districts?province_code=' + pc).then(function(r){return r.json();}).then(function(d){
        s.innerHTML = '<option value=""><?= t('select_district') ?></option>' + d.map(function(x){return '<option value="'+x.code+'">'+x.name_en+'</option>';}).join('');
        s.disabled = false; if(sd){s.value=sd;loadCommunes(sd,sc,sv);}
    });
}
function loadCommunes(dc, sc, sv) {
    var s = document.getElementById('selCommune'), v = document.getElementById('selVillage');
    s.innerHTML = '<option value=""><?= t('loading') ?></option>'; s.disabled = true;
    v.innerHTML = '<option value=""><?= t('select_village') ?></option>'; v.disabled = true;
    if (!dc) { s.innerHTML = '<option value=""><?= t('select_commune') ?></option>'; return; }
    fetch('<?= BASE_URL ?>api/communes?district_code=' + dc).then(function(r){return r.json();}).then(function(d){
        s.innerHTML = '<option value=""><?= t('select_commune') ?></option>' + d.map(function(x){return '<option value="'+x.code+'">'+x.name_en+'</option>';}).join('');
        s.disabled = false; if(sc){s.value=sc;loadVillages(sc,sv);}
    });
}
function loadVillages(cc, sv) {
    var s = document.getElementById('selVillage');
    s.innerHTML = '<option value=""><?= t('loading') ?></option>'; s.disabled = true;
    if (!cc) { s.innerHTML = '<option value=""><?= t('select_village') ?></option>'; return; }
    fetch('<?= BASE_URL ?>api/villages?commune_code=' + cc).then(function(r){return r.json();}).then(function(d){
        s.innerHTML = '<option value=""><?= t('select_village') ?></option>' + d.map(function(x){return '<option value="'+x.code+'">'+x.name_en+'</option>';}).join('');
        s.disabled = false; if(sv) s.value = sv;
    });
}
</script>
