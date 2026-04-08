@extends('front_layout.main')

@section('content')
<div id="screen-shopprofile" class="screen active fade-up pb-24">
  @php
    // Time & Status Logic
    $isOpen = false;
    if($shop->open_time && $shop->close_time){
        $now = now();
        $start = \Carbon\Carbon::parse($shop->open_time);
        $end = \Carbon\Carbon::parse($shop->close_time);
        $isOpen = $now->between($start, $end);
    }
    
    // Decoding JSON data
    $offers = is_array($shop->offers) ? $shop->offers : json_decode($shop->offers, true);
    $categories = is_array($shop->categories) ? $shop->categories : json_decode($shop->categories, true);
    $offDays = is_array($shop->off_days) ? $shop->off_days : json_decode($shop->off_days, true);
  @endphp

  <div class="gradient-brand relative">
    <div class="flex items-center gap-2 px-4 pt-3">
      <button onclick="window.history.back()" class="w-8 h-8 rounded-full glass flex items-center justify-center">
        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
      </button>
      <span class="flex-1 text-center text-white text-sm font-semibold">Shop Profile</span>
      <button class="w-8 h-8 rounded-full glass flex items-center justify-center" onclick="shareShop('{{ $shop->shop_name }}')">
        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
      </button>
    </div>

    <div class="px-4 pt-3 pb-12 text-white">
      <div class="w-16 h-16 bg-white rounded-2xl shadow-lg flex items-center justify-center overflow-hidden mb-3">
        @if($shop->shop_photo)
            <img src="{{ asset('shop_photo/' . $shop->shop_photo) }}" class="w-full h-full object-cover">
        @else
            <span class="text-3xl">🏪</span>
        @endif
      </div>
      <h2 class="font-display text-2xl font-bold">{{ ucwords($shop->shop_name) }}</h2>
      <p class="text-white/70 text-sm">
          {{ $categories ? implode(' & ', $categories) : 'Shop' }} • Est. {{ $shop->est_year ?? 'N/A' }}
      </p>
      <div class="flex gap-2 mt-2 flex-wrap">
        <span class="glass rounded-full px-2.5 py-0.5 text-xs">★ {{ $shop->rating ?? '4.5' }} ({{ $shop->reviews_count ?? '0' }})</span>
        <span class="glass rounded-full px-2.5 py-0.5 text-xs">📍 {{ $shop->address }}</span>
        @if($isOpen)
            <span class="glass rounded-full px-2.5 py-0.5 text-xs">🟢 Open till {{ date('g:i A', strtotime($shop->close_time)) }}</span>
        @else
            <span class="glass rounded-full px-2.5 py-0.5 text-xs bg-red-500/20">🔴 Closed</span>
        @endif
      </div>
    </div>
  </div>

  <div class="px-4 mt-6 overflow-y-auto">
    <div class="flex gap-2 mb-4">
      <button onclick="showScreen('spin')" class="flex-1 gradient-brand text-white font-display font-bold rounded-2xl py-3 text-sm shadow-md btn-press">🎡 Spin & Win</button>
      
      <a href="tel:{{ $shop->phone }}" class="w-12 bg-white border border-ink-200 rounded-2xl flex items-center justify-center shadow-sm hover:border-saffron-300 transition">
        <span class="text-lg">📞</span>
      </a>

      @if($shop->is_whatsapp)
      <a href="https://wa.me/91{{ $shop->phone }}" class="w-12 bg-green-500 rounded-2xl flex items-center justify-center shadow-sm">
        <span class="text-white text-lg">💬</span>
      </a>
      @endif
    </div>

    <div class="flex border-b border-ink-100 mb-4 sticky top-0 bg-gray-50 z-10">
      <button onclick="shopTab(this,'offers')" class="flex-1 py-2.5 text-xs font-semibold tab-active" id="tab-offers">Offers</button>
      <button onclick="shopTab(this,'items')" class="flex-1 py-2.5 text-xs font-semibold text-ink-400" id="tab-items">Items</button>
      <button onclick="shopTab(this,'reviews')" class="flex-1 py-2.5 text-xs font-semibold text-ink-400" id="tab-reviews">Reviews</button>
      <button onclick="shopTab(this,'info')" class="flex-1 py-2.5 text-xs font-semibold text-ink-400" id="tab-info">Info</button>
    </div>

    <div id="shopTab-offers" class="tab-content">
      <div class="grid grid-cols-3 gap-2 mb-4">
        @if($offers)
            @foreach(array_filter($offers) as $offer)
                <div class="bg-saffron-50 border border-saffron-200 rounded-xl p-3 text-center">
                  <p class="font-display font-bold text-saffron-700 text-sm leading-tight">{{ $offer }}</p>
                </div>
            @endforeach
        @endif
      </div>
      
      <div class="relative bg-gradient-to-br from-ink-100 to-ink-200 rounded-2xl overflow-hidden mb-4 cursor-pointer group" style="height:160px" onclick="playShopVideo('{{ $shop->video_url??'none' }}')">
        <div class="absolute inset-0 flex flex-col items-center justify-center gap-2">
          <div class="w-14 h-14 gradient-brand rounded-full flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6 text-white ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M5 3l14 9-14 9V3z"/></svg>
          </div>
          <p class="text-sm text-ink-600 font-semibold">Shop Video dekho (30 sec)</p>
        </div>
        <div class="absolute top-3 right-3 gradient-brand text-white text-xs font-bold px-2.5 py-1 rounded-full">+3 coins</div>
      </div>

      <div class="bg-white border border-ink-100 rounded-2xl p-4 mb-4">
        <p class="text-xs font-semibold text-ink-400 uppercase tracking-wide mb-3">Yahan Coins Kamao</p>
        <div class="space-y-2">
          <div class="flex items-center justify-between bg-forest-50 rounded-xl px-3 py-2.5"><div class="flex items-center gap-2"><span>⭐</span><span class="text-sm text-ink-700 font-medium">Review likho</span></div><span class="text-forest-600 font-bold text-sm">+15</span></div>
          <div class="flex items-center justify-between bg-forest-50 rounded-xl px-3 py-2.5"><div class="flex items-center gap-2"><span>📸</span><span class="text-sm text-ink-700 font-medium">Photo daalo</span></div><span class="text-forest-600 font-bold text-sm">+10</span></div>
          <div class="flex items-center justify-between bg-forest-50 rounded-xl px-3 py-2.5"><div class="flex items-center gap-2"><span>👍</span><span class="text-sm text-ink-700 font-medium">Rating do</span></div><span class="text-forest-600 font-bold text-sm">+5</span></div>
          <div class="flex items-center justify-between bg-forest-50 rounded-xl px-3 py-2.5"><div class="flex items-center gap-2"><span>📤</span><span class="text-sm text-ink-700 font-medium">Share karo</span></div><span class="text-forest-600 font-bold text-sm">+20</span></div>
        </div>
      </div>
    </div>

    <div id="shopTab-items" class="tab-content hidden">
      <div class="grid grid-cols-2 gap-3 mb-4">
          @php
              $itemPhotos = is_array($shop->item_photos) ? $shop->item_photos : json_decode($shop->item_photos, true);
          @endphp

          @if($itemPhotos && count($itemPhotos) > 0)
              @foreach($itemPhotos as $index => $photo)
                  @php
                      // Support both Cloudinary format {"url":"...","public_id":"..."} and old local string format
                      if (is_array($photo) && !empty($photo['url'])) {
                          $photoUrl = $photo['url'];
                      } elseif (is_string($photo) && !empty($photo)) {
                          $photoUrl = asset('shop_photo/' . $photo);
                      } else {
                          $photoUrl = null;
                      }
                  @endphp
                  <div class="bg-white border border-ink-100 rounded-2xl overflow-hidden card-hover">
                    <div class="h-28 bg-ink-50 overflow-hidden">
                        @if($photoUrl)
                            <img src="{{ $photoUrl }}" 
                                 class="w-full h-full object-cover"
                                 onerror="this.src='https://placehold.co/400x400?text=Item+Photo'">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-3xl">📦</div>
                        @endif
                    </div>
                    <div class="p-2.5 text-center">
                      <p class="text-[10px] font-bold text-ink-800 uppercase tracking-tight">Product #{{ $index + 1 }}</p>
                      <span class="bg-forest-100 text-forest-600 text-[9px] font-bold px-1.5 py-0.5 rounded-lg mt-1 inline-block">Available</span>
                    </div>
                  </div>
              @endforeach
          @else
              <div class="col-span-2 py-10 text-center">
                  <p class="text-xs text-ink-400">Bhai, abhi tak koi item photo upload nahi hui.</p>
              </div>
          @endif
      </div>
    </div>

    <div id="shopTab-reviews" class="tab-content hidden">
      <div class="bg-white border border-ink-100 rounded-2xl p-4 mb-4">
        <div class="flex items-center gap-4 mb-4">
          <div class="text-center">
              <p class="font-display font-bold text-4xl text-ink-800">{{ $shop->rating ?? '4.5' }}</p>
              <p class="text-xs text-ink-400">{{ $shop->reviews_count ?? '0' }} reviews</p>
          </div>
          <div class="flex-1">
            <div class="flex items-center gap-2 mb-1"><span class="text-xs w-4">5</span><div class="flex-1 bg-ink-100 rounded-full h-1.5"><div class="bg-gold-400 h-1.5 rounded-full" style="width:70%"></div></div></div>
            <div class="flex items-center gap-2 mb-1"><span class="text-xs w-4">4</span><div class="flex-1 bg-ink-100 rounded-full h-1.5"><div class="bg-gold-400 h-1.5 rounded-full" style="width:20%"></div></div></div>
            <div class="flex items-center gap-2"><span class="text-xs w-4">1-3</span><div class="flex-1 bg-ink-100 rounded-full h-1.5"><div class="bg-red-400 h-1.5 rounded-full" style="width:10%"></div></div></div>
          </div>
        </div>
        
        <div class="space-y-3">
             <p class="text-center text-xs text-ink-400 py-4 italic">No recent reviews yet. Be the first!</p>
        </div>
      </div>
    </div>

    <div id="shopTab-info" class="tab-content hidden">
      <div class="bg-white border border-ink-100 rounded-2xl p-4 mb-4">
        <div class="space-y-3 divide-y divide-ink-50">
          <div class="flex justify-between text-sm pt-0"><span class="text-ink-400">Pata</span><span class="font-medium text-ink-700 text-right text-xs">{{ $shop->address }}</span></div>
          <div class="flex justify-between text-sm pt-3"><span class="text-ink-400">Samay</span><span class="font-medium text-ink-700">{{ date('g:i A', strtotime($shop->open_time)) }} – {{ date('g:i A', strtotime($shop->close_time)) }}</span></div>
          <div class="flex justify-between text-sm pt-3"><span class="text-ink-400">Chhuti</span><span class="font-medium text-ink-700">{{ $offDays ? implode(', ', $offDays) : 'No Holiday' }}</span></div>
          <div class="flex justify-between text-sm pt-3"><span class="text-ink-400">Phone</span><span class="font-medium text-ink-700">+91 {{ $shop->phone }}</span></div>
          <div class="flex justify-between text-sm pt-3"><span class="text-ink-400">Payment</span><span class="font-medium text-ink-700">UPI, Cash</span></div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection