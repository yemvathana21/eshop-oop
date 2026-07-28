<!-- Hero Section -->
<section class="relative bg-[#2b3445] min-h-[500px] flex items-center overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10 w-full">
        <div class="grid md:grid-cols-2 gap-8 items-center">
            <div class="text-white space-y-6 animate-fade-in">
                <h4 class="text-blue-400 font-bold tracking-widest uppercase text-sm">Welcome to Ecommerce PHP</h4>
                <h1 class="text-5xl md:text-6xl font-extrabold leading-tight">Shop Online for Latest <span class="text-blue-500">Women Accessories</span></h1>
                <div class="space-y-2">
                    <p class="text-2xl font-semibold text-gray-300">50% Discount on All Products</p>
                    <p class="text-gray-400 max-w-md">Lorem ipsum dolor sit amet, an labores explicari qui, eu nostrum copiosae argumentum has.</p>
                </div>
                <div class="flex gap-4 pt-4">
                    <a href="<?= BASE_URL ?>shop" class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 rounded-lg font-bold transition transform hover:scale-105 shadow-xl">
                        Shop Now
                    </a>
                </div>
            </div>
            <div class="hidden md:block relative animate-float">
                <div class="relative bg-white/5 backdrop-blur-md rounded-full p-10 border border-white/10">
                    <img src="https://dummyimage.com/500x500/2b3445/fff&text=E-Shop+Promo" class="w-full max-w-md mx-auto drop-shadow-2xl rounded-full">
                </div>
            </div>
        </div>
    </div>

    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-2">
        <div class="w-3 h-3 rounded-full bg-blue-600"></div>
        <div class="w-3 h-3 rounded-full bg-gray-600"></div>
        <div class="w-3 h-3 rounded-full bg-gray-600"></div>
    </div>
</section>

<!-- Trust Badges -->
<section class="bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 py-16 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-8">
            <!-- Easy Returns -->
            <div class="flex flex-col items-center text-center group">
                <div class="w-14 h-14 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-sm">
                    <i class="fas fa-rotate-left text-xl"></i>
                </div>
                <h4 class="font-bold text-gray-900 dark:text-white text-sm mb-1">Easy Returns</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-tight px-2">Return any item before 15 days!</p>
            </div>
            <!-- Free Shipping -->
            <div class="flex flex-col items-center text-center group">
                <div class="w-14 h-14 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300 shadow-sm">
                    <i class="fas fa-truck text-xl"></i>
                </div>
                <h4 class="font-bold text-gray-900 dark:text-white text-sm mb-1">Free Shipping</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-tight px-2">Enjoy free shipping inside US.</p>
            </div>
            <!-- Fast Shipping -->
            <div class="flex flex-col items-center text-center group">
                <div class="w-14 h-14 bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-orange-600 group-hover:text-white transition-all duration-300 shadow-sm">
                    <i class="fas fa-bolt text-xl"></i>
                </div>
                <h4 class="font-bold text-gray-900 dark:text-white text-sm mb-1">Fast Shipping</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-tight px-2">Items are shipped within 24 hours.</p>
            </div>
            <!-- Satisfaction -->
            <div class="flex flex-col items-center text-center group">
                <div class="w-14 h-14 bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-purple-600 group-hover:text-white transition-all duration-300 shadow-sm">
                    <i class="fas fa-face-smile text-xl"></i>
                </div>
                <h4 class="font-bold text-gray-900 dark:text-white text-sm mb-1">Satisfaction</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-tight px-2">We guarantee you with our quality satisfaction.</p>
            </div>
            <!-- Secure Checkout -->
            <div class="flex flex-col items-center text-center group">
                <div class="w-14 h-14 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300 shadow-sm">
                    <i class="fas fa-shield-check text-xl"></i>
                </div>
                <h4 class="font-bold text-gray-900 dark:text-white text-sm mb-1">Secure Checkout</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-tight px-2">Providing Secure Checkout Options for all</p>
            </div>
            <!-- Money Back -->
            <div class="flex flex-col items-center text-center group">
                <div class="w-14 h-14 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-red-600 group-hover:text-white transition-all duration-300 shadow-sm">
                    <i class="fas fa-hand-holding-dollar text-xl"></i>
                </div>
                <h4 class="font-bold text-gray-900 dark:text-white text-sm mb-1">Money Back</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-tight px-2">Offer money back guarantee on our products</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-20 bg-gray-50 dark:bg-gray-950 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-3 tracking-tight"><?= t('featured_products') ?></h2>
            <p class="text-gray-500 dark:text-gray-400 text-lg"><?= t('featured_products_subtitle') ?></p>
            <div class="w-24 h-1.5 bg-blue-600 mx-auto mt-6 rounded-full shadow-sm shadow-blue-500/50"></div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php foreach ($featured as $product): ?>
                <?php include APP_PATH . 'Views/customer/partials/product_card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Latest Products -->
<section class="py-20 bg-white dark:bg-gray-900 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-3 tracking-tight"><?= t('latest_products') ?></h2>
            <p class="text-gray-500 dark:text-gray-400 text-lg"><?= t('latest_products_subtitle') ?></p>
            <div class="w-24 h-1.5 bg-blue-600 mx-auto mt-6 rounded-full shadow-sm shadow-blue-500/50"></div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php foreach ($latest as $product): ?>
                <?php include APP_PATH . 'Views/customer/partials/product_card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Popular Products -->
<section class="py-20 bg-gray-50 dark:bg-gray-950 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-3 tracking-tight"><?= t('popular_products') ?></h2>
            <p class="text-gray-500 dark:text-gray-400 text-lg"><?= t('popular_products_subtitle') ?></p>
            <div class="w-24 h-1.5 bg-blue-600 mx-auto mt-6 rounded-full shadow-sm shadow-blue-500/50"></div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php foreach ($popular as $product): ?>
                <?php include APP_PATH . 'Views/customer/partials/product_card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Newsletter CTA -->
<section class="bg-[#232f3e] py-24 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-blue-600/5 mix-blend-overlay"></div>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h2 class="text-4xl font-extrabold mb-4 tracking-tight"><?= t('newsletter_title') ?></h2>
        <p class="text-gray-400 text-lg mb-10">Subscribe to get special offers, free giveaways, and once-in-a-lifetime deals delivered straight to your inbox.</p>
        <form class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto" onsubmit="return false;">
            <input type="email" placeholder="<?= t('email_placeholder') ?>"
                   class="flex-1 px-6 py-4 rounded-2xl bg-gray-800 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-inner text-lg">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-10 py-4 rounded-2xl font-bold transition transform hover:scale-105 shadow-xl text-lg">
                Subscribe
            </button>
        </form>
    </div>
</section>

<style>
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }
    .animate-float { animation: float 6s ease-in-out infinite; }

    @keyframes fade-in {
        from { opacity: 0; transform: translateX(-30px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .animate-fade-in { animation: fade-in 1s ease-out forwards; }
</style>
