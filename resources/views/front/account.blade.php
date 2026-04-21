@extends('front_layout.main')

@section('content')
    @push('css_or_link')
        <style>
            #collapsible-content {
                display: none;
            }

            #delete-modal {
                display: none;
                position: fixed;
                inset: 0;
                z-index: 9999;
                background: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(4px);
                align-items: flex-end;
                justify-content: center;
            }

            #delete-modal.show {
                display: flex;
            }

            #delete-modal-box {
                background: white;
                border-radius: 24px 24px 0 0;
                padding: 24px 20px 36px;
                width: 100%;
                max-width: 480px;
                animation: slideUp 0.25s ease;
            }

            @keyframes slideUp {
                from {
                    transform: translateY(100%);
                    opacity: 0;
                }

                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
        </style>
    @endpush

    @php
        $shopId = Session::get('shopuser')->id ?? null;
        $items = $shopId ? DB::table('items')->where('shop_id', $shopId)->get() : collect();
        $offers = json_decode($shop->offers ?? '[]', true) ?? [];
    @endphp

    <div id="screen-shopprofile" class="screen active fade-up pb-24">
        <div class="gradient-brand relative">

            <div class="flex items-center gap-2 px-4 pt-3">
                <button onclick="goBack()" class="w-8 h-8 rounded-full glass flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M15 18l-6-6 6-6" />
                    </svg>
                </button>

                <span class="flex-1 text-center text-white text-sm font-semibold capitalize">
                    {{ Session::get('shopuser')->shop_name ?? 'na' }}
                </span>

                <button onclick='openEditModal(@json($shop))'
                    class="w-8 h-8 rounded-full glass flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </button>
            </div>
            <div class="px-4 pt-3 pb-2 text-white">
                <div
                    class="w-16 h-16 bg-red rounded-2xl shadow-lg flex items-center justify-center text-3xl mb-3 capitalize hidden">
                    {{ Str::substr(Session::get('shopuser')->shop_name ?? 'na', 0, 1) }}
                </div>
                <h2 class="font-display text-2xl font-bold capitalize">{{ Session::get('shopuser')->shop_name ?? 'na' }}
                </h2>
                <p class="text-white/70 text-sm hidden">Footwear & Sports Shoes • Est. 1998</p>
                <div class="flex gap-2 mt-2 flex-wrap hidden">
                    <span class="glass rounded-full px-2.5 py-0.5 text-xs">★ 4.5 (128)</span>
                    <span class="glass rounded-full px-2.5 py-0.5 text-xs">📍
                        {{ Session::get('shopuser')->address ?? 'na' }}</span>
                    <span class="glass rounded-full px-2.5 py-0.5 text-xs">🟢 Open till
                        {{ date('h:i A', strtotime(Session::get('shopuser')->close_time ?? '0')) }}</span>
                </div>
                <div class="grid grid-cols-2 gap-3 p-3 bg-white1">
                    <div
                        class="p-3 rounded-xl border border-dotted border-slate-300 flex flex-col items-center justify-center text-center bg-transparent">
                        <span class="text-[10px] font-bold text-white-400 uppercase tracking-widest mb-1">Profile
                            Visits</span>
                        <h2 id="count_visits" class="text-base font-black text-white">
                            {{ ($stat->profile_visits ?? 0) == 0 ? '--' : $stat->profile_visits }}</h2>
                    </div>

                    <div
                        class="p-3 rounded-xl border border-dotted border-slate-300 flex flex-col items-center justify-center text-center bg-transparent">
                        <span class="text-[10px] font-bold text-white-400 uppercase tracking-widest mb-1">Regular
                            Customer</span>
                        <h2 id="count_regular" class="text-base font-black text-white">
                            {{ ($stat->regular_customer ?? 0) == 0 ? '--' : $stat->regular_customer }}</h2>
                    </div>

                    <div
                        class="p-3 rounded-xl border border-dotted border-slate-300 flex flex-col items-center justify-center text-center bg-transparent">
                        <span class="text-[10px] font-bold text-white-400 uppercase tracking-widest mb-1">Repeat
                            Customer</span>
                        <h2 id="count_repeat" class="text-base font-black text-white">
                            {{ ($stat->repeat_customer ?? 0) == 0 ? '--' : $stat->repeat_customer }}</h2>
                    </div>

                    <div
                        class="p-3 rounded-xl border border-dotted border-slate-300 flex flex-col items-center justify-center text-center bg-transparent">
                        <span class="text-[10px] font-bold text-white-400 uppercase tracking-widest mb-1">Offers
                            Display</span>
                        <h2 id="count_sales" class="text-base font-black text-white">
                            {{ ($stat->offer_display ?? 0) == 0 ? '--' : $stat->offer_display }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-4 mt-6 overflow-y-auto">

            <!-- Action Row -->
            <div class="grid grid-cols-2 gap-3 mb-5">

                <button onclick="Turbo.visit('{{ url('/categories') }}')"
                    class="flex items-center justify-center gap-2 bg-white border-2 border-dashed border-orange-200 text-orange-700 font-bold rounded-2xl py-4 shadow-sm active:scale-95 transition-all">
                    <span class="text-xl">📁</span>
                    <div class="text-left">
                        <p class="text-xs font-bold leading-none">Add Category</p>
                        <p class="text-[9px] font-normal text-gray-400 mt-1">Saman ki list</p>
                    </div>
                </button>
                <button onclick="Turbo.visit('{{ url('/item-register') }}')"
                    class="flex items-center justify-center gap-2 bg-white border-2 border-dashed border-green-200 text-green-700 font-bold rounded-2xl py-4 shadow-sm active:scale-95 transition-all">
                    <span class="text-xl">📦</span>
                    <div class="text-left">
                        <p class="text-xs font-bold leading-none">Add Item</p>
                        <p class="text-[9px] font-normal text-gray-400 mt-1">Naya maal jodein</p>
                    </div>
                </button>
                <button onclick="Turbo.visit('{{ url('/shop/offers') }}')"
                    class="flex items-center justify-center gap-2 bg-white border-2 border-dashed border-purple-200 text-purple-700 font-bold rounded-2xl py-4 shadow-sm active:scale-95 transition-all">
                    <span class="text-xl">🎁</span>
                    <div class="text-left">
                        <p class="text-xs font-bold leading-none">Add Offer</p>
                        <p class="text-[9px] font-normal text-gray-400 mt-1">Discount offer</p>
                    </div>
                </button>




                <button onclick="Turbo.visit('{{ url('/service-register') }}')"
                    class="flex items-center justify-center gap-2 bg-white border-2 border-dashed border-blue-200 text-blue-700 font-bold rounded-2xl py-4 shadow-sm active:scale-95 transition-all {{ (json_decode(Session::get('shopuser')->categories ?? '[]')[0] ?? '') == 'salon' ? 'rn' : 'hidden' }}">
                    <span class="text-xl">🛠️</span>
                    <div class="text-left">
                        <p class="text-xs font-bold leading-none">Add Service</p>
                        <p class="text-[9px] font-normal text-gray-400 mt-1">Service manage</p>
                    </div>
                </button>
            </div>

            <!-- QR Code -->
            <div
                style="margin: 10px; background: #fff; border: 1px solid #eee; border-radius: 16px; display: flex; align-items: center; padding: 12px; gap: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">

                <div
                    style="flex-shrink: 0; width: 110px; height: 110px; background: #f9fafb; border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 1px solid #f0f0f0;">
                    <img id="qrDisplay"
                        src="https://quickchart.io/qr?text={{ urlencode(url('/shopprofile-details/' . str_replace(' ', '-', Session::get('shopuser')->shop_name ?? 's'))) }}&size=400"
                        style="width: 100px; height: 100px; border-radius: 6px; display: block;">
                </div>

                <div style="flex: 1; min-width: 0;">
                    <div
                        style="font-size: 9px; font-weight: 800; color: #10b981; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 2px;">
                        Shop QR Code
                    </div>
                    <div id="shopNameTxt"
                        style="font-size: 16px; font-weight: 900; color: #1e293b; margin-bottom: 4px; line-height: 1.2; word-wrap: break-word;"
                        class="capitalize">
                        {{ Session::get('shopuser')->shop_name ?? 'Shop Name' }}
                    </div>
                    <p style="font-size: 11px; color: #64748b; margin-bottom: 12px; line-height: 1.3;">Scan to view our
                        profile & offers</p>

                    <button onclick="generateDownload()"
                        style="background: #000; color: #fff; border: none; padding: 7px 12px; font-size: 11px; border-radius: 8px; font-weight: bold; cursor: pointer; width: 100%; box-shadow: 0 4px 10px rgba(0,0,0,0.1); active:scale-95 transition:all 0.2s;">
                        Download QR
                    </button>
                </div>
            </div>
            <canvas id="myCanvas" style="display:none;"></canvas>

            <!-- Tabs -->
            <div class="flex border-b border-ink-100 mb-4">
                <button onclick="shopTab(this,'offers')" class="flex-1 py-2.5 text-xs font-semibold tab-active"
                    id="tab-offers">Offers</button>
                <button onclick="shopTab(this,'items')" class="flex-1 py-2.5 text-xs font-semibold text-ink-400"
                    id="tab-items">Items</button>
                <button onclick="shopTab(this,'service')"
                    class="flex-1 py-2.5 text-xs font-semibold text-ink-400 {{ (json_decode(Session::get('shopuser')->categories ?? '[]')[0] ?? '') == 'salon' ? 'rn' : 'hidden' }}"
                    id="tab-service">Services</button>
                <button onclick="shopTab(this,'reviews')" class="flex-1 py-2.5 text-xs font-semibold text-ink-400"
                    id="tab-reviews">Reviews</button>
                <button onclick="shopTab(this,'info')" class="flex-1 py-2.5 text-xs font-semibold text-ink-400"
                    id="tab-info">Info</button>
            </div>

            <!-- Tab: Offers -->
            <div id="shopTab-offers">
                <div class="grid grid-cols-3 gap-2 mb-4">
                    @forelse(array_filter($offers) as $offer)
                        <div class="bg-saffron-50 border border-saffron-200 rounded-xl p-3 text-center">
                            <p class="font-display font-bold text-saffron-700 text-xl">
                                {{ is_array($offer) ? $offer['text'] : $offer }}
                            </p>
                            <p class="text-xs text-saffron-600 mt-0.5 leading-tight hidden">off formal shoes</p>
                        </div>
                    @empty
                        <div class="col-span-3 text-center text-xs text-ink-400 py-4">No Offers</div>
                    @endforelse
                </div>

                <!-- Video -->
                <div class="relative bg-gradient-to-br from-ink-100 to-ink-200 rounded-2xl overflow-hidden mb-4 cursor-pointer group"
                    style="height:160px" onclick="showScreen('spin')">
                    <div class="absolute inset-0 flex flex-col items-center justify-center gap-2">
                        <div
                            class="w-14 h-14 gradient-brand rounded-full flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M5 3l14 9-14 9V3z" />
                            </svg>
                        </div>
                        <p class="text-sm text-ink-600 font-semibold">Shop Video dekho (30 sec)</p>
                    </div>
                    <div
                        class="absolute top-3 right-3 gradient-brand text-white text-xs font-bold px-2.5 py-1 rounded-full">
                        +3 coins</div>
                    <div class="absolute bottom-3 left-3 bg-black/60 text-white text-xs px-2 py-0.5 rounded-full">0:30
                    </div>
                </div>

                <!-- Earn coins -->
                <div class="bg-white border border-ink-100 rounded-2xl p-4 mb-4">
                    <p class="text-xs font-semibold text-ink-400 uppercase tracking-wide mb-3">Yahan Coins Kamao</p>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between bg-forest-50 rounded-xl px-3 py-2.5">
                            <div class="flex items-center gap-2"><span>⭐</span><span
                                    class="text-sm text-ink-700 font-medium">Review likho</span></div>
                            <span class="text-forest-600 font-bold text-sm">+15</span>
                        </div>
                        <div class="flex items-center justify-between bg-forest-50 rounded-xl px-3 py-2.5">
                            <div class="flex items-center gap-2"><span>📸</span><span
                                    class="text-sm text-ink-700 font-medium">Photo daalo</span></div>
                            <span class="text-forest-600 font-bold text-sm">+10</span>
                        </div>
                        <div class="flex items-center justify-between bg-forest-50 rounded-xl px-3 py-2.5">
                            <div class="flex items-center gap-2"><span>👍</span><span
                                    class="text-sm text-ink-700 font-medium">Rating do</span></div>
                            <span class="text-forest-600 font-bold text-sm">+5</span>
                        </div>
                        <div class="flex items-center justify-between bg-forest-50 rounded-xl px-3 py-2.5">
                            <div class="flex items-center gap-2"><span>📤</span><span
                                    class="text-sm text-ink-700 font-medium">Share karo</span></div>
                            <span class="text-forest-600 font-bold text-sm">+20</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Items -->
            <div id="shopTab-items" class="hidden">
                @if ($items->isEmpty())
                    <div class="flex flex-col items-center justify-center py-14 text-center">
                        <div class="text-5xl mb-3">📦</div>
                        <p class="text-sm font-bold text-ink-700">Koi item nahi mila</p>
                        <p class="text-xs text-ink-400 mt-1">Abhi pehla item add karein</p>
                        <button onclick="Turbo.visit('{{ url('/item-register') }}')"
                            class="mt-4 gradient-brand text-white text-xs font-bold px-5 py-2.5 rounded-full shadow-md active:scale-95 transition-all">
                            + Item Jodein
                        </button>
                    </div>
                @else
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        @foreach ($items as $item)
                            @php
                                // ── Photo: handle both Cloudinary & old local format ──
                                $photos = json_decode($item->photos ?? '[]', true) ?? [];
                                $firstPhoto = null;

                                if (!empty($photos)) {
                                    $first = $photos[0];
                                    if (is_array($first) && !empty($first['url'])) {
                                        // New Cloudinary format: {"url":"...","public_id":"..."}
                                        $firstPhoto = $first['url'];
                                    } elseif (is_string($first) && !empty($first)) {
                                        // Old local format: "filename.webp"
                                        $firstPhoto = asset('items/' . ltrim($first, '/'));
                                    }
                                }

                                // ── Pricing ──
                                $hasDiscount = $item->is_discount_on && $item->discount_price > 0;
                                $displayPrice = $hasDiscount ? $item->discount_price : $item->mrp_price;
                                $discountPercent =
                                    $hasDiscount && $item->mrp_price > 0
                                        ? round((($item->mrp_price - $item->discount_price) / $item->mrp_price) * 100)
                                        : 0;

                                // ── Stock ──
                                $stockMap = [
                                    'available' => ['label' => 'In Stock', 'class' => 'bg-forest-100 text-forest-600'],
                                    'out_of_stock' => ['label' => 'Out of Stock', 'class' => 'bg-red-100 text-red-600'],
                                    'limited' => ['label' => 'Limited', 'class' => 'bg-amber-100 text-amber-600'],
                                ];
                                $stock = $stockMap[$item->stock_status] ?? [
                                    'label' => ucfirst($item->stock_status),
                                    'class' => 'bg-ink-100 text-ink-500',
                                ];

                                // ── Card gradient & emoji fallback ──
                                $gradients = [
                                    'from-orange-100 to-amber-100',
                                    'from-blue-100 to-indigo-100',
                                    'from-pink-100 to-rose-100',
                                    'from-green-100 to-teal-100',
                                    'from-purple-100 to-violet-100',
                                    'from-yellow-100 to-lime-100',
                                ];
                                $gradient = $gradients[$item->id % count($gradients)];
                                $emojis = ['📦', '🛍️', '🧴', '🧺', '🎁', '🪄', '🧲', '🔖'];
                                $emoji = $emojis[$item->id % count($emojis)];
                            @endphp

                            <div class="bg-white border border-ink-100 rounded-2xl overflow-hidden card-hover relative">
                                <button
                                    onclick="confirmDelete({{ $item->id }}, '{{ addslashes($item->item_name) }}')"
                                    class="absolute top-1.5 right-1.5 z-10 w-6 h-6 bg-white/90 rounded-full flex items-center justify-center shadow border border-red-100 active:scale-90 transition-all">
                                    <svg class="w-3 h-3 text-red-500" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                                <div
                                    class="h-24 bg-gradient-to-br {{ $gradient }} flex items-center justify-center relative overflow-hidden">
                                    @if ($firstPhoto)
                                        <img src="{{ $firstPhoto }}" alt="{{ $item->item_name }}"
                                            class="w-full h-full object-cover" loading="lazy"
                                            onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                        <span class="text-4xl" style="display:none">{{ $emoji }}</span>
                                    @else
                                        <span class="text-4xl">{{ $emoji }}</span>
                                    @endif

                                    @if ($item->is_spin_wheel)
                                        <div
                                            class="absolute top-1.5 left-1.5 gradient-brand text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full leading-none shadow">
                                            🎡 Spin
                                        </div>
                                    @endif

                                    @if ($hasDiscount && $discountPercent > 0)
                                        <div
                                            class="absolute bottom-1.5 right-1.5 bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full leading-none shadow">
                                            -{{ $discountPercent }}%
                                        </div>
                                    @endif
                                </div>

                                <div class="p-2.5">
                                    <p class="text-xs font-bold text-ink-800 leading-tight truncate">
                                        {{ $item->item_name }}</p>
                                    <p class="text-[10px] text-ink-400 truncate mt-0.5">{{ $item->category ?? '—' }}</p>
                                    <div class="flex items-center justify-between mt-1.5 gap-1 flex-wrap">
                                        <div class="flex items-baseline gap-1">
                                            <p class="font-display font-bold text-saffron-600 text-sm leading-none">
                                                ₹{{ number_format($displayPrice, 0) }}
                                            </p>
                                            @if ($hasDiscount)
                                                <p class="text-[9px] text-ink-300 line-through leading-none">
                                                    ₹{{ number_format($item->mrp_price, 0) }}
                                                </p>
                                            @endif
                                        </div>
                                        <span
                                            class="{{ $stock['class'] }} text-[9px] font-bold px-1.5 py-0.5 rounded-lg whitespace-nowrap flex-shrink-0">
                                            {{ $stock['label'] }}
                                        </span>
                                    </div>
                                    @if ($item->special_offer_text)
                                        <p class="text-[9px] text-saffron-600 font-semibold mt-1.5 truncate">
                                            🏷️ {{ $item->special_offer_text }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Tab: Services -->
            <div id="shopTab-service" class="space-y-3 hidden">
                @forelse($services as $service)
                    <div
                        class="bg-white border border-gray-100 rounded-2xl p-3 flex items-center gap-4 shadow-sm relative active:scale-[0.98] transition-transform">
                        <div
                            class="w-16 h-16 bg-slate-50 rounded-xl flex-shrink-0 flex items-center justify-center overflow-hidden border border-gray-50">
                            <img src="{{ asset('storage/' . ($service->photos ?? 'lk')) }}"
                                class="w-full h-full object-cover"
                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($service->item_name) }}&background=random'">
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-0.5">
                                <h4 class="font-bold text-ink-900 text-sm capitalize">{{ $service->item_name }}</h4>
                                <span
                                    class="text-[9px] px-1.5 py-0.5 rounded-md font-bold {{ $service->status == 'active' ? 'bg-green-100 text-green-600' : 'bg-orange-100 text-orange-600' }}">
                                    {{ strtoupper($service->status) }}
                                </span>
                            </div>
                            <p class="text-[11px] text-gray-500 font-medium italic">
                                {{ $service->category }} • {{ $service->service_duration }} min
                            </p>
                            <div class="mt-1.5 flex items-center gap-2">
                                @if ($service->is_discount_on && $service->discount_price > 0)
                                    <span class="text-sm font-bold text-indigo-600">₹{{ $service->discount_price }}</span>
                                    <span class="text-[10px] text-gray-400 line-through">₹{{ $service->mrp_price }}</span>
                                @else
                                    <span class="text-sm font-bold text-ink-900">₹{{ $service->mrp_price }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-col items-center justify-center pr-1">
                            <a href="{{ url('services/list') }}"
                                class="w-9 h-9 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <p class="text-gray-400 text-sm">Koi service nahi mili bhai!</p>
                    </div>
                @endforelse
            </div>

            <!-- Tab: Reviews -->
            <div id="shopTab-reviews" class="hidden">
                <div class="bg-white border border-ink-100 rounded-2xl p-4 mb-4">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="text-center">
                            <p class="font-display font-bold text-4xl text-ink-800">4.5</p>
                            <p class="text-xs text-ink-400">128 reviews</p>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs w-4">5</span>
                                <div class="flex-1 bg-ink-100 rounded-full h-1.5">
                                    <div class="bg-gold-400 h-1.5 rounded-full" style="width:70%"></div>
                                </div>
                                <span class="text-xs text-ink-400">70%</span>
                            </div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs w-4">4</span>
                                <div class="flex-1 bg-ink-100 rounded-full h-1.5">
                                    <div class="bg-gold-400 h-1.5 rounded-full" style="width:20%"></div>
                                </div>
                                <span class="text-xs text-ink-400">20%</span>
                            </div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs w-4">3</span>
                                <div class="flex-1 bg-ink-100 rounded-full h-1.5">
                                    <div class="bg-gold-400 h-1.5 rounded-full" style="width:7%"></div>
                                </div>
                                <span class="text-xs text-ink-400">7%</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs w-4">1-2</span>
                                <div class="flex-1 bg-ink-100 rounded-full h-1.5">
                                    <div class="bg-red-400 h-1.5 rounded-full" style="width:3%"></div>
                                </div>
                                <span class="text-xs text-ink-400">3%</span>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex gap-3 pb-3 border-b border-ink-50">
                            <div
                                class="w-9 h-9 bg-saffron-100 rounded-full flex items-center justify-center text-xs font-bold text-saffron-700 flex-shrink-0">
                                PR</div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="text-xs font-bold text-ink-800">Priya Rani</p>
                                    <span class="text-saffron-400 text-xs">★★★★★</span>
                                </div>
                                <p class="text-xs text-ink-400 mt-0.5">Bahut achha collection hai. Spin se 20% off mila —
                                    aur joote bhi best quality ke hain!</p>
                                <p class="text-xs text-ink-300 mt-1">2 ghante pehle</p>
                            </div>
                        </div>
                        <div class="flex gap-3 pb-3 border-b border-ink-50">
                            <div
                                class="w-9 h-9 bg-blue-100 rounded-full flex items-center justify-center text-xs font-bold text-blue-700 flex-shrink-0">
                                AK</div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="text-xs font-bold text-ink-800">Amit Kumar</p>
                                    <span class="text-saffron-400 text-xs">★★★★☆</span>
                                </div>
                                <p class="text-xs text-ink-400 mt-0.5">Good shop, friendly staff. Earned 25 coins today
                                    from spin!</p>
                                <p class="text-xs text-ink-300 mt-1">Kal</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Info -->
            <div id="shopTab-info" class="hidden">
                <div class="bg-white border border-ink-100 rounded-2xl p-4 mb-4">
                    <div class="space-y-3 divide-y divide-ink-50">
                        <div class="flex justify-between text-sm pt-0">
                            <span class="text-ink-400">Pata</span>
                            <span
                                class="font-medium text-ink-700 text-right text-xs">{{ Session::get('shopuser')->address ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between text-sm pt-3">
                            <span class="text-ink-400">Samay</span>
                            <span class="font-medium text-ink-700">
                                {{ Session::get('shopuser')->open_time ? date('h:i A', strtotime(Session::get('shopuser')->open_time)) : 'N/A' }}
                                –
                                {{ Session::get('shopuser')->close_time ? date('h:i A', strtotime(Session::get('shopuser')->close_time)) : 'N/A' }}
                            </span>
                        </div>
                        <div class="flex justify-between text-sm pt-3">
                            <span class="text-ink-400">Chhuti</span>
                            <span
                                class="font-medium text-ink-700">{{ Session::get('shopuser')->off_days ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between text-sm pt-3">
                            <span class="text-ink-400">Category</span>
                            <span
                                class="font-medium text-ink-700">{{ Session::get('shopuser')->categories ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between text-sm pt-3">
                            <span class="text-ink-400">Payment</span>
                            <span
                                class="font-medium text-ink-700">{{ Session::get('shopuser')->payment_modes ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-modal" onclick="closeDeleteModal(event)">
        <div id="delete-modal-box">
            <div class="flex justify-center mb-3">
                <div class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center">
                    <svg class="w-7 h-7 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a1 1 0 00-1-1h-4a1 1 0 00-1 1H5" />
                    </svg>
                </div>
            </div>
            <p class="text-center text-base font-bold text-ink-800">Item Delete Karein?</p>
            <p class="text-center text-xs text-ink-400 mt-1 mb-1">Aap yeh item delete karne wale hain:</p>
            <div class="bg-red-50 border border-red-100 rounded-xl px-4 py-2.5 mx-2 mb-4 text-center">
                <p class="text-sm font-bold text-red-600" id="delete-item-name">—</p>
            </div>
            <p class="text-center text-xs text-ink-400 mb-5">Yeh action undo nahi ho sakta.</p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModalDirect()"
                    class="flex-1 bg-ink-100 text-ink-600 font-bold text-sm rounded-2xl py-3 active:scale-95 transition-all">
                    Cancel
                </button>
                <button onclick="submitDelete()"
                    class="flex-1 bg-red-500 text-white font-bold text-sm rounded-2xl py-3 active:scale-95 transition-all shadow-md">
                    🗑️ Haan, Delete Karo
                </button>
            </div>
        </div>
    </div>

    {{-- Hidden DELETE form --}}
    <form id="delete-form" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
    @include('front.partial.shop_edit_pop')
@endsection

@push('script')
    <script>
        $(document).on('turbo:load', function() {
            $('#toggle-profile').off('click').on('click', function() {
                $('#collapsible-content').slideToggle(300, function() {
                    $('#arrow').text($(this).is(':visible') ? '▲' : '▼');
                });
            });
        });

        function confirmDelete(itemId, itemName) {
            document.getElementById('delete-item-name').textContent = itemName;
            document.getElementById('delete-form').action = '/item-delete/' + itemId;
            document.getElementById('delete-modal').classList.add('show');
        }

        function closeDeleteModal(event) {
            if (event.target === document.getElementById('delete-modal')) {
                closeDeleteModalDirect();
            }
        }

        function closeDeleteModalDirect() {
            document.getElementById('delete-modal').classList.remove('show');
        }

        function submitDelete() {
            document.getElementById('delete-form').submit();
        }

        function generateDownload() {
            const canvas = document.getElementById('myCanvas');
            const ctx = canvas.getContext('2d');
            const shopName = document.getElementById('shopNameTxt').innerText;
            const qrImg = new Image();

            qrImg.crossOrigin = "anonymous";
            qrImg.src = document.getElementById('qrDisplay').src;

            qrImg.onload = function() {
                canvas.width = 500;
                canvas.height = 620;

                ctx.fillStyle = "#ffffff";
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                ctx.drawImage(qrImg, 0, 0, 500, 500);

                ctx.fillStyle = "#000000";
                ctx.font = "bold 45px Arial";
                ctx.textAlign = "center";
                ctx.fillText(shopName, 250, 570);

                const link = document.createElement('a');
                link.download = shopName.replace(/\s+/g, '_') + '_QR.png';
                link.href = canvas.toDataURL("image/png");
                link.click();
            };
        }
    </script>
@endpush
