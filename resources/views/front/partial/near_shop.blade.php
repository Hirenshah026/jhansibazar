<div class="px-4">
    <div class="flex items-center justify-between mb-3">
        <p class="text-xs font-semibold text-ink-400 uppercase tracking-wider">Aas-paas ki Dukaanein</p>
        <div class="flex gap-1 overflow-x-auto pb-1 no-scrollbar">
            <button class="cat-chip cat-active text-xs px-2.5 py-1 rounded-full border border-saffron-200 font-medium"
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

    <div class="flex flex-col gap-3" id="shopList_near">
        @foreach ($shops as $sp)
            @php
                // Open/Closed Logic
                $isOpen = false;
                if ($sp->open_time && $sp->close_time) {
                    $now = now();
                    $start = \Carbon\Carbon::parse($sp->open_time);
                    $end = \Carbon\Carbon::parse($sp->close_time);
                    $isOpen = $now->between($start, $end);
                }

                // Categories decode (agar JSON hai)
                $cats = is_array($sp->categories) ? $sp->categories : json_decode($sp->categories, true);
                $catString = $cats ? implode(' & ', array_slice($cats, 0, 2)) : 'Shop';

                // Offers decode
                $offers = is_array($sp->offers) ? $sp->offers : json_decode($sp->offers, true);
                $slug = str_replace(' ', '-', strtolower($sp->shop_name));
            @endphp

            <div class="shop-card bg-white border border-ink-100 rounded-2xl p-4 card-hover"
                data-cat="{{ $cats[0] ?? 'all' }}" onclick="showScreen('shopprofile','{{$slug}}')">

                <div class="flex gap-3 mb-3">
                    <div
                        class="w-14 h-14 bg-amber-50 rounded-xl flex items-center justify-center overflow-hidden flex-shrink-0 border border-ink-50">
                        @if ($sp->shop_photo)
                            <img src="{{ asset('shop_photo/' . $sp->shop_photo) }}" class="w-full h-full object-cover"
                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($sp->shop_name) }}&background=random'">
                        @else
                            <span class="text-3xl">🏪</span>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="font-display font-bold text-ink-800 text-sm truncate">{{ ucwords($sp->shop_name) }}
                                </h3>
                                <p class="text-xs text-ink-400 truncate">{{ $catString }} • {{ $sp->address }}</p>
                            </div>

                            @if ($isOpen)
                                <span
                                    class="bg-forest-100 text-forest-700 text-[10px] font-bold px-2 py-0.5 rounded-full flex-shrink-0">Open</span>
                            @else
                                <span
                                    class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-0.5 rounded-full flex-shrink-0">Closed</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs text-saffron-500 font-semibold">★ {{ $sp->rating ?? '4.5' }}</span>
                            <span class="text-ink-200 text-xs">•</span>
                            <span class="text-xs text-ink-400">{{ rand(100, 900) }}m</span> <span
                                class="text-ink-200 text-xs">•</span>
                            <span class="text-xs text-ink-400">{{ $sp->reviews_count ?? rand(50, 200) }} reviews</span>
                        </div>
                    </div>
                </div>

                @if ($offers)
                    <div class="flex gap-1.5 flex-wrap mb-3">
                        @foreach (array_filter($offers) as $offer)
                            <span
                                class="bg-saffron-50 text-saffron-700 border border-saffron-200 rounded-full px-2 py-0.5 text-[10px] font-medium">
                                {{ $offer }}
                            </span>
                        @endforeach
                        <span
                            class="bg-saffron-50 text-saffron-700 border border-saffron-200 rounded-full px-2 py-0.5 text-[10px] font-medium">
                            🎡 Spin available
                        </span>
                    </div>
                @endif

                <button
                    class="w-full gradient-brand text-white rounded-xl py-2.5 text-sm font-display font-bold btn-press shadow-sm"
                    onclick="event.stopPropagation(); showScreen('spin','{{str_replace(' ', '-', strtolower($sp->shop_name));}}')">
                    🎡 Spin & Win Offers
                </button>
            </div>
        @endforeach
    </div>
</div>
