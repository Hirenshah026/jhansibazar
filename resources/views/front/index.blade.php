@extends('front_layout.main')
@section('content')
<style>
    .h-24{
        height:15rem!important;
    }
    .w-44{
        width: 14rem!important;
    }
    .star {
        font-size: 13px;
        color: #FCD34D
    }

    .text-xs{
        font-size: 0.95rem!important;
    }

    .text-\[10px\]{
        font-size: 12px!important;
    }

    /* Growth Services */
    .gs-card {
        border-radius: 18px;
        padding: 14px 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        position: relative;
        overflow: hidden;
        transition: transform .15s, box-shadow .15s;
        cursor: pointer;
    }
    .gs-card:active { transform: scale(0.96) }
    .gs-card::before {
        content: '';
        position: absolute;
        top: -20px; right: -20px;
        width: 60px; height: 60px;
        border-radius: 50%;
        opacity: 0.08;
        background: currentColor;
    }
    .gs-icon-wrap {
        width: 48px; height: 48px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px;
        margin-bottom: 8px;
        flex-shrink: 0;
    }
    .gs-btn {
        margin-top: 8px;
        font-size: 9px; font-weight: 800;
        padding: 4px 12px;
        border-radius: 20px;
        border: none;
        cursor: pointer;
        letter-spacing: 0.03em;
    }

    /* Listee promo banner */
    .listee-promo {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 50%, #c2410c 100%);
        border-radius: 20px;
        padding: 16px;
        position: relative;
        overflow: hidden;
        margin: 0 12px 6px;
    }
    .listee-promo::after {
        content: '';
        position: absolute;
        top: -30px; right: -30px;
        width: 100px; height: 100px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }
    .listee-promo-dots {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 40px;
        background: repeating-linear-gradient(
            45deg,
            rgba(255,255,255,0.03) 0,
            rgba(255,255,255,0.03) 1px,
            transparent 1px,
            transparent 8px
        );
    }
</style>

@php
    // ── Build dynamic brand offers from all shops ──────────────────
    $brandOffers = [];
    foreach($shopsByCategory as $cat => $catShops) {
        foreach($catShops as $shop) {
            $offers = $shop->offers_list ?? [];
            foreach($offers as $offer) {
                if (!empty($offer['is_active']) && (strtotime($offer['expiry_date']) <= strtotime(date('Y-m-d'))) && isset($offer['category']) && $offer['category'] != 'spin') {
                    $offerText = isset($offer['text'])
                        ? $offer['text']
                        : 'Special Offer';

                    $brandOffers[] = [
                        'shop_name'  => $shop->shop_name,
                        'image'      => (!empty($offer['image']) ? $offer['image'] : ($shop->shop_photo ?? null)),
                        'offer_text' => $offerText,
                        'category'   => $offer['category'] ?? 'offer',
                        'shop_slug'  => str_replace(' ', '-', strtolower($shop->shop_name ?? 's')),
                    ];
                }
            }
        }
    }
    shuffle($brandOffers);
    $brandOffers = array_slice($brandOffers, 0, 8);
