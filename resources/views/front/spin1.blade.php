@extends('front_layout.main')

@section('content')
    @push('css_or_link')
        <style>
            .tracking-tighter {
                letter-spacing: 1px !important;
            }
        </style>
    @endpush
    @php
        date_default_timezone_set('Asia/Kolkata');
        $currentTime = date('H:i:s');

        $openTime = $shop->open_time ?? '09:00:00';
        $closeTime = $shop->close_time ?? '21:00:00';

        // Shop open hai ya nahi check karein
        $isShopOpen = $currentTime >= $openTime && $currentTime <= $closeTime;
        $rawOffers = isset($shop) ? json_decode($shop->offers, true) : [];
        $dbOffers = array_filter(is_array($rawOffers) ? $rawOffers : [], function ($val) {
            return !empty(trim((string) $val));
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
                'label' => strtoupper(trim((string) $offer)),
                'sub' => 'Limited Offer',
                'color' => $colors[$i % 6],
                'emoji' => $emojis[$i % 6],
            ];
            $i++;
        }
    @endphp

    <div id="screen-spin" class="screen active fade-up pb-24">
        <div class="flex items-center gap-3 px-4 py-3 bg-white border-b border-ink-100 sticky top-0 z-10">
            <button onclick="window.history.back()" class="w-9 h-9 rounded-xl bg-ink-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-ink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M15 18l-6-6 6-6" />
                </svg>
            </button>
            <div class="flex-1">
                <p class="font-display font-bold text-ink-800 text-sm">{{ ucwords($shop->shop_name ?? 'Jhansi Bazaar') }}
                </p>
                <p class="text-xs text-ink-400">Spin karo — Lucky offer jeeto!</p>
            </div>
            <div class="flex items-center gap-1 bg-saffron-50 border border-saffron-200 rounded-full px-2.5 py-1">
                <span class="text-xs font-bold text-saffron-600" id="spinsLeftBadge">1 spin</span>
            </div>
        </div>

        <div class="flex flex-col items-center px-4 pt-5">
            <div
                class="w-0 h-0 border-l-[14px] border-r-[14px] border-t-[24px] border-l-transparent border-r-transparent border-t-saffron-500 z-10 relative drop-shadow">
            </div>
            <canvas id="mainWheel" width="290" height="290" class="wheel-canvas rounded-full"
                onclick="triggerSpin()"></canvas>

            <p class="text-sm text-ink-400 mt-3 text-center" id="spinStatusMsg">Wheel pe tap karo!</p>

            <div class="flex items-center gap-2 mt-2 mb-4">
                <div class="flex items-center gap-1.5 bg-gold-50 border border-gold-200 rounded-full px-3 py-1">
                    <span class="text-xs font-bold text-gold-700">🪙 340 coins</span>
                </div>
                <div class="flex items-center gap-1.5 bg-saffron-50 border border-saffron-200 rounded-full px-3 py-1">
                    <span class="text-xs font-bold text-saffron-600" id="spinsCount">1 spin bacha</span>
                </div>
            </div>

            <button id="spinMainBtn" onclick="triggerSpin()"
                class="w-full gradient-brand text-white font-display font-bold text-xl rounded-2xl py-4 shadow-lg active:scale-95 transition-transform mb-4">SPIN
                NOW 🎡</button>

            <div class="grid grid-cols-2 gap-2.5 w-full">
                <button onclick="earnBonus('video')"
                    class="bg-white border border-ink-200 rounded-2xl p-3 flex flex-col items-center gap-1 active:bg-slate-50">
                    <span class="text-2xl">📹</span>
                    <p class="text-xs font-bold text-ink-700">Video dekho</p>
                    <p class="text-xs text-saffron-500 font-bold">+1 extra spin</p>
                </button>
                <button onclick="earnBonus('share')"
                    class="bg-white border border-ink-200 rounded-2xl p-3 flex flex-col items-center gap-1 active:bg-slate-50">
                    <span class="text-2xl">📤</span>
                    <p class="text-xs font-bold text-ink-700">Share karo</p>
                    <p class="text-xs text-gold-600 font-bold">+20 coins</p>
                </button>
            </div>
        </div>

        <div id="winModal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/80 backdrop-blur-sm">
    
            <div class="relative w-full max-w-sm">
                
                {{-- Floating emoji burst --}}
                <div class="text-center mb-4 animate-bounce">
                    <span class="text-6xl drop-shadow-lg" id="winEmojiEl">🎉</span>
                </div>

                {{-- Main Card --}}
                <div style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
                            border: 1px solid rgba(255,255,255,0.12);
                            box-shadow: 0 0 60px rgba(163,230,53,0.2), 0 25px 50px rgba(0,0,0,0.6);
                            border-radius: 2rem;
                            overflow: hidden;
                            position: relative;">

                    {{-- Top glow line --}}
                    <div style="height:3px; background: linear-gradient(90deg, transparent, #bef264, #ffd700, #bef264, transparent);"></div>

                    <div class="p-6">

                        {{-- Badge --}}
                        <div class="flex justify-center mb-4">
                            <div style="background: rgba(190,242,100,0.1); border: 1px solid rgba(190,242,100,0.35); border-radius:999px; padding: 4px 16px; display:inline-flex; align-items:center; gap:6px;">
                                <span style="width:7px;height:7px;background:#bef264;border-radius:50%;display:inline-block;box-shadow:0 0 6px #bef264;"></span>
                                <span style="color:#bef264;font-size:9px;font-weight:900;letter-spacing:0.2em;text-transform:uppercase;">Jackpot Unlocked</span>
                            </div>
                        </div>

                        {{-- Heading --}}
                        <div class="text-center mb-5">
                            <h2 style="font-size:2.2rem;font-weight:900;color:#fff;line-height:1;letter-spacing:-1px;">
                                Badhai <span style="color:#bef264;font-style:italic;">Ho!</span>
                            </h2>
                            <p style="color:#94a3b8;font-size:11px;margin-top:6px;font-weight:600;letter-spacing:0.1em;">AAPKA LUCKY PRIZE MILA</p>
                        </div>

                        {{-- Prize Coupon --}}
                        <div style="background:#fff; border-radius:1.25rem; padding:2px; margin-bottom:20px; position:relative; overflow:hidden;">
                            
                            {{-- Dashed middle line --}}
                            <div style="position:absolute;top:50%;left:0;right:0;border-top:2px dashed #e2e8f0;z-index:0;"></div>
                            
                            {{-- Left notch --}}
                            <div style="position:absolute;left:-12px;top:50%;transform:translateY(-50%);width:24px;height:24px;background:#1e1b4b;border-radius:50%;z-index:2;"></div>
                            {{-- Right notch --}}
                            <div style="position:absolute;right:-12px;top:50%;transform:translateY(-50%);width:24px;height:24px;background:#1e1b4b;border-radius:50%;z-index:2;"></div>

                            <div style="position:relative;z-index:1;padding:20px 24px;text-align:center;">
                                <p style="color:#64748b;font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:0.15em;margin-bottom:8px;">
                                    🏷️ Aapka Prize
                                </p>
                                <p style="font-size:1.6rem;font-weight:900;color:#0f172a;letter-spacing:-0.5px;line-height:1.2;" id="winPrizeText">---</p>
                                <div style="margin-top:10px;display:flex;justify-content:center;align-items:center;gap:8px;">
                                    <span style="font-size:9px;color:#94a3b8;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;">Shop pe dikhayein</span>
                                    <span style="width:4px;height:4px;background:#cbd5e1;border-radius:50%;display:inline-block;"></span>
                                    <span style="font-size:9px;color:#ef4444;font-weight:800;text-transform:uppercase;">Limited Time</span>
                                </div>
                            </div>
                        </div>

                        {{-- Claim Button --}}
                        <button onclick="closeWin()"
                            style="width:100%;background:linear-gradient(135deg,#bef264,#84cc16);color:#14532d;font-weight:900;font-size:1.1rem;border:none;border-radius:1rem;padding:18px;cursor:pointer;box-shadow:0 6px 0 #4d7c0f;transition:all 0.1s;letter-spacing:-0.3px;"
                            onmousedown="this.style.boxShadow='0 2px 0 #4d7c0f';this.style.transform='translateY(4px)'"
                            onmouseup="this.style.boxShadow='0 6px 0 #4d7c0f';this.style.transform='translateY(0)'">
                            CLAIM REWARD 🙌
                            <div style="font-size:9px;opacity:0.6;font-weight:700;letter-spacing:0.15em;margin-top:2px;">SHOP PE JAAKE REDEEM KAREIN</div>
                        </button>

                        {{-- Footer --}}
                        <div style="margin-top:16px;text-align:center;">
                            <p style="font-size:10px;color:#475569;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;cursor:pointer;"
                               onclick="closeWin()">
                                💾 Save to Wallet
                            </p>
                        </div>

                    </div>

                    {{-- Bottom glow line --}}
                    <div style="height:2px; background: linear-gradient(90deg, transparent, #ffd700, transparent);"></div>
                </div>

                {{-- Listee branding --}}
                <div class="text-center mt-5" style="opacity:0.35;">
                    <span style="color:#fff;font-weight:900;font-size:14px;font-style:italic;letter-spacing:-0.5px;">LiSTee.org</span>
                </div>
            </div>
        </div>
    </div>
    @include('front.partial.spin_popup')
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>    
    <script>
        const isShopOpen = {{ $isShopOpen ? 'true' : 'false' }};
        const shopOpenTime = "{{ \Carbon\Carbon::parse($openTime)->format('h:i A') }}";
        const shopCloseTime = "{{ \Carbon\Carbon::parse($closeTime)->format('h:i A') }}";

        const segments = {!! json_encode($finalSegments) !!};
        let curAngle = 0;
        let isSpinning = false;
        // Initial count set kar rahe hain
        let spinsLeft = {{ $shop->spins_left ?? 1 }};

        function drawWheel(rot) {
            const c = document.getElementById('mainWheel');
            if (!c) return;
            const ctx = c.getContext('2d'),
                cx = 145,
                cy = 145,
                r = 136,
                n = segments.length,
                arc = (2 * Math.PI) / n;
            ctx.clearRect(0, 0, 290, 290);

            segments.forEach((seg, i) => {
                const s = rot + i * arc - Math.PI / 2,
                    e = s + arc;
                ctx.beginPath();
                ctx.moveTo(cx, cy);
                ctx.arc(cx, cy, r, s, e);
                ctx.fillStyle = seg.color;
                ctx.fill();
                ctx.strokeStyle = '#fff';
                ctx.lineWidth = 2;
                ctx.stroke();

                ctx.save();
                ctx.translate(cx, cy);
                ctx.rotate(s + arc / 2);
                ctx.textAlign = 'right';
                ctx.fillStyle = '#fff';
                ctx.font = 'bold 11px sans-serif';
                ctx.fillText(seg.label.substring(0, 15), r - 10, 4);
                ctx.restore();
            });

            // Center Pin
            ctx.beginPath();
            ctx.arc(cx, cy, 24, 0, 2 * Math.PI);
            ctx.fillStyle = '#fff';
            ctx.fill();
            ctx.fillStyle = '#FF6B35';
            ctx.font = 'bold 14px sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText('JB', cx, cy);
        }

        function triggerSpin() {
            if (isSpinning) return;

            if (!isShopOpen) {
                Swal.fire({
                    icon: 'error',
                    title: 'Shop Closed Hai!',
                    html: `Maaf kijiye, spin sirf shop timing ke beech chalta hai.<br><br><b>Timing:</b> ${shopOpenTime} - ${shopCloseTime}`,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Theek hai'
                });
                return;
            }

            if (spinsLeft <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Spins Khatam!',
                    text: 'Video dekh kar extra spin kamaayein.',
                    confirmButtonColor: '#FF6B35'
                });
                return;
            }

            isSpinning = true;
            spinsLeft--; // Spin count turant kam karo
            updateSpinUI();

            const btn = document.getElementById('spinMainBtn');
            btn.disabled = true;
            btn.innerHTML = '🌀 Spinning...';

            const extra = 7 + Math.floor(Math.random() * 5);
            const rand = Math.random() * 2 * Math.PI;
            const target = curAngle + (extra * 2 * Math.PI) + rand;
            const dur = 3500;
            const t0 = performance.now();
            const a0 = curAngle;

            function frame(now) {
                const p = Math.min((now - t0) / dur, 1);
                const ease = 1 - Math.pow(1 - p, 4);
                curAngle = a0 + (target - a0) * ease;
                drawWheel(curAngle);
                if (p < 1) requestAnimationFrame(frame);
                else {
                    isSpinning = false;
                    btn.disabled = false;
                    btn.textContent = spinsLeft > 0 ? 'SPIN AGAIN 🎡' : 'Spins Khatam 😅';
                    showWin(curAngle);
                }
            }
            requestAnimationFrame(frame);
        }

        function showWin(angle) {
            const n = segments.length,
                arc = (2 * Math.PI) / n;
            const norm = ((-angle % (2 * Math.PI)) + (2 * Math.PI)) % (2 * Math.PI);
            const idx = Math.floor(norm / arc) % n;
            const seg = segments[idx];

            // 🎉 Confetti Blast
            const count = 200;
            const defaults = { origin: { y: 0.7 } };

            function fire(particleRatio, opts) {
                confetti({
                    ...defaults,
                    ...opts,
                    particleCount: Math.floor(count * particleRatio),
                    colors: ['#ff0000', '#ffd700', '#ffffff', '#bef264']
                });
            }

            fire(0.25, { spread: 26, startVelocity: 55 });
            fire(0.2,  { spread: 60 });
            fire(0.35, { spread: 100, decay: 0.91, scalar: 0.8 });
            fire(0.1,  { spread: 120, startVelocity: 25, decay: 0.92, scalar: 1.2 });
            fire(0.1,  { spread: 120, startVelocity: 45 });

            // Show modal
            document.getElementById('winPrizeText').textContent = seg.label;
            document.getElementById('winModal').classList.remove('hidden');
        }

        function closeWin() {
            document.getElementById('winModal').classList.add('hidden');
        }

        function earnBonus(type) {
            if (type === 'video') {
                Swal.fire({
                    title: 'Video Load Ho Raha Hai...',
                    timer: 1500,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                }).then(() => {
                    spinsLeft++;
                    updateSpinUI();
                    Swal.fire({
                        icon: 'success',
                        title: '+1 Spin Mila!',
                        toast: true,
                        position: 'top-end',
                        timer: 2000,
                        showConfirmButton: false
                    });
                });
            }
        }

        function updateSpinUI() {
            const counts = document.getElementById('spinsCount');
            const badge = document.getElementById('spinsLeftBadge');
            if (counts) counts.textContent = `${spinsLeft} spin bacha`;
            if (badge) badge.textContent = `${spinsLeft} spin`;
        }

        setTimeout(() => drawWheel(0), 100);
    </script>
@endpush
