@extends('front_layout.main')

@section('content')
    <div id="screen-rozana" class="screen active fade-up pb-24">
        <div class="gradient-dark px-4 pt-4 pb-8 text-white">
            <button onclick="goBack()" class="flex items-center gap-2 mb-3 text-white/70 text-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M15 18l-6-6 6-6" />
                </svg> Back
            </button>
            <h2 class="font-display font-bold text-2xl">Rozana Wala</h2>
            <p class="text-white/60 text-sm mt-1">Milkman, Taxi, Thela — sab ek jagah</p>
            <div class="flex gap-2 mt-3">
                <button onclick="emergencyRequest()"
                    class="glass rounded-full px-3 py-1.5 text-xs font-semibold text-red-300 border border-red-400/30">🆘
                    Emergency Request</button>
                <button class="glass rounded-full px-3 py-1.5 text-xs font-semibold">📍 Mere aas-paas</button>
            </div>
        </div>
        <div class="px-4 -mt-4">
            <!-- Category filter -->
            <div class="flex gap-2 overflow-x-auto pb-3 mb-3">
                <button
                    class="flex-shrink-0 cat-chip cat-active text-xs px-3 py-1.5 rounded-full border border-saffron-200 font-medium"
                    onclick="filterCat(this,'all')">Sab</button>
                <button
                    class="flex-shrink-0 cat-chip text-xs px-3 py-1.5 rounded-full border border-ink-200 text-ink-500 font-medium bg-white"
                    onclick="filterCat(this,'milk')">🥛 Milkman</button>
                <button
                    class="flex-shrink-0 cat-chip text-xs px-3 py-1.5 rounded-full border border-ink-200 text-ink-500 font-medium bg-white"
                    onclick="filterCat(this,'taxi')">🚖 Taxi</button>
                <button
                    class="flex-shrink-0 cat-chip text-xs px-3 py-1.5 rounded-full border border-ink-200 text-ink-500 font-medium bg-white"
                    onclick="filterCat(this,'thela')">🛒 Thela</button>
                <button
                    class="flex-shrink-0 cat-chip text-xs px-3 py-1.5 rounded-full border border-ink-200 text-ink-500 font-medium bg-white"
                    onclick="filterCat(this,'loader')">🚚 Loader</button>
            </div>

            <!-- Workers -->
            <div class="flex flex-col gap-3">
                <div class="bg-white border border-ink-100 rounded-2xl p-4 card-hover" data-cat="milk">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-2xl">🥛</div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h3 class="font-display font-bold text-ink-800 text-sm">Raju Dairy</h3>
                                <span
                                    class="bg-forest-100 text-forest-700 text-xs font-bold px-2 py-0.5 rounded-full">Available</span>
                            </div>
                            <p class="text-xs text-ink-400">Ward 5, Sipri Bazar</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-xs text-saffron-500 font-semibold">★ 4.8</span>
                                <span class="text-xs text-ink-300">•</span>
                                <span class="text-xs text-ink-400">47 customers</span>
                                <span class="text-xs text-ink-300">•</span>
                                <span class="text-xs text-forest-600 font-medium">6AM delivery</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-blue-50 rounded-xl p-2.5 mb-3">
                        <p class="text-xs font-bold text-blue-700 mb-1">Aaj ka update:</p>
                        <p class="text-xs text-blue-600">"Extra full cream milk available — ₹60/litre. WhatsApp pe order
                            karo!"</p>
                    </div>
                    <div class="flex gap-2">
                        <button
                            class="flex-1 gradient-brand text-white rounded-xl py-2 text-xs font-bold btn-press">Subscribe
                            ₹49/month</button>
                        <button class="flex-1 bg-forest-500 text-white rounded-xl py-2 text-xs font-bold btn-press"
                            onclick="emergencyRequest()">Abhi order</button>
                    </div>
                </div>

                <div class="bg-white border border-ink-100 rounded-2xl p-4 card-hover" data-cat="taxi">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center text-2xl">🚖</div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h3 class="font-display font-bold text-ink-800 text-sm">Mohan Taxi Service</h3>
                                <span
                                    class="bg-forest-100 text-forest-700 text-xs font-bold px-2 py-0.5 rounded-full">Online</span>
                            </div>
                            <p class="text-xs text-ink-400">Civil Lines, Jhansi</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-xs text-saffron-500 font-semibold">★ 4.6</span>
                                <span class="text-xs text-ink-300">•</span>
                                <span class="text-xs text-ink-400">Local + Outstation</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-yellow-50 rounded-xl p-2.5 mb-3">
                        <p class="text-xs font-bold text-amber-700">Aaj available: 8AM – 8PM | Outstation: Gwalior, Agra,
                            Mathura</p>
                    </div>
                    <div class="flex gap-2">
                        <button class="flex-1 gradient-brand text-white rounded-xl py-2 text-xs font-bold btn-press">📞 Call
                            Now</button>
                        <button class="flex-1 bg-green-500 text-white rounded-xl py-2 text-xs font-bold btn-press">💬
                            WhatsApp</button>
                    </div>
                </div>

                <div class="bg-white border border-ink-100 rounded-2xl p-4 card-hover" data-cat="thela">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-2xl">🛒</div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h3 class="font-display font-bold text-ink-800 text-sm">Suresh Sabzi Thela</h3>
                                <span class="badge-new">FRESH</span>
                            </div>
                            <p class="text-xs text-ink-400">Sipri Bazar • Har gali mein</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-xs text-saffron-500 font-semibold">★ 4.5</span>
                                <span class="text-xs text-ink-300">•</span>
                                <span class="text-xs text-ink-400">Morning 7AM–11AM</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-green-50 rounded-xl p-2.5 mb-3">
                        <p class="text-xs font-bold text-forest-700 mb-1">Aaj ke rates:</p>
                        <div class="grid grid-cols-3 gap-1 text-xs text-forest-600">
                            <span>🍅 ₹30/kg</span><span>🧅 ₹25/kg</span><span>🥬 ₹20/gudi</span>
                        </div>
                    </div>
                    <button class="w-full gradient-brand text-white rounded-xl py-2.5 text-xs font-bold btn-press">Subscribe
                        — Daily Ghar Delivery</button>
                </div>

                <div class="bg-white border border-ink-100 rounded-2xl p-4 card-hover" data-cat="loader">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-2xl">🚚</div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h3 class="font-display font-bold text-ink-800 text-sm">Ram Loader & Tempo</h3>
                                <span class="bg-amber-100 text-amber-700 text-xs font-bold px-2 py-0.5 rounded-full">Call
                                    first</span>
                            </div>
                            <p class="text-xs text-ink-400">Shifting, saman dhulai</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-xs text-saffron-500 font-semibold">★ 4.3</span>
                                <span class="text-xs text-ink-400">• ₹500/day from</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
