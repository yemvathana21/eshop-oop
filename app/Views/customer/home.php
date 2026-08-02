<!-- Hero Slider Section -->
<section class="relative min-h-[600px] flex items-center overflow-hidden bg-gray-900 mt-[-1px]">
    <!-- Slider Container -->
    <div id="heroSlider" class="relative w-full h-full min-h-[600px]">
        <!-- Slide 1 -->
        <div class="hero-slide active transition-all duration-1000 ease-in-out opacity-100 absolute inset-0 w-full h-full flex items-center">
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0 z-0">
                <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" class="w-full h-full object-cover" alt="Women Fashion">
                <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/80 to-transparent"></div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10 w-full">
                <div class="text-white space-y-6 animate-fade-in max-w-2xl">
                    <h4 class="text-blue-400 font-bold tracking-widest uppercase text-sm"><?= t('hero_welcome') ?></h4>
                    <h1 class="text-5xl md:text-6xl font-extrabold leading-tight"><?= t('hero_slide1_title') ?></h1>
                    <div class="space-y-2">
                        <p class="text-2xl font-semibold text-gray-300"><?= t('hero_slide1_subtitle') ?></p>
                        <p class="text-gray-400 text-lg"><?= t('hero_slide1_desc') ?></p>
                    </div>
                    <div class="flex gap-4 pt-4">
                        <a href="<?= BASE_URL ?>shop" class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 rounded-lg font-bold transition transform hover:scale-105 shadow-xl">
                            <?= t('shop_now') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="hero-slide transition-all duration-1000 ease-in-out opacity-0 absolute inset-0 w-full h-full invisible flex items-center">
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0 z-0">
                <img src="https://images.unsplash.com/photo-1558002038-1055907df827?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" class="w-full h-full object-cover" alt="Smart Home">
                <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/80 to-transparent"></div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10 w-full">
                <div class="text-white space-y-6 max-w-2xl">
                    <h4 class="text-blue-400 font-bold tracking-widest uppercase text-sm"><?= t('hero_welcome') ?></h4>
                    <h1 class="text-5xl md:text-6xl font-extrabold leading-tight"><?= t('hero_slide2_title') ?></h1>
                    <div class="space-y-2">
                        <p class="text-2xl font-semibold text-gray-300"><?= t('hero_slide2_subtitle') ?></p>
                        <p class="text-gray-400 text-lg"><?= t('hero_slide2_desc') ?></p>
                    </div>
                    <div class="flex gap-4 pt-4">
                        <a href="<?= BASE_URL ?>shop" class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 rounded-lg font-bold transition transform hover:scale-105 shadow-xl">
                            <?= t('shop_now') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="hero-slide transition-all duration-1000 ease-in-out opacity-0 absolute inset-0 w-full h-full invisible flex items-center">
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0 z-0">
                <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" class="w-full h-full object-cover" alt="Fashion Collection">
                <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/80 to-transparent"></div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10 w-full">
                <div class="text-white space-y-6 max-w-2xl">
                    <h4 class="text-blue-400 font-bold tracking-widest uppercase text-sm"><?= t('hero_welcome') ?></h4>
                    <h1 class="text-5xl md:text-6xl font-extrabold leading-tight"><?= t('hero_slide3_title') ?></h1>
                    <div class="space-y-2">
                        <p class="text-2xl font-semibold text-gray-300"><?= t('hero_slide3_subtitle') ?></p>
                        <p class="text-gray-400 text-lg"><?= t('hero_slide3_desc') ?></p>
                    </div>
                    <div class="flex gap-4 pt-4">
                        <a href="<?= BASE_URL ?>shop" class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 rounded-lg font-bold transition transform hover:scale-105 shadow-xl">
                            <?= t('shop_now') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Dots -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-3 z-20">
        <button onclick="goToSlide(0)" class="slider-dot w-3 h-3 rounded-full bg-blue-600 transition-all duration-300 ring-4 ring-blue-600/20"></button>
        <button onclick="goToSlide(1)" class="slider-dot w-3 h-3 rounded-full bg-gray-600 transition-all duration-300 hover:bg-gray-400"></button>
        <button onclick="goToSlide(2)" class="slider-dot w-3 h-3 rounded-full bg-gray-600 transition-all duration-300 hover:bg-gray-400"></button>
    </div>
