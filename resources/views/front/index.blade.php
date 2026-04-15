@extends('front_layout.main')
@section('content')
    <div id="screen-home" class="screen active fade-up pb-24">

        <!-- Hero Banner -->
        <div class="gradient-brand px-5 pt-4 pb-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full -translate-y-8 translate-x-8">
            </div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white opacity-5 rounded-full translate-y-8 -translate-x-8">
            </div>
            <div class="flex items-start justify-between relative z-10">
                <div>
                    <p class="text-white/70 text-xs mb-1">Namaste, {{ Session::get('shopuser')->shop_name ?? 'Sir' }} 👋</p>

                    <div class="flex items-center my-2">
                        <img src="{{url('logo/logo3a.png')}}" alt="Logo" loading="lazy"
                            class="h-12 w-auto object-contain" style=" border-radius: 5px;">
                    </div>

                    <p class="text-white/70 text-xs mt-1">Scan · Spin · Earn · Save</p>
                </div>
                <div class="text-right hidden">
                    <div class="glass rounded-2xl px-3 py-2 text-center cursor-pointer" onclick="showScreen('wallet')">
                        <p class="text-white/70 text-xs">Wallet</p>
                        <p class="text-white font-display font-bold text-lg leading-none">340</p>
                        <p class="text-gold-300 text-xs font-medium">coins 🪙</p>
                    </div>
                </div>
            </div>
            <!-- Streak Bar -->
            <div class="flex items-center gap-2 mt-4 relative z-10">
                <div class="glass rounded-full px-3 py-1.5 flex items-center gap-1.5">
                    <span class="text-xs">🔥</span><span class="text-white text-xs font-semibold">3-day streak</span>
                </div>
                <div class="glass rounded-full px-3 py-1.5 flex items-center gap-1.5">
                    <span class="text-xs">🏆</span><span class="text-white text-xs font-semibold">Explorer Level</span>
                </div>
            </div>
        </div>

        <div class="px-3 pt-3 pb-1">
            <div class="relative overflow-hidden rounded-2xl" id="bannerWrap">
                <div class="flex transition-transform duration-400 ease-in-out" id="bannerTrack"
                    style="will-change: transform;">
                    <div class="flex-shrink-0 w-full">
                        <img src="https://res.cloudinary.com/dhsiw4fc5/image/upload/v1776236646/1_ndf70j.png"
                            class="w-full h-auto rounded-2xl object-cover" loading="lazy" alt="Offer 1">
                    </div>
                    <div class="flex-shrink-0 w-full">
                        <img src="https://res.cloudinary.com/dhsiw4fc5/image/upload/v1776236645/2_qeo48l.png"
                            class="w-full h-auto rounded-2xl object-cover" loading="lazy" alt="Offer 2">
                    </div>
                    <div class="flex-shrink-0 w-full">
                        <img src="https://res.cloudinary.com/dhsiw4fc5/image/upload/v1776236646/3_ontuk3.png"
                            class="w-full h-auto rounded-2xl object-cover" loading="lazy" alt="Offer 3">
                    </div>
                    <div class="flex-shrink-0 w-full">
                        <img src="https://res.cloudinary.com/dhsiw4fc5/image/upload/v1776236645/4_vwmwro.png"
                            class="w-full h-auto rounded-2xl object-cover" loading="lazy" alt="Offer 4">
                    </div>
                </div>
            </div>
            <div class="flex justify-center gap-1.5 mt-5" id="bannerDots">
                <button class="banner-dot w-4 h-1.5 rounded-full bg-saffron-500 transition-all duration-300"></button>
                <button class="banner-dot w-1.5 h-1.5 rounded-full bg-ink-200 transition-all duration-300"></button>
                <button class="banner-dot w-1.5 h-1.5 rounded-full bg-ink-200 transition-all duration-300"></button>
                <button class="banner-dot w-1.5 h-1.5 rounded-full bg-ink-200 transition-all duration-300"></button>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="px-4 mt-5 mb-3 z-20 relative">
            <div class="bg-white rounded-2xl shadow-lg border border-ink-100 px-4 py-3 flex items-center gap-3">
                <svg class="w-4 h-4 text-ink-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.35-4.35" />
                </svg>
                <input type="text" placeholder="Dukan, service ya offer dhundho..."
                    class="flex-1 text-sm text-ink-600 bg-transparent border-none focus:outline-none placeholder-ink-300" />
                <button class="w-7 h-7 gradient-brand rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2.5">
                        <path d="M3 6h18M6 12h12M10 18h4" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Categories -->
        <div class="px-4 mb-4">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-ink-400 uppercase tracking-wider">Categories</p>
                <span class="text-xs text-saffron-500 font-semibold cursor-pointer">Sab dekho →</span>
            </div>
            <div class="grid grid-cols-4 gap-2" id="catGrid">
                <button onclick="filterAndNav('food')"
                    class="flex flex-col items-center gap-1 bg-ink-50 rounded-2xl p-2.5 hover:bg-saffron-50 transition border border-transparent hover:border-saffron-200">
                    <span class="text-2xl">🍛</span><span class="text-xs text-ink-500 font-medium">Food</span>
                </button>
                <button onclick="filterAndNav('salon')"
                    class="flex flex-col items-center gap-1 bg-ink-50 rounded-2xl p-2.5 hover:bg-saffron-50 transition border border-transparent hover:border-saffron-200">
                    <span class="text-2xl">✂️</span><span class="text-xs text-ink-500 font-medium">Salon</span>
                </button>
                <button onclick="filterAndNav('kirana')"
                    class="flex flex-col items-center gap-1 bg-ink-50 rounded-2xl p-2.5 hover:bg-saffron-50 transition border border-transparent hover:border-saffron-200">
                    <span class="text-2xl">🛒</span><span class="text-xs text-ink-500 font-medium">Kirana</span>
                </button>
                <button onclick="filterAndNav('clothing')"
                    class="flex flex-col items-center gap-1 bg-ink-50 rounded-2xl p-2.5 hover:bg-saffron-50 transition border border-transparent hover:border-saffron-200">
                    <span class="text-2xl">👗</span><span class="text-xs text-ink-500 font-medium">Kapde</span>
                </button>
                <button onclick="filterAndNav('medical')"
                    class="flex flex-col items-center gap-1 bg-ink-50 rounded-2xl p-2.5 hover:bg-saffron-50 transition border border-transparent hover:border-saffron-200">
                    <span class="text-2xl">💊</span><span class="text-xs text-ink-500 font-medium">Medical</span>
                </button>
                <button onclick="filterAndNav('rozana')"
                    class="flex flex-col items-center gap-1 bg-ink-50 rounded-2xl p-2.5 hover:bg-saffron-50 transition border border-transparent hover:border-saffron-200">
                    <span class="text-2xl">🥛</span><span class="text-xs text-ink-500 font-medium">Rozana</span>
                </button>
                <button onclick="filterAndNav('footwear')"
                    class="flex flex-col items-center gap-1 bg-ink-50 rounded-2xl p-2.5 hover:bg-saffron-50 transition border border-transparent hover:border-saffron-200">
                    <span class="text-2xl">👟</span><span class="text-xs text-ink-500 font-medium">Joote</span>
                </button>
                <button onclick="showScreen('explore')"
                    class="flex flex-col items-center gap-1 bg-ink-50 rounded-2xl p-2.5 hover:bg-saffron-50 transition border border-transparent hover:border-saffron-200">
                    <span class="text-2xl">🔍</span><span class="text-xs text-ink-500 font-medium">Aur</span>
                </button>
            </div>
        </div>

        @include('front.partial.shop_listing')
        @include('front.partial.shop_list_slider')
        <!-- Flash Deals -->
        <div class="px-4 mb-4">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-ink-400 uppercase tracking-wider">⚡ Flash Deals</p>
                <div class="flex items-center gap-1 bg-red-50 border border-red-200 rounded-full px-2 py-0.5">
                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                    <span class="text-xs text-red-600 font-semibold" id="flashTimer">1:47:23</span>
                </div>
            </div>
            <div class="flex gap-3 overflow-x-auto pb-1">
                <div class="flex-shrink-0 w-48 card-hover cursor-pointer" onclick="openShop('sharma-sweets')">
                    <div
                        class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl p-3 text-white relative overflow-hidden">
                        <div class="absolute top-2 right-2 bg-white/20 rounded-full px-2 py-0.5 text-xs font-bold">-30%
                        </div>
                        <div class="text-3xl mb-1">🍡</div>
                        <p class="font-display font-bold text-sm">Sharma Sweets</p>
                        <p class="text-xs opacity-80">Free samosa on ₹200</p>
                        <div class="mt-2 bg-white/20 rounded-lg px-2 py-1">
                            <p class="text-xs font-bold">⏰ 1 hr 47 min bacha</p>
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0 w-48 card-hover cursor-pointer" onclick="showScreen('spin')">
                    <div
                        class="bg-gradient-to-br from-saffron-500 to-saffron-700 rounded-2xl p-3 text-white relative overflow-hidden">
                        <div class="absolute top-2 right-2 bg-white/20 rounded-full px-2 py-0.5 text-xs font-bold">SPIN
                        </div>
                        <div class="text-3xl mb-1">👟</div>
                        <p class="font-display font-bold text-sm">Raj Shoe Store</p>
                        <p class="text-xs opacity-80">Spin karo — 20% OFF jeeto</p>
                        <div class="mt-2 bg-white/20 rounded-lg px-2 py-1">
                            <p class="text-xs font-bold">🎡 1 free spin</p>
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0 w-48 card-hover cursor-pointer" onclick="openShop('glamour-salon')">
                    <div
                        class="bg-gradient-to-br from-pink-500 to-rose-600 rounded-2xl p-3 text-white relative overflow-hidden">
                        <div class="absolute top-2 right-2 bg-white/20 rounded-full px-2 py-0.5 text-xs font-bold">NEW
                        </div>
                        <div class="text-3xl mb-1">✂️</div>
                        <p class="font-display font-bold text-sm">Glamour Salon</p>
                        <p class="text-xs opacity-80">Navratri Package ₹499</p>
                        <div class="mt-2 bg-white/20 rounded-lg px-2 py-1">
                            <p class="text-xs font-bold">📅 Sirf 10 slots bache</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Health Card Banner -->
        <div class="mx-4 mb-4">
            <div class="gradient-dark rounded-2xl p-4 flex items-center gap-3 cursor-pointer card-hover"
                onclick="showScreen('healthcard')">
                <div class="w-12 h-12 bg-gold-500 rounded-xl flex items-center justify-center text-2xl float">🏥</div>
                <div class="flex-1">
                    <p class="text-gold-300 text-xs font-semibold uppercase tracking-wide">City Hospital Card</p>
                    <p class="text-white font-display font-bold text-sm">Hamesha 20% OFF medicines</p>
                    <p class="text-white/50 text-xs">Sirf ₹300 mein — digital card</p>
                </div>
                <svg class="w-5 h-5 text-gold-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </div>

        <!-- Rozana Wala Section -->
        <div class="px-4 mb-4">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-ink-400 uppercase tracking-wider">🥛 Rozana Wala</p>
                <span class="text-xs text-saffron-500 font-semibold cursor-pointer" onclick="showScreen('rozana')">Sab
                    dekho
                    →</span>
            </div>
            <div class="flex gap-3 overflow-x-auto pb-1">
                <div class="flex-shrink-0 w-36 bg-white border border-ink-100 rounded-2xl p-3 card-hover cursor-pointer"
                    onclick="showScreen('rozana')">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-xl mb-2">🥛</div>
                    <p class="font-display font-bold text-xs text-ink-700">Raju Dairy</p>
                    <p class="text-xs text-ink-400">Ward 5 • ★4.8</p>
                    <p class="text-xs text-forest-600 font-semibold mt-1">47 customers</p>
                </div>
                <div class="flex-shrink-0 w-36 bg-white border border-ink-100 rounded-2xl p-3 card-hover cursor-pointer"
                    onclick="showScreen('rozana')">
                    <div class="w-10 h-10 bg-yellow-50 rounded-xl flex items-center justify-center text-xl mb-2">🚖</div>
                    <p class="font-display font-bold text-xs text-ink-700">Mohan Taxi</p>
                    <p class="text-xs text-ink-400">Civil Lines • ★4.6</p>
                    <p class="text-xs text-forest-600 font-semibold mt-1">Available now</p>
                </div>
                <div class="flex-shrink-0 w-36 bg-white border border-ink-100 rounded-2xl p-3 card-hover cursor-pointer"
                    onclick="showScreen('rozana')">
                    <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center text-xl mb-2">🛒</div>
                    <p class="font-display font-bold text-xs text-ink-700">Suresh Thela</p>
                    <p class="text-xs text-ink-400">Sipri • ★4.5</p>
                    <p class="text-xs text-forest-600 font-semibold mt-1">Aaj fresh stock</p>
                </div>
            </div>
        </div>

        <!-- Nearby Shops -->
        <div class="px-4">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-ink-400 uppercase tracking-wider">Aas-paas ki Dukaanein</p>
                <div class="flex gap-1 overflow-x-auto">
                    <button
                        class="cat-chip cat-active text-xs px-2.5 py-1 rounded-full border border-saffron-200 font-medium"
                        onclick="filterCat(this,'all')">Sab</button>
                    <button
                        class="cat-chip text-xs px-2.5 py-1 rounded-full border border-ink-200 text-ink-500 font-medium bg-white"
                        onclick="filterCat(this,'food')">Food</button>
                    <button
                        class="cat-chip text-xs px-2.5 py-1 rounded-full border border-ink-200 text-ink-500 font-medium bg-white"
                        onclick="filterCat(this,'salon')">Salon</button>
                    <button
                        class="cat-chip text-xs px-2.5 py-1 rounded-full border border-ink-200 text-ink-500 font-medium bg-white"
                        onclick="filterCat(this,'kirana')">Kirana</button>
                </div>
            </div>
            <div class="flex flex-col gap-3" id="shopList">

                <!-- Shop: Sharma Sweets -->
                <div class="shop-card bg-white border border-ink-100 rounded-2xl p-4 card-hover" data-cat="food"
                    onclick="openShop('sharma-sweets')">
                    <div class="flex gap-3 mb-3">
                        <div
                            class="w-14 h-14 bg-amber-50 rounded-xl flex items-center justify-center text-3xl flex-shrink-0">
                            🍡
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-display font-bold text-ink-800 text-sm">Sharma Sweet House</h3>
                                    <p class="text-xs text-ink-400">Mithai & Snacks • Sipri Bazar</p>
                                </div>
                                <span
                                    class="bg-forest-100 text-forest-700 text-xs font-bold px-2 py-0.5 rounded-full flex-shrink-0">Open</span>
                            </div>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-saffron-500 font-semibold">★ 4.8</span>
                                <span class="text-ink-200 text-xs">•</span>
                                <span class="text-xs text-ink-400">280m</span>
                                <span class="text-ink-200 text-xs">•</span>
                                <span class="text-xs text-ink-400">128 reviews</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-1.5 flex-wrap mb-3">
                        <span
                            class="bg-saffron-50 text-saffron-700 border border-saffron-200 rounded-full px-2 py-0.5 text-xs font-medium">Free
                            samosa ₹200+</span>
                        <span
                            class="bg-saffron-50 text-saffron-700 border border-saffron-200 rounded-full px-2 py-0.5 text-xs font-medium">10%
                            off sweets</span>
                        <span
                            class="bg-saffron-50 text-saffron-700 border border-saffron-200 rounded-full px-2 py-0.5 text-xs font-medium">🎡
                            Spin available</span>
                    </div>
                    <button
                        class="w-full gradient-brand text-white rounded-xl py-2.5 text-sm font-display font-bold btn-press"
                        onclick="event.stopPropagation();showScreen('spin')">🎡 Spin & Win Offers</button>
                </div>

                <!-- Shop: Glamour Salon -->
                <div class="shop-card bg-white border border-ink-100 rounded-2xl p-4 card-hover" data-cat="salon"
                    onclick="openShop('glamour-salon')">
                    <div class="flex gap-3 mb-3">
                        <div
                            class="w-14 h-14 bg-pink-50 rounded-xl flex items-center justify-center text-3xl flex-shrink-0">
                            ✂️
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-display font-bold text-ink-800 text-sm">Glamour Salon & Spa</h3>
                                    <p class="text-xs text-ink-400">Unisex Salon • Sadar Bazar</p>
                                </div>
                                <span class="badge-new">NEW</span>
                            </div>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-saffron-500 font-semibold">★ 4.7</span>
                                <span class="text-ink-200 text-xs">•</span>
                                <span class="text-xs text-ink-400">150m</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-pink-50 rounded-xl p-2.5 mb-3 flex items-center gap-2">
                        <span class="text-base">💆</span>
                        <div>
                            <p class="text-xs font-bold text-pink-700">Navratri Special Package</p>
                            <p class="text-xs text-pink-500">Haircut + Facial + Eyebrows — ₹499 only</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="flex-1 gradient-brand text-white rounded-xl py-2 text-xs font-bold btn-press"
                            onclick="event.stopPropagation();showScreen('spin')">🎡 Spin & Win</button>
                        <button
                            class="flex-1 bg-ink-50 border border-ink-200 text-ink-700 rounded-xl py-2 text-xs font-bold btn-press">📅
                            Book Now</button>
                    </div>
                </div>

                <!-- Shop: Ramesh Kirana -->
                <div class="shop-card bg-white border border-ink-100 rounded-2xl p-4 card-hover" data-cat="kirana"
                    onclick="openShop('ramesh-kirana')">
                    <div class="flex gap-3 mb-3">
                        <div
                            class="w-14 h-14 bg-green-50 rounded-xl flex items-center justify-center text-3xl flex-shrink-0">
                            🛒
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-display font-bold text-ink-800 text-sm">Ramesh Kirana Store</h3>
                            <p class="text-xs text-ink-400">Grocery • Lohamandi</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-saffron-500 font-semibold">★ 4.4</span>
                                <span class="text-ink-200 text-xs">•</span>
                                <span class="text-xs text-ink-400">400m • WhatsApp order</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-green-50 rounded-xl p-2.5 mb-3">
                        <p class="text-xs font-bold text-forest-700 mb-1">📋 Aaj ki Special List</p>
                        <div class="grid grid-cols-2 gap-1 text-xs text-forest-600">
                            <span>🍅 Tamatar — ₹30/kg</span>
                            <span>🧅 Pyaz — ₹25/kg</span>
                            <span>🥔 Aloo — ₹20/kg</span>
                            <span>🫚 Tel — ₹140/L</span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="flex-1 gradient-brand text-white rounded-xl py-2 text-xs font-bold btn-press"
                            onclick="event.stopPropagation();showScreen('spin')">🎡 Spin & Win</button>
                        <button class="flex-1 bg-green-500 text-white rounded-xl py-2 text-xs font-bold btn-press">💬
                            WhatsApp
                            Order</button>
                    </div>
                </div>

                <!-- Shop: Raj Shoe Store -->
                <div class="shop-card bg-white border border-ink-100 rounded-2xl p-4 card-hover" data-cat="footwear"
                    onclick="showScreen('shopprofile','Raj Shoe Store')">
                    <div class="flex gap-3 mb-3">
                        <div
                            class="w-14 h-14 bg-orange-50 rounded-xl flex items-center justify-center text-3xl flex-shrink-0">
                            👟
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-display font-bold text-ink-800 text-sm">Raj Shoe Store</h3>
                                    <p class="text-xs text-ink-400">Footwear • Sadar Bazar</p>
                                </div>
                                <div
                                    class="flex items-center gap-1 bg-red-50 border border-red-200 rounded-full px-2 py-0.5">
                                    <span class="text-red-500 text-xs font-bold">SALE</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-saffron-500 font-semibold">★ 4.5</span>
                                <span class="text-ink-200 text-xs">•</span>
                                <span class="text-xs text-ink-400">120m</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-1.5 flex-wrap mb-3">
                        <span
                            class="bg-saffron-50 text-saffron-700 border border-saffron-200 rounded-full px-2 py-0.5 text-xs font-medium">20%
                            off formals</span>
                        <span
                            class="bg-saffron-50 text-saffron-700 border border-saffron-200 rounded-full px-2 py-0.5 text-xs font-medium">Free
                            polish</span>
                        <span
                            class="bg-red-50 text-red-600 border border-red-200 rounded-full px-2 py-0.5 text-xs font-medium">Buy
                            2
                            get 1</span>
                    </div>
                    <button
                        class="w-full gradient-brand text-white rounded-xl py-2.5 text-sm font-display font-bold btn-press"
                        onclick="event.stopPropagation();showScreen('spin')">🎡 Spin & Win Offers</button>
                </div>

                <!-- Shop: Kapoor Cloth -->
                <div class="shop-card bg-white border border-ink-100 rounded-2xl p-4 card-hover" data-cat="clothing">
                    <div class="flex gap-3 mb-3">
                        <div
                            class="w-14 h-14 bg-indigo-50 rounded-xl flex items-center justify-center text-3xl flex-shrink-0">
                            👗
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-display font-bold text-ink-800 text-sm">Kapoor Fashion House</h3>
                            <p class="text-xs text-ink-400">Clothing • Sadar Bazar</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-saffron-500 font-semibold">★ 4.3</span>
                                <span class="text-ink-200 text-xs">•</span>
                                <span class="text-xs text-ink-400">350m</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-indigo-50 rounded-xl p-2.5 mb-3 flex items-center gap-2">
                        <span class="text-base">👗</span>
                        <p class="text-xs font-bold text-indigo-700">Naya Collection Aaya! — Navratri Special Sarees ₹899
                            se shuru
                        </p>
                    </div>
                    <button
                        class="w-full gradient-brand text-white rounded-xl py-2.5 text-sm font-display font-bold btn-press"
                        onclick="showScreen('spin')">🎡 Spin & Win Offers</button>
                </div>

            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        (function () {
            let cur = 0, total = 4, timer, startX = 0;
            const track = document.getElementById('bannerTrack');
            const dots = document.querySelectorAll('#bannerDots .banner-dot');

            function goTo(i) {
                cur = (i + total) % total;
                track.style.transform = `translateX(-${cur * 100}%)`;
                dots.forEach((d, idx) => {
                    d.classList.toggle('bg-saffron-500', idx === cur);
                    d.classList.toggle('w-4', idx === cur);
                    d.classList.toggle('bg-ink-200', idx !== cur);
                    d.classList.toggle('w-1.5', idx !== cur);
                });
            }

            function startAuto() { timer = setInterval(() => goTo(cur + 1), 3000); }
            function stopAuto() { clearInterval(timer); }

            const wrap = document.getElementById('bannerWrap');
            wrap.addEventListener('touchstart', e => { startX = e.touches[0].clientX; stopAuto(); }, { passive: true });
            wrap.addEventListener('touchend', e => {
                const diff = startX - e.changedTouches[0].clientX;
                if (Math.abs(diff) > 40) goTo(cur + (diff > 0 ? 1 : -1));
                startAuto();
            }, { passive: true });

            dots.forEach((d, i) => d.addEventListener('click', () => { goTo(i); stopAuto(); startAuto(); }));

            startAuto();
        })();
        function openShop(id) {
            showScreen('account', );
        }

        function filterCat(btn, cat) {
            const container = btn.closest('.flex') || btn.parentElement;
            container.querySelectorAll('.cat-chip').forEach(c => {
                c.classList.remove('cat-active');
                c.classList.add('bg-white', 'text-ink-500', 'border-ink-200');
                c.classList.remove('border-saffron-200');
            });
            btn.classList.add('cat-active');
            btn.classList.remove('bg-white', 'text-ink-500');
            const items = document.querySelectorAll('#shopList .shop-card, .flex.flex-col.gap-3 [data-cat]');
            items.forEach(c => {
                c.style.display = (cat === 'all' || c.dataset.cat === cat) ? '' : 'none';
            });
        }
        let flashSecs = 6443;

        function updateFlash() {
            const h = Math.floor(flashSecs / 3600),
                m = Math.floor((flashSecs % 3600) / 60),
                s = flashSecs % 60;
            const el = document.getElementById('flashTimer');
            if (el) el.textContent = `${h}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
            if (flashSecs > 0) flashSecs--;
        }
        setInterval(updateFlash, 1000);
    </script>
@endpush
