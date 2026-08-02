<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="<?= BASE_URL ?>cart" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 mb-6 text-sm font-medium">
        <i class="fas fa-arrow-left mr-2"></i><?= t('back_to_shop') ?>
    </a>

    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8"><?= t('checkout') ?></h1>

    <!-- Progress Bar -->
    <div class="mb-10">
        <div class="flex items-center justify-between max-w-2xl mx-auto">
            <?php
            $steps = [
                ['icon' => 'fa-map-pin', 'label' => 'Address', 'step' => '1'],
                ['icon' => 'fa-truck', 'label' => 'Shipping', 'step' => '2'],
                ['icon' => 'fa-eye', 'label' => 'Review', 'step' => '3'],
                ['icon' => 'fa-credit-card', 'label' => 'Pay', 'step' => '4'],
            ];
            foreach ($steps as $i => $s):
                $active = $i === 0;
            ?>
            <div class="flex items-center flex-1">
                <div class="flex flex-col items-center">
                    <?php
                        $dotClass = $active ? 'bg-blue-600 text-white shadow-lg shadow-blue-200 dark:shadow-blue-900/40' : 'bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500';
                        $labelClass = $active ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500';
                        $lineClass = $active ? 'bg-blue-200 dark:bg-blue-800' : 'bg-gray-200 dark:bg-gray-700';
                    ?>
                    <div class="step-dot w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300 <?= $dotClass ?>" data-icon="<?= $s['icon'] ?>">
                        <i class="fas <?= $s['icon'] ?>"></i>
                    </div>
                    <span class="text-[10px] mt-1.5 font-semibold uppercase tracking-wider <?= $labelClass ?>"><?= t('step') ?> <?= $s['step'] ?></span>
                    <span class="text-xs -mt-0.5 font-medium <?= $labelClass ?>"><?= $s['label'] ?></span>
                </div>
                <?php if ($i < count($steps) - 1): ?>
                <div class="step-line flex-1 h-0.5 mx-3 rounded transition-all duration-300 <?= $lineClass ?>" data-step="<?= $i + 1 ?>"></div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <!-- ====== STEP 1: SHIPPING ADDRESS ====== -->
            <div id="step1" class="step-panel">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 transition-colors">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2.5 py-1 rounded-full"><?= t('step') ?> 1</span>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white"><i class="fas fa-map-pin mr-2 text-blue-500"></i><?= t('shipping_address') ?></h2>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">Where should we deliver your order?</p>

                    <!-- Saved Addresses -->
                    <?php if (!empty($addresses)): ?>
                    <div class="space-y-3 mb-5" id="savedAddresses">
                        <?php foreach ($addresses as $addr): ?>
                        <?php
                            $isSelected = (!empty($savedAddress) && ($savedAddress['id'] ?? '') == $addr['id']);
                        ?>
                        <label class="address-radio flex items-start gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all <?= $isSelected ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-700' ?>">
                            <input type="radio" name="address_id" value="<?= $addr['id'] ?>" <?= $isSelected ? 'checked' : '' ?> class="mt-1 accent-blue-600 address-select">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-gray-900 dark:text-white text-sm"><?= htmlspecialchars($addr['full_name'] ?? '') ?></span>
                                    <?php if (!empty($addr['is_default'])): ?>
                                    <span class="text-[10px] bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 px-2 py-0.5 rounded-full font-medium">Default</span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"><?= htmlspecialchars($addr['phone'] ?? '') ?></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"><?= htmlspecialchars($addr['street'] ?? '') ?></p>
                            </div>
                            <span class="text-xs font-medium text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded"><?= htmlspecialchars($addr['label'] ?? 'Address') ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Add New Address Toggle -->
                    <button type="button" id="showNewAddress" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 font-medium flex items-center gap-1 <?= !empty($addresses) ? 'mb-4' : '' ?>">
                        <i class="fas fa-plus-circle"></i> <?= t('add_new_address') ?>
                    </button>

                    <!-- New Address Form -->
                    <div id="newAddressForm" class="space-y-4 <?= (!empty($savedAddress) && empty($savedAddress['id'])) ? '' : 'hidden' ?>">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('full_name') ?> <span class="text-red-500">*</span></label>
                                <input type="text" id="addr_name" value="<?= htmlspecialchars($savedAddress['full_name'] ?? '') ?>" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500 transition-all">
                                <p id="nameError" class="text-[10px] text-red-500 mt-1 hidden flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> <?= t('please_enter_name') ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('phone') ?> <span class="text-red-500">*</span></label>
                                <input type="tel" id="addr_phone" value="<?= htmlspecialchars($savedAddress['phone'] ?? '') ?>" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500 transition-all">
                                <p id="phoneError" class="text-[10px] text-red-500 mt-1 hidden flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> <?= t('please_enter_phone') ?></p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('province') ?></label>
                            <select id="selProvince" onchange="loadDistricts(this.value); clearError(this, 'provinceError')" class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500 transition-all">
                                <option value=""><?= t('select_province') ?></option>
                                <?php foreach ($provinces as $p): ?>
                                <option value="<?= htmlspecialchars($p['code']) ?>" <?= (!empty($savedAddress['province_code']) && $savedAddress['province_code'] == $p['code']) ? 'selected' : '' ?>><?= htmlspecialchars($p['name_en']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p id="provinceError" class="text-[10px] text-red-500 mt-1 hidden flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> <?= t('please_select_province') ?></p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('district') ?></label>
                                <select id="selDistrict" onchange="loadCommunes(this.value); clearError(this, 'districtError')" disabled class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500 transition-all">
                                    <option value=""><?= t('select_district') ?></option>
                                </select>
                                <p id="districtError" class="text-[10px] text-red-500 mt-1 hidden flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> <?= t('please_select_district') ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('commune') ?></label>
                                <select id="selCommune" onchange="loadVillages(this.value); clearError(this, 'communeError')" disabled class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500 transition-all">
                                    <option value=""><?= t('select_commune') ?></option>
                                </select>
                                <p id="communeError" class="text-[10px] text-red-500 mt-1 hidden flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> <?= t('please_select_commune') ?></p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('village') ?></label>
                                <select id="selVillage" onchange="clearError(this, 'villageError')" disabled class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500 transition-all">
                                    <option value=""><?= t('select_village') ?></option>
                                </select>
                                <p id="villageError" class="text-[10px] text-red-500 mt-1 hidden flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> <?= t('please_select_village') ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= t('street_address') ?> <span class="text-red-500">*</span></label>
                                <input type="text" id="addr_street" value="<?= htmlspecialchars($savedAddress['street'] ?? '') ?>" placeholder="House/Street No." class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-900 dark:text-white outline-none focus:border-blue-500 transition-all">
                                <p id="streetError" class="text-[10px] text-red-500 mt-1 hidden flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> <?= t('please_enter_street') ?></p>
                            </div>
                        </div>

                        <input type="hidden" id="addr_latitude" value="<?= htmlspecialchars($savedAddress['latitude'] ?? '') ?>">
                        <input type="hidden" id="addr_longitude" value="<?= htmlspecialchars($savedAddress['longitude'] ?? '') ?>">
                    </div>

                    <div class="flex justify-end pt-4">
                        <button onclick="saveStep1()" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2.5 rounded-lg font-medium transition text-sm shadow-sm">
                            <?= t('continue') ?> <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ====== STEP 2: SHIPPING METHOD ====== -->
            <div id="step2" class="step-panel hidden">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 transition-colors">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2.5 py-1 rounded-full"><?= t('step') ?> 2</span>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white"><i class="fas fa-truck mr-2 text-blue-500"></i><?= t('shipping_method') ?></h2>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">Choose a delivery option.</p>

                    <div class="space-y-3" id="shippingOptions">
                        <?php foreach ($shippingMethods as $key => $method):
                            $checked = (!empty($savedShipping) && ($savedShipping['key'] ?? '') === $key);
                        ?>
                        <label class="shipping-radio flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all <?= $checked ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-700' ?>">
                            <input type="radio" name="shipping_method" value="<?= $key ?>" <?= $checked ? 'checked' : '' ?> onchange="clearShippingError()" class="mt-1 accent-blue-600">
                            <div class="flex-1">
                                <span class="font-semibold text-gray-900 dark:text-white text-sm"><?= $method['label'] ?></span>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"><?= $method['days'] ?></p>
                            </div>
                            <span class="font-bold text-gray-900 dark:text-white text-sm">
                                <?= $method['cost'] > 0 ? '$' . number_format($method['cost'], 2) : 'Free' ?>
                            </span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                    <p id="shippingError" class="text-[10px] text-red-500 mt-2 hidden flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> <?= t('please_select_shipping') ?></p>

                    <div class="flex items-center justify-between pt-4">
                        <button onclick="goToStep(1)" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-sm font-medium flex items-center gap-1">
                            <i class="fas fa-arrow-left"></i> Back
                        </button>
                        <button onclick="saveStep2()" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2.5 rounded-lg font-medium transition text-sm shadow-sm">
                            <?= t('continue') ?> <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ====== STEP 3: REVIEW ====== -->
            <div id="step3" class="step-panel hidden">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 transition-colors">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2.5 py-1 rounded-full"><?= t('step') ?> 3</span>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white"><i class="fas fa-eye mr-2 text-blue-500"></i>Review Your Order</h2>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">Please verify everything before paying.</p>

                    <!-- Items -->
                    <div class="divide-y divide-gray-100 dark:divide-gray-700 mb-5">
                        <?php foreach ($cart as $productId => $item): ?>
                        <div class="flex items-center justify-between py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex-shrink-0 overflow-hidden flex items-center justify-center">
                                    <?php if (!empty($item['image'])): ?>
                                    <img src="<?= BASE_URL . 'uploads/' . rawurlencode($item['image']) ?>"
                                         onerror="this.src='<?= BASE_URL . 'images/' . rawurlencode($item['image']) ?>'; this.onerror=null;"
                                         class="w-full h-full object-cover" alt="<?= htmlspecialchars($item['name']) ?>">
                                    <?php else: ?>
                                    <i class="fas fa-image text-gray-300 dark:text-gray-600"></i>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white text-sm truncate max-w-[200px]"><?= htmlspecialchars($item['name']) ?></p>
                                    <p class="text-gray-500 dark:text-gray-400 text-xs"><?= t('qty') ?>: <?= $item['quantity'] ?> &times; $<?= number_format($item['price'], 2) ?></p>
                                </div>
                            </div>
                            <p class="font-bold text-gray-900 dark:text-white text-sm">$<?= number_format($item['price'] * $item['quantity'], 2) ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Selected Address -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 mb-4">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1"><i class="fas fa-map-pin mr-1"></i> Shipping To</p>
                        <p id="reviewAddress" class="text-sm text-gray-900 dark:text-white font-medium">—</p>
                    </div>

                    <!-- Selected Shipping -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1"><i class="fas fa-truck mr-1"></i> Shipping Method</p>
                        <p id="reviewShipping" class="text-sm text-gray-900 dark:text-white font-medium">—</p>
                    </div>

                    <div class="flex items-center justify-between pt-4">
                        <button onclick="goToStep(2)" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-sm font-medium flex items-center gap-1">
                            <i class="fas fa-arrow-left"></i> Back
                        </button>
                        <button onclick="goToStep(4)" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2.5 rounded-lg font-medium transition text-sm shadow-sm">
                            <?= t('continue_to_payment') ?> <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ====== STEP 4: PAYMENT & CONFIRM ====== -->
            <div id="step4" class="step-panel hidden">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 transition-colors">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2.5 py-1 rounded-full"><?= t('step') ?> 4</span>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white"><i class="fas fa-credit-card mr-2 text-blue-500"></i><?= t('payment_method') ?></h2>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">Select how you'd like to pay.</p>

                    <div class="space-y-3" id="paymentOptions">
                        <label class="payment-radio flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all <?= ($savedPayment === 'card') ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-700' ?>">
                            <input type="radio" name="payment_method" value="card" <?= ($savedPayment === 'card') ? 'checked' : '' ?> onchange="toggleQrCode(true); clearPaymentError();" class="accent-blue-600 payment-select">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                <i class="fas fa-qrcode"></i>
                            </div>
                            <div class="flex-1">
                                <span class="font-semibold text-gray-900 dark:text-white text-sm"><?= t('credit_debit_card') ?></span>
                                <p class="text-xs text-gray-500 dark:text-gray-400"><?= t('pay_with_card') ?></p>
                            </div>
                        </label>
                        <label class="payment-radio flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all <?= ($savedPayment === 'cod') ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-700' ?>">
                            <input type="radio" name="payment_method" value="cod" <?= ($savedPayment === 'cod') ? 'checked' : '' ?> onchange="toggleQrCode(false); clearPaymentError();" class="accent-blue-600">
                            <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="flex-1">
                                <span class="font-semibold text-gray-900 dark:text-white text-sm"><?= t('cash_on_delivery') ?></span>
                                <p class="text-xs text-gray-500 dark:text-gray-400"><?= t('pay_on_delivery') ?></p>
                            </div>
                        </label>
                    </div>

                    <!-- QR Code Display -->
                    <div id="qrCodeContainer" class="<?= ($savedPayment === 'card' && !empty($savedPayment)) ? '' : 'hidden' ?> mt-6 p-6 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700 text-center animate-fadeIn">
                        <p class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4"><?= t('scan_to_pay') ?: 'Scan to Pay' ?></p>
                        <div class="inline-block p-4 bg-white rounded-2xl shadow-xl mb-4">
                            <img src="<?= BASE_URL ?>uploads/qr_code.png" alt="Payment QR Code" class="w-48 h-48 object-contain mx-auto" onerror="this.src='https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=YourShopPayment';">
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 max-w-xs mx-auto">
                            <?= t('qr_upload_instruction') ?>
                        </p>
                    </div>

                    <p id="paymentError" class="text-[10px] text-red-500 mt-2 hidden flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> <?= t('please_select_payment') ?></p>

                    <div class="flex items-center justify-between pt-4">
                        <button onclick="goToStep(3)" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-sm font-medium flex items-center gap-1">
                            <i class="fas fa-arrow-left"></i> Back
                        </button>
                        <button onclick="placeOrder()" class="bg-green-600 hover:bg-green-700 text-white px-10 py-3 rounded-lg font-bold transition shadow-sm text-base">
                            <i class="fas fa-lock mr-2"></i> <?= t('place_order') ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sticky top-4 transition-colors">
                <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4 uppercase tracking-wide"><?= t('order_summary') ?></h2>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400"><?= t('subtotal') ?> (<?= count($cart) ?> <?= t('items') ?>)</span>
                        <span class="font-medium text-gray-900 dark:text-white" id="summarySubtotal">$<?= number_format($subtotal, 2) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400"><?= t('shipping') ?></span>
                        <span class="font-medium" id="summaryShipping">—</span>
                    </div>
                    <hr class="border-gray-100 dark:border-gray-700">
                    <div class="flex justify-between text-lg">
                        <span class="font-bold text-gray-900 dark:text-white"><?= t('total') ?></span>
                        <span class="font-bold text-blue-600 dark:text-blue-400" id="summaryTotal">$<?= number_format($subtotal, 2) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="orderForm" method="POST" action="<?= BASE_URL ?>checkout/process" class="hidden">
    <input type="hidden" name="cart_hash" value="<?= md5(json_encode($cart)) ?>">
</form>

<script>
<?php
$initialAddressText = '—';
if (!empty($savedAddress)) {
    $addrModel = new \App\Models\User\UserAddress();
    $loc = $addrModel->getFullAddress($savedAddress);
    $name = $savedAddress['full_name'] ?? '';
    $phone = $savedAddress['phone'] ?? '';
    $parts = [$name];
    if ($phone) $parts[] = '(' . $phone . ')';
    $parts[] = '- ' . $loc;
    $initialAddressText = implode(' ', $parts);
}
?>
var addressText = <?= json_encode($initialAddressText, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

// ====== STEP NAVIGATION ======
var currentStep = 1;
var totalSteps = 4;

function goToStep(step) {
    document.querySelectorAll('.step-panel').forEach(function(el) { el.classList.add('hidden'); });
    document.getElementById('step' + step).classList.remove('hidden');
    document.getElementById('step' + step).style.animation = 'fadeIn 0.3s ease';

    document.querySelectorAll('.step-dot').forEach(function(dot, i) {
        var idx = i + 1;
        var icon = dot.querySelector('i');
        var origIcon = dot.getAttribute('data-icon');

        dot.classList.remove('bg-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-200', 'dark:shadow-blue-900/40');
        dot.classList.remove('bg-gray-100', 'dark:bg-gray-700', 'text-gray-400', 'dark:text-gray-500');

        if (idx <= step) {
            dot.classList.add('bg-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-200', 'dark:shadow-blue-900/40');
            if (idx < step && icon && origIcon) {
                icon.className = 'fas fa-check';
            } else if (icon && origIcon) {
                icon.className = 'fas ' + origIcon;
            }
        } else {
            dot.classList.add('bg-gray-100', 'dark:bg-gray-700', 'text-gray-400', 'dark:text-gray-500');
            if (icon && origIcon) {
                icon.className = 'fas ' + origIcon;
            }
        }

        var labels = dot.parentElement.querySelectorAll('span');
        labels.forEach(function(label) {
            label.classList.remove('text-blue-600', 'dark:text-blue-400', 'text-gray-400', 'dark:text-gray-500');
            if (idx <= step) {
                label.classList.add('text-blue-600', 'dark:text-blue-400');
            } else {
                label.classList.add('text-gray-400', 'dark:text-gray-500');
            }
        });
    });

    document.querySelectorAll('.step-line').forEach(function(line) {
        var s = parseInt(line.getAttribute('data-step'));
        line.classList.remove('bg-blue-200', 'bg-gray-200', 'dark:bg-blue-800', 'dark:bg-gray-700');
        if (s < step) {
            line.classList.add('bg-blue-200', 'dark:bg-blue-800');
        } else {
            line.classList.add('bg-gray-200', 'dark:bg-gray-700');
        }
    });

    currentStep = step;

    if (step === 3) {
        populateReview();
    }
}

function populateReview() {
    document.getElementById('reviewAddress').textContent = addressText || 'Address selected';

    var ship = document.querySelector('input[name="shipping_method"]:checked');
    if (ship) {
        var parentLabel = ship.closest('label');
        document.getElementById('reviewShipping').textContent = parentLabel ? parentLabel.querySelector('.flex-1 span').textContent.trim() : 'Selected';
    }
}

// ====== STEP 1: ADDRESS ======
var showNewAddrBtn = document.getElementById('showNewAddress');
if (showNewAddrBtn) {
    showNewAddrBtn.addEventListener('click', function() {
        var form = document.getElementById('newAddressForm');
        form.classList.toggle('hidden');
        if (!form.classList.contains('hidden')) {
            initLocationSelects();
        }
    });
}

// If new address form is visible on load, init selects
document.addEventListener('DOMContentLoaded', function() {
    var sel = document.getElementById('selProvince');
    if (sel && sel.value) {
        loadDistricts(sel.value,
            '<?= addslashes($savedAddress['district_code'] ?? '') ?>' || null,
            '<?= addslashes($savedAddress['commune_code'] ?? '') ?>' || null,
            '<?= addslashes($savedAddress['village_code'] ?? '') ?>' || null
        );
    }
});

function initLocationSelects(pc, dc, cc, vc) {
    if (pc) {
        loadDistricts(pc, dc, cc, vc);
    }
}

function saveStep1() {
    var addrRadio = document.querySelector('input[name="address_id"]:checked');
    var form = new FormData();
    form.append('step', 'address');

    if (addrRadio) {
        form.append('address_id', addrRadio.value);
    } else {
        var nameInput = document.getElementById('addr_name');
        var phoneInput = document.getElementById('addr_phone');
        var streetInput = document.getElementById('addr_street');
        var provinceInput = document.getElementById('selProvince');
        var districtInput = document.getElementById('selDistrict');
        var communeInput = document.getElementById('selCommune');
        var villageInput = document.getElementById('selVillage');

        var name = nameInput.value.trim();
        var phone = phoneInput.value.trim();
        var street = streetInput.value.trim();
        var province = provinceInput.value;
        var district = districtInput.value;
        var commune = communeInput.value;
        var village = villageInput.value;

        var isValid = true;

        if (!name) { applyError(nameInput, 'nameError'); isValid = false; }
        if (!phone) { applyError(phoneInput, 'phoneError'); isValid = false; }
        if (!street) { applyError(streetInput, 'streetError'); isValid = false; }
        if (!province) { applyError(provinceInput, 'provinceError'); isValid = false; }
        if (!district) { applyError(districtInput, 'districtError'); isValid = false; }
        if (!commune) { applyError(communeInput, 'communeError'); isValid = false; }
        if (!village) { applyError(villageInput, 'villageError'); isValid = false; }

        if (!isValid) return;

        form.append('full_name', name);
        form.append('phone', phone);
        form.append('street', street);
        form.append('province_code', province);
        form.append('district_code', district);
        form.append('commune_code', commune);
        form.append('village_code', village);
        form.append('latitude', document.getElementById('addr_latitude').value);
        form.append('longitude', document.getElementById('addr_longitude').value);
    }

    fetch('<?= BASE_URL ?>checkout/save-step', { method: 'POST', body: form })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            if (data.address_text) addressText = data.address_text;
            goToStep(2);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(function() { showToast('Network error. Please try again.', 'error'); });
}

// ====== STEP 2: SHIPPING ======
function saveStep2() {
    var selected = document.querySelector('input[name="shipping_method"]:checked');
    if (!selected) {
        document.querySelectorAll('.shipping-radio').forEach(function(el) {
            el.classList.add('border-red-500', 'ring-2', 'ring-red-50', 'dark:ring-red-900/20');
            el.classList.remove('border-gray-200', 'dark:border-gray-600');
        });
        document.getElementById('shippingError').classList.remove('hidden');
        return;
    }

    var form = new FormData();
    form.append('step', 'shipping');
    form.append('method', selected.value);

    fetch('<?= BASE_URL ?>checkout/save-step', { method: 'POST', body: form })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            var costText = selected.closest('label').querySelector('.font-bold').textContent;
            var cost = costText.indexOf('Free') !== -1 ? 0 : parseFloat(costText.replace('$', ''));
            document.getElementById('summaryShipping').textContent = cost > 0 ? '$' + cost.toFixed(2) : 'Free';
            var subtotal = <?= $subtotal ?>;
            document.getElementById('summaryTotal').textContent = '$' + (subtotal + cost).toFixed(2);
            goToStep(3);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(function() { showToast('Network error. Please try again.', 'error'); });
}

// ====== STEP 4: PAYMENT ======
var orderInProgress = false;
function placeOrder() {
    if (orderInProgress) return;
    var selected = document.querySelector('input[name="payment_method"]:checked');
    if (!selected) {
        document.querySelectorAll('.payment-radio').forEach(function(el) {
            el.classList.add('border-red-500', 'ring-2', 'ring-red-50', 'dark:ring-red-900/20');
            el.classList.remove('border-gray-200', 'dark:border-gray-600');
        });
        document.getElementById('paymentError').classList.remove('hidden');
        return;
    }

    orderInProgress = true;
    var btn = document.querySelector('.bg-green-600');
    if (btn) { btn.disabled = true; btn.classList.add('opacity-50', 'cursor-not-allowed'); }

    var form = new FormData();
    form.append('step', 'payment');
    form.append('method', selected.value);

    fetch('<?= BASE_URL ?>checkout/save-step', { method: 'POST', body: form })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            document.getElementById('orderForm').submit();
        } else {
            orderInProgress = false;
            if (btn) { btn.disabled = false; btn.classList.remove('opacity-50', 'cursor-not-allowed'); }
            showToast(data.message, 'error');
        }
    })
    .catch(function() {
        orderInProgress = false;
        if (btn) { btn.disabled = false; btn.classList.remove('opacity-50', 'cursor-not-allowed'); }
        showToast('Network error. Please try again.', 'error');
    });
}

