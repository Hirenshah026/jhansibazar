@extends('front_layout.main')

@section('content')

    @push('css_or_link')
        <link
            href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;700;800&family=Nunito:wght@400;500;600;700;800&display=swap"
            rel="stylesheet" />
        <style>
            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
                font-family: 'Nunito', sans-serif
            }

            .fd {
                font-family: 'Baloo 2', sans-serif
            }

            body {
                background: #F4F6FF;
                min-height: 100vh
            }

            @keyframes slideRight {
                from {
                    opacity: 0;
                    transform: translateX(24px)
                }

                to {
                    opacity: 1;
                    transform: translateX(0)
                }
            }

            .sr {
                animation: slideRight .3s ease both
            }

            /* ─── Chips & Tags ─── */
            .cat-chip {
                flex-shrink: 0;
                border: 1.5px solid #C7D7FF;
                border-radius: 20px;
                padding: 7px 16px;
                font-size: 12px;
                font-weight: 700;
                cursor: pointer;
                transition: all .15s;
                white-space: nowrap;
                background: #fff;
                color: #64748b
            }

            .cat-chip.active {
                background: #3B5BDB;
                color: #fff;
                border-color: #3B5BDB
            }

            .tag {
                font-size: 10px;
                font-weight: 800;
                padding: 3px 9px;
                border-radius: 20px;
                display: inline-block
            }

            .t-blue {
                background: #EEF2FF;
                color: #3B5BDB;
                border: 1px solid #C7D7FF
            }

            .t-green {
                background: #DCFCE7;
                color: #16A34A;
                border: 1px solid #86EFAC
            }

            .t-red {
                background: #FEF2F2;
                color: #EF4444;
                border: 1px solid #FECACA
            }

            .t-amber {
                background: #FFFBEB;
                color: #B45309;
                border: 1px solid #FDE68A
            }

            /* ─── Detail page specific ─── */
            .thumb {
                width: 72px;
                height: 72px;
                border-radius: 12px;
                object-fit: cover;
                border: 2.5px solid transparent;
                cursor: pointer;
                transition: all .15s;
                flex-shrink: 0
            }

            .thumb.active {
                /* border-color: #3B5BDB; */
                box-shadow: 0 0 0 3px rgba(59, 91, 219, .2)
            }

            .star {
                font-size: 13px;
                color: #FCD34D
            }

            .dtab {
                padding: 9px 18px;
                border: none;
                background: transparent;
                font-family: 'Nunito', sans-serif;
                font-weight: 700;
                font-size: 13px;
                cursor: pointer;
                color: #64748b;
                border-bottom: 2.5px solid transparent;
                transition: all .15s;
                white-space: nowrap
            }

            .dtab.on {
                color: #3B5BDB;
                border-bottom-color: #3B5BDB
            }

            .btn-action {
                border: none;
                border-radius: 16px;
                font-weight: 800;
                font-size: 14px;
                cursor: pointer;
                transition: all .2s;
                text-decoration: none;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .btn-action:active {
                transform: scale(0.96);
            }

            .hide-scroll::-webkit-scrollbar {
                display: none;
            }

            .service-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                /* Ye banayega col-6 layout */
                gap: 12px;
                padding: 15px;
            }

            /* App Style Service Card */
            .s-card {
                background: #fff;
                border-radius: 16px;
                padding: 10px;
                border: 1px solid #E2E8F0;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                position: relative;
                transition: 0.2s;
            }

            .s-card:active {
                transform: scale(0.96);
            }

            /* Small Image like App */
            .s-img {
                width: 100%;
                height: 90px;
                background: #F1F5F9;
                border-radius: 12px;
                margin-bottom: 8px;
                overflow: hidden;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 30px;
            }

            .s-img img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .s-name {
                font-weight: 800;
                color: #1e293b;
                font-size: 13px;
                line-height: 1.2;
                margin-bottom: 4px;
            }

            .s-price {
                color: #3B5BDB;
                font-weight: 800;
                font-size: 15px;
            }

            /* Add Button Small */
            .btn-add {
                position: absolute;
                bottom: 10px;
                right: 10px;
                background: #3B5BDB;
                color: #fff;
                width: 28px;
                height: 28px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 18px;
                font-weight: bold;
                border: none;
            }

            :root {
                --primary-green: #27ae60;
                --light-bg: #f4f9f6;
                --text-dark: #333;
            }

            .service-wrapper {
                max-width: 500px;
                margin: auto;
                /* padding: 15px; */
                background: #fff;
                font-family: 'Segoe UI', sans-serif;
            }

            /* Green Filter Tabs */
            .filter-tabs {
                display: flex;
                gap: 8px;
                margin-bottom: 20px;
                overflow-x: auto;
            }

            .t-btn {
                padding: 8px 16px;
                border-radius: 8px;
                border: 1px solid #ddd;
                background: #f9f9f9;
                font-size: 13px;
                font-weight: 600;
                cursor: pointer;
                white-space: nowrap;
            }

            .t-btn.active {
                background: var(--primary-green);
                color: white;
                border-color: var(--primary-green);
            }

            /* Flex Card - Green Style */
            .s-flex-card {
                display: flex;
                align-items: center;
                justify-content: space-between;
                background: #ffffff;
                padding: 12px 16px;
                border-radius: 12px;
                margin-bottom: 12px;
                border: 1px solid #eee;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
            }

            /* Left Initial Box */
            .s-initial {
                flex: 0 0 45px;
                height: 45px;
                background: var(--light-bg);
                /* Soft Green Background */
                color: var(--primary-green);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
                font-weight: 700;
                border-radius: 10px;
                margin-right: 15px;
                border: 1px solid #d4edda;
            }

            .s-content {
                flex: 1;
                display: flex;
                flex-direction: column;
            }

            .s-name {
                font-size: 15px;
                font-weight: 600;
                color: var(--text-dark);
                margin: 0;
            }

            .s-time {
                font-size: 12px;
                color: #888;
                margin-top: 3px;
            }

            .s-side-info {
                flex: 0 0 auto;
                text-align: right;
                display: flex;
                flex-direction: column;
                align-items: flex-end;
            }

            .s-price {
                font-size: 16px;
                font-weight: 700;
                color: #222;
            }

            .s-link {
                font-size: 12px;
                color: var(--primary-green);
                text-decoration: none;
                font-weight: 600;
                margin-top: 4px;
            }

            /* Filter Logic Class */
            .hide {
                display: none !important;
            }
        </style>
    @endpush
    @php
        $offers = json_decode($shop->offers, true) ?? [];
        $cleanOffers = array_filter($offers, fn($v) => !empty(trim((string) $v)));
        $hasSpin = count($cleanOffers) > 0;
        // Generating Clean Slug for URL
        $shopSlug = str_replace(' ', '-', strtolower($shop->shop_name));
    @endphp
    <div id="screen-detail" class="sr"
        style="background:#fff; min-height:100vh; padding-bottom:100px; max-width:480px; margin:0 auto; position:relative;">

        <div
            style="background:#fff; padding:12px 16px; display:flex; align-items:center; gap:10px; position:sticky; top:0; z-index:100; border-bottom:1.5px solid #E0E8FF">
            <button onclick="window.history.back()"
                style="width:36px; height:36px; border-radius:12px; background:#EEF2FF; border:none; display:flex; align-items:center; justify-content:center; flex-shrink:0">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3B5BDB" stroke-width="2.5">
                    <path d="M15 18l-6-6 6-6" />
                </svg>
            </button>
            <p class="fd"
                style="font-size:15px; font-weight:800; color:#1e293b; flex:1; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                {{ ucwords($shop->shop_name) }}
            </p>
            <button style="background:none; border:none; font-size:20px; margin-right:5px">🤍</button>
            <button
                style="width:36px; height:36px; border-radius:12px; background:#EEF2FF; border:none; display:flex; align-items:center; justify-content:center">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3B5BDB" stroke-width="2.5">
                    <path d="M4 12v8a2 2 0 002 2h12a2 2 0 002-2v-8M16 6l-4-4-4 4M12 2v13" />
                </svg>
            </button>
        </div>

        <div style="position:relative; background:#F8FAFF">
            @php
                $itemPhotos = json_decode($shop->item_photos, true);
                $mainImg = $shop->shop_photo ? $shop->shop_photo : 'https://placehold.co/600x400?text=Jhansi+Bazaar';
            @endphp
            <img id="detailMainImg" src="{{ $mainImg }}" style="width:100%; height:300px; object-fit:cover">

            <div style="position:absolute; top:12px; left:12px; display:flex; gap:6px; flex-wrap:wrap">
                @if ($shop->status == 'pending')
                    <span class="tag t-amber">🕒 Verification Pending</span>
                @endif
                @if ($shop->status == 'draft')
                    <span class="tag t-gray" style="background:#f1f5f9; color:#64748b; border:1px solid #e2e8f0;">📝
                        Draft</span>
                @endif
            </div>
        </div>

        <div style="background:#fff; padding:14px; border-bottom:1.5px solid #E0E8FF">
            <div style="display:flex; gap:10px; overflow-x:auto padding:4px;" class="hide-scroll">
                <img src="{{ $mainImg }}" class="thumb active" onclick="changeImg(this)">
                @if ($itemPhotos)
                    @foreach ($itemPhotos as $photo)
                        @if (!empty($photo))
                            <img src="{{ $photo['url'] }}" class="thumb" onclick="changeImg(this)">
                        @endif
                    @endforeach
                @endif
            </div>
        </div>

        <div style="padding:18px 16px 0">
            <div class="flex items-center justify-between gap-1 mb-4 w-full flex-nowrap">

                <div
                    class="flex-1 min-w-0 bg-white border border-gray-100 rounded-2xl p-2 shadow-sm flex items-center gap-2">
                    <div
                        class="flex-shrink-0 w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xs shadow-md">
                        {{ substr(ucwords($shop->shop_name), 0, 1) }}
                    </div>

                    <div class="min-w-0 flex-1">
                        <h4 class="text-[11px] font-bold text-gray-800 truncate">{{ ucwords($shop->shop_name) }}</h4>
                        <p class="text-[10px] text-gray-400 truncate">{{ ucwords($shop->address) }}</p>
                    </div>
 
                    <button id="followBtn" data-shopid="{{ $shop->id }}" data-userid="{{ Session::get('public_user')->id??0 }}"
                        class="flex-shrink-0 bg-black text-white text-[10px] font-bold px-3 py-1.5 rounded-full shadow-sm">
                        Follow
                    </button>
                </div>

                <div class="flex flex-shrink-0 items-center gap-1.5">

                    <div class="relative flex flex-col items-center group">
                        <a href="tel:{{ $shop->mobile ?? '00' }}"
                            class="w-8 h-8 bg-blue-50 rounded-full flex items-center justify-center border border-blue-200 text-blue-600 hover:bg-blue-600 hover:text-white transition-all duration-300">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                </path>
                            </svg>
                        </a>
                        <div
                            class="absolute bottom-full mb-2 right-0 flex flex-col items-end opacity-0 group-hover:opacity-100 pointer-events-none transition-all duration-200 translate-y-2 group-hover:translate-y-0 z-[999]">
                            <div
                                class="bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded-lg whitespace-nowrap shadow-xl">
                                <span class="text-blue-400">CALL:</span> {{ $shop->mobile ?? 'N/A' }}
                            </div>
                            <div class="w-2 h-2 -mt-1 mr-3 rotate-45 bg-slate-900"></div>
                        </div>
                    </div>

                    <div class="relative flex flex-col items-center group">
                        <a href="https://wa.me/{{ $shop->whatsapp_number ?? '00' }}"
                            class="w-8 h-8 bg-green-50 rounded-full flex items-center justify-center border border-green-200 text-green-600 hover:bg-green-500 hover:text-white transition-all duration-300">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.587-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217s.231.001.332.005c.101.005.237-.038.37.281.144.354.491 1.197.534 1.284.045.087.072.188.014.303-.058.116-.087.188-.173.289l-.26.303c-.087.101-.177.211-.077.383.101.173.448.739.961 1.197.661.59 1.218.774 1.391.859.173.087.274.072.376-.043.101-.116.433-.505.548-.678.116-.173.231-.144.39-.087s1.011.477 1.184.563c.173.087.289.13.332.202.045.072.045.477-.099.882z">
                                </path>
                            </svg>
                        </a>
                        <div
                            class="absolute bottom-full mb-2 right-0 flex flex-col items-end opacity-0 group-hover:opacity-100 pointer-events-none transition-all duration-200 translate-y-2 group-hover:translate-y-0 z-[999]">
                            <div
                                class="bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded-lg whitespace-nowrap shadow-xl">
                                <span class="text-green-400">WA:</span> {{ $shop->whatsapp_number ?? '00' }}
                            </div>
                            <div class="w-2 h-2 -mt-1 mr-3 rotate-45 bg-slate-900"></div>
                        </div>
                    </div>

                    @if ($hasSpin)
                        <div class="relative group flex items-center justify-center">
                            <a href="{{ url('/spin/' . $shopSlug) }}"
                                class="w-8 h-8 bg-emerald-600 text-white rounded-lg flex items-center justify-center border border-emerald-500 shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2.5"
                                    class="animate-[spin_5s_linear_infinite]">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 2v2M12 20v2M2 12h2M20 12h2M12 12L16 10" />
                                </svg>
                            </a>
                            <div
                                class="absolute bottom-full mb-2 right-0 opacity-0 group-hover:opacity-100 transition-all duration-200 z-[999]">
                                <div
                                    class="bg-slate-800 text-white text-[9px] font-bold px-2 py-1 rounded shadow-lg whitespace-nowrap uppercase">
                                    Spin Available</div>
                                <div class="w-1.5 h-1.5 bg-slate-800 rotate-45 -mt-1 ml-auto mr-3"></div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            <div style="display:flex; gap:6px; flex-wrap:wrap; margin-bottom:15px">
                @if ($shop->tagline)
                    <span class="tag t-green">✨ {{ $shop->tagline }}</span>
                @endif
                @php $categories = json_decode($shop->categories, true); @endphp
                @if ($categories)
                    @foreach ($categories as $cat)
                        <span class="tag t-blue">{{ ucfirst($cat) }}</span>
                    @endforeach
                @endif
            </div>
            <div
                style="display:flex;align-items:center;gap:8px;margin-bottom:12px;padding-bottom:12px;border-bottom:1.5px solid #F1F5F9">
                <div style="display:flex;gap:2px" id="starRow"><span class="star">★</span><span
                        class="star">★</span><span class="star">★</span><span class="star">★</span><span
                        class="star empty">★</span></div>
                <span id="ratingNum" style="font-size:13px;font-weight:800;color:#1e293b">4.9</span>
                <span style="color:#E2E8F0;font-size:12px">|</span>
                <span id="reviewCount" style="font-size:12px;color:#64748b">87 reviews</span>
                <span style="color:#E2E8F0;font-size:12px">|</span>
                <span id="soldCount" style="font-size:12px;color:#16A34A;font-weight:700" class="hidden">200+ sold</span>
            </div>
            <div
                style="display:none; align-items:center; gap:10px; margin-bottom:15px; padding-bottom:15px; border-bottom:1.5px solid #F1F5F9">
                <span style="font-size:13px; color:#16A34A; font-weight:700">📍 {{ $shop->address }}</span>
                <span style="color:#E2E8F0; font-size:12px">|</span>
                <span style="font-size:13px; color:#64748b">
                    {{ date('g:i a', strtotime($shop->open_time)) }} - {{ date('g:i a', strtotime($shop->close_time)) }}
                </span>
            </div>

            <div style="display:flex; border-bottom:1.5px solid #F1F5F9; margin:0 -16px; padding:0 16px; overflow-x:auto"
                class="hide-scroll">
                <button class="dtab on" onclick="switchTab(this, 'dt-desc')">About</button>
                <button class="dtab" onclick="switchTab(this, 'dt-details')">Timing</button>
                <button class="dtab hidden" onclick="switchTab(this, 'dt-offers')">Services</button>
                <button class="dtab" onclick="switchTab(this, 'dt-items')">Items</button>
                <button class="dtab" onclick="switchTab(this, 'dt-reviews')">Reviews</button>
            </div>

            <div id="dt-desc" class="tab-content" style="padding:16px 0">
                <p style="font-size:14px; color:#475569; line-height:1.7; margin-bottom:15px">
                    {{ $shop->description ?? 'No detailed description available for this shop.' }}
                </p>
                <div
                    style="background:#F8FAFF; border-radius:14px; padding:12px; border:1.5px solid #E0E8FF; display:flex; gap:12px; align-items:center">
                    <div
                        style="width:40px; height:40px; border-radius:50%; background:#EEF2FF; display:flex; align-items:center; justify-content:center; font-size:18px">
                        👤</div>
                    <div>
                        <p style="font-size:13px; font-weight:800; color:#3B5BDB">Shop Owner</p>
                        <p style="font-size:12px; color:#64748b">{{ $shop->owner_name }}</p>
                    </div>
                </div>
            </div>

            <div id="dt-details" class="tab-content" style="display:none; padding:16px 0">
                <div style="display:flex; flex-direction:column; gap:12px">
                    <div
                        style="display:flex; justify-content:space-between; padding:12px; background:#f8fafc; border-radius:12px">
                        <span style="font-size:14px; color:#64748b; font-weight:600">Open/Close Time</span>
                        <span style="font-size:14px; font-weight:700; color:#ef4444">
                            {{ date('g:i a', strtotime($shop->open_time)) }} -
                            {{ date('g:i a', strtotime($shop->close_time)) }}
                        </span>
                    </div>
                    <div
                        style="display:flex; justify-content:space-between; padding:12px; background:#f8fafc; border-radius:12px">
                        <span style="font-size:14px; color:#64748b; font-weight:600">Weekly Off</span>
                        <span style="font-size:14px; font-weight:700; color:#ef4444">
                            @php $offDays = json_decode($shop->off_days, true); @endphp
                            {{ $offDays && count($offDays) > 0 ? implode(', ', $offDays) : 'Open Everyday' }}
                        </span>
                    </div>
                    <div
                        style="display:flex; justify-content:space-between; padding:12px; background:#f8fafc; border-radius:12px">
                        <span style="font-size:14px; color:#64748b; font-weight:600">Payment Methods</span>
                        <span style="font-size:14px; font-weight:700; color:#1e293b">
                            @php $payments = json_decode($shop->payment_modes, true); @endphp
                            {{ $payments && count($payments) > 0 ? implode(', ', $payments) : 'Cash & Online' }}
                        </span>
                    </div>
                    <div
                        style="display:flex; justify-content:space-between; padding:12px; background:#f8fafc; border-radius:12px">
                        <span style="font-size:14px; color:#64748b; font-weight:600">Address</span>
                        <span style="font-size:14px; font-weight:700; color:#1e293b">
                            📍 {{ $shop->address }}
                        </span>
                    </div>
                    <div>

                    </div>
                </div>
            </div>

            <div id="dt-offers" class="tab-content" style="display:none; padding:16px 0">

                <div class="service-wrapper">
                    <div class="filter-tabs">
                        <button class="t-btn active" onclick="filter('all', this)">All</button>
                        <button class="t-btn" onclick="filter('men', this)">Men</button>
                        <button class="t-btn" onclick="filter('women', this)">Women</button>
                        <button class="t-btn" onclick="filter('unisex', this)">Unisex</button>
                    </div>

                    <div id="service-container">
                        @foreach ($services as $service)
                            {{-- Aapki table me 'salon_cat' column filter ke liye use hoga --}}
                            <div class="s-flex-card" data-cat="{{ strtolower($service->salon_cat ?? 'unisex') }}">
                                <div class="s-initial">
                                    {{-- Item Name ka pehla letter nikalne ke liye --}}
                                    {{ strtoupper(substr($service->item_name, 0, 1)) }}
                                </div>
                                <div class="s-content">
                                    <p class="s-name">{{ $service->item_name }}</p>
                                    <span class="s-time">⏱ {{ $service->service_duration }} min</span>
                                </div>
                                <div class="s-side-info">
                                    <span class="s-price">₹{{ number_format($service->mrp_price, 0) }}</span>
                                    <a href="#" class="s-link">Book →</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
            <div id="dt-items" class="tab-content" style="display: none; padding:16px 0">
                @if ($items->isEmpty())
                    <div class="flex flex-col items-center justify-center py-14 text-center">
                        <div class="text-5xl mb-3">📦</div>
                        <p class="text-sm font-bold text-ink-700">Koi item nahi mila</p>
                     
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
                                            class="w-full h-full object-cover pop_show" loading="lazy"
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
            <div id="dt-reviews" class="tab-content" style="display:none; padding:16px 0">
                <div style="display:flex; gap:20px; margin-bottom:20px; align-items:center">
                    <div style="text-align:center">
                        <p class="fd" style="font-size:42px; font-weight:800; color:#1e293b; line-height:1">4.8</p>
                        <div style="display:flex; gap:2px; justify-content:center; margin:5px 0">
                            <span class="star">★</span><span class="star">★</span><span class="star">★</span><span
                                class="star">★</span><span class="star">★</span>
                        </div>
                        <p style="font-size:11px; color:#94a3b8">12 Reviews</p>
                    </div>
                    <div style="flex:1">
                        <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px">
                            <span style="font-size:10px; color:#64748b; width:10px">5</span>
                            <div style="flex:1; height:6px; background:#F1F5F9; border-radius:10px; overflow:hidden">
                                <div style="width:85%; height:100%; background:#FCD34D"></div>
                            </div>
                        </div>
                        <div style="display:flex; align-items:center; gap:8px">
                            <span style="font-size:10px; color:#64748b; width:10px">4</span>
                            <div style="flex:1; height:6px; background:#F1F5F9; border-radius:10px; overflow:hidden">
                                <div style="width:10%; height:100%; background:#FCD34D"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    style="background:#F8FAFF; border-radius:16px; padding:14px; border:1.5px solid #E0E8FF; margin-bottom:12px">
                    <p style="font-size:13px; font-weight:800; color:#1e293b; margin-bottom:4px">Rahul Kumar</p>
                    <p style="font-size:12px; color:#475569">Sahi rate aur acchi quality. Jhansi mein best shop hai!</p>
                </div>
                <div style="background:#F8FAFF; border-radius:16px; padding:14px; border:1.5px solid #E0E8FF">
                    <p style="font-size:13px; font-weight:800; color:#1e293b; margin-bottom:4px">Anjali S.</p>
                    <p style="font-size:12px; color:#475569">Bohot accha collection hai. Jarur jayein!</p>
                </div>
            </div>
        </div>

        <div style="position:fixed1; bottom:0; width:100%; max-width:480px; background:#fff; border-top:1.5px solid #E0E8FF; padding:16px; z-index:100"
            class="hidden">
            <div style="display:flex; gap:12px">
                <a href="tel:{{ $shop->phone }}" class="btn-action"
                    style="flex:1; background:#F1F5F9; color:#1e293b; padding:15px">Call Shop</a>
                <a href="https://wa.me/{{ $shop->phone }}" class="btn-action"
                    style="flex:1.5; background:#16A34A; color:#fff; padding:15px; gap:8px">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.587-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217s.231.001.332.005c.101.005.237-.038.37.281.144.354.491 1.197.534 1.284.045.087.072.188.014.303-.058.116-.087.188-.173.289l-.26.303c-.087.101-.177.211-.077.383.101.173.448.739.961 1.197.661.59 1.218.774 1.391.859.173.087.274.072.376-.043.101-.116.433-.505.548-.678.116-.173.231-.144.39-.087s1.011.477 1.184.563c.173.087.289.13.332.202.045.072.045.477-.099.882z" />
                    </svg>
                    <span>WhatsApp</span>
                </a>
            </div>
        </div>

    </div>
    @include('front.partial.img_popup')
    

