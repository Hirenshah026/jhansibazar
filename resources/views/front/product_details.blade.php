@extends('front_layout.main')

@section('content')

@push('css_or_link')
<link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;700;800&family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
<style>
* { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Nunito', sans-serif }
.fd { font-family: 'Baloo 2', sans-serif }
body { background: #F0F4FF; min-height: 100vh }

@keyframes slideRight { from { opacity:0; transform:translateX(24px) } to { opacity:1; transform:translateX(0) } }
.sr { animation: slideRight .3s ease both }

@keyframes pulse-ring {
    0% { transform: scale(1); opacity: 1 }
    70% { transform: scale(1.18); opacity: 0 }
    100% { transform: scale(1.18); opacity: 0 }
}
@keyframes spin-slow { from { transform: rotate(0deg) } to { transform: rotate(360deg) } }
@keyframes bounce-in { 0% { transform: scale(0.7); opacity:0 } 70% { transform: scale(1.08) } 100% { transform: scale(1); opacity:1 } }
@keyframes shimmer-move { 0% { left: -60% } 100% { left: 120% } }

.spin-btn-wrap { position: relative; display: inline-flex; align-items: center; justify-content: center }
.spin-ring {
    position: absolute; inset: -6px; border-radius: 20px;
    border: 2.5px solid #f59e0b;
    animation: pulse-ring 1.8s ease-out infinite;
    pointer-events: none;
}
.spin-ring-2 {
    position: absolute; inset: -6px; border-radius: 20px;
    border: 2.5px solid #f59e0b;
    animation: pulse-ring 1.8s ease-out infinite 0.6s;
    pointer-events: none;
}
.spin-btn {
    position: relative; overflow: hidden;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    border: 1.5px solid #f59e0b;
    border-radius: 16px;
    padding: 10px 18px;
    display: flex; align-items: center; gap: 8px;
    cursor: pointer; text-decoration: none;
    box-shadow: 0 0 20px rgba(245,158,11,0.35), inset 0 1px 0 rgba(255,255,255,0.1);
    transition: transform .15s, box-shadow .15s;
}
.spin-btn:active { transform: scale(0.96) }
.spin-btn::after {
    content: '';
    position: absolute; top: 0; width: 40px; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.18), transparent);
    animation: shimmer-move 2.2s ease-in-out infinite;
}
.spin-icon { animation: spin-slow 4s linear infinite }
.spin-label { font-size: 13px; font-weight: 800; color: #fbbf24; letter-spacing: 0.03em; line-height: 1 }
.spin-sub { font-size: 10px; color: #f59e0b; opacity: 0.8; font-weight: 600; line-height: 1 }

.hide-scroll::-webkit-scrollbar { display: none }

.thumb {
    width: 64px; height: 64px; border-radius: 12px;
    object-fit: cover; border: 2.5px solid transparent;
    cursor: pointer; transition: all .15s; flex-shrink: 0;
}
.thumb.active { border-color: #3B5BDB; box-shadow: 0 0 0 3px rgba(59,91,219,.2) }

.dtab {
    padding: 10px 16px; border: none; background: transparent;
    font-family: 'Nunito', sans-serif; font-weight: 700; font-size: 13px;
    cursor: pointer; color: #94a3b8; border-bottom: 2.5px solid transparent;
    transition: all .15s; white-space: nowrap;
}
.dtab.on { color: #3B5BDB; border-bottom-color: #3B5BDB }

.tag { font-size: 10px; font-weight: 800; padding: 3px 9px; border-radius: 20px; display: inline-block }
.t-blue { background: #EEF2FF; color: #3B5BDB; border: 1px solid #C7D7FF }
.t-green { background: #DCFCE7; color: #16A34A; border: 1px solid #86EFAC }
.t-amber { background: #FFFBEB; color: #B45309; border: 1px solid #FDE68A }

.info-pill {
    display: flex; align-items: center; gap: 6px;
    background: #fff; border: 1px solid #E0E8FF; border-radius: 12px;
    padding: 8px 12px; font-size: 12px; font-weight: 700; color: #1e293b;
}
.info-pill svg { flex-shrink: 0 }

/* Stat cards — no border, subtle shadow only */
.stat-card {
    flex: 1; background: #F8FAFF; border-radius: 16px;
    padding: 12px 10px; text-align: center;
}

.action-btn {
    flex: 1; border: none; border-radius: 14px; font-weight: 800; font-size: 13px;
    cursor: pointer; transition: all .2s; text-decoration: none;
    display: flex; align-items: center; justify-content: center; gap: 6px; padding: 13px;
}
.action-btn:active { transform: scale(0.97) }

.s-flex-card {
    display: flex; align-items: center; gap: 12px;
    background: #fff; padding: 12px 14px; border-radius: 14px;
    margin-bottom: 10px; border: 1px solid #F1F5F9;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}
.s-initial {
    flex: 0 0 42px; height: 42px; background: #EEF2FF;
    color: #3B5BDB; display: flex; align-items: center; justify-content: center;
    font-size: 18px; font-weight: 800; border-radius: 10px; border: 1px solid #C7D7FF;
}
.s-price { font-size: 15px; font-weight: 800; color: #1e293b }
.s-link { font-size: 11px; color: #3B5BDB; text-decoration: none; font-weight: 700; margin-top: 3px }

.t-btn {
    padding: 7px 14px; border-radius: 8px; border: 1.5px solid #E0E8FF;
    background: #fff; font-size: 12px; font-weight: 700; cursor: pointer; white-space: nowrap;
}
.t-btn.active { background: #3B5BDB; color: #fff; border-color: #3B5BDB }

.item-card {
    background: #fff; border-radius: 16px; overflow: hidden;
    border: 1px solid #E8EEFF; transition: transform .15s;
}
.item-card:active { transform: scale(0.97) }

.hide { display: none !important }

.top-bar {
    background: rgba(255,255,255,0.96);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    padding: 12px 16px;
    display: flex; align-items: center; gap: 10px;
    position: sticky; top: 0; z-index: 100;
    border-bottom: 1px solid #E0E8FF;
}

.section-title {
    font-size: 11px; font-weight: 800; letter-spacing: 0.08em;
    text-transform: uppercase; color: #94a3b8; margin-bottom: 10px;
}

/* ── Review form styles ── */
.review-star { font-size: 28px; cursor: pointer; color: #e2e8f0; transition: color .15s; line-height: 1 }
.review-star.active, .review-star:hover { color: #FCD34D }
.review-textarea {
    width: 100%; border: 1.5px solid #E0E8FF; border-radius: 14px;
    padding: 12px 14px; font-size: 13px; font-family: 'Nunito', sans-serif;
    color: #1e293b; resize: none; outline: none; transition: border-color .15s;
    background: #F8FAFF;
}
.review-textarea:focus { border-color: #3B5BDB; background: #fff }
.review-input {
    width: 100%; border: 1.5px solid #E0E8FF; border-radius: 12px;
    padding: 10px 14px; font-size: 13px; font-family: 'Nunito', sans-serif;
    color: #1e293b; outline: none; transition: border-color .15s; background: #F8FAFF;
}
.review-input:focus { border-color: #3B5BDB; background: #fff }

/* ── Followed label (non-clickable) ── */
.followed-label {
    flex-shrink: 0;
    display: inline-flex; align-items: center; gap: 5px;
    color: #16A34A; font-size: 12px; font-weight: 800;
    padding: 7px 12px;
    pointer-events: none; user-select: none;
}
</style>
@endpush

@php
    $offers = json_decode($shop->offers, true) ?? [];

    // Filter based on the 'text' key inside each offer array
    $cleanOffers = array_filter($offers, function($v) {
        return isset($v['text']) && !empty(trim((string)$v['text']));
    });


    $hasSpin = count($cleanOffers) > 0;
    $shopSlug = str_replace(' ', '-', strtolower($shop->shop_name));
    $mainImg = $shop->shop_photo ?: 'https://placehold.co/600x400?text=Jhansi+Bazaar';
    $itemPhotos = json_decode($shop->item_photos, true);
    $categories = json_decode($shop->categories, true) ?? [];
    $payments = json_decode($shop->payment_modes, true) ?? [];
    $offDays = json_decode($shop->off_days, true) ?? [];
    $shopInitial = strtoupper(substr($shop->shop_name, 0, 1));

    /* ── Dynamic review stats ── */
    $reviewCount   = $reviews->count();
    $avgRating     = $reviewCount > 0 ? round($reviews->avg('rating'), 1) : 0;
    $starCounts    = [5=>0, 4=>0, 3=>0, 2=>0, 1=>0];
    foreach ($reviews as $r) { $starCounts[(int)$r->rating] = ($starCounts[(int)$r->rating] ?? 0) + 1; }

    /* ── Is current user logged in? ── */
    $loggedUser = Session::get('public_user');
    $isLogged   = Session::has('public_user') && $loggedUser;

    /* ── Is already following? ── */
    $isFollowing = $isFollowed ?? false; // pass $isFollowed boolean from controller
@endphp

<div id="screen-detail" class="sr" style="background:#F0F4FF; min-height:100vh; padding-bottom:110px; max-width:480px; margin:0 auto; position:relative;">

    {{-- ── TOP BAR ── --}}
    <div class="top-bar">
        <button onclick="window.history.back()"
            style="width:36px; height:36px; border-radius:12px; background:#EEF2FF; border:none; display:flex; align-items:center; justify-content:center; flex-shrink:0; cursor:pointer">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3B5BDB" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
        </button>
        <p class="fd" style="font-size:15px; font-weight:800; color:#1e293b; flex:1; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
            {{ ucwords($shop->shop_name) }}
        </p>
        <button style="background:none; border:none; cursor:pointer; font-size:18px; line-height:1">🤍</button>
        <button style="width:36px; height:36px; border-radius:12px; background:#EEF2FF; border:none; display:flex; align-items:center; justify-content:center; cursor:pointer">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3B5BDB" stroke-width="2.5">
                <path d="M4 12v8a2 2 0 002 2h12a2 2 0 002-2v-8M16 6l-4-4-4 4M12 2v13"/>
            </svg>
        </button>
    </div>

    {{-- ── HERO IMAGE ── --}}
    <div style="position:relative">
        <img id="detailMainImg" src="{{ $mainImg }}"
            style="width:100%; height:280px; object-fit:cover; display:block;">

        <div style="position:absolute; bottom:0; left:0; right:0; height:100px;
            background:linear-gradient(to top, rgba(15,20,40,0.7), transparent); pointer-events:none"></div>

        <div style="position:absolute; bottom:14px; left:14px; right:14px">
            <p class="fd" style="font-size:20px; font-weight:800; color:#fff; text-shadow:0 1px 6px rgba(0,0,0,0.4); line-height:1.2">
                {{ ucwords($shop->shop_name) }}
            </p>
            @if($shop->tagline)
            <p style="font-size:12px; color:rgba(255,255,255,0.85); margin-top:3px">✨ {{ $shop->tagline }}</p>
            @endif
        </div>

        @if($shop->status == 'pending')
        <div style="position:absolute; top:12px; right:12px">
            <span class="tag t-amber">🕒 Pending</span>
        </div>
        @endif

        @if($hasSpin)
        <div style="position:absolute; top:12px; left:12px">
            <div class="spin-btn-wrap">
                <div class="spin-ring"></div>
                <div class="spin-ring-2"></div>
                <a href="{{ url('/spin/' . $shopSlug) }}" class="spin-btn">
                    <svg class="spin-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="2.2" stroke-linecap="round">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 8v4l3 3"/>
                        <path d="M16.24 7.76a6 6 0 010 8.49M7.76 16.24a6 6 0 010-8.49"/>
                    </svg>
                    <div>
                        <div class="spin-label">Spin & Win!</div>
                        <div class="spin-sub">Free offers inside</div>
                    </div>
                </a>
            </div>
        </div>
        @endif
    </div>

    {{-- ── THUMBNAIL ROW ── --}}
    @if($itemPhotos && count($itemPhotos) > 0)
    <div style="background:#fff; padding:12px 14px; border-bottom:1px solid #E0E8FF; display:flex; gap:8px; overflow-x:auto" class="hide-scroll">
        <img src="{{ $mainImg }}" class="thumb active" onclick="changeImg(this)">
        @foreach($itemPhotos as $photo)
            @if(!empty($photo))
            <img src="{{ $photo['url'] ?? $photo }}" class="thumb" onclick="changeImg(this)">
            @endif
        @endforeach
    </div>
    @endif

    {{-- ── SHOP INFO CARD ── --}}
    <div style="margin:12px 12px 0; background:#fff; border-radius:20px; border:1px solid #E0E8FF; overflow:hidden">

        {{-- Identity row --}}
        <div style="padding:14px 14px 12px; display:flex; align-items:center; gap:12px; border-bottom:1px solid #F1F5F9">
            <div style="width:48px; height:48px; border-radius:14px; background:linear-gradient(135deg,#3B5BDB,#6B8EFF);
                display:flex; align-items:center; justify-content:center; color:#fff; font-size:20px; font-weight:800; font-family:'Baloo 2',sans-serif; flex-shrink:0">
                {{ $shopInitial }}
            </div>
            <div style="flex:1; min-width:0">
                <p style="font-size:14px; font-weight:800; color:#1e293b; truncate">{{ ucwords($shop->shop_name) }}</p>
                <p style="font-size:11px; color:#64748b; margin-top:2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis">
                    📍 {{ $shop->address }}
                </p>
            </div>

            @if($isFollowing)
                <span class="followed-label">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                         stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Followed
                </span>
            @else
                <button id="followBtn" 
                    data-shopid="{{ $shop->id }}" 
                    data-userid="{{ $isLogged ? ($loggedUser->id ?? 0) : 0 }}"
                    data-islogged="{{ $isLogged ? 'true' : 'false' }}"
                    style="flex-shrink:0; background:#3B5BDB; color:#fff; font-size:11px; font-weight:800; 
                           padding:7px 14px; border-radius:20px; border:none; cursor:pointer">
                    + Follow
                </button>
            @endif
        </div>

        {{-- Quick stats row — no borders, just background tint --}}
        <div class="hidden" style="display:flex; padding:12px 14px; gap:8px; border-bottom:1px solid #F1F5F9">
            <div class="stat-card">
                <p style="font-size:18px; font-weight:800; color:#1e293b" class="fd">{{ count($followCount) }}</p>
                <p style="font-size:10px; color:#94a3b8; font-weight:700; margin-top:2px">Followers</p>
            </div>
            <div class="stat-card hidden">
                <p style="font-size:18px; font-weight:800; color:#16A34A" class="fd">{{ $services->count() }}</p>
                <p style="font-size:10px; color:#94a3b8; font-weight:700; margin-top:2px">Services</p>
            </div>
            <div class="stat-card">
                <p style="font-size:18px; font-weight:800; color:#3B5BDB" class="fd">{{ $items->count() }}</p>
                <p style="font-size:10px; color:#94a3b8; font-weight:700; margin-top:2px">Items</p>
            </div>
            @if($hasSpin)
            <div class="stat-card" style="background:#FFFBEB">
                <p style="font-size:18px; font-weight:800; color:#B45309" class="fd">{{ count($cleanOffers) }}</p>
                <p style="font-size:10px; color:#B45309; font-weight:700; margin-top:2px">Offers</p>
            </div>
            @endif
        </div>

        {{-- Action buttons --}}
        <div style="display:flex; padding:12px 14px; gap:8px">
            <a href="tel:{{ $shop->phone }}" class="action-btn" style="background:#EEF2FF; color:#3B5BDB">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                    <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
                </svg>
                Call
            </a>
            <a href="{{ $whatsappShareUrl }}" target="_blank" class="action-btn" style="background:#DCFCE7; color:#15803D">
                <svg width="27" height="27" viewBox="0 0 24 24" stroke-width="2.5" fill="currentColor">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.587-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.005c.101.005.237-.038.37.281.144.354.491 1.197.534 1.284.045.087.072.188.014.303-.058.116-.087.188-.173.289l-.26.303c-.087.101-.177.211-.077.383.101.173.448.739.961 1.197.661.59 1.218.774 1.391.859.173.087.274.072.376-.043.101-.116.433-.505.548-.678.116-.173.231-.144.39-.087s1.011.477 1.184.563.289.13.332.202c.045.072.045.477-.099.882z"/>
                </svg>
                Share
            </a>
            @if($hasSpin)
            <a href="{{ url('/spin/' . $shopSlug) }}" class="action-btn" style="background:linear-gradient(135deg,#1a1a2e,#0f3460); color:#fbbf24; border:1px solid #f59e0b; flex:1.2">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                    <circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/>
                </svg>
                Spin & Win
            </a>
            @endif
        </div>
    </div>

    {{-- ── SPIN THE WHEEL BANNER ── --}}
    @if($hasSpin)
    <a href="{{ url('/spin/' . $shopSlug) }}"
        style="display:flex; align-items:center; gap:14px; margin:10px 12px 0;
            background:linear-gradient(135deg,#1a1a2e 0%,#16213e 60%,#0f3460 100%);
            border:1.5px solid rgba(245,158,11,0.5); border-radius:20px; padding:16px 18px;
            text-decoration:none; position:relative; overflow:hidden;">
        <div style="position:absolute;top:0;left:-60%;width:40%;height:100%;
            background:linear-gradient(90deg,transparent,rgba(255,255,255,0.06),transparent);
            animation:shimmer-move 3s ease-in-out infinite; pointer-events:none"></div>
        <div style="width:52px;height:52px;border-radius:14px;background:rgba(245,158,11,0.15);
            border:1.5px solid rgba(245,158,11,0.4);display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg class="spin-icon" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="2" stroke-linecap="round">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 8v4l3 3"/>
                <path d="M16.24 7.76a6 6 0 010 8.49M7.76 16.24a6 6 0 010-8.49"/>
            </svg>
        </div>
        <div style="flex:1">
            <p class="fd" style="font-size:16px;font-weight:800;color:#fbbf24;line-height:1.2">🎡 Spin the Wheel!</p>
            <p style="font-size:12px;color:rgba(245,158,11,0.75);margin-top:3px;font-weight:600">
                {{ count($cleanOffers) }} free offers — spin & claim now
            </p>
        </div>
        <div style="background:rgba(245,158,11,0.2);border:1px solid rgba(245,158,11,0.4);border-radius:10px;padding:8px 12px;flex-shrink:0">
            <p style="font-size:12px;font-weight:800;color:#fbbf24">Try →</p>
        </div>
    </a>
    @endif

    {{-- ── TABS & CONTENT ── --}}
    <div style="margin:12px 12px 0; background:#fff; border-radius:20px; border:1px solid #E0E8FF; overflow:hidden">

        <div style="display:flex; border-bottom:1px solid #F1F5F9; overflow-x:auto" class="hide-scroll">
            <button class="dtab on" onclick="switchTab(this,'dt-desc')">About</button>
            <button class="dtab" onclick="switchTab(this,'dt-details')">Timing</button>
            @if($services->count())
            <button class="dtab hidden" onclick="switchTab(this,'dt-offers')">Services</button>
            @endif
            <button class="dtab" onclick="switchTab(this,'dt-items')">Items</button>
            <button class="dtab" onclick="switchTab(this,'dt-reviews')">Reviews
                @if($reviewCount > 0)
                <span style="display:inline-block;margin-left:4px;background:#EEF2FF;color:#3B5BDB;font-size:10px;font-weight:800;padding:1px 6px;border-radius:10px">{{ $reviewCount }}</span>
                @endif
            </button>
        </div>

        {{-- ── ABOUT TAB ── --}}
        <div id="dt-desc" class="tab-content" style="padding:16px">
            <p style="font-size:14px; color:#475569; line-height:1.75; margin-bottom:16px">
                {{ $shop->description ?? 'No description available for this shop.' }}
            </p>
            <div style="background:#F8FAFF; border-radius:14px; padding:12px 14px; border:1px solid #E0E8FF; display:flex; gap:12px; align-items:center; margin-bottom:14px">
                <div style="width:40px;height:40px;border-radius:50%;background:#EEF2FF;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0">👤</div>
                <div>
                    <p style="font-size:11px;color:#94a3b8;font-weight:700;text-transform:uppercase;letter-spacing:0.05em">Owner</p>
                    <p style="font-size:14px;font-weight:800;color:#1e293b">{{ $shop->owner_name }}</p>
                </div>
            </div>
            <div style="display:flex;gap:6px;flex-wrap:wrap">
                @if($shop->tagline)
                    <span class="tag t-green">✨ {{ $shop->tagline }}</span>
                @endif
                @foreach($categories as $cat)
                    <span class="tag t-blue">{{ ucfirst($cat) }}</span>
                @endforeach
                @if($shop->is_whatsapp)
                    <span class="tag" style="background:#DCFCE7;color:#15803D;border:1px solid #86EFAC">💬 WhatsApp</span>
                @endif
            </div>
        </div>

        {{-- ── TIMING TAB ── --}}
        <div id="dt-details" class="tab-content" style="display:none; padding:16px">
            <div style="display:flex;flex-direction:column;gap:10px">
                <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 14px;background:#F8FAFF;border-radius:14px;border:1px solid #E0E8FF">
                    <div style="display:flex;align-items:center;gap:8px">
                        <div style="width:32px;height:32px;background:#EEF2FF;border-radius:10px;display:flex;align-items:center;justify-content:center">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#3B5BDB" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                        </div>
                        <span style="font-size:13px;color:#64748b;font-weight:600">Open / Close</span>
                    </div>
                    <span style="font-size:13px;font-weight:800;color:#1e293b">
                        {{ date('g:i a', strtotime($shop->open_time)) }} — {{ date('g:i a', strtotime($shop->close_time)) }}
                    </span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 14px;background:#F8FAFF;border-radius:14px;border:1px solid #E0E8FF">
                    <div style="display:flex;align-items:center;gap:8px">
                        <div style="width:32px;height:32px;background:#FEF2F2;border-radius:10px;display:flex;align-items:center;justify-content:center">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2.2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                        </div>
                        <span style="font-size:13px;color:#64748b;font-weight:600">Weekly Off</span>
                    </div>
                    <span style="font-size:13px;font-weight:800;color:#1e293b">
                        {{ count($offDays) > 0 ? implode(', ', $offDays) : 'Open Everyday' }}
                    </span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 14px;background:#F8FAFF;border-radius:14px;border:1px solid #E0E8FF">
                    <div style="display:flex;align-items:center;gap:8px">
                        <div style="width:32px;height:32px;background:#FFFBEB;border-radius:10px;display:flex;align-items:center;justify-content:center">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#B45309" stroke-width="2.2"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>
                        </div>
                        <span style="font-size:13px;color:#64748b;font-weight:600">Payment</span>
                    </div>
                    <span style="font-size:13px;font-weight:800;color:#1e293b">
                        {{ count($payments) > 0 ? implode(', ', $payments) : 'Cash & Online' }}
                    </span>
                </div>
                <div style="padding:12px 14px;background:#F8FAFF;border-radius:14px;border:1px solid #E0E8FF">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">
                        <div style="width:32px;height:32px;background:#DCFCE7;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2.2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <span style="font-size:13px;color:#64748b;font-weight:600">Address</span>
                    </div>
                    <p style="font-size:13px;font-weight:700;color:#1e293b;padding-left:40px">{{ $shop->address }}</p>
                </div>
            </div>
        </div>

        {{-- ── SERVICES TAB ── --}}
        @if($services->count())
        <div id="dt-offers" class="tab-content" style="display:none; padding:14px">
            <div style="display:flex;gap:6px;margin-bottom:14px;overflow-x:auto" class="hide-scroll">
                <button class="t-btn active" onclick="filter('all',this)">All</button>
                <button class="t-btn" onclick="filter('men',this)">Men</button>
                <button class="t-btn" onclick="filter('women',this)">Women</button>
                <button class="t-btn" onclick="filter('unisex',this)">Unisex</button>
            </div>
            <div id="service-container">
                @foreach($services as $service)
                <div class="s-flex-card" data-cat="{{ strtolower($service->salon_cat ?? 'unisex') }}">
                    <div class="s-initial">{{ strtoupper(substr($service->item_name,0,1)) }}</div>
                    <div style="flex:1;min-width:0">
                        <p style="font-size:14px;font-weight:800;color:#1e293b;truncate">{{ $service->item_name }}</p>
                        <p style="font-size:11px;color:#94a3b8;margin-top:2px">⏱ {{ $service->service_duration }} min</p>
                    </div>
                    <div style="text-align:right;flex-shrink:0">
                        <p class="s-price">₹{{ number_format($service->mrp_price,0) }}</p>
                        <a href="#" class="s-link">Book →</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── ITEMS TAB ── --}}
        <div id="dt-items" class="tab-content" style="display:none; padding:14px">
            @if($items->isEmpty())
                <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:40px 20px;text-align:center">
                    <div style="font-size:48px;margin-bottom:10px">📦</div>
                    <p style="font-size:14px;font-weight:800;color:#94a3b8">Koi item nahi mila</p>
                </div>
            @else
                <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px">
                    @foreach($items as $item)
                    @php
                        $photos = json_decode($item->photos ?? '[]', true) ?? [];
                        $firstPhoto = null;
                        if(!empty($photos)) {
                            $first = $photos[0];
                            if(is_array($first) && !empty($first['url'])) $firstPhoto = $first['url'];
                            elseif(is_string($first) && !empty($first)) $firstPhoto = asset('items/'.ltrim($first,'/'));
                        }
                        $hasDiscount = $item->is_discount_on && $item->discount_price > 0;
                        $displayPrice = $hasDiscount ? $item->discount_price : $item->mrp_price;
                        $discountPercent = $hasDiscount && $item->mrp_price > 0
                            ? round((($item->mrp_price - $item->discount_price) / $item->mrp_price) * 100) : 0;
                        $stockMap = [
                            'available' => ['label'=>'In Stock','bg'=>'#DCFCE7','color'=>'#16A34A'],
                            'out_of_stock' => ['label'=>'Out','bg'=>'#FEF2F2','color'=>'#EF4444'],
                            'limited' => ['label'=>'Limited','bg'=>'#FFFBEB','color'=>'#B45309'],
                        ];
                        $stock = $stockMap[$item->stock_status] ?? ['label'=>ucfirst($item->stock_status),'bg'=>'#F1F5F9','color'=>'#64748b'];
                        $bgs = ['#EEF2FF','#DCFCE7','#FFF7ED','#FDF2F8','#F0FDF4','#FFFBEB'];
                        $bg = $bgs[$item->id % count($bgs)];
                        $emojis = ['📦','🛍️','🧴','🧺','🎁','🪄'];
                        $emoji = $emojis[$item->id % count($emojis)];
                    @endphp
                    <div class="item-card">
                        <div style="height:100px;background:{{ $bg }};display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden">
                            @if($firstPhoto)
                                <img src="{{ $firstPhoto }}" alt="{{ $item->item_name }}" class="pop_show"
                                    style="width:100%;height:100%;object-fit:cover" loading="lazy"
                                    onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                <span style="font-size:36px;display:none;width:100%;height:100%;align-items:center;justify-content:center">{{ $emoji }}</span>
                            @else
                                <span style="font-size:36px">{{ $emoji }}</span>
                            @endif
                            @if($hasDiscount && $discountPercent > 0)
                            <div style="position:absolute;bottom:6px;right:6px;background:#EF4444;color:#fff;font-size:9px;font-weight:800;padding:2px 6px;border-radius:20px">
                                -{{ $discountPercent }}%
                            </div>
                            @endif
                            @if($item->is_spin_wheel ?? false)
                            <div style="position:absolute;top:6px;left:6px;background:#1a1a2e;color:#fbbf24;font-size:9px;font-weight:800;padding:2px 7px;border-radius:20px;border:1px solid rgba(245,158,11,0.4)">
                                🎡 Spin
                            </div>
                            @endif
                        </div>
                        <div style="padding:10px">
                            <p style="font-size:12px;font-weight:800;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $item->item_name }}</p>
                            <p style="font-size:10px;color:#94a3b8;margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $item->category ?? '—' }}</p>
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-top:7px;gap:4px;flex-wrap:wrap">
                                <div style="display:flex;align-items:baseline;gap:4px">
                                    <span class="fd" style="font-size:15px;font-weight:800;color:#3B5BDB;line-height:1">₹{{ number_format($displayPrice,0) }}</span>
                                    @if($hasDiscount)
                                    <span style="font-size:9px;color:#cbd5e1;text-decoration:line-through">₹{{ number_format($item->mrp_price,0) }}</span>
                                    @endif
                                </div>
                                <span style="font-size:9px;font-weight:800;padding:2px 6px;border-radius:8px;background:{{ $stock['bg'] }};color:{{ $stock['color'] }};white-space:nowrap">
                                    {{ $stock['label'] }}
                                </span>
                            </div>
                            @if($item->special_offer_text)
                            <p style="font-size:10px;color:#B45309;font-weight:700;margin-top:5px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                🏷️ {{ $item->special_offer_text }}
                            </p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ── REVIEWS TAB (Dynamic) ── --}}
        <div id="dt-reviews" class="tab-content" style="display:none; padding:16px">

            {{-- Rating summary --}}
            <div style="display:flex;gap:16px;margin-bottom:18px;align-items:center">
                <div style="text-align:center;flex-shrink:0">
                    <p class="fd" style="font-size:44px;font-weight:800;color:#1e293b;line-height:1">
                        {{ $reviewCount > 0 ? $avgRating : '—' }}
                    </p>
                    <div style="display:flex;gap:2px;justify-content:center;margin:5px 0;font-size:14px;color:#FCD34D">
                        @for($s = 1; $s <= 5; $s++)
                            {{ $s <= round($avgRating) ? '★' : '☆' }}
                        @endfor
                    </div>
                    <p style="font-size:11px;color:#94a3b8">{{ $reviewCount }} {{ Str::plural('Review', $reviewCount) }}</p>
                </div>
                <div style="flex:1">
                    @foreach([5,4,3,2,1] as $star)
                    @php
                        $cnt = $starCounts[$star] ?? 0;
                        $pct = $reviewCount > 0 ? round(($cnt / $reviewCount) * 100) : 0;
                    @endphp
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:5px">
                        <span style="font-size:11px;color:#64748b;width:10px">{{ $star }}</span>
                        <div style="flex:1;height:6px;background:#F1F5F9;border-radius:10px;overflow:hidden">
                            <div style="width:{{ $pct }}%;height:100%;background:#FCD34D;border-radius:10px;transition:width .4s"></div>
                        </div>
                        <span style="font-size:10px;color:#94a3b8;width:24px;text-align:right">{{ $cnt }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ── ADD REVIEW FORM ── --}}
            <div style="background:#F8FAFF;border-radius:16px;padding:14px;border:1px solid #E0E8FF;margin-bottom:16px">
                <p style="font-size:12px;font-weight:800;color:#1e293b;margin-bottom:12px;text-transform:uppercase;letter-spacing:0.05em">✍️ Write a Review</p>

                {{-- Star picker --}}
                <div style="display:flex;gap:6px;margin-bottom:12px" id="starPicker">
                    @for($s = 1; $s <= 5; $s++)
                    <span class="review-star" data-val="{{ $s }}" onclick="setRating({{ $s }})">★</span>
                    @endfor
                </div>
                <input type="hidden" id="selectedRating" value="0">

                {{-- Name + Phone (only if not logged in) --}}
                @if(!$isLogged)
                <div style="display:flex;gap:8px;margin-bottom:10px">
                    <input class="review-input" id="reviewerName" type="text" placeholder="Your name" style="flex:1">
                    <input class="review-input" id="reviewerPhone" type="tel" placeholder="Phone" style="flex:1">
                </div>
                @endif

                <textarea class="review-textarea" id="reviewComment" rows="3" placeholder="Share your experience with this shop..."></textarea>

                <button onclick="submitReview()"
                    style="margin-top:10px;width:100%;background:#3B5BDB;color:#fff;border:none;border-radius:12px;
                        padding:12px;font-size:13px;font-weight:800;cursor:pointer;font-family:'Nunito',sans-serif;
                        transition:opacity .15s" id="reviewSubmitBtn">
                    Submit Review
                </button>
                <div id="reviewMsg" style="margin-top:8px;font-size:12px;font-weight:700;text-align:center;display:none"></div>
            </div>

            {{-- ── REVIEWS LIST ── --}}
            <div id="reviews-list" style="display:flex;flex-direction:column;gap:10px">
                @forelse($reviews as $review)
                @php
                    $initials = strtoupper(substr($review->reviewer_name, 0, 2));
                    $avatarColors = ['#EEF2FF|#3B5BDB', '#DCFCE7|#15803D', '#FDF2F8|#9D174D', '#FFF7ED|#C2410C', '#F0FDF4|#166534'];
                    [$avatarBg, $avatarColor] = explode('|', $avatarColors[$review->id % count($avatarColors)]);
                @endphp
                <div class="review-item" style="background:#F8FAFF;border-radius:14px;padding:13px;border:1px solid #E0E8FF">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">
                        <div style="width:34px;height:34px;border-radius:50%;background:{{ $avatarBg }};display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;color:{{ $avatarColor }};flex-shrink:0">
                            {{ $initials }}
                        </div>
                        <div style="flex:1">
                            <p style="font-size:13px;font-weight:800;color:#1e293b">{{ ucwords($review->reviewer_name) }}</p>
                            <div style="display:flex;align-items:center;gap:6px">
                                <span style="font-size:11px;color:#FCD34D">
                                    @for($s=1;$s<=5;$s++){{ $s<=$review->rating ? '★' : '☆' }}@endfor
                                </span>
                                @if($review->created_at)
                                <span style="font-size:10px;color:#94a3b8">· {{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($review->comment)
                    <p style="font-size:13px;color:#475569;line-height:1.6">{{ $review->comment }}</p>
                    @endif
                </div>
                @empty
                <div style="text-align:center;padding:30px 20px">
                    <div style="font-size:40px;margin-bottom:8px">💬</div>
                    <p style="font-size:13px;font-weight:800;color:#94a3b8">No reviews yet</p>
                    <p style="font-size:11px;color:#cbd5e1;margin-top:4px">Be the first to share your experience!</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>{{-- end tabs card --}}

    {{-- ── BOTTOM SPIN BANNER ── --}}
    @if($hasSpin)
    <div style="margin:12px 12px 0">
        <a href="{{ url('/spin/' . $shopSlug) }}"
            style="display:block;text-align:center;background:linear-gradient(135deg,#92400e,#b45309,#92400e);
                border-radius:18px;padding:16px;text-decoration:none;position:relative;overflow:hidden">
            <div style="position:absolute;inset:0;background:repeating-linear-gradient(45deg,rgba(255,255,255,0.03) 0,rgba(255,255,255,0.03) 1px,transparent 1px,transparent 8px);pointer-events:none"></div>
            <p class="fd" style="font-size:18px;font-weight:800;color:#fbbf24;margin-bottom:3px">🎡 Try Your Luck!</p>
            <p style="font-size:12px;color:rgba(251,191,36,0.8);font-weight:600">Spin the wheel and win exclusive offers from {{ ucwords($shop->shop_name) }}</p>
        </a>
    </div>
    @endif

</div>

@include('front.partial.img_popup')
@include('front.partial.follow_login')

@endsection

@push('script')
<script>
/* ── Tab switching ── */
function switchTab(btn, tabId) {
    $('.dtab').removeClass('on');
    $(btn).addClass('on');
    $('.tab-content').hide();
    $('#' + tabId).fadeIn(200);
}

/* ── Image thumbnail ── */
function changeImg(el) {
    $('.thumb').removeClass('active');
    $(el).addClass('active');
    const src = $(el).attr('src');
    $('#detailMainImg').fadeOut(150, function() { $(this).attr('src', src).fadeIn(150) });
}

/* ── Service filter ── */
function filter(cat, btn) {
    document.querySelectorAll('.t-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.s-flex-card').forEach(card => {
        if (cat === 'all' || card.getAttribute('data-cat') === cat)
            card.classList.remove('hide');
        else card.classList.add('hide');
    });
}

/* ── Star rating picker ── */
function setRating(val) {
    document.getElementById('selectedRating').value = val;
    document.querySelectorAll('.review-star').forEach((el, i) => {
        el.classList.toggle('active', i < val);
    });
}

/* ── Submit review via AJAX ── */
function submitReview() {
    const rating = parseInt(document.getElementById('selectedRating').value);
    const comment = document.getElementById('reviewComment').value.trim();

    @if(!$isLogged)
    const name  = document.getElementById('reviewerName').value.trim();
    const phone = document.getElementById('reviewerPhone').value.trim();
    @else
    const name  = '{{ $loggedUser->name ?? '' }}';
    const phone = '{{ $loggedUser->phone ?? '' }}';
    @endif

    const msgEl  = document.getElementById('reviewMsg');
    const btnEl  = document.getElementById('reviewSubmitBtn');

    if (rating < 1) { showReviewMsg('Please select a star rating.', '#EF4444'); return; }
    if (!name)      { showReviewMsg('Please enter your name.', '#EF4444'); return; }

    btnEl.disabled = true;
    btnEl.textContent = 'Submitting…';

    $.ajax({
        url: '{{ url("/shop-review/store") }}',
        method: 'POST',
        data: {
            shop_id:       {{ $shop->id }},
            reviewer_name:  name,
            reviewer_phone: phone,
            rating:        rating,
            comment:       comment,
            _token:        '{{ csrf_token() }}'
        },
        success: function(res) {
            if (res.success) {
                showReviewMsg('✅ Review submitted! Thank you.', '#16A34A');
                // Prepend new review card
                const colors = ['#EEF2FF|#3B5BDB','#DCFCE7|#15803D','#FDF2F8|#9D174D','#FFF7ED|#C2410C'];
                const [bg, col] = colors[Math.floor(Math.random() * colors.length)].split('|');
                const initials = name.substring(0, 2).toUpperCase();
                const stars = '★'.repeat(rating) + '☆'.repeat(5 - rating);
                const html = `
                    <div class="review-item" style="background:#F8FAFF;border-radius:14px;padding:13px;border:1px solid #E0E8FF;animation:bounce-in .3s ease both">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">
                            <div style="width:34px;height:34px;border-radius:50%;background:${bg};display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;color:${col};flex-shrink:0">${initials}</div>
                            <div style="flex:1">
                                <p style="font-size:13px;font-weight:800;color:#1e293b">${name}</p>
                                <div style="display:flex;align-items:center;gap:6px">
                                    <span style="font-size:11px;color:#FCD34D">${stars}</span>
                                    <span style="font-size:10px;color:#94a3b8">· just now</span>
                                </div>
                            </div>
                        </div>
                        ${comment ? `<p style="font-size:13px;color:#475569;line-height:1.6">${comment}</p>` : ''}
                    </div>`;
                // Remove empty state if present
                $('#reviews-list').find('[style*="No reviews"]').closest('div').remove();
                $('#reviews-list').prepend(html);
                // Reset form
                document.getElementById('reviewComment').value = '';
                @if(!$isLogged)
                document.getElementById('reviewerName').value = '';
                document.getElementById('reviewerPhone').value = '';
                @endif
                setRating(0);
            } else {
                showReviewMsg(res.message || 'Something went wrong.', '#EF4444');
            }
            btnEl.disabled = false;
            btnEl.textContent = 'Submit Review';
        },
        error: function() {
            showReviewMsg('Server error. Please try again.', '#EF4444');
            btnEl.disabled = false;
            btnEl.textContent = 'Submit Review';
        }
    });
}

function showReviewMsg(text, color) {
    const el = document.getElementById('reviewMsg');
    el.textContent = text;
    el.style.color = color;
    el.style.display = 'block';
    setTimeout(() => { el.style.display = 'none'; }, 4000);
}

</script>
@endpush