function clearShippingError() {
    document.querySelectorAll('.shipping-radio').forEach(function(el) {
        el.classList.remove('border-red-500', 'ring-2', 'ring-red-50', 'dark:ring-red-900/20');
        el.classList.add('border-gray-200', 'dark:border-gray-600');
    });
    document.getElementById('shippingError').classList.add('hidden');
}

function clearPaymentError() {
    document.querySelectorAll('.payment-radio').forEach(function(el) {
        el.classList.remove('border-red-500', 'ring-2', 'ring-red-50', 'dark:ring-red-900/20');
        el.classList.add('border-gray-200', 'dark:border-gray-600');
    });
    document.getElementById('paymentError').classList.add('hidden');
}

function toggleQrCode(show) {
    const container = document.getElementById('qrCodeContainer');
    if (!container) return;

    if (show) {
        container.classList.remove('hidden');
        container.style.animation = 'fadeIn 0.5s ease forwards';
    } else {
        container.classList.add('hidden');
    }

    // Update radio styles
    document.querySelectorAll('.payment-radio').forEach(function(label) {
        const radio = label.querySelector('input');
        if (radio.checked) {
            label.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
            label.classList.remove('border-gray-200', 'dark:border-gray-600');
        } else {
            label.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
            label.classList.add('border-gray-200', 'dark:border-gray-600');
        }
    });
}