@endsection
@push('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function switchTab(btn, tabId) {
            $('.dtab').removeClass('on');
            $(btn).addClass('on');
            $('.tab-content').hide();
            $('#' + tabId).fadeIn(200);
        }

        function changeImg(el) {
            $('.thumb').removeClass('active');
            $(el).addClass('active');
            const newSrc = $(el).attr('src');
            $('#detailMainImg').fadeOut(150, function() {
                $(this).attr('src', newSrc).fadeIn(150);
            });
        }
    </script>
    <script>
        function filter(cat, btn) {
            // Active Button Change
            document.querySelectorAll('.t-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            // Card Filtering
            const cards = document.querySelectorAll('.s-flex-card');
            cards.forEach(card => {
                if (cat === 'all' || card.getAttribute('data-cat') === cat) {
                    card.classList.remove('hide');
                } else {
                    card.classList.add('hide');
                }
            });
        }
        $(document).ready(function() {
            // Event delegation taaki dynamic cards par bhi chale
            $(document).on('click', '#followBtn', function(e) {
                e.preventDefault();
                let btn = $(this);
                let userId = btn.data('userid');
                let shopId = btn.data('shopid');
                // 1. UI update turant kar do (Optimistic UI)
                if (!btn.hasClass('is-following')) {
                    btn.addClass('is-following bg-green-700 text-white').removeClass(
                        'bg-black');
                    btn.text('Following');
                } else {
                    btn.removeClass('is-following bg-gray-100 text-gray-500').addClass(
                        'bg-black text-white');
                    btn.text('Follow');
                }
                // 2. AJAX Request (Toggle logic)
                $.ajax({
                    url: "{{ url('/follow-user') }}",
                    method: 'POST',
                    data: {
                        user_id: userId,
                        shopId: shopId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        if (res.status === 'error') {
                            $('.trigger-login').click();
                            btn.removeClass('is-following bg-gray-100 text-gray-500').addClass(
                                'bg-black text-white');
                            btn.text('Follow');
                        }
                        // console.log('Status: ' + res.status);
                    },
                    error: function() {
                        // Network error par reset
                        alert("Something went wrong!");
                        // location.reload();
                    }
                });
            });
        });
    </script>
@endpush
