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
            }
            .fr-modal-sheet {
                background: #fff; border-radius: 24px 24px 0 0;
                width: 100%; max-width: 480px; padding: 24px 20px 36px;
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

            /* Followed state */
            .followed-badge {
                display: inline-flex; align-items: center; gap: 5px;
                background: #dcfce7; color: #15803d; font-size: 11px; font-weight: 800;
                padding: 6px 12px; border-radius: 20px; border: 1px solid #86efac;
            }
        </style>
    @endpush
    @php
        date_default_timezone_set('Asia/Kolkata');
        $currentTime = date('H:i:s');

        $openTime = $shop->open_time ?? '09:00:00';
        $closeTime = $shop->close_time ?? '21:00:00';

        $isShopOpen = $currentTime >= $openTime && $currentTime <= $closeTime;
        $rawOffers = isset($shop) ? json_decode($shop->offers, true) : [];
        $dbOffers = array_filter(is_array($rawOffers) ? $rawOffers : [], function ($val) {
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
                'sub' => 'Limited Offer',
                'color' => $colors[$i % 6],
                'emoji' => $emojis[$i % 6],
            ];
            $i++;
        }

        $loggedUser = Session::get('public_user');
        $isLogged   = Session::has('public_user') && $loggedUser;
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
            <div class="flex items-center gap-1 bg-saffron-50 border border-saffron-200 rounded-full px-2.5 py-1">
                <span class="text-xs font-bold text-saffron-600" id="spinsLeftBadge">1 spin</span>
            </div>
        </div>

        {{-- WHEEL --}}
        <div class="flex flex-col items-center px-4 pt-5">
            <div
                class="w-0 h-0 border-l-[14px] border-r-[14px] border-t-[24px] border-l-transparent border-r-transparent border-t-saffron-500 z-10 relative drop-shadow">
            </div>
            <canvas id="mainWheel" width="290" height="290" class="wheel-canvas rounded-full"
                onclick="triggerSpin()"></canvas>

            <p class="text-sm text-ink-400 mt-3 text-center" id="spinStatusMsg">Wheel pe tap karo!</p>

            <div class="flex items-center gap-2 mt-2 mb-4">
                <div class="flex items-center gap-1.5 bg-gold-50 border border-gold-200 rounded-full px-3 py-1">
                    <span class="text-xs font-bold text-gold-700" id="coinsDisplay">🪙 {{ $userCoins ?? 340 }} coins</span>
                </div>
                <div class="flex items-center gap-1.5 bg-saffron-50 border border-saffron-200 rounded-full px-3 py-1">
                    <span class="text-xs font-bold text-saffron-600" id="spinsCount"><?= $shop->spins_left ?> spin bacha</span>
                </div>
            </div>

            <button id="spinMainBtn" onclick="triggerSpin()"
                class="w-full gradient-brand text-white font-display font-bold text-xl rounded-2xl py-4 shadow-lg active:scale-95 transition-transform mb-4">
                SPIN NOW 🎡
            </button>

            {{-- ── BONUS CARDS ── --}}
            <div style="display:flex; flex-direction:column; gap:10px; width:100%; margin-bottom:8px;">

                {{-- Card 1: Add 1 Spin --}}
                <div class="bonus-card" onclick="earnBonus('addspin')" id="addSpinCard">
                    <div class="bonus-icon-wrap" style="background:#EEF2FF;">🎡</div>
                    <div style="flex:1; min-width:0;">
                        <p class="bonus-card-title">Add 1 Spin</p>
                        <p class="bonus-card-sub" style="color:#3B5BDB;">Aap seedha 1 spin kharid sakte hain</p>
                    </div>
                    <div class="bonus-card-arrow">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                    </div>
                </div>

                {{-- Card 2: Get 10 Rs + 1 Spin --}}
                <div class="bonus-card" onclick="earnBonus('reward')" id="rewardCard">
                    <div class="bonus-icon-wrap" style="background:#FFFBEB;">🎁</div>
                    <div style="flex:1; min-width:0;">
                        <p class="bonus-card-title">Get ₹10 + 1 Spin</p>
                        <p class="bonus-card-sub" style="color:#B45309;">Special bonus — claim karein!</p>
                    </div>
                    <div class="bonus-card-arrow">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                    </div>
                </div>

                {{-- Card 3: Follow → 1 Spin --}}
                <div class="bonus-card" onclick="openFollowSheet()" id="followBonusCard">
                    <div class="bonus-icon-wrap" style="background:#DCFCE7;">❤️</div>
                    <div style="flex:1; min-width:0;">
                        <p class="bonus-card-title">Follow</p>
                        <p class="bonus-card-sub" style="color:#15803D;">Follow karo → <strong>+1 Free Spin</strong></p>
                    </div>
                    <div id="followBonusArrow" class="bonus-card-arrow">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                    </div>
                </div>

                {{-- Card 4: Review → 2 Spin + 10Rs --}}
                <div class="bonus-card" onclick="openReviewSheet()" id="reviewBonusCard">
                    <div class="bonus-icon-wrap" style="background:#FDF2F8;">⭐</div>
                    <div style="flex:1; min-width:0;">
                        <p class="bonus-card-title">Review</p>
                        <p class="bonus-card-sub" style="color:#9D174D;"><strong>+2 Spins</strong> + <strong>₹10</strong> milega</p>
                    </div>
                    <div class="bonus-card-arrow">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                    </div>
                </div>

            </div>
        </div>

        {{-- ── WIN MODAL ── --}}
        <div id="winModal"
            class="hidden fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/80 backdrop-blur-sm">
            <div class="relative w-full max-w-sm">
                <div class="text-center mb-4 animate-bounce">
                    <span class="text-6xl drop-shadow-lg" id="winEmojiEl">🎉</span>
                </div>
                <div
                    style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
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

        {{-- ── FOLLOW BOTTOM SHEET ── --}}
        <div id="followSheet" class="fr-modal-overlay" style="display:none;" onclick="closeFollowSheet(event)">
            <div class="fr-modal-sheet" onclick="event.stopPropagation()">
                <div style="width:40px;height:4px;background:#e2e8f0;border-radius:4px;margin:0 auto 20px;"></div>
                <div style="text-align:center;margin-bottom:20px;">
                    <div style="font-size:48px;margin-bottom:8px;">❤️</div>
                    <h3 style="font-size:20px;font-weight:900;color:#1e293b;letter-spacing:-0.5px;">Shop Follow Karein</h3>
                    <p style="font-size:13px;color:#64748b;margin-top:5px;font-weight:600;">Follow karo aur <span style="color:#3B5BDB;font-weight:800;">1 Free Spin</span> pao!</p>
                </div>

                @if(!$isLogged)
                <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:16px;">
                    <input class="fr-input" id="followName" type="text" placeholder="Aapka naam">
                    <input class="fr-input" id="followPhone" type="tel" placeholder="Mobile number">
                </div>
                @endif

                <div id="followMsg" style="display:none;padding:10px 14px;border-radius:12px;font-size:13px;font-weight:700;text-align:center;margin-bottom:12px;"></div>

                <button class="fr-pill-btn" id="followSheetBtn" onclick="submitFollow()"
                    style="background:linear-gradient(135deg,#3B5BDB,#5C7CFA);color:#fff;margin-bottom:10px;">
                    ❤️ Follow &amp; Get 1 Spin
                </button>
                <button class="fr-pill-btn" onclick="closeFollowSheet(null)"
                    style="background:#F1F5F9;color:#64748b;font-size:13px;padding:12px;">
                    Baad mein
                </button>
            </div>
        </div>

        {{-- ── REVIEW BOTTOM SHEET ── --}}
        <div id="reviewSheet" class="fr-modal-overlay" style="display:none;" onclick="closeReviewSheet(event)">
            <div class="fr-modal-sheet" onclick="event.stopPropagation()">
                <div style="width:40px;height:4px;background:#e2e8f0;border-radius:4px;margin:0 auto 20px;"></div>
                <div style="text-align:center;margin-bottom:16px;">
                    <div style="font-size:48px;margin-bottom:8px;">⭐</div>
                    <h3 style="font-size:20px;font-weight:900;color:#1e293b;letter-spacing:-0.5px;">Review Likhein</h3>
                    <p style="font-size:13px;color:#64748b;margin-top:5px;font-weight:600;">Review do → <span style="color:#9D174D;font-weight:800;">+2 Spins + ₹10</span> milega!</p>
                </div>

                {{-- Star picker --}}
                <div style="display:flex;justify-content:center;gap:6px;margin-bottom:14px;" id="reviewStarRow">
                    @for($s = 1; $s <= 5; $s++)
                    <span class="review-star-pick" data-val="{{ $s }}" onclick="setReviewRating({{ $s }})">★</span>
                    @endfor
                </div>
                <input type="hidden" id="reviewRatingVal" value="0">

                @if(!$isLogged)
                <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:10px;">
                    <input class="fr-input" id="reviewerNameSpin" type="text" placeholder="Aapka naam">
                    <input class="fr-input" id="reviewerPhoneSpin" type="tel" placeholder="Mobile number">
                </div>
                @endif

                <textarea class="fr-textarea" id="reviewCommentSpin" rows="3" placeholder="Shop ke baare mein apna anubhav likhein..." style="margin-bottom:10px;"></textarea>

                <div id="reviewSheetMsg" style="display:none;padding:10px 14px;border-radius:12px;font-size:13px;font-weight:700;text-align:center;margin-bottom:12px;"></div>

                <button class="fr-pill-btn" id="reviewSheetBtn" onclick="submitSpinReview()"
                    style="background:linear-gradient(135deg,#7C3AED,#9D174D);color:#fff;margin-bottom:10px;">
                    ⭐ Review Submit Karein
                </button>
                <button class="fr-pill-btn" onclick="closeReviewSheet(null)"
                    style="background:#F1F5F9;color:#64748b;font-size:13px;padding:12px;">
                    Baad mein
                </button>
            </div>
        </div>

    </div>

    @include('front.partial.spin_popup')
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script>
        const isLoggedIn  = {{ Session::has('public_user') ? 'true' : 'false' }};
        const isShopOpen  = {{ $isShopOpen ? 'true' : 'false' }};
        const shopOpenTime  = "{{ \Carbon\Carbon::parse($openTime)->format('h:i A') }}";
        const shopCloseTime = "{{ \Carbon\Carbon::parse($closeTime)->format('h:i A') }}";
        const segments    = {!! json_encode($finalSegments) !!};
        const shopId      = {{ $shop->id }};
        let curAngle      = 0;
        let isSpinning    = false;
        let spinsLeft     = {{ $shop->spins_left ?? 1 }};
        let userCoins     = {{ $userCoins ?? 340 }};
        let alreadyFollowed = {{ $isFollowing ? 'true' : 'false' }};
        let reviewDone    = false;

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

        /* ─── Spin Logic ─── */
        function triggerSpin() {
            if (isSpinning) return;
            if (!isShopOpen) {
                Swal.fire({ icon: 'error', title: 'Shop Closed Hai!',
                    html: `Maaf kijiye, spin sirf shop timing ke beech chalta hai.<br><br><b>Timing:</b> ${shopOpenTime} - ${shopCloseTime}`,
                    confirmButtonColor: '#3085d6', confirmButtonText: 'Theek hai' });
                return;
            }
            if (spinsLeft <= 0) {
                Swal.fire({ icon: 'warning', title: 'Spins Khatam!',
                    text: 'Neeche diye bonus cards se extra spin kamaayein.',
                    confirmButtonColor: '#FF6B35' });
                return;
            }
            if (!localStorage.getItem('user_mobile')) {
                $('#spinPopup').removeClass('hidden');
                return;
            }
            doSpin();
        }

        function doSpin() {
            isSpinning = true; updateSpinUI();
            const btn = document.getElementById('spinMainBtn');
            btn.disabled = true; btn.innerHTML = '🌀 Spinning...';
            const extra = 7 + Math.floor(Math.random() * 5);
            const rand  = Math.random() * 2 * Math.PI;
            const target = curAngle + (extra * 2 * Math.PI) + rand;
            const dur = 3500, t0 = performance.now(), a0 = curAngle;
            function frame(now) {
                const p = Math.min((now - t0) / dur, 1);
                const ease = 1 - Math.pow(1 - p, 4);
                curAngle = a0 + (target - a0) * ease;
                drawWheel(curAngle);
                if (p < 1) { requestAnimationFrame(frame); }
                else {
                    isSpinning = false; btn.disabled = false;
                    btn.textContent = spinsLeft > 0 ? 'SPIN AGAIN 🎡' : 'Spins Khatam 😅';
                    showWin(curAngle);
                }
            }
            requestAnimationFrame(frame);
        }

        function showWin(angle) {
            const n = segments.length, arc = (2 * Math.PI) / n;
            const norm = ((-angle % (2 * Math.PI)) + (2 * Math.PI)) % (2 * Math.PI);
            const idx  = Math.floor(norm / arc) % n;
            const seg  = segments[idx];
            const count = 200, defaults = { origin: { y: 0.7 } };
            function fire(r, opts) {
                confetti({ ...defaults, ...opts, particleCount: Math.floor(count * r),
                    colors: ['#ff0000','#ffd700','#ffffff','#bef264'] });
            }
            fire(0.25, { spread: 26, startVelocity: 55 });
            fire(0.2,  { spread: 60 });
            fire(0.35, { spread: 100, decay: 0.91, scalar: 0.8 });
            fire(0.1,  { spread: 120, startVelocity: 25, decay: 0.92, scalar: 1.2 });
            fire(0.1,  { spread: 120, startVelocity: 45 });
            document.getElementById('winPrizeText').textContent = seg.label;
            document.getElementById('winModal').classList.remove('hidden');
        }

        function closeWin() {
            document.getElementById('winModal').classList.add('hidden');
            if (isLoggedIn) {
                $.ajax({
                    url: '{{ route('spin.decrement') }}',
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) { spinsLeft = res.spinsLeft; updateSpinUI(); },
                    error: function() { updateSpinUI(); }
                });
            }
        }

        /* ─── Bonus Cards ─── */
        function earnBonus(type) {
            if (type === 'addspin') {
                Swal.fire({
                    icon: 'info', title: '1 Spin Add Karein',
                    text: 'Yeh feature jald aane wala hai. Tab tak follow ya review karke free spin kamaayein!',
                    confirmButtonColor: '#3B5BDB', confirmButtonText: 'Theek hai'
                });
                return;
            }
            if (type === 'reward') {
                Swal.fire({
                    icon: 'success', title: '🎁 Special Bonus!',
                    html: `<b>₹10 + 1 Spin</b> aapko mila!<br><small style="color:#64748b">Wallet mein add ho gaya</small>`,
                    confirmButtonColor: '#B45309', confirmButtonText: 'Claim!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        spinsLeft++;
                        userCoins += 10;
                        updateSpinUI();
                        updateCoinsDisplay();
                    }
                });
                return;
            }
        }

        function updateSpinUI() {
            const counts = document.getElementById('spinsCount');
            const badge  = document.getElementById('spinsLeftBadge');
            if (counts) counts.textContent = `${spinsLeft} spin bacha`;
            if (badge)  badge.textContent  = `${spinsLeft} spin`;
            const btn = document.getElementById('spinMainBtn');
            if (btn && !isSpinning) btn.textContent = spinsLeft > 0 ? 'SPIN NOW 🎡' : 'Spins Khatam 😅';
        }

        function updateCoinsDisplay() {
            const el = document.getElementById('coinsDisplay');
            if (el) el.textContent = `🪙 ${userCoins} coins`;
        }

        /* ─── Follow Sheet ─── */
        function openFollowSheet() {
            if (alreadyFollowed) {
                Swal.fire({ icon: 'info', title: 'Already Followed!',
                    text: 'Aapne is shop ko pehle se follow kar rakha hai.',
                    confirmButtonColor: '#3B5BDB' });
                return;
            }
            document.getElementById('followSheet').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        function closeFollowSheet(e) {
            if (e && e.target !== document.getElementById('followSheet')) return;
            document.getElementById('followSheet').style.display = 'none';
            document.body.style.overflow = '';
        }

        function submitFollow() {
            const btn = document.getElementById('followSheetBtn');
            const msgEl = document.getElementById('followMsg');

            @if(!$isLogged)
            const name  = document.getElementById('followName').value.trim();
            const phone = document.getElementById('followPhone').value.trim();
            if (!name || !phone) {
                showSheetMsg('followMsg', 'Naam aur number zaroori hai.', '#EF4444');
                return;
            }
            @else
            const name  = '{{ $loggedUser->name ?? "" }}';
            const phone = '{{ $loggedUser->phone ?? "" }}';
            @endif

            btn.disabled = true;
            btn.textContent = 'Following...';

            $.ajax({
                url: '{{ url("/shop/follow") }}',
                method: 'POST',
                data: {
                    shop_id: shopId,
                    name: name,
                    phone: phone,
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    if (res.success) {
                        alreadyFollowed = true;
                        spinsLeft++;
                        updateSpinUI();
                        showSheetMsg('followMsg', '✅ Followed! +1 Spin mila!', '#15803D');
                        // Update follow card UI
                        const card = document.getElementById('followBonusCard');
                        if (card) {
                            card.style.opacity = '0.6';
                            card.style.pointerEvents = 'none';
                            card.querySelector('.bonus-card-title').textContent = '✅ Followed!';
                            card.querySelector('.bonus-card-sub').textContent = '+1 Spin added';
                        }
                        setTimeout(() => {
                            document.getElementById('followSheet').style.display = 'none';
                            document.body.style.overflow = '';
                        }, 1800);
                    } else {
                        showSheetMsg('followMsg', res.message || 'Kuch galat hua.', '#EF4444');
                        btn.disabled = false; btn.textContent = '❤️ Follow & Get 1 Spin';
                    }
                },
                error: function() {
                    showSheetMsg('followMsg', 'Server error. Dobara koshish karein.', '#EF4444');
                    btn.disabled = false; btn.textContent = '❤️ Follow & Get 1 Spin';
                }
            });
        }

        /* ─── Review Sheet ─── */
        let currentReviewRating = 0;

        function openReviewSheet() {
            if (reviewDone) {
                Swal.fire({ icon: 'info', title: 'Review Already Done!',
                    text: 'Aapne pehle se review de diya hai. +2 spins aur ₹10 already mila.',
                    confirmButtonColor: '#7C3AED' });
                return;
            }
            document.getElementById('reviewSheet').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        function closeReviewSheet(e) {
            if (e && e.target !== document.getElementById('reviewSheet')) return;
            document.getElementById('reviewSheet').style.display = 'none';
            document.body.style.overflow = '';
        }

        function setReviewRating(val) {
            currentReviewRating = val;
            document.getElementById('reviewRatingVal').value = val;
            document.querySelectorAll('.review-star-pick').forEach((el, i) => {
                el.classList.toggle('lit', i < val);
            });
        }

        function submitSpinReview() {
            const btn = document.getElementById('reviewSheetBtn');
            const rating  = currentReviewRating;
            const comment = document.getElementById('reviewCommentSpin').value.trim();

            @if(!$isLogged)
            const name  = document.getElementById('reviewerNameSpin').value.trim();
            const phone = document.getElementById('reviewerPhoneSpin').value.trim();
            @else
            const name  = '{{ $loggedUser->name ?? "" }}';
            const phone = '{{ $loggedUser->phone ?? "" }}';
            @endif

            if (rating < 1) { showSheetMsg('reviewSheetMsg', 'Star rating zaroor chunein.', '#EF4444'); return; }
            if (!name)      { showSheetMsg('reviewSheetMsg', 'Naam zaroori hai.', '#EF4444'); return; }

            btn.disabled = true; btn.textContent = 'Submitting...';

            $.ajax({
                url: '{{ url("/shop-review/store") }}',
                method: 'POST',
                data: {
                    shop_id:        shopId,
                    reviewer_name:  name,
                    reviewer_phone: phone,
                    rating:         rating,
                    comment:        comment,
                    _token:         '{{ csrf_token() }}'
                },
                success: function(res) {
                    if (res.success) {
                        reviewDone = true;
                        spinsLeft  += 2;
                        userCoins  += 10;
                        updateSpinUI();
                        updateCoinsDisplay();
                        showSheetMsg('reviewSheetMsg', '🎉 +2 Spins & ₹10 mila! Shukriya!', '#15803D');
                        // Update review card UI
                        const rcard = document.getElementById('reviewBonusCard');
                        if (rcard) {
                            rcard.style.opacity = '0.6';
                            rcard.style.pointerEvents = 'none';
                            rcard.querySelector('.bonus-card-title').textContent = '✅ Reviewed!';
                            rcard.querySelector('.bonus-card-sub').textContent = '+2 Spins + ₹10 added';
                        }
                        setTimeout(() => {
                            document.getElementById('reviewSheet').style.display = 'none';
                            document.body.style.overflow = '';
                        }, 2000);
                    } else {
                        showSheetMsg('reviewSheetMsg', res.message || 'Kuch galat hua.', '#EF4444');
                        btn.disabled = false; btn.textContent = '⭐ Review Submit Karein';
                    }
                },
                error: function() {
                    showSheetMsg('reviewSheetMsg', 'Server error. Dobara koshish karein.', '#EF4444');
                    btn.disabled = false; btn.textContent = '⭐ Review Submit Karein';
                }
            });
        }

        /* ─── Shared Sheet Message Helper ─── */
        function showSheetMsg(elId, text, color) {
            const el = document.getElementById(elId);
            if (!el) return;
            el.textContent  = text;
            el.style.color  = color;
            el.style.background = color === '#15803D' ? '#dcfce7' : '#fef2f2';
            el.style.border = `1px solid ${color === '#15803D' ? '#86efac' : '#fca5a5'}`;
            el.style.display = 'block';
        }

        /* ─── Init ─── */
        setTimeout(() => {
            drawWheel(0);
            if (alreadyFollowed) {
                const card = document.getElementById('followBonusCard');
                if (card) {
                    card.style.opacity = '0.6';
                    card.style.pointerEvents = 'none';
                    card.querySelector('.bonus-card-title').textContent = '✅ Followed!';
                    card.querySelector('.bonus-card-sub').textContent = 'Already followed this shop';
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