// ====== LOCATION CASCADING ======
function loadDistricts(pc, sd, sc, sv) {
    var s = document.getElementById('selDistrict'), c = document.getElementById('selCommune'), v = document.getElementById('selVillage');
    s.innerHTML = '<option value="">Loading...</option>'; s.disabled = true;
    c.innerHTML = '<option value=""><?= addslashes(t('select_commune')) ?></option>'; c.disabled = true;
    v.innerHTML = '<option value=""><?= addslashes(t('select_village')) ?></option>'; v.disabled = true;
    if (!pc) { s.innerHTML = '<option value=""><?= addslashes(t('select_district')) ?></option>'; return; }

    var url = '<?= BASE_URL ?>api/districts?province_code=' + encodeURIComponent(pc);
    console.log('Fetching districts from:', url);

    fetch(url)
    .then(function(r) {
        if (!r.ok) throw new Error('Network response was not ok: ' + r.statusText);
        return r.json();
    })
    .then(function(d) {
        console.log('Districts received:', d);
        s.innerHTML = '<option value=""><?= addslashes(t('select_district')) ?></option>';
        if (d && d.length > 0) {
            d.forEach(function(x) { s.innerHTML += '<option value="' + x.code + '">' + x.name_en + '</option>'; });
            s.disabled = false;
        } else {
            s.innerHTML = '<option value="">No districts found</option>';
        }
        if (sd) { s.value = sd; loadCommunes(sd, sc, sv); }
    })
    .catch(function(err) {
        console.error('Fetch error:', err);
        s.innerHTML = '<option value="">Error loading districts</option>';
        showToast('Failed to load districts. Check console for details.', 'error');
    });
}