</section>

<!-- Trust Badges -->
<section class="bg-white dark:bg-gray-950 py-16 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8">
            <!-- Easy Returns -->
            <div class="text-center group">
                <div class="w-16 h-16 bg-blue-50 dark:bg-blue-900/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition duration-300">
                    <i class="fas fa-arrow-rotate-left text-blue-600 text-xl"></i>
                </div>
                <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1"><?= t('trust_easy_returns') ?></h4>
                <p class="text-[11px] text-gray-500 leading-tight"><?= t('trust_easy_returns_desc') ?></p>
            </div>

            <!-- Free Shipping -->
            <div class="text-center group">
                <div class="w-16 h-16 bg-green-50 dark:bg-green-900/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition duration-300">
                    <i class="fas fa-truck text-green-600 text-xl"></i>
                </div>
                <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1"><?= t('trust_free_shipping') ?></h4>
                <p class="text-[11px] text-gray-500 leading-tight"><?= t('trust_free_shipping_desc') ?></p>
            </div>

            <!-- Fast Shipping -->
            <div class="text-center group">
                <div class="w-16 h-16 bg-orange-50 dark:bg-orange-900/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition duration-300">
                    <i class="fas fa-bolt-lightning text-orange-500 text-xl"></i>
                </div>
                <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1"><?= t('trust_fast_shipping') ?></h4>
                <p class="text-[11px] text-gray-500 leading-tight"><?= t('trust_fast_shipping_desc') ?></p>
            </div>

            <!-- Satisfaction -->
            <div class="text-center group">
                <div class="w-16 h-16 bg-purple-50 dark:bg-purple-900/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition duration-300">
                    <i class="fas fa-smile text-purple-600 text-xl"></i>
                </div>
                <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1"><?= t('trust_satisfaction') ?></h4>
                <p class="text-[11px] text-gray-500 leading-tight"><?= t('trust_satisfaction_desc') ?></p>
            </div>

            <!-- Secure Checkout -->
            <div class="text-center group">
                <div class="w-16 h-16 bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition duration-300">
                    <i class="fas fa-shield-halved text-indigo-600 text-xl"></i>
                </div>
                <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1"><?= t('trust_secure_checkout') ?></h4>
                <p class="text-[11px] text-gray-500 leading-tight"><?= t('trust_secure_checkout_desc') ?></p>
            </div>

            <!-- Money Back -->
            <div class="text-center group">
                <div class="w-16 h-16 bg-red-50 dark:bg-red-900/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition duration-300">
                    <i class="fas fa-hand-holding-dollar text-red-600 text-xl"></i>
                </div>
                <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1"><?= t('trust_money_back') ?></h4>
                <p class="text-[11px] text-gray-500 leading-tight"><?= t('trust_money_back_desc') ?></p>
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
    @keyframes fade-in {
        from { opacity: 0; transform: translateX(-30px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .animate-fade-in { animation: fade-in 1s ease-out forwards; }

    .hero-slide {
        transition: opacity 1s ease-in-out, visibility 1s;
    }
    .hero-slide.active {
        opacity: 1 !important;
        visibility: visible !important;
        z-index: 10;
    }
</style>

<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.slider-dot');
    let slideInterval;

    function showSlide(n) {
        slides.forEach(slide => {
            slide.classList.remove('active');
            slide.classList.add('opacity-0', 'invisible');
        });
        dots.forEach(dot => {
            dot.classList.remove('bg-blue-600', 'ring-4', 'ring-blue-600/20');
            dot.classList.add('bg-gray-600');
        });

        currentSlide = (n + slides.length) % slides.length;
        slides[currentSlide].classList.add('active');
        slides[currentSlide].classList.remove('opacity-0', 'invisible');

        dots[currentSlide].classList.add('bg-blue-600', 'ring-4', 'ring-blue-600/20');
        dots[currentSlide].classList.remove('bg-gray-600');
    }

    function nextSlide() {
        showSlide(currentSlide + 1);
    }

    function goToSlide(n) {
        showSlide(n);
        resetInterval();
    }

    function resetInterval() {
        clearInterval(slideInterval);
        slideInterval = setInterval(nextSlide, 5000);
    }

    // Initialize
    resetInterval();
</script>
