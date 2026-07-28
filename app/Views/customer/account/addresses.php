<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="max-w-2xl mx-auto pb-10">
    <div class="mb-6">
        <a href="<?= BASE_URL ?>account/dashboard" class="text-sm text-blue-600 dark:text-blue-400 hover:underline"><i class="fas fa-chevron-left mr-1"></i> Settings</a>
    </div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= t('my_addresses') ?></h1>
        <button onclick="openAddressForm()" class="px-4 py-2 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition flex items-center gap-1.5"><i class="fas fa-plus"></i> <?= t('add_address') ?></button>
    </div>

    <!-- Address Form -->
    <div id="addressFormContainer" class="hidden mb-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
        <div class="flex items-center gap-2 mb-5 pb-3 border-b border-gray-100 dark:border-gray-700">
            <i class="fas fa-map-pin text-blue-500 text-sm"></i>
            <h4 id="addressFormTitle" class="font-semibold text-gray-900 dark:text-white">Add Address</h4>
        </div>
        <form id="addressForm" action="<?= BASE_URL ?>account/address/save" method="POST" class="space-y-4">
            <input type="hidden" name="id" id="addressId" value="">
            <input type="hidden" name="latitude" id="addrLat" value="">
            <input type="hidden" name="longitude" id="addrLng" value="">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Name</label>
                    <input type="text" name="full_name" id="addrFullName" required class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Company</label>
                    <input type="text" name="company" id="addrCompany" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Email</label>
                    <input type="email" name="email" id="addrEmail" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Phone Number <span class="text-red-500">*</span></label>
                    <input type="tel" name="phone" id="addrPhone" required class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Location <span class="text-red-500">*</span></label>
                <select name="province_code" id="selProvince" onchange="loadDistricts(this.value)" required class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500">
                    <option value="">Choose Location</option>
                    <?php foreach ($provinces as $p): ?>
                    <option value="<?= htmlspecialchars($p['code']) ?>"><?= htmlspecialchars($p['name_en']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">District</label>
                    <select name="district_code" id="selDistrict" onchange="loadCommunes(this.value)" disabled class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500">
                        <option value="">Select District</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Commune</label>
                    <select name="commune_code" id="selCommune" onchange="loadVillages(this.value)" disabled class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500">
                        <option value="">Select Commune</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Village</label>
                    <select name="village_code" id="selVillage" disabled class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500">
                        <option value="">Select Village</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Address <span class="text-red-500">*</span></label>
                <input type="text" name="street" id="addrStreet" required placeholder="Enter the address (e.g., House No. 225, Serey Vuth Street, Phnom Penh)" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500">
            </div>

            <div>
                <button type="button" onclick="openMap()" class="w-full py-2.5 rounded-lg text-sm font-medium border-2 border-dashed border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:border-blue-400 hover:text-blue-500 transition flex items-center justify-center gap-2">
                    <i class="fas fa-map-marker-alt"></i> Select Location on Map
                </button>
                <p id="mapCoords" class="text-xs text-gray-400 mt-1 text-center hidden"></p>
            </div>

            <!-- Map Container -->
            <div id="mapContainer" class="hidden rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700" style="height: 300px;"></div>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Category</label>
                <select name="label" id="addrLabel" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500">
                    <option value="Home">Home</option>
                    <option value="Work">Work</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 cursor-pointer">
                <input type="checkbox" name="is_default" value="1" id="addrDefault" class="accent-blue-600"> Set as default
            </label>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-5 py-2.5 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition">Submit</button>
                <button type="button" onclick="closeAddressForm()" class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition">Cancel</button>
            </div>
        </form>
    </div>

    <?php if (empty($addresses)): ?>
    <div class="text-center py-16">
        <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mx-auto mb-4"><i class="fas fa-map-marker-alt text-2xl text-gray-400"></i></div>
        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1"><?= t('no_addresses') ?></h3>
        <p class="text-sm text-gray-500 mb-5">Add a shipping address to get started.</p>
        <button onclick="openAddressForm()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition"><i class="fas fa-plus"></i> <?= t('add_address') ?></button>
    </div>
    <?php else: ?>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700">
        <?php foreach ($addresses as $addr): ?>
        <div class="flex items-start gap-3 px-4 py-4 hover:bg-gray-100 dark:hover:bg-gray-700 transition cursor-default">
            <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0 mt-0.5"><i class="fas fa-map-pin text-sm"></i></div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-0.5">
                    <span class="text-sm font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($addr['label']) ?></span>
                    <?php if (!empty($addr['is_default'])): ?><span class="text-xs px-2 py-0.5 rounded bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-medium"><?= t('default') ?></span><?php endif; ?>
                </div>
                <p class="text-sm text-gray-500"><?= htmlspecialchars($addressModel->getFullAddress($addr)) ?></p>
                <p class="text-xs text-gray-400 mt-0.5"><?= htmlspecialchars($addr['phone']) ?></p>
            </div>
            <div class="flex items-center gap-1 shrink-0 mt-0.5">
                <button onclick="editAddress(<?= $addr['id'] ?>)" class="p-2 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition" title="<?= t('edit') ?>"><i class="fas fa-pen text-xs"></i></button>
                <a href="<?= BASE_URL ?>account/address/delete?id=<?= $addr['id'] ?>" onclick="return confirm('<?= t('confirm_delete') ?>')" class="p-2 rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition" title="<?= t('delete') ?>"><i class="fas fa-trash-alt text-xs"></i></a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="mt-4">
        <button onclick="openAddressForm()" class="w-full py-3 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition flex items-center justify-center gap-2"><i class="fas fa-plus"></i> <?= t('add_address') ?></button>
    </div>
    <?php endif; ?>
</div>

<script>
var addressMap = null, addressMarker = null;

function openAddressForm(data) {
    var c = document.getElementById('addressFormContainer');
    c.classList.remove('hidden');
    c.scrollIntoView({ behavior: 'smooth', block: 'start' });
    if (data) {
        document.getElementById('addressFormTitle').textContent = 'Edit Address';
        document.getElementById('addressId').value = data.id || '';
        document.getElementById('addrFullName').value = data.full_name || '';
        document.getElementById('addrCompany').value = data.company || '';
        document.getElementById('addrEmail').value = data.email || '';
        document.getElementById('addrPhone').value = data.phone || '';
        document.getElementById('addrLabel').value = data.label || 'Home';
        document.getElementById('addrStreet').value = data.street || '';
        document.getElementById('addrDefault').checked = !!data.is_default;
        if (data.latitude && data.longitude) {
            document.getElementById('addrLat').value = data.latitude;
            document.getElementById('addrLng').value = data.longitude;
            document.getElementById('mapCoords').textContent = data.latitude + ', ' + data.longitude;
            document.getElementById('mapCoords').classList.remove('hidden');
        }
        if (data.province_code) { document.getElementById('selProvince').value = data.province_code; loadDistricts(data.province_code, data.district_code, data.commune_code, data.village_code); }
    } else {
        document.getElementById('addressFormTitle').textContent = 'Add Address';
        document.getElementById('addressForm').reset();
        document.getElementById('addressId').value = '';
        document.getElementById('addrLat').value = '';
        document.getElementById('addrLng').value = '';
        document.getElementById('mapCoords').classList.add('hidden');
        document.getElementById('mapContainer').classList.add('hidden');
    }
}

function closeAddressForm() {
    document.getElementById('addressFormContainer').classList.add('hidden');
    if (addressMap) { addressMap.remove(); addressMap = null; addressMarker = null; }
}

function editAddress(id) {
    <?php foreach ($addresses as $addr): ?>
    if (<?= $addr['id'] ?> === id) {
        openAddressForm({
            id: <?= $addr['id'] ?>,
            full_name: '<?= htmlspecialchars(addslashes($addr['full_name'])) ?>',
            company: '<?= htmlspecialchars(addslashes($addr['company'] ?? '')) ?>',
            email: '<?= htmlspecialchars(addslashes($addr['email'] ?? '')) ?>',
            phone: '<?= htmlspecialchars(addslashes($addr['phone'])) ?>',
            label: '<?= htmlspecialchars(addslashes($addr['label'])) ?>',
            street: '<?= htmlspecialchars(addslashes($addr['street'])) ?>',
            province_code: '<?= $addr['province_code'] ?>',
            district_code: '<?= $addr['district_code'] ?>',
            commune_code: '<?= $addr['commune_code'] ?>',
            village_code: '<?= $addr['village_code'] ?>',
            latitude: '<?= $addr['latitude'] ?>',
            longitude: '<?= $addr['longitude'] ?>',
            is_default: <?= !empty($addr['is_default']) ? 'true' : 'false' ?>
        });
    }
    <?php endforeach; ?>
}

function loadDistricts(pc, sd, sc, sv) {
    var s = document.getElementById('selDistrict'), c = document.getElementById('selCommune'), v = document.getElementById('selVillage');
    s.innerHTML = '<option value="">Loading</option>'; s.disabled = true;
    c.innerHTML = '<option value="">Select Commune</option>'; c.disabled = true;
    v.innerHTML = '<option value="">Select Village</option>'; v.disabled = true;
    if (!pc) { s.innerHTML = '<option value="">Select District</option>'; return; }
    fetch('<?= BASE_URL ?>api/districts?province_code=' + pc).then(function(r){return r.json();}).then(function(d){
        s.innerHTML = '<option value="">Select District</option>' + d.map(function(x){return '<option value="'+x.code+'">'+x.name_en+'</option>';}).join('');
        s.disabled = false; if(sd){s.value=sd;loadCommunes(sd,sc,sv);}
    });
}

function loadCommunes(dc, sc, sv) {
    var s = document.getElementById('selCommune'), v = document.getElementById('selVillage');
    s.innerHTML = '<option value="">Loading</option>'; s.disabled = true;
    v.innerHTML = '<option value="">Select Village</option>'; v.disabled = true;
    if (!dc) { s.innerHTML = '<option value="">Select Commune</option>'; return; }
    fetch('<?= BASE_URL ?>api/communes?district_code=' + dc).then(function(r){return r.json();}).then(function(d){
        s.innerHTML = '<option value="">Select Commune</option>' + d.map(function(x){return '<option value="'+x.code+'">'+x.name_en+'</option>';}).join('');
        s.disabled = false; if(sc){s.value=sc;loadVillages(sc,sv);}
    });
}

function loadVillages(cc, sv) {
    var s = document.getElementById('selVillage');
    s.innerHTML = '<option value="">Loading</option>'; s.disabled = true;
    if (!cc) { s.innerHTML = '<option value="">Select Village</option>'; return; }
    fetch('<?= BASE_URL ?>api/villages?commune_code=' + cc).then(function(r){return r.json();}).then(function(d){
        s.innerHTML = '<option value="">Select Village</option>' + d.map(function(x){return '<option value="'+x.code+'">'+x.name_en+'</option>';}).join('');
        s.disabled = false; if(sv) s.value = sv;
    });
}

function openMap() {
    var c = document.getElementById('mapContainer');
    c.classList.remove('hidden');
    if (addressMap) { addressMap.invalidateSize(); return; }
    var lat = parseFloat(document.getElementById('addrLat').value) || 11.5564;
    var lng = parseFloat(document.getElementById('addrLng').value) || 104.9282;
    addressMap = L.map('mapContainer').setView([lat, lng], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(addressMap);
    if (document.getElementById('addrLat').value) {
        addressMarker = L.marker([lat, lng], { draggable: true }).addTo(addressMap);
    }
    addressMap.on('click', function(e) {
        if (addressMarker) addressMarker.setLatLng(e.latlng);
        else addressMarker = L.marker(e.latlng, { draggable: true }).addTo(addressMap);
        document.getElementById('addrLat').value = e.latlng.lat.toFixed(7);
        document.getElementById('addrLng').value = e.latlng.lng.toFixed(7);
        document.getElementById('mapCoords').textContent = e.latlng.lat.toFixed(7) + ', ' + e.latlng.lng.toFixed(7);
        document.getElementById('mapCoords').classList.remove('hidden');
    });
    setTimeout(function() { addressMap.invalidateSize(); }, 200);
}
</script>