function loadCommunes(dc, sc, sv) {
    var s = document.getElementById('selCommune'), v = document.getElementById('selVillage');
    s.innerHTML = '<option value="">Loading...</option>'; s.disabled = true;
    v.innerHTML = '<option value=""><?= addslashes(t('select_village')) ?></option>'; v.disabled = true;
    if (!dc) { s.innerHTML = '<option value=""><?= addslashes(t('select_commune')) ?></option>'; return; }

    var url = '<?= BASE_URL ?>api/communes?district_code=' + encodeURIComponent(dc);
    fetch(url)
    .then(function(r) {
        if (!r.ok) throw new Error('Network response was not ok');
        return r.json();
    })
    .then(function(d) {
        s.innerHTML = '<option value=""><?= addslashes(t('select_commune')) ?></option>';
        if (d && d.length > 0) {
            d.forEach(function(x) { s.innerHTML += '<option value="' + x.code + '">' + x.name_en + '</option>'; });
            s.disabled = false;
        } else {
            s.innerHTML = '<option value="">No communes found</option>';
        }
        if (sc) { s.value = sc; loadVillages(sc, sv); }
    })
    .catch(function(err) {
        console.error('Fetch error:', err);
        s.innerHTML = '<option value="">Error loading communes</option>';
    });
}

