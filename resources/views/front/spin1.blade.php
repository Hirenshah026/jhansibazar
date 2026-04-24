@extends('front_layout.main')

@section('content')
    @push('css_or_link')
        <style>
            .tracking-tighter {
                letter-spacing: 1px !important;
            }

            /* ── Follow / Review Modal Styles ── */
            .fr-modal-overlay {
                position: fixed; inset: 0; z-index: 60;
                background: rgba(0,0,0,0.75); backdrop-filter: blur(6px);
                display: flex; align-items: flex-end; justify-content: center;
                bottom: 30px;
            }
            .fr-modal-sheet {
                background: #fff; border-radius: 24px 24px 0 0;
                width: 100%; max-width: 480px; padding: 20px 20px 36px;
                animation: slideUp .3s cubic-bezier(.22,1,.36,1) both;
            }
            @keyframes slideUp {
                from { transform: translateY(100%); opacity: 0 }
                to   { transform: translateY(0);    opacity: 1 }
            }
            .fr-pill-btn {
                width: 100%; padding: 14px; border: none; border-radius: 14px;
                font-size: 15px; font-weight: 800; cursor: pointer;
                font-family: inherit; transition: all .15s; letter-spacing: -0.3px;
            }
            .fr-pill-btn:active { transform: scale(0.97); }
            .fr-pill-btn:disabled { opacity: 0.6; cursor: not-allowed; }
            .review-star-pick { font-size: 32px; cursor: pointer; color: #e2e8f0; transition: color .15s; }
            .review-star-pick.lit { color: #FCD34D; }
            .fr-input {
                width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px;
                padding: 11px 14px; font-size: 14px; font-family: inherit;
                color: #1e293b; outline: none; background: #f8faff;
                transition: border-color .15s;
            }
            .fr-input:focus { border-color: #3B5BDB; background: #fff; }
            .fr-textarea {
                width: 100%; border: 1.5px solid #e2e8f0; border-radius: 12px;
                padding: 11px 14px; font-size: 14px; font-family: inherit;
                color: #1e293b; outline: none; background: #f8faff; resize: none;
                transition: border-color .15s;
            }
            .fr-textarea:focus { border-color: #3B5BDB; background: #fff; }

            /* Sheet header with close */
            .sheet-header {
                display: flex; align-items: flex-start; justify-content: space-between;
                margin-bottom: 16px;
            }
            .sheet-close-btn {
                width: 32px; height: 32px; border-radius: 50%; background: #f1f5f9;
                border: none; cursor: pointer; display: flex; align-items: center;
                justify-content: center; font-size: 16px; color: #64748b;
                flex-shrink: 0; transition: background .15s; line-height: 1;
            }
            .sheet-close-btn:hover { background: #e2e8f0; }

            /* Bonus cards */
            .bonus-card {
                background: #fff; border: 1px solid #e8eeff;
                border-radius: 18px; padding: 14px 14px;
                display: flex; align-items: center; gap: 12px;
                cursor: pointer; transition: all .15s;
                box-shadow: 0 1px 4px rgba(0,0,0,0.05);
            }
            .bonus-card:active { transform: scale(0.97); background: #f8faff; }
            .bonus-icon-wrap {
                width: 48px; height: 48px; border-radius: 14px;
                display: flex; align-items: center; justify-content: center;
                font-size: 24px; flex-shrink: 0;
            }
            .bonus-card-title { font-size: 13px; font-weight: 800; color: #1e293b; }
            .bonus-card-sub   { font-size: 11px; font-weight: 700; margin-top: 2px; }
            .bonus-card-arrow {
                margin-left: auto; width: 28px; height: 28px; border-radius: 8px;
                background: #f1f5f9; display: flex; align-items: center; justify-content: center;
                flex-shrink: 0;
            }

            /* Stats row */
            .stats-row {
                display: flex; gap: 8px; width: 100%; margin: 10px 0 12px;
            }
            .stat-pill {
                flex: 1; border-radius: 16px; padding: 10px 8px; text-align: center;
            }
            .stat-pill .stat-val {
                font-size: 20px; font-weight: 800; display: block;
            }
            .stat-pill .stat-lbl {
                font-size: 10px; font-weight: 700; display: block;
                margin-top: 2px; text-transform: uppercase; letter-spacing: 0.05em;
            }
            .coins-pill { background: #fffbeb; border: 1px solid #fcd34d; }
            .coins-pill .stat-val { color: #b45309; }
            .coins-pill .stat-lbl { color: #d97706; }
            .spins-pill { background: #fff7ed; border: 1px solid #fdba74; }
            .spins-pill .stat-val { color: #c2410c; }
            .spins-pill .stat-lbl { color: #ea580c; }

            /* Toast */
            .spin-toast {
                position: fixed; bottom: 80px; left: 50%; transform: translateX(-50%);
                background: #1e293b; color: #fff; padding: 10px 20px;
                border-radius: 20px; font-size: 13px; font-weight: 700;
                z-index: 999; opacity: 0; transition: opacity .3s;
                white-space: nowrap; max-width: 90vw; text-align: center;
                pointer-events: none;
            }
        </style>
    @endpush

    @php
        date_default_timezone_set('Asia/Kolkata');
        $currentTime = date('H:i:s');

        $openTime  = $shop->open_time  ?? '09:00:00';
        $closeTime = $shop->close_time ?? '21:00:00';

        $isShopOpen = $currentTime >= $openTime && $currentTime <= $closeTime;
        $rawOffers  = isset($shop) ? json_decode($shop->offers, true) : [];
        $dbOffers   = array_filter(is_array($rawOffers) ? $rawOffers : [], function ($val) {
            return isset($val['text']) && !empty(trim((string) $val['text']));
        });

        if (empty($dbOffers)) {
            $dbOffers = ['20% OFF', '+15 COINS', 'FREE POLISH', 'TRY AGAIN', 'BUY 2 GET 1', '+10 COINS'];
        }

        $colors = ['#8b0000', '#1a237e', '#1b5e20', '#3e2723', '#212121', '#DC2626'];
        $emojis = ['👟', '🪙', '✨', '😅', '🎁', '🪙'];

        $finalSegments = [];
        $i = 0;
        foreach ($dbOffers as $offer) {
            $finalSegments[] = [
                'label' => strtoupper(trim((string) ($offer['text'] ?? 'OFFER'))),
                'sub'   => 'Limited Offer',
                'color' => $colors[$i % 6],
                'emoji' => $emojis[$i % 6],
            ];
            $i++;
        }

        $loggedUser  = Session::get('public_user');
        $isLogged    = Session::has('public_user') && $loggedUser;
        $isFollowing = $isFollowed ?? false;
    @endphp

    <div id="screen-spin" class="screen active fade-up pb-24">

        {{-- TOP BAR --}}
        <div class="flex items-center gap-3 px-4 py-3 bg-white border-b border-ink-100 sticky top-0 z-10">
            <button onclick="window.history.back()" class="w-9 h-9 rounded-xl bg-ink-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-ink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M15 18l-6-6 6-6" />
                </svg>
            </button>
            <div class="flex-1">
                <p class="font-display font-bold text-ink-800 text-sm">{{ ucwords($shop->shop_name ?? 'Jhansi Bazaar') }}</p>
                <p class="text-xs text-ink-400">Spin karo — Lucky offer jeeto!</p>
            </div>
        </div>

        {{-- WHEEL --}}
        <div class="flex flex-col items-center px-4 pt-5">

            {{-- Pointer --}}
            <div class="w-0 h-0 border-l-[14px] border-r-[14px] border-t-[24px] border-l-transparent border-r-transparent border-t-saffron-500 z-10 relative drop-shadow"></div>

            <canvas id="mainWheel" width="290" height="290" class="wheel-canvas rounded-full" onclick="triggerSpin()"></canvas>

            <p class="text-sm text-ink-400 mt-3 text-center" id="spinStatusMsg">Wheel pe tap karo!</p>

            {{-- Stats Row --}}
            <div class="stats-row">
                <div class="stat-pill coins-pill">
                    <span class="stat-val" id="coinsDisplay">🪙 {{ $userCoins ?? 340 }}</span>
                    <span class="stat-lbl">Coins</span>
                </div>
                <div class="stat-pill spins-pill">
                    <span class="stat-val" id="spinsCount">{{ $shop->spins_left ?? 1 }}</span>
                    <span class="stat-lbl">Spins Bacha</span>
                </div>
            </div>

            <button id="spinMainBtn" onclick="triggerSpin()"
                class="w-full gradient-brand text-white font-display font-bold text-xl rounded-2xl py-4 shadow-lg active:scale-95 transition-transform mb-4">
                SPIN NOW 🎡
            </button>

            {{-- BONUS CARDS --}}
            <div style="display:flex; flex-direction:column; gap:10px; width:100%; margin-bottom:80px;">

                {{-- Follow Card --}}
                <div class="bonus-card" onclick="openFollowSheet()" id="followBonusCard">
                    <div class="bonus-icon-wrap" style="background:#DCFCE7;">❤️</div>
                    <div style="flex:1; min-width:0;">
                        <p class="bonus-card-title" id="followBonusTitle">Follow Karein</p>
                        <p class="bonus-card-sub" style="color:#15803D;" id="followBonusSub">Follow karo → <strong>+1 Free Spin</strong></p>
                    </div>
                    <div class="bonus-card-arrow">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                    </div>
                </div>

                {{-- Review Card --}}
                <div class="bonus-card" onclick="openReviewSheet()" id="reviewBonusCard">
                    <div class="bonus-icon-wrap" style="background:#FDF2F8;">⭐</div>
                    <div style="flex:1; min-width:0;">
                        <p class="bonus-card-title" id="reviewBonusTitle">Review Likhein</p>
                        <p class="bonus-card-sub" style="color:#9D174D;" id="reviewBonusSub"><strong>+2 Spins</strong> + <strong>₹10</strong> milega</p>
                    </div>
                    <div class="bonus-card-arrow">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                    </div>
                </div>

            </div>
        </div>

        <div id="winModal"
            class="hidden fixed inset-0 z-50 px-4 bg-black/80 backdrop-blur-sm pt-20"
            style="display:flex; align-items:normal; justify-content:center;">
            <div class="relative w-full max-w-sm">

                {{-- Close Button --}}
                <button onclick="justCloseWin()"
                    style="position:absolute; top:-14px; right:0; width:36px; height:36px; border-radius:50%;
                           background:#fff; border:none; cursor:pointer; font-size:18px; font-weight:900;
                           color:#1e293b; display:flex; align-items:center; justify-content:center;
                           box-shadow:0 2px 8px rgba(0,0,0,0.4); z-index:10; line-height:1;">✕</button>

                <div class="text-center mb-4" style="animation: winBounce 1s infinite;">
                    <span class="text-6xl drop-shadow-lg">🎉</span>
                </div>
                <div style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
                            border: 1px solid rgba(255,255,255,0.12);
                            box-shadow: 0 0 60px rgba(163,230,53,0.2), 0 25px 50px rgba(0,0,0,0.6);
                            border-radius: 2rem; overflow: hidden; position: relative;">
                    <div style="height:3px; background: linear-gradient(90deg, transparent, #bef264, #ffd700, #bef264, transparent);"></div>
                    <div class="p-6">
                        <div class="flex justify-center mb-4">
                            <div style="background: rgba(190,242,100,0.1); border: 1px solid rgba(190,242,100,0.35); border-radius:999px; padding: 4px 16px; display:inline-flex; align-items:center; gap:6px;">
                                <span style="width:7px;height:7px;background:#bef264;border-radius:50%;display:inline-block;box-shadow:0 0 6px #bef264;"></span>
                                <span style="color:#bef264;font-size:9px;font-weight:900;letter-spacing:0.2em;text-transform:uppercase;">Jackpot Unlocked</span>
                            </div>
                        </div>
                        <div class="text-center mb-5">
                            <h2 style="font-size:2.2rem;font-weight:900;color:#fff;line-height:1;letter-spacing:-1px;">
                                Badhai <span style="color:#bef264;font-style:italic;">Ho!</span>
                            </h2>
                            <p style="color:#94a3b8;font-size:11px;margin-top:6px;font-weight:600;letter-spacing:0.1em;">AAPKA LUCKY PRIZE MILA</p>
                        </div>
                        <div style="background:#fff; border-radius:1.25rem; padding:2px; margin-bottom:20px; position:relative; overflow:hidden;">
                            <div style="position:absolute;top:50%;left:0;right:0;border-top:2px dashed #e2e8f0;z-index:0;"></div>
                            <div style="position:absolute;left:-12px;top:50%;transform:translateY(-50%);width:24px;height:24px;background:#1e1b4b;border-radius:50%;z-index:2;"></div>
                            <div style="position:absolute;right:-12px;top:50%;transform:translateY(-50%);width:24px;height:24px;background:#1e1b4b;border-radius:50%;z-index:2;"></div>
                            <div style="position:relative;z-index:1;padding:20px 24px;text-align:center;">
                                <p style="color:#64748b;font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:0.15em;margin-bottom:8px;">🏷️ Aapka Prize</p>
                                <p style="font-size:1.6rem;font-weight:900;color:#0f172a;letter-spacing:-0.5px;line-height:1.2;" id="winPrizeText">---</p>
                                <div style="margin-top:10px;display:flex;justify-content:center;align-items:center;gap:8px;">
                                    <span style="font-size:9px;color:#94a3b8;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;">Shop pe dikhayein</span>
                                    <span style="width:4px;height:4px;background:#cbd5e1;border-radius:50%;display:inline-block;"></span>
                                    <span style="font-size:9px;color:#ef4444;font-weight:800;text-transform:uppercase;">Limited Time</span>
                                </div>
                            </div>
                        </div>
                        <button onclick="closeWin()"
                            style="width:100%;background:linear-gradient(135deg,#bef264,#84cc16);color:#14532d;font-weight:900;font-size:1.1rem;border:none;border-radius:1rem;padding:18px;cursor:pointer;box-shadow:0 6px 0 #4d7c0f;transition:all 0.1s;letter-spacing:-0.3px;"
                            onmousedown="this.style.boxShadow='0 2px 0 #4d7c0f';this.style.transform='translateY(4px)'"
                            onmouseup="this.style.boxShadow='0 6px 0 #4d7c0f';this.style.transform='translateY(0)'">
                            CLAIM REWARD 🙌
                            <div style="font-size:9px;opacity:0.6;font-weight:700;letter-spacing:0.15em;margin-top:2px;">SHOP PE JAAKE REDEEM KAREIN</div>
                        </button>
                        <div style="margin-top:16px;text-align:center;">
                            <p style="font-size:10px;color:#475569;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;cursor:pointer;" onclick="closeWin()">
                                💾 Save to Wallet
                            </p>
                        </div>
                    </div>
                    <div style="height:2px; background: linear-gradient(90deg, transparent, #ffd700, transparent);"></div>
                </div>
                <div class="text-center mt-5" style="opacity:0.35;">
                    <span style="color:#fff;font-weight:900;font-size:14px;font-style:italic;letter-spacing:-0.5px;">LiSTee.org</span>
                </div>
            </div>
        </div>

        {{-- FOLLOW BOTTOM SHEET --}}
        <div id="followSheet" class="fr-modal-overlay" style="display:none;" onclick="sheetBgClose(event,'followSheet')">
            <div class="fr-modal-sheet" onclick="event.stopPropagation()">
                <div style="width:40px;height:4px;background:#e2e8f0;border-radius:4px;margin:0 auto 16px;"></div>

                <div class="sheet-header">
                    <div>
                        <div style="font-size:40px;margin-bottom:6px;">❤️</div>
                        <h3 style="font-size:20px;font-weight:900;color:#1e293b;letter-spacing:-0.5px;">Shop Follow Karein</h3>
                        <p style="font-size:13px;color:#64748b;margin-top:4px;font-weight:600;">Follow karo aur <span style="color:#3B5BDB;font-weight:800;">1 Free Spin</span> pao!</p>
                    </div>
                    <button class="sheet-close-btn" onclick="closeSheet('followSheet')">✕</button>
                </div>

                <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:16px;">
                    <input class="fr-input" id="followPhone" type="tel" placeholder="Mobile number">
                    <input class="fr-input" id="followName" type="text" placeholder="Aapka naam (optional)">
                    @if($isLogged)
                    {{-- Pre-fill hidden for logged users, shown fields stay for confirm --}}
                    @endif
                </div>

                <div id="followMsg" style="display:none;padding:10px 14px;border-radius:12px;font-size:13px;font-weight:700;text-align:center;margin-bottom:12px;"></div>

                <button class="fr-pill-btn" id="followSheetBtn" onclick="submitFollow()"
                    style="background:linear-gradient(135deg,#3B5BDB,#5C7CFA);color:#fff;margin-bottom:10px;">
                    ❤️ Follow &amp; Get 1 Spin
                </button>
                <button class="fr-pill-btn" onclick="closeSheet('followSheet')"
                    style="background:#F1F5F9;color:#64748b;font-size:13px;padding:12px;">
                    Baad mein
                </button>
            </div>
        </div>

        {{-- REVIEW BOTTOM SHEET --}}
        <div id="reviewSheet" class="fr-modal-overlay" style="display:none;" onclick="sheetBgClose(event,'reviewSheet')">
            <div class="fr-modal-sheet" onclick="event.stopPropagation()">
                <div style="width:40px;height:4px;background:#e2e8f0;border-radius:4px;margin:0 auto 16px;"></div>

                <div class="sheet-header">
                    <div>
                        <div style="font-size:40px;margin-bottom:6px;">⭐</div>
                        <h3 style="font-size:20px;font-weight:900;color:#1e293b;letter-spacing:-0.5px;">Review Likhein</h3>
                        <p style="font-size:13px;color:#64748b;margin-top:4px;font-weight:600;">Review do → <span style="color:#9D174D;font-weight:800;">+2 Spins + ₹10</span> milega!</p>
                    </div>
                    <button class="sheet-close-btn" onclick="closeSheet('reviewSheet')">✕</button>
                </div>

                {{-- Star picker --}}
                <div style="display:flex;justify-content:center;gap:6px;margin-bottom:14px;" id="reviewStarRow">
                    @for($s = 1; $s <= 5; $s++)
                    <span class="review-star-pick" data-val="{{ $s }}" onclick="setReviewRating({{ $s }})">★</span>
                    @endfor
                </div>
                <input type="hidden" id="reviewRatingVal" value="0">

                <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:10px;">
                    <input class="fr-input" id="reviewerPhoneSpin" type="tel" placeholder="Mobile number">
                    <input class="fr-input" id="reviewerNameSpin" type="text" placeholder="Aapka naam (optional)">
                </div>

                <textarea class="fr-textarea" id="reviewCommentSpin" rows="3"
                    placeholder="Shop ke baare mein apna anubhav likhein... (optional)"
                    style="margin-bottom:10px;"></textarea>

                <div id="reviewSheetMsg" style="display:none;padding:10px 14px;border-radius:12px;font-size:13px;font-weight:700;text-align:center;margin-bottom:12px;"></div>

                <button class="fr-pill-btn" id="reviewSheetBtn" onclick="submitSpinReview()"
                    style="background:linear-gradient(135deg,#7C3AED,#9D174D);color:#fff;margin-bottom:10px;">
                    ⭐ Review Submit Karein
                </button>
                <button class="fr-pill-btn" onclick="closeSheet('reviewSheet')"
                    style="background:#F1F5F9;color:#64748b;font-size:13px;padding:12px;">
                    Baad mein
                </button>
            </div>
        </div>

        {{-- TOAST --}}
        <div class="spin-toast" id="spinToast"></div>

    </div>

    @include('front.partial.spin_popup')

    <style>
        @keyframes winBounce {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-12px); }
        }
    </style>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script>
        const isLoggedIn    = {{ Session::has('public_user') ? 'true' : 'false' }};
        const isShopOpen    = {{ $isShopOpen ? 'true' : 'false' }};
        const shopOpenTime  = "{{ \Carbon\Carbon::parse($openTime)->format('h:i A') }}";
        const shopCloseTime = "{{ \Carbon\Carbon::parse($closeTime)->format('h:i A') }}";
        const segments      = {!! json_encode($finalSegments) !!};
        const shopId        = {{ $shop->id }};

        let curAngle        = 0;
        let isSpinning      = false;
        let spinsLeft       = {{ $shop->spins_left ?? 1 }};
        let userCoins       = {{ $userCoins ?? 340 }};
        let alreadyFollowed = {{ $isFollowing ? 'true' : 'false' }};
        let reviewDone      = false;
        let currentReviewRating = 0;

        /* ─── Toast Helper ─── */
        function showToast(msg) {
            const el = document.getElementById('spinToast');
            el.textContent = msg;
            el.style.opacity = '1';
            clearTimeout(el._t);
            el._t = setTimeout(() => { el.style.opacity = '0'; }, 2800);
        }

        /* ─── Sheet Message Helper ─── */
        function showSheetMsg(elId, text, isSuccess) {
            const el = document.getElementById(elId);
            if (!el) return;
            el.textContent = text;
            el.style.color      = isSuccess ? '#15803D' : '#991B1B';
            el.style.background = isSuccess ? '#dcfce7' : '#fef2f2';
            el.style.border     = isSuccess ? '1px solid #86efac' : '1px solid #fca5a5';
            el.style.display    = 'block';
        }

        /* ─── Confetti ─── */
        function fireConfetti() {
            const count = 200;
            const defaults = { origin: { y: 0.7 }, colors: ['#ff0000','#ffd700','#ffffff','#bef264'] };
            function fire(r, opts) {
                confetti({ ...defaults, ...opts, particleCount: Math.floor(count * r) });
            }
            fire(0.25, { spread: 26, startVelocity: 55 });
            fire(0.2,  { spread: 60 });
            fire(0.35, { spread: 100, decay: 0.91, scalar: 0.8 });
            fire(0.1,  { spread: 120, startVelocity: 25, decay: 0.92, scalar: 1.2 });
            fire(0.1,  { spread: 120, startVelocity: 45 });
        }

        /* ─── Wheel Draw ─── */
        function drawWheel(rot) {
            const c = document.getElementById('mainWheel');
            if (!c) return;
            const ctx = c.getContext('2d'), cx = 145, cy = 145, r = 136,
                  n = segments.length, arc = (2 * Math.PI) / n;
            ctx.clearRect(0, 0, 290, 290);
            segments.forEach((seg, i) => {
                const s = rot + i * arc - Math.PI / 2, e = s + arc;
                ctx.beginPath(); ctx.moveTo(cx, cy);
                ctx.arc(cx, cy, r, s, e);
                ctx.fillStyle = seg.color; ctx.fill();
                ctx.strokeStyle = '#fff'; ctx.lineWidth = 2; ctx.stroke();
                ctx.save(); ctx.translate(cx, cy); ctx.rotate(s + arc / 2);
                ctx.textAlign = 'right'; ctx.fillStyle = '#fff';
                ctx.font = 'bold 11px sans-serif';
                ctx.fillText(seg.label.substring(0, 15), r - 10, 4);
                ctx.restore();
            });
            ctx.beginPath(); ctx.arc(cx, cy, 24, 0, 2 * Math.PI);
            ctx.fillStyle = '#fff'; ctx.fill();
            ctx.fillStyle = '#FF6B35'; ctx.font = 'bold 14px sans-serif';
            ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
            ctx.fillText('JB', cx, cy);
        }

        /* ─── UI Update ─── */
        function updateSpinUI() {
            const coinsEl = document.getElementById('coinsDisplay');
            const spinsEl = document.getElementById('spinsCount');
            if (coinsEl) coinsEl.textContent = `🪙 ${userCoins}`;
            if (spinsEl) spinsEl.textContent  = spinsLeft;
            const btn = document.getElementById('spinMainBtn');
            if (btn && !isSpinning) {
                btn.textContent = spinsLeft > 0 ? 'SPIN NOW 🎡' : 'Spins Khatam 😅';
            }
        }

        /* ─── Spin Logic ─── */
        function triggerSpin() {
            if (isSpinning) return;
            if (!isShopOpen) {
                Swal.fire({
                    icon: 'error', title: 'Shop Closed Hai!',
                    html: `Maaf kijiye, spin sirf shop timing ke beech chalta hai.<br><br><b>Timing:</b> ${shopOpenTime} - ${shopCloseTime}`,
                    confirmButtonColor: '#3085d6', confirmButtonText: 'Theek hai'
                });
                return;
            }
            if (spinsLeft <= 0) {
                Swal.fire({
                    icon: 'warning', title: 'Spins Khatam!',
                    text: 'Neeche diye bonus cards se extra spin kamaayein.',
                    confirmButtonColor: '#FF6B35'
                });
                return;
            }
            if (!localStorage.getItem('user_mobile')) {
                $('#spinPopup').removeClass('hidden');
                return;
            }
            doSpin();
        }

        function doSpin() {
            isSpinning = true;
            updateSpinUI();
            const btn = document.getElementById('spinMainBtn');
            btn.disabled = true; btn.textContent = '🌀 Spinning...';
            document.getElementById('spinStatusMsg').textContent = '🎡 Wheel ghum raha hai...';

            const extra  = 7 + Math.floor(Math.random() * 5);
            const rand   = Math.random() * 2 * Math.PI;
            const target = curAngle + (extra * 2 * Math.PI) + rand;
            const dur = 3500, t0 = performance.now(), a0 = curAngle;

            function frame(now) {
                const p    = Math.min((now - t0) / dur, 1);
                const ease = 1 - Math.pow(1 - p, 4);
                curAngle   = a0 + (target - a0) * ease;
                drawWheel(curAngle);
                if (p < 1) {
                    requestAnimationFrame(frame);
                } else {
                    isSpinning    = false;
                    btn.disabled  = false;
                    document.getElementById('spinStatusMsg').textContent = spinsLeft > 0 ? 'Phir se khelo!' : 'Bonus se aur spin pao!';
                    updateSpinUI();
                    showWin(curAngle);
                }
            }
            requestAnimationFrame(frame);
        }

        function showWin(angle) {
            const n    = segments.length, arc = (2 * Math.PI) / n;
            const norm = ((-angle % (2 * Math.PI)) + (2 * Math.PI)) % (2 * Math.PI);
            const idx  = Math.floor(norm / arc) % n;
            const seg  = segments[idx];
            document.getElementById('winPrizeText').textContent = seg.label;
            document.getElementById('winModal').classList.remove('hidden');
            fireConfetti();
        }

        function closeWin() {
            document.getElementById('winModal').classList.add('hidden');
            fireConfetti();
            if (isLoggedIn) {
                $.ajax({
                    url: '{{ route('spin.decrement') }}',
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        spinsLeft = res.spinsLeft;
                        updateSpinUI();
                    },
                    error: function() {
                        // Silent — UI already updated locally
                        updateSpinUI();
                    }
                });
            }
        }

        function justCloseWin() {
            document.getElementById('winModal').classList.add('hidden');
        }

        /* ─── Sheet Open / Close ─── */
        function openFollowSheet() {
            if (alreadyFollowed) {
                showToast('Aapne pehle se follow kar rakha hai! ✅');
                return;
            }
            document.getElementById('followSheet').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function openReviewSheet() {
            if (reviewDone) {
                showToast('Review pehle se de diya! +2 spins aur ₹10 mila. 🎉');
                return;
            }
            document.getElementById('reviewSheet').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeSheet(id) {
            document.getElementById(id).style.display = 'none';
            document.body.style.overflow = '';
        }

        function sheetBgClose(e, id) {
            if (e.target === document.getElementById(id)) closeSheet(id);
        }

        /* ─── Star Rating ─── */
        function setReviewRating(val) {
            currentReviewRating = val;
            document.getElementById('reviewRatingVal').value = val;
            document.querySelectorAll('.review-star-pick').forEach((el, i) => {
                el.classList.toggle('lit', i < val);
            });
        }

        /* ─── Submit Follow ─── */
        function submitFollow() {
            const phone = document.getElementById('followPhone').value.trim();

            @if($isLogged)
            const finalPhone = '{{ $loggedUser->id ?? "0" }}';
            @else
            const finalPhone = phone;
            @endif

            if (!finalPhone) {
                showSheetMsg('followMsg', 'Mobile number zaroori hai.', false);
                return;
            }

            const btn = document.getElementById('followSheetBtn');
            btn.disabled = true; btn.textContent = 'Following...';
            @if($isLogged)
                const data = {
                    shopId: shopId,
                    user_id:   finalPhone,
                    _token:  '{{ csrf_token() }}'
                }
            @else
                const data = {
                    shopId: shopId,
                    phone:   finalPhone,
                    _token:  '{{ csrf_token() }}'
                }
            @endif
            $.ajax({
                url: '{{ url("/follow-user") }}',
                method: 'POST',
                data: data,
                success: function(res) {
                    alreadyFollowed = true;
                    spinsLeft++;
                    updateSpinUI();
                    fireConfetti();
                    showSheetMsg('followMsg', '✅ Followed! +1 Spin mila!', true);
                    const card = document.getElementById('followBonusCard');
                    if (card) {
                        card.style.opacity = '0.6';
                        card.style.pointerEvents = 'none';
                        document.getElementById('followBonusTitle').textContent = '✅ Followed!';
                        document.getElementById('followBonusSub').textContent   = '+1 Spin added';
                    }
                    setTimeout(() => closeSheet('followSheet'), 1800);
                },
                error: function() {
                    showToast('Kuch ho gaya 🙏 dobara koshish karein');
                    btn.disabled = false; btn.textContent = '❤️ Follow & Get 1 Spin';
                }
            });
        }

        /* ─── Submit Review ─── */
        function submitSpinReview() {
            const phone   = document.getElementById('reviewerPhoneSpin').value.trim();
            const name    = document.getElementById('reviewerNameSpin').value.trim();
            const rating  = currentReviewRating;
            const comment = document.getElementById('reviewCommentSpin').value.trim();

            @if($isLogged)
            const finalName  = name  || '{{ $loggedUser->name  ?? "" }}';
            const finalPhone = phone || '{{ $loggedUser->phone ?? "" }}';
            @else
            const finalName  = name;
            const finalPhone = phone;
            @endif

            if (rating < 1) {
                showSheetMsg('reviewSheetMsg', 'Star rating zaroor chunein.', false);
                return;
            }
            if (!finalPhone) {
                showSheetMsg('reviewSheetMsg', 'Mobile number zaroori hai.', false);
                return;
            }

            const btn = document.getElementById('reviewSheetBtn');
            btn.disabled = true; btn.textContent = 'Submitting...';

            $.ajax({
                url: '{{ url("/shop-review/store") }}',
                method: 'POST',
                data: {
                    shop_id:        shopId,
                    reviewer_name:  finalName,
                    reviewer_phone: finalPhone,
                    rating:         rating,
                    comment:        comment,
                    _token:         '{{ csrf_token() }}'
                },
                success: function(res) {
                    if (res.success) {
                        reviewDone  = true;
                        spinsLeft  += 2;
                        userCoins  += 10;
                        updateSpinUI();
                        fireConfetti();
                        showSheetMsg('reviewSheetMsg', '🎉 +2 Spins & ₹10 mila! Shukriya!', true);
                        const rcard = document.getElementById('reviewBonusCard');
                        if (rcard) {
                            rcard.style.opacity = '0.6';
                            rcard.style.pointerEvents = 'none';
                            document.getElementById('reviewBonusTitle').textContent = '✅ Reviewed!';
                            document.getElementById('reviewBonusSub').textContent   = '+2 Spins + ₹10 added';
                        }
                        setTimeout(() => closeSheet('reviewSheet'), 2000);
                    } else {
                        showSheetMsg('reviewSheetMsg', res.message || 'Kuch ho gaya, dobara try karein.', false);
                        btn.disabled = false; btn.textContent = '⭐ Review Submit Karein';
                    }
                },
                error: function() {
                    showToast('Kuch ho gaya 🙏 dobara koshish karein');
                    btn.disabled = false; btn.textContent = '⭐ Review Submit Karein';
                }
            });
        }

        /* ─── Init ─── */
        setTimeout(() => {
            drawWheel(0);
            updateSpinUI();

            if (alreadyFollowed) {
                const card = document.getElementById('followBonusCard');
                if (card) {
                    card.style.opacity = '0.6';
                    card.style.pointerEvents = 'none';
                    document.getElementById('followBonusTitle').textContent = '✅ Followed!';
                    document.getElementById('followBonusSub').textContent   = 'Already followed this shop';
                }
            }

            if (localStorage.getItem('user_mobile')) {
                setTimeout(() => {
                    document.getElementById('spinStatusMsg').textContent = '🎉 Spin ho raha hai...';
                    triggerSpin();
                }, 800);
            }
        }, 100);
    </script>
@endpush