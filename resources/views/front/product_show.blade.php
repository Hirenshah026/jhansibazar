@extends('front_layout.main')

@section('content')
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Nunito', sans-serif;
        }

        .fd {
            font-family: 'Baloo 2', sans-serif;
        }

        body {
            background: #F4F6FF;
            min-height: 100vh;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(12px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .fu {
            animation: fadeUp .35s ease both;
        }

        .shop-tab {
            background: #F1F5F9;
            border: 1.5px solid #E2E8F0;
            padding: 8px 14px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            color: #64748b;
            cursor: pointer;
        }

        .shop-tab.on {
            background: #3B5BDB;
            color: #fff;
            border-color: #3B5BDB;
        }

        .cat-chip {
            flex-shrink: 0;
            border: 1.5px solid #C7D7FF;
            border-radius: 20px;
            padding: 7px 16px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            background: #fff;
            color: #64748b;
            white-space: nowrap;
        }

        .cat-chip.active {
            background: #3B5BDB;
            color: #fff;
            border-color: #3B5BDB;
        }

        .p-card {
            background: #fff;
            border-radius: 18px;
            overflow: hidden;
            border: 1.5px solid #E0E8FF;
        }

        .tag {
            font-size: 9px;
            font-weight: 800;
            padding: 3px 8px;
            border-radius: 20px;
            display: inline-block;
        }

        .t-amber {
            background: #FFFBEB;
            color: #D97706;
            border: 1px solid #FDE68A;
        }

        .t-green {
            background: #F0FDF4;
            color: #16A34A;
            border: 1px solid #BBF7D0;
        }

        .hide-scroll::-webkit-scrollbar {
            display: none;
        }

        .hide-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <div id="screen-main" class="fu active"
        style="padding-bottom:80px; max-width:480px; margin:0 auto; background:#F4F6FF; min-height:100vh;">

        <div style="background:#3B5BDB; padding:13px 16px; position:sticky; top:0; z-index:100">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px">
                <div>
                    <p class="fd" style="color:#fff; font-size:17px; font-weight:800; line-height:1">
                        {{ $shop->shop_name ?? 'Shop Name' }}
                    </p>
                    <p style="color:rgba(255,255,255,.65); font-size:11px">
                        {{ $shop->address ?? 'Jhansi' }} •
                        @if ($shop->open_time)
                            Open
                            {{ date('g a', strtotime($shop->open_time)) }}–{{ date('g a', strtotime($shop->close_time)) }}
                        @endif
                    </p>
                </div>
                <div style="display:flex; gap:8px; align-items:center">
                    @if ($shop->status == 'pending')
                        <span
                            style="background:rgba(252,186,3,.25); border:1px solid rgba(252,186,3,.4); border-radius:20px; padding:4px 10px; color:#fccb03; font-size:10px; font-weight:800">🟡
                            Pending</span>
                    @else
                        <span
                            style="background:rgba(34,197,94,.25); border:1px solid rgba(34,197,94,.4); border-radius:20px; padding:4px 10px; color:#4ade80; font-size:10px; font-weight:800">🟢
                            Open</span>
                    @endif

                    <div style="background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25); border-radius:8px; padding:6px; cursor:pointer"
                        onclick="$('#shopSelector').slideToggle(200)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white"
                            stroke-width="2.5">
                            <path d="M3 6h18M3 12h18M3 18h18" />
                        </svg>
                    </div>
                </div>
            </div>
            <div
                style="background:rgba(255,255,255,.15); border:1.5px solid rgba(255,255,255,.25); border-radius:12px; display:flex; align-items:center; gap:8px; padding:9px 13px">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.8)"
                    stroke-width="2.5">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.35-4.35" />
                </svg>
                <input id="searchInp"
                    style="background:transparent; border:none; outline:none; color:#fff; font-size:13px; width:100%"
                    placeholder="Item dhundho..." oninput="searchItems(this.value)">
            </div>
        </div>

        <div id="shopSelector" style="display:none; background:#fff; border-bottom:1.5px solid #E0E8FF; padding:12px 14px">
            <p
                style="font-size:10px; font-weight:800; color:#94a3b8; text-transform:uppercase; letter-spacing:.7px; margin-bottom:10px">
                Shop Type</p>
            <div style="display:flex; gap:8px; flex-wrap:wrap">
                @php $cats = json_decode($shop->categories) ?? []; @endphp
                @foreach ($cats as $cat)
                    <button class="shop-tab {{ $loop->first ? 'on' : '' }}">{{ ucfirst($cat) }}</button>
                @endforeach
            </div>
        </div>

        <div
            style="margin:12px; border-radius:20px; overflow:hidden; position:relative; height:165px; box-shadow: 0 10px 20px rgba(59, 91, 219, 0.15)">
            @if ($shop->shop_photo)
                <img src="{{ url('/shop_photo/' . $shop->shop_photo) }}"
                    style="width:100%; height:100%; object-fit:cover">
            @else
                <img src="https://images.unsplash.com/photo-1612203985729-70726954388c?w=900&q=80"
                    style="width:100%; height:100%; object-fit:cover">
            @endif
            <div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(30,41,59,0.8), transparent)">
            </div>
            <div
                style="position:absolute; inset:0; padding:16px; display:flex; flex-direction:column; justify-content:flex-end">
                <span class="tag t-amber" style="margin-bottom:6px; font-size:10px">✨
                    {{ $shop->tagline ?? 'Jhansi Bazaar' }}</span>
                <p class="fd" style="color:#fff; font-size:20px; font-weight:800; line-height:1.2">
                    {{ $shop->description ?? 'Welcome to our shop' }}</p>
            </div>
        </div>

        <div style="padding:4px 0 12px">
            <div style="display:flex; gap:10px; overflow-x:auto; padding:0 12px" class="hide-scroll">
                <button class="cat-chip active">All Items</button>
                @foreach ($cats as $cat)
                    <button class="cat-chip">{{ ucfirst($cat) }}</button>
                @endforeach
            </div>
        </div>

        <div style="padding:0 12px 20px">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px">
                <p class="fd" style="font-size:16px; font-weight:800; color:#1e293b">Shop Collection</p>
                <p style="font-size:11px; color:#64748b; font-weight:700">Registration:
                    {{ $shop->registration_id ?? 'N/A' }}</p>
            </div>

            <div id="itemsGrid" style="display:grid; grid-template-columns:1fr 1fr; gap:12px">

                {{-- Loop for items - Example --}}
                <a href="{{url('/shopprofile-details')}}/{{ str_replace(' ', '-', strtolower($shop->shop_name));}}">
                    <div class="p-card fu">
                        <div style="position:relative">
                            <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&q=60"
                                style="width:100%; height:135px; object-fit:cover">
                            <div style="position:absolute; top:8px; right:8px"><span class="tag t-green">Verified</span>
                            </div>
                        </div>
                        <div style="padding:12px">
                            <p class="fd"
                                style="font-size:13px; font-weight:800; color:#1e293b; line-height:1.3; margin-bottom:4px">
                                Sample Product</p>
                            <div style="display:flex; align-items:center; justify-content:space-between; margin-top:8px">
                                <span class="fd" style="font-size:17px; font-weight:800; color:#3B5BDB">₹ --</span>
                                <button
                                    style="background:#3B5BDB; color:#fff; border:none; border-radius:10px; padding:7px 12px; font-size:11px; font-weight:800">+
                                    Add</button>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function searchItems(val) {
            let query = val.toLowerCase().trim();
            $('.p-card').each(function() {
                let text = $(this).text().toLowerCase();
                $(this).toggle(text.includes(query));
            });
        }
    </script>
@endsection