function loadVillages(cc, sv) {
    var s = document.getElementById('selVillage');
    s.innerHTML = '<option value="">Loading...</option>'; s.disabled = true;
    if (!cc) { s.innerHTML = '<option value=""><?= addslashes(t('select_village')) ?></option>'; return; }

    var url = '<?= BASE_URL ?>api/villages?commune_code=' + encodeURIComponent(cc);
    fetch(url)
    .then(function(r) {
        if (!r.ok) throw new Error('Network response was not ok');
        return r.json();
    })
    .then(function(d) {
        s.innerHTML = '<option value=""><?= addslashes(t('select_village')) ?></option>';
        if (d && d.length > 0) {
            d.forEach(function(x) { s.innerHTML += '<option value="' + x.code + '">' + x.name_en + '</option>'; });
            s.disabled = false;
        } else {
            s.innerHTML = '<option value="">No villages found</option>';
        }
        if (sv) { s.value = sv; }
    })
    .catch(function(err) {
        console.error('Fetch error:', err);
        s.innerHTML = '<option value="">Error loading villages</option>';
    });
}

// ====== UTILITY ======
function applyError(input, errorId) {
    input.classList.add('border-red-500', 'ring-2', 'ring-red-50', 'dark:ring-red-900/20');
    input.classList.remove('border-gray-200', 'dark:border-gray-600', 'bg-gray-50', 'dark:bg-gray-700');
    var err = document.getElementById(errorId);
    if (err) err.classList.remove('hidden');
}

