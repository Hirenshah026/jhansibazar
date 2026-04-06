@extends('front_layout.main')

@section('content')
    <div id="screen-wallet" class="screen active fade-up pb-24">
        <div class="gradient-dark px-5 pt-5 pb-10 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-40 h-40 bg-gold-500 opacity-5 rounded-full -translate-y-10 translate-x-10">
            </div>
            <p class="text-xs opacity-40 uppercase tracking-widest mb-4 font-semibold">Mera Wallet</p>
            <div class="flex items-center gap-4 mb-4">
                <div
                    class="w-16 h-16 gradient-gold rounded-2xl flex items-center justify-center text-3xl coin-pulse shadow-lg">
                    🪙</div>
                <div>
                    <p class="font-display font-bold text-5xl leading-none">340</p>
                    <p class="text-white/50 text-xs mt-1">total coins</p>
                </div>
            </div>
            <div class="mb-2">
                <div class="flex justify-between text-xs opacity-60 mb-1.5">
                    <span>₹50 cashback tak</span><span>340 / 500 coins</span>
                </div>
                <div class="bg-white/10 rounded-full h-3 overflow-hidden">
                    <div class="gradient-gold h-full rounded-full progress-fill" style="width:68%"></div>
                </div>
                <p class="text-xs opacity-40 mt-1.5">160 aur coins chahiye</p>
            </div>
            <button class="glass rounded-xl px-4 py-2 text-xs font-semibold mt-2">Redeem → ₹50 cashback</button>
        </div>

        <div class="px-4 mt-4 pb-4">
            <!-- Stats -->
            <div class="grid grid-cols-3 gap-2 mb-4">
                <div class="bg-white rounded-2xl p-3 shadow-sm border border-ink-100 text-center">
                    <p class="font-display font-bold text-forest-500 text-xl">+480</p>
                    <p class="text-xs text-ink-400 mt-0.5">Kamaye</p>
                </div>
                <div class="bg-white rounded-2xl p-3 shadow-sm border border-ink-100 text-center">
                    <p class="font-display font-bold text-red-400 text-xl">-140</p>
                    <p class="text-xs text-ink-400 mt-0.5">Use kiye</p>
                </div>
                <div class="bg-white rounded-2xl p-3 shadow-sm border border-ink-100 text-center">
                    <p class="font-display font-bold text-saffron-500 text-xl">12</p>
                    <p class="text-xs text-ink-400 mt-0.5">Shops</p>
                </div>
            </div>

            <!-- Quick Earn -->
            <div class="bg-white border border-ink-100 rounded-2xl p-4 mb-4">
                <p class="text-xs font-semibold text-ink-400 uppercase tracking-wide mb-3">Jaldi Coins Kamao</p>
                <div class="grid grid-cols-2 gap-2">
                    <button onclick="showScreen('spin')"
                        class="flex items-center gap-2 bg-saffron-50 rounded-xl p-2.5 hover:bg-saffron-100 transition"><span>🎡</span>
                        <div class="text-left">
                            <p class="text-xs font-semibold text-ink-700">Spin karo</p>
                            <p class="text-xs text-saffron-600 font-bold">+10 coins</p>
                        </div>
                    </button>
                    <button
                        class="flex items-center gap-2 bg-blue-50 rounded-xl p-2.5 hover:bg-blue-100 transition"><span>👥</span>
                        <div class="text-left">
                            <p class="text-xs font-semibold text-ink-700">Friend refer karo</p>
                            <p class="text-xs text-blue-600 font-bold">+25 coins</p>
                        </div>
                    </button>
                    <button
                        class="flex items-center gap-2 bg-purple-50 rounded-xl p-2.5 hover:bg-purple-100 transition"><span>📹</span>
                        <div class="text-left">
                            <p class="text-xs font-semibold text-ink-700">Video dekho</p>
                            <p class="text-xs text-purple-600 font-bold">+3 coins</p>
                        </div>
                    </button>
                    <button
                        class="flex items-center gap-2 bg-gold-50 rounded-xl p-2.5 hover:bg-gold-100 transition"><span>⭐</span>
                        <div class="text-left">
                            <p class="text-xs font-semibold text-ink-700">Review likho</p>
                            <p class="text-xs text-gold-600 font-bold">+15 coins</p>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Transactions -->
            <div class="bg-white border border-ink-100 rounded-2xl p-4">
                <p class="text-xs font-semibold text-ink-400 uppercase tracking-wide mb-3">Transactions</p>
                <div class="divide-y divide-ink-50">
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-green-100 rounded-xl flex items-center justify-center">⭐</div>
                            <div>
                                <p class="text-sm font-semibold text-ink-800">Raj Shoes review</p>
                                <p class="text-xs text-ink-300">Aaj, 2:30pm</p>
                            </div>
                        </div><span class="text-forest-600 font-bold text-sm">+15</span>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-saffron-100 rounded-xl flex items-center justify-center">🎡</div>
                            <div>
                                <p class="text-sm font-semibold text-ink-800">Spin wheel win</p>
                                <p class="text-xs text-ink-300">Aaj, 2:28pm</p>
                            </div>
                        </div><span class="text-forest-600 font-bold text-sm">+10</span>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center">📹</div>
                            <div>
                                <p class="text-sm font-semibold text-ink-800">Sharma Sweets video</p>
                                <p class="text-xs text-ink-300">Kal, 5:10pm</p>
                            </div>
                        </div><span class="text-forest-600 font-bold text-sm">+3</span>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-red-100 rounded-xl flex items-center justify-center">🛍️</div>
                            <div>
                                <p class="text-sm font-semibold text-ink-800">Kapoor Fashion redeem</p>
                                <p class="text-xs text-ink-300">2 din pehle</p>
                            </div>
                        </div><span class="text-red-400 font-bold text-sm">-50</span>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-purple-100 rounded-xl flex items-center justify-center">👥</div>
                            <div>
                                <p class="text-sm font-semibold text-ink-800">Friend referral</p>
                                <p class="text-xs text-ink-300">3 din pehle</p>
                            </div>
                        </div><span class="text-forest-600 font-bold text-sm">+25</span>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-pink-100 rounded-xl flex items-center justify-center">💊</div>
                            <div>
                                <p class="text-sm font-semibold text-ink-800">Health card purchase code</p>
                                <p class="text-xs text-ink-300">1 hafte pehle</p>
                            </div>
                        </div><span class="text-forest-600 font-bold text-sm">+30</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