@endphp

    <div id="screen-home" class="screen active fade-up pb-24">

        <!-- Hero Banner -->
        <div class="bg-gradient-to-r mb-4 from-green-700 to-green-500 px-4 pt-4 pb-6 relative overflow-hidden rounded-b-3xl">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full -translate-y-8 translate-x-8"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white opacity-5 rounded-full translate-y-8 -translate-x-8"></div>

            <div class="relative z-10 mt-2 grid grid-cols-2 gap-3">
                <div>
                    <p class="text-white/80 text-xs mb-1">
                        Namaste, {{ Session::get('shopuser')->shop_name ?? 'Sir' }} 👋
                    </p>
                    <img src="{{url('logo/logo_listee.png')}}" class="h-12 object-contain mb-2">
                    <button class="bg-white text-green-700 text-xs font-semibold px-4 py-1.5 rounded-full shadow">
                        Get a daily deal 🏷️
                    </button>
                </div>
                <div>
                    <?php 
                        if(count($brandOffers) > 0)
                        {
                    ?>
                    <p class="text-white font-semibold text-sm mb-2">Featured Brand Offers</p>
                    <?php } ?>

                    {{-- Brand slider wrapper --}}
                    <div class="relative overflow-hidden rounded-2xl" id="brandWrap">
                        <div class="flex transition-transform duration-400 ease-in-out" id="brandTrack" style="will-change: transform;">
                            @forelse($brandOffers as $bo)
                            <a href="{{ url('/shopprofile-details') }}/{{ $bo['shop_slug'] }}"
                               class="flex-shrink-0 w-[110px] bg-white rounded-2xl p-2 text-center shadow-md mr-2 block"
                               style="text-decoration:none">
                                @if($bo['image'])
                                    <img src="{{ $bo['image'] }}"
                                         class="w-14 h-14 rounded-xl object-cover mx-auto mb-1"
                                         loading="lazy"
                                         alt="{{ $bo['shop_name'] }}">
                                @else
                                    <div class="w-12 h-12 rounded-xl mx-auto mb-1 bg-orange-100 flex items-center justify-center text-2xl">🏪</div>
                                @endif
                                <p class="text-[10px] font-semibold text-gray-700 leading-tight line-clamp-2 min-h-[24px]">{{ $bo['shop_name'] }}</p>
                                <span class="bg-green-100 text-green-700 text-[9px] px-1.5 py-0.5 rounded-full block leading-tight">
                                    {{ ($bo['category'] === 'spin') ? '🎡 ' : '🏷️ ' }}{{ $bo['offer_text'] }}
                                </span>
                            </a>
                            @empty
                            <!-- <div class="flex-shrink-0 w-[90px] bg-white/20 rounded-2xl p-2 text-center">
                                <p class="text-white text-[10px] font-medium">No offers yet</p>
                            </div> -->
                            @endforelse
                        </div>
                    </div>

                    <div class="flex justify-center gap-1.5 mt-2" id="brandDots"></div>
                </div>
            </div>
        </div>

        <!-- Categories (hidden) -->
        <div class="px-4 my-4 hidden">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-ink-400 uppercase tracking-wider">Categories</p>
                <span class="text-xs text-saffron-500 font-semibold cursor-pointer">Sab dekho →</span>
            </div>
            <div class="grid grid-cols-4 gap-2" id="catGrid">
                <button onclick="filterAndNav('food')" class="flex flex-col items-center gap-1 bg-ink-50 rounded-2xl p-2.5 hover:bg-saffron-50 transition border border-transparent hover:border-saffron-200">
                    <span class="text-2xl">🍛</span><span class="text-xs text-ink-500 font-medium">Food</span>
                </button>
                <button onclick="filterAndNav('salon')" class="flex flex-col items-center gap-1 bg-ink-50 rounded-2xl p-2.5 hover:bg-saffron-50 transition border border-transparent hover:border-saffron-200">
                    <span class="text-2xl">✂️</span><span class="text-xs text-ink-500 font-medium">Salon</span>
                </button>
                <button onclick="filterAndNav('kirana')" class="flex flex-col items-center gap-1 bg-ink-50 rounded-2xl p-2.5 hover:bg-saffron-50 transition border border-transparent hover:border-saffron-200">
                    <span class="text-2xl">🛒</span><span class="text-xs text-ink-500 font-medium">Kirana</span>
                </button>
                <button onclick="filterAndNav('clothing')" class="flex flex-col items-center gap-1 bg-ink-50 rounded-2xl p-2.5 hover:bg-saffron-50 transition border border-transparent hover:border-saffron-200">
                    <span class="text-2xl">👗</span><span class="text-xs text-ink-500 font-medium">Kapde</span>
                </button>
                <button onclick="filterAndNav('medical')" class="flex flex-col items-center gap-1 bg-ink-50 rounded-2xl p-2.5 hover:bg-saffron-50 transition border border-transparent hover:border-saffron-200">
                    <span class="text-2xl">💊</span><span class="text-xs text-ink-500 font-medium">Medical</span>
                </button>
                <button onclick="filterAndNav('rozana')" class="flex flex-col items-center gap-1 bg-ink-50 rounded-2xl p-2.5 hover:bg-saffron-50 transition border border-transparent hover:border-saffron-200">
                    <span class="text-2xl">🥛</span><span class="text-xs text-ink-500 font-medium">Rozana</span>
                </button>
                <button onclick="filterAndNav('footwear')" class="flex flex-col items-center gap-1 bg-ink-50 rounded-2xl p-2.5 hover:bg-saffron-50 transition border border-transparent hover:border-saffron-200">
                    <span class="text-2xl">👟</span><span class="text-xs text-ink-500 font-medium">Joote</span>
                </button>
                <button onclick="showScreen('explore')" class="flex flex-col items-center gap-1 bg-ink-50 rounded-2xl p-2.5 hover:bg-saffron-50 transition border border-transparent hover:border-saffron-200">
                    <span class="text-2xl">🔍</span><span class="text-xs text-ink-500 font-medium">Aur</span>
                </button>
            </div>
        </div>

        @php
            $catConfig = [
                'food'        => ['emoji' => '🍛', 'label' => 'Food Zone',          'color' => 'from-amber-400 to-orange-400'],
                'sweets'      => ['emoji' => '🍡', 'label' => 'Sweets & Mithai',        'color' => 'from-yellow-400 to-amber-400'],
                'bakery'      => ['emoji' => '🎂', 'label' => 'Bakery & Cake',          'color' => 'from-pink-300 to-rose-400'],
                'salon'       => ['emoji' => '✂️', 'label' => 'Salon & Beauty',         'color' => 'from-pink-400 to-rose-500'],
                'kirana'      => ['emoji' => '🛒', 'label' => 'Kirana & Grocery',       'color' => 'from-green-400 to-emerald-500'],
                'clothing'    => ['emoji' => '👗', 'label' => 'Kapde & Fashion',        'color' => 'from-indigo-400 to-violet-500'],
                'footwear'    => ['emoji' => '👟', 'label' => 'Joote & Footwear',       'color' => 'from-yellow-400 to-amber-500'],
                'medical'     => ['emoji' => '💊', 'label' => 'Medical & Pharmacy',     'color' => 'from-blue-400 to-cyan-500'],
                'hospital'    => ['emoji' => '🏥', 'label' => 'Hospital & Clinic',      'color' => 'from-cyan-400 to-blue-500'],
                'hardware'    => ['emoji' => '🔧', 'label' => 'Hardware & Tools',       'color' => 'from-slate-400 to-gray-600'],
                'paan'        => ['emoji' => '🌿', 'label' => 'Paan & General',         'color' => 'from-lime-400 to-green-500'],
                'electronics' => ['emoji' => '📱', 'label' => 'Electronics & Mobile',   'color' => 'from-violet-400 to-purple-500'],
                'coaching'    => ['emoji' => '📚', 'label' => 'Coaching & Classes',     'color' => 'from-orange-400 to-red-400'],
                'dairy'       => ['emoji' => '🥛', 'label' => 'Dairy & Milk',           'color' => 'from-teal-400 to-green-500'],
                'taxi'        => ['emoji' => '🚖', 'label' => 'Taxi & Transport',       'color' => 'from-yellow-300 to-amber-400'],
                'rozana'      => ['emoji' => '🥛', 'label' => 'Rozana Wala',            'color' => 'from-teal-400 to-green-500'],
                'other'       => ['emoji' => '➕', 'label' => 'Kuch Aur',               'color' => 'from-gray-400 to-slate-500'],
            ];
            $i=0;
        @endphp

        @foreach($shopsByCategory as $cat => $catShops)
        @php
            $i++;
            $cfg = $catConfig[$cat] ?? $catConfig['other'];
            $sectionId = 'section-' . $cat;
        @endphp
        @if($i == 3)
        <div class="listee-promo mx-3 mb-4">
            <div class="listee-promo-dots"></div>
            <div class="relative z-10 flex items-center gap-3">
                <div style="flex:1">
                    <p class="text-white/70 text-[9px] font-bold uppercase tracking-widest mb-1">Listee ke saath baro</p>
                    <h3 class="text-white font-black text-base leading-tight mb-2">
                        Apna business list karo &<br>
                        <span class="text-yellow-300">lakho customers</span> tak pahuncho!
                    </h3>
                    <button class="bg-white text-orange-600 text-[11px] font-black px-4 py-2 rounded-full shadow-lg active:scale-95 transition-transform">
                        ADD YOUR BUSINESS →
                    </button>
                </div>
                <div style="flex-shrink:0">
                    <img src="https://cdni.iconscout.com/illustration/premium/thumb/business-deal-illustration-download-in-svg-png-gif-file-formats--agreement-handshake-partnership-working-people-pack-illustrations-5349479.png"
                         class="w-24 object-contain drop-shadow-lg" alt="Grow">
                </div>
            </div>
        </div>
        @endif
        @if($i == 5)
        <div class="px-3 pt-3 pb-1">
            <div class="relative overflow-hidden rounded-2xl" id="bannerWrap">
                <div class="flex transition-transform duration-400 ease-in-out" id="bannerTrack" style="will-change: transform;">
                    <div class="flex-shrink-0 w-full">
                        <img src="https://res.cloudinary.com/dhsiw4fc5/image/upload/v1776236646/1_ndf70j.png" class="w-full h-auto rounded-2xl object-cover" loading="lazy" alt="Offer 1">
                    </div>
                    <div class="flex-shrink-0 w-full">
                        <img src="https://res.cloudinary.com/dhsiw4fc5/image/upload/v1776236645/2_qeo48l.png" class="w-full h-auto rounded-2xl object-cover" loading="lazy" alt="Offer 2">
                    </div>
                    <div class="flex-shrink-0 w-full">
                        <img src="https://res.cloudinary.com/dhsiw4fc5/image/upload/v1776236646/3_ontuk3.png" class="w-full h-auto rounded-2xl object-cover" loading="lazy" alt="Offer 3">
                    </div>
                    <div class="flex-shrink-0 w-full">
                        <img src="https://res.cloudinary.com/dhsiw4fc5/image/upload/v1776236645/4_vwmwro.png" class="w-full h-auto rounded-2xl object-cover" loading="lazy" alt="Offer 4">
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
        @endif

        <div class="px-4 mt-3">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 bg-gradient-to-br {{ $cfg['color'] }} rounded-lg flex items-center justify-center text-sm shadow-sm">
                        {{ $cfg['emoji'] }}
                    </div>
                    <p class="text-sm font-bold text-ink-700">{{ $cfg['label'] }}</p>
                </div>
            </div>

            <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide" id="{{ $sectionId }}">
                @foreach($catShops as $shop)
                @php
                    $photo      = $shop->shop_photo ?? null;
                    $itemPhotos = $shop->photos_list ?? [];
                    $displayPhoto = !empty($photo) ? $photo : (!empty($itemPhotos[0]) ? $itemPhotos[0] : '');
                    $offersList = $shop->offers_list ?? [];
                    $firstOffer = $offersList[0] ?? null;
                    $isOpen     = $shop->is_open;
                @endphp

                <div class="flex-shrink-0 w-44 bg-white rounded-2xl border border-ink-100 overflow-hidden shadow-sm card-hover cursor-pointer"
                     onclick="location.href='{{ url('/shopprofile-details') }}/{{ str_replace(' ', '-', strtolower($shop->shop_name ?? 's')) }}';">

                    <div class="relative h-24 bg-ink-50 overflow-hidden">
                        @if($displayPhoto)
                            @php
                                if (is_array($displayPhoto)) {
                                    $img = $displayPhoto[0] ?? 'https://ui-avatars.com/api/?name='.urlencode($shop->shop_name).'&background=random';
                                } else {
                                    $img = $displayPhoto ?? 'https://ui-avatars.com/api/?name='.urlencode($shop->shop_name).'&background=random';
                                }
                            @endphp                            
                            <img src="{{ $img }}" 
                                 class="w-full h-full object-fill" 
                                 loading="lazy" 
                                 alt="{{ $shop->shop_name }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br {{ $cfg['color'] }} opacity-20">
                                <span class="text-4xl">{{ $cfg['emoji'] }}</span>
                            </div>
                        @endif

                        @if($isOpen !== null)
                            <span class="absolute top-1.5 left-1.5 text-[9px] font-bold px-1.5 py-0.5 rounded-full {{ $isOpen ? 'bg-green-500 text-white' : 'bg-red-100 text-red-600' }}">
                                {{ $isOpen ? 'Open' : 'Closed' }}
                            </span>
                        @endif

                        @if($firstOffer)
                            <span class="absolute top-1.5 right-1.5 bg-saffron-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full max-w-[70px] truncate">
                                🏷️ Offer
                            </span>
                        @endif
                    </div>

                    <div class="p-2.5">
                        <h3 class="font-bold text-ink-800 text-xs leading-tight truncate mb-0.5">{{ $shop->shop_name }}</h3>
                        <p class="text-[10px] text-ink-400 truncate mb-1">{{ $shop->tagline ?? $shop->address }}</p>

                        @if($shop->open_time && $shop->close_time)
                        <p class="text-[10px] text-ink-400 mb-1.5">
                            🕐 {{ \Carbon\Carbon::parse($shop->open_time)->format('g A') }} – {{ \Carbon\Carbon::parse($shop->close_time)->format('g A') }}
                        </p>
                        @endif

                        @php
                            $rating = round($shop->avg_rating, 1);
                            $fullStars = floor($rating);
                            $halfStar  = ($rating - $fullStars) >= 0.5;
                            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                        @endphp

                        @if($shop->review_count > 0)
                        <div style="display:flex;align-items:center;gap:6px;">
                            <div style="display:flex;gap:1px">
                                @for($s = 0; $s < $fullStars; $s++)<span class="star">★</span>@endfor
                                @if($halfStar)<span class="star" style="opacity:0.5">★</span>@endif
                                @for($s = 0; $s < $emptyStars; $s++)<span class="star" style="opacity:0.2">★</span>@endfor
                            </div>
                            <span style="font-size:12px;color:#1e293b">{{ $rating }}</span>
                        </div>
                        @else
                        <span style="font-size:10px;color:#94a3b8">No reviews yet</span>
                        @endif

                        <div class="flex gap-1.5 mt-1.5">
                            @if($shop->is_whatsapp && $shop->phone)
                            <a href="https://wa.me/91{{ $shop->phone }}" onclick="event.stopPropagation()" target="_blank"
                               class="flex-1 bg-green-500 text-white rounded-lg py-1.5 text-[10px] font-bold btn-press text-center">
                                💬 Chat
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach        
        

        {{-- ═══════════════════════════════════════════════════════
             IMPORTANT GROWTH SERVICES
        ════════════════════════════════════════════════════════ --}}
        <div class="px-4 my-6">

            {{-- Section Header --}}
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 bg-gradient-to-br from-orange-400 to-red-500 rounded-lg flex items-center justify-center text-sm shadow-sm">
                        🚀
                    </div>
                    <p class="text-sm font-bold text-ink-700">Important Growth Services</p>
                </div>
                <span class="text-[10px] text-ink-400 font-semibold">For Businesses</span>
            </div>

            {{-- 2-column grid --}}
            <div class="grid grid-cols-2 gap-3">

                {{-- Targeted Ad Campaigns --}}
                <div class="gs-card" style="background:#FFF7ED; border:1px solid #FED7AA; color:#c2410c">
                    <div class="gs-icon-wrap" style="background:#FFEDD5">📢</div>
                    <p class="text-[11px] font-black text-gray-800 leading-tight mb-1">Targeted Ad Campaigns</p>
                    <p class="text-[9px] text-gray-500 leading-snug">Sahi audience tak pahuncho Listee Ads se</p>
                    <button class="gs-btn" style="background:#c2410c; color:#fff">Learn More</button>
                </div>

                {{-- Exclusive Offer Creator --}}
                <div class="gs-card" style="background:#FFF0F6; border:1px solid #FBCFE8; color:#9d174d">
                    <div class="gs-icon-wrap" style="background:#FCE7F3">🎁</div>
                    <p class="text-[11px] font-black text-gray-800 leading-tight mb-1">Exclusive Offer Creator</p>
                    <p class="text-[9px] text-gray-500 leading-snug">Offers banao aur shop traffic badhao</p>
                    <button class="gs-btn" style="background:#ec4899; color:#fff">Create Offer</button>
                </div>

                {{-- Customer Loyalty Program --}}
                <div class="gs-card" style="background:#FFF7ED; border:1px solid #FDE68A; color:#92400e">
                    <div class="gs-icon-wrap" style="background:#FFFBEB">🏅</div>
                    <p class="text-[11px] font-black text-gray-800 leading-tight mb-1">Customer Loyalty Program</p>
                    <p class="text-[9px] text-gray-500 leading-snug">Digital points se repeat customers banao</p>
                    <button class="gs-btn" style="background:#f59e0b; color:#fff">Set Up</button>
                </div>

                {{-- WhatsApp Integration --}}
                <div class="gs-card" style="background:#F0FDF4; border:1px solid #BBF7D0; color:#15803d">
                    <div class="gs-icon-wrap" style="background:#DCFCE7">💬</div>
                    <p class="text-[11px] font-black text-gray-800 leading-tight mb-1">WhatsApp Integration</p>
                    <p class="text-[9px] text-gray-500 leading-snug">Customers se seedha WhatsApp pe baat karo</p>
                    <button class="gs-btn" style="background:#16a34a; color:#fff">Integrate</button>
                </div>

                {{-- Spin the Wheel --}}
                <div class="gs-card" style="background:#EEF2FF; border:1px solid #C7D7FF; color:#3730a3">
                    <div class="gs-icon-wrap" style="background:#E0E7FF">🎡</div>
                    <p class="text-[11px] font-black text-gray-800 leading-tight mb-1">Spin the Wheel Offers</p>
                    <p class="text-[9px] text-gray-500 leading-snug">Customers ko spin se engage karo</p>
                    <button class="gs-btn" style="background:#4f46e5; color:#fff">Activate</button>
                </div>

                {{-- Digital Storefront --}}
                <div class="gs-card" style="background:#F0FDFA; border:1px solid #99F6E4; color:#0f766e">
                    <div class="gs-icon-wrap" style="background:#CCFBF1">🏪</div>
                    <p class="text-[11px] font-black text-gray-800 leading-tight mb-1">Digital Storefront</p>
                    <p class="text-[9px] text-gray-500 leading-snug">Apni online dukan set karo ek minute mein</p>
                    <button class="gs-btn" style="background:#0d9488; color:#fff">Get Started</button>
                </div>

            </div>
        </div>
        {{-- ═══════════════════════ END GROWTH SERVICES ══════════ --}}

        @include('front.partial.shop_list_slider')
    </div>
