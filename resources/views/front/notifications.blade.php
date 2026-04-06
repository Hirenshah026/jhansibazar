@extends('front_layout.main')

@section('content')
<div id="screen-notifications" class="screen active fade-up pb-24">
  <div class="flex items-center gap-3 px-4 py-3 bg-white border-b border-ink-100 sticky top-0 z-10">
    <button onclick="goBack()" class="w-9 h-9 rounded-xl bg-ink-50 flex items-center justify-center"><svg class="w-4 h-4 text-ink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg></button>
    <h2 class="font-display font-bold text-ink-800 flex-1">Notifications</h2>
    <span class="text-xs text-saffron-500 font-semibold">Sab padha</span>
  </div>
  <div class="divide-y divide-ink-50">
    <div class="flex items-start gap-3 px-4 py-4 bg-saffron-50/50">
      <div class="w-10 h-10 gradient-brand rounded-xl flex items-center justify-center text-lg flex-shrink-0">🎡</div>
      <div class="flex-1"><p class="text-sm font-semibold text-ink-800">Raj Shoe Store mein spin available hai!</p><p class="text-xs text-ink-400 mt-0.5">20% off jeeto — abhi spin karo</p><p class="text-xs text-saffron-500 mt-1 font-medium">2 min pehle</p></div>
    </div>
    <div class="flex items-start gap-3 px-4 py-4 bg-saffron-50/50">
      <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center text-lg flex-shrink-0">⚡</div>
      <div class="flex-1"><p class="text-sm font-semibold text-ink-800">Flash Deal — Sharma Sweets!</p><p class="text-xs text-ink-400 mt-0.5">Free samosa on ₹200. Sirf 2 ghante!</p><p class="text-xs text-saffron-500 mt-1 font-medium">15 min pehle</p></div>
    </div>
    <div class="flex items-start gap-3 px-4 py-4">
      <div class="w-10 h-10 bg-gold-100 rounded-xl flex items-center justify-center text-lg flex-shrink-0">🪙</div>
      <div class="flex-1"><p class="text-sm font-semibold text-ink-800">+15 coins mile!</p><p class="text-xs text-ink-400 mt-0.5">Raj Shoe Store review ke liye</p><p class="text-xs text-ink-400 mt-1">1 ghanta pehle</p></div>
    </div>
    <div class="flex items-start gap-3 px-4 py-4">
      <div class="w-10 h-10 bg-pink-100 rounded-xl flex items-center justify-center text-lg flex-shrink-0">✂️</div>
      <div class="flex-1"><p class="text-sm font-semibold text-ink-800">Glamour Salon — Navratri Package</p><p class="text-xs text-ink-400 mt-0.5">Haircut+Facial+Eyebrows sirf ₹499. Book karo!</p><p class="text-xs text-ink-400 mt-1">3 ghante pehle</p></div>
    </div>
    <div class="flex items-start gap-3 px-4 py-4">
      <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center text-lg flex-shrink-0">🥛</div>
      <div class="flex-1"><p class="text-sm font-semibold text-ink-800">Raju Dairy — Extra milk available</p><p class="text-xs text-ink-400 mt-0.5">Full cream ₹60/litre. Order on WhatsApp</p><p class="text-xs text-ink-400 mt-1">Aaj subah</p></div>
    </div>
    <div class="flex items-start gap-3 px-4 py-4">
      <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center text-lg flex-shrink-0">🏥</div>
      <div class="flex-1"><p class="text-sm font-semibold text-ink-800">Health Card se ₹45 bachaye!</p><p class="text-xs text-ink-400 mt-0.5">Aaj City Hospital pharmacy mein</p><p class="text-xs text-ink-400 mt-1">Kal</p></div>
    </div>
  </div>
</div>
@endsection