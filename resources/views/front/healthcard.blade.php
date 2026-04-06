@extends('front_layout.main')

@section('content')
    <div id="screen-healthcard" class="screen active fade-up pb-24">
        <div class="gradient-dark px-4 pt-4 pb-8 text-white relative overflow-hidden">
            <div class="absolute inset-0 opacity-5">
                <div class="absolute top-4 right-4 w-32 h-32 border-2 border-gold-400 rounded-full"></div>
                <div class="absolute top-8 right-8 w-20 h-20 border-2 border-gold-400 rounded-full"></div>
            </div>
            <button onclick="goBack()" class="flex items-center gap-2 mb-4 text-white/60 text-sm relative z-10">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M15 18l-6-6 6-6" />
                </svg> Back
            </button>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-14 h-14 gradient-gold rounded-2xl flex items-center justify-center text-3xl">🏥</div>
                    <div>
                        <p class="text-gold-300 text-xs font-semibold uppercase tracking-wide">City Hospital</p>
                        <h2 class="font-display text-xl font-bold">Digital Health Card</h2>
                    </div>
                </div>
                <!-- Card Preview -->
                <div class="gradient-gold rounded-2xl p-4 relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 w-24 h-24 bg-white opacity-10 rounded-full -translate-y-4 translate-x-4">
                    </div>
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-ink-800 text-xs font-semibold opacity-70">JHANSI BAZAAR</p>
                            <p class="font-display font-bold text-ink-900 text-lg">Health Card</p>
                            <p class="text-ink-700 text-xs mt-1">Rahul Sharma</p>
                        </div>
                        <div class="text-right">
                            <p class="font-display font-bold text-ink-900 text-3xl">20%</p>
                            <p class="text-ink-700 text-xs">hamesha OFF</p>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center justify-between">
                        <p class="text-ink-700 text-xs">Valid: Jan 2025 – Jan 2026</p>
                        <p class="font-display font-bold text-ink-900">★ 4.9</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-4 mt-4">
            <!-- Price -->
            <div class="bg-white border border-ink-100 rounded-2xl p-4 mb-3 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="font-display font-bold text-ink-800 text-xl">₹300 / year</p>
                        <p class="text-xs text-ink-400">Ek baar liya — saal bhar ka fayda</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-forest-600 font-bold">+30 coins bhi milenge</p>
                        <p class="text-xs text-ink-400">card activate karne pe</p>
                    </div>
                </div>
                <button
                    class="w-full gradient-gold text-ink-900 font-display font-bold text-base rounded-2xl py-3.5 btn-press shadow">Card
                    Kharido — ₹300 🏥</button>
            </div>
            <!-- Benefits -->
            <div class="bg-white border border-ink-100 rounded-2xl p-4 mb-3">
                <p class="text-xs font-semibold text-ink-400 uppercase tracking-wide mb-3">Card ke Fayde</p>
                <div class="space-y-2.5">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center text-base flex-shrink-0">
                            💊</div>
                        <div>
                            <p class="text-sm font-semibold text-ink-700">Hamesha 20% OFF medicines</p>
                            <p class="text-xs text-ink-400">City Hospital ki pharmacy mein</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center text-base flex-shrink-0">
                            🏥</div>
                        <div>
                            <p class="text-sm font-semibold text-ink-700">Partner pharmacies mein bhi valid</p>
                            <p class="text-xs text-ink-400">Jhansi ki 15+ pharmacies mein</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div
                            class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center text-base flex-shrink-0">
                            👨‍👩‍👧‍👦</div>
                        <div>
                            <p class="text-sm font-semibold text-ink-700">Family Plan — ₹500 mein 4 members</p>
                            <p class="text-xs text-ink-400">Pura parivaar ek card pe</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-gold-50 rounded-lg flex items-center justify-center text-base flex-shrink-0">
                            🪙</div>
                        <div>
                            <p class="text-sm font-semibold text-ink-700">Har medicine pe coins bhi milenge</p>
                            <p class="text-xs text-ink-400">₹100 ke saman pe 5 coins</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Partner Hospitals -->
            <div class="bg-white border border-ink-100 rounded-2xl p-4">
                <p class="text-xs font-semibold text-ink-400 uppercase tracking-wide mb-3">Partner Hospitals & Pharmacies
                </p>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="bg-ink-50 rounded-xl p-2.5 text-center text-ink-600 font-medium">🏥 City Hospital</div>
                    <div class="bg-ink-50 rounded-xl p-2.5 text-center text-ink-600 font-medium">💊 Jain Medical</div>
                    <div class="bg-ink-50 rounded-xl p-2.5 text-center text-ink-600 font-medium">🏥 Bundelkhand Clinic</div>
                    <div class="bg-ink-50 rounded-xl p-2.5 text-center text-ink-600 font-medium">💊 Sharma Pharma</div>
                </div>
            </div>
        </div>
    </div>
@endsection