@endsection

@push('script')
<script>
    // ── Banner Slider ──────────────────────────────────────────────
    (function () {
        let cur = 0, total = 4, timer, startX = 0;
        const track = document.getElementById('bannerTrack');
        const dots  = document.querySelectorAll('#bannerDots .banner-dot');

        function goTo(i) {
            cur = (i + total) % total;
            track.style.transform = `translateX(-${cur * 100}%)`;
            dots.forEach((d, idx) => {
                d.classList.toggle('bg-saffron-500', idx === cur);
                d.classList.toggle('w-4',            idx === cur);
                d.classList.toggle('bg-ink-200',     idx !== cur);
                d.classList.toggle('w-1.5',          idx !== cur);
            });
        }

        function startAuto() { timer = setInterval(() => goTo(cur + 1), 3000); }
        function stopAuto()  { clearInterval(timer); }

        const wrap = document.getElementById('bannerWrap');
        wrap.addEventListener('touchstart', e => { startX = e.touches[0].clientX; stopAuto(); }, { passive: true });
        wrap.addEventListener('touchend',   e => {
            const diff = startX - e.changedTouches[0].clientX;
            if (Math.abs(diff) > 40) goTo(cur + (diff > 0 ? 1 : -1));
            startAuto();
        }, { passive: true });

        dots.forEach((d, i) => d.addEventListener('click', () => { goTo(i); stopAuto(); startAuto(); }));
        startAuto();
    })();

    // ── Brand Slider (dynamic — cards rendered by Blade) ──────────
    (function () {
        const track  = document.getElementById('brandTrack');
        const dotsEl = document.getElementById('brandDots');
        const wrap   = document.getElementById('brandWrap');

        if (!track || !wrap) return;

        const cards = Array.from(track.children);
        const total = cards.length;
        if (!total) return;

        let cur = 0, autoTimer;

        // Build dots dynamically based on card count
        cards.forEach((_, i) => {
            const dot = document.createElement('button');
            dot.className = `brand-dot h-1.5 rounded-full transition-all duration-300 ${i === 0 ? 'w-4 bg-white' : 'w-1.5 bg-white/40'}`;
            dot.addEventListener('click', () => { goTo(i); resetAuto(); });
            dotsEl.appendChild(dot);
        });

        function cardWidth() {
            return cards[0] ? cards[0].offsetWidth + 8 : 98;
        }

        function goTo(i) {
            cur = (i + total) % total;
            const maxOffset = (total - 1) * cardWidth();
            track.style.transform = `translateX(-${Math.min(cur * cardWidth(), maxOffset)}px)`;
            dotsEl.querySelectorAll('.brand-dot').forEach((d, idx) => {
                d.className = `brand-dot h-1.5 rounded-full transition-all duration-300 ${
                    idx === cur ? 'w-4 bg-white' : 'w-1.5 bg-white/40'
                }`;
            });
        }

        function resetAuto() {
            clearInterval(autoTimer);
            autoTimer = setInterval(() => goTo(cur + 1), 2500);
        }

        let startX = 0;
        wrap.addEventListener('touchstart', e => {
            startX = e.touches[0].clientX;
            clearInterval(autoTimer);
        }, { passive: true });

        wrap.addEventListener('touchend', e => {
            const diff = startX - e.changedTouches[0].clientX;
            if (Math.abs(diff) > 30) goTo(cur + (diff > 0 ? 1 : -1));
            resetAuto();
        }, { passive: true });

        goTo(0);
        resetAuto();
    })();

    // ── Flash Deal Timer ───────────────────────────────────────────
    let flashSecs = 6443;
    function updateFlash() {
        const h = Math.floor(flashSecs / 3600);
        const m = Math.floor((flashSecs % 3600) / 60);
        const s = flashSecs % 60;
        const el = document.getElementById('flashTimer');
        if (el) el.textContent = `${h}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
        if (flashSecs > 0) flashSecs--;
    }
    setInterval(updateFlash, 1000);

    function openShop(id) { showScreen('shopprofile', id); }

    function filterCat(btn, cat) {
        const chipContainer = btn.closest('.flex');
        if (chipContainer) {
            chipContainer.querySelectorAll('.cat-chip').forEach(c => {
                c.classList.remove('cat-active', 'border-saffron-200');
                c.classList.add('bg-white', 'text-ink-500', 'border-ink-200');
            });
        }
        btn.classList.add('cat-active');
        btn.classList.remove('bg-white', 'text-ink-500', 'border-ink-200');
        btn.classList.add('border-saffron-200');

        const items = document.querySelectorAll('#shopList .shop-card');
        items.forEach(card => {
            const match = cat === 'all' || card.dataset.cat === cat;
            card.style.display = match ? '' : 'none';
        });
    }
</script>
@endpush