function clearError(input, errorId) {
    input.classList.remove('border-red-500', 'ring-2', 'ring-red-50', 'dark:ring-red-900/20');
    input.classList.add('border-gray-200', 'dark:border-gray-600', 'bg-gray-50', 'dark:bg-gray-700');
    var err = document.getElementById(errorId);
    if (err) err.classList.add('hidden');
}

// Add event listeners to clear errors on input
document.addEventListener('DOMContentLoaded', function() {
    ['addr_name', 'addr_phone', 'addr_street'].forEach(function(id) {
        var input = document.getElementById(id);
        if (input) {
            input.addEventListener('input', function() {
                clearError(this, id.replace('addr_', '') + 'Error');
            });
        }
    });

    // Clear all errors when a saved address is selected
    document.querySelectorAll('.address-select').forEach(function(radio) {
        radio.addEventListener('change', function() {
            if (this.checked) {
                ['nameError', 'phoneError', 'streetError', 'provinceError', 'districtError', 'communeError', 'villageError'].forEach(function(eid) {
                    var err = document.getElementById(eid);
                    if (err) err.classList.add('hidden');
                });
                ['addr_name', 'addr_phone', 'addr_street', 'selProvince', 'selDistrict', 'selCommune', 'selVillage'].forEach(function(id) {
                    var input = document.getElementById(id);
                    if (input) {
                        input.classList.remove('border-red-500', 'ring-2', 'ring-red-50', 'dark:ring-red-900/20');
                        input.classList.add('border-gray-200', 'dark:border-gray-600', 'bg-gray-50', 'dark:bg-gray-700');
                    }
                });
            }
        });
    });
});

var style = document.createElement('style');
style.textContent = '@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }';
document.head.appendChild(style);
</script>
