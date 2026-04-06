@extends('front_layout.main')

@section('content')
    <div id="screen-spin" class="screen active fade-up pb-24">
        <div class="flex items-center gap-3 px-4 py-3 bg-white border-b border-ink-100 sticky top-0 z-10">
            <button onclick="goBack()"
                class="w-9 h-9 rounded-xl bg-ink-50 flex items-center justify-center hover:bg-ink-100 transition">
                <svg class="w-4 h-4 text-ink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M15 18l-6-6 6-6" />
                </svg>
            </button>
            <div class="flex-1">
                <p class="font-display font-bold text-ink-800 text-sm">{{ ucwords($shop->shop_name??'Raj Shoe Store') }}</p>
                <p class="text-xs text-ink-400">Spin karo — Lucky offer jeeto!</p>
            </div>
            <div class="flex items-center gap-1 bg-saffron-50 border border-saffron-200 rounded-full px-2.5 py-1">
                <span class="text-xs font-bold text-saffron-600" id="spinsLeftBadge">1 spin</span>
            </div>
        </div>

        <div class="flex flex-col items-center px-4 pt-5">
            <!-- Shop mini card -->
            <div class="w-full bg-ink-50 rounded-2xl p-3 flex items-center gap-3 mb-5 border border-ink-100">
                <div class="w-11 h-11 bg-orange-50 rounded-xl flex items-center justify-center text-2xl">👟</div>
                <div class="flex-1">
                    <p class="font-display font-bold text-ink-800 text-sm">{{ ucwords($shop->shop_name??'Raj Shoe Store') }}</p>
                    <p class="text-xs text-ink-400">Sadar Bazar • 120m away</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-saffron-500 font-semibold">★ 4.5</p>
                    <p class="text-xs text-ink-400">128 reviews</p>
                </div>
            </div>

            <!-- Pointer -->
            <div
                class="w-0 h-0 border-l-[14px] border-r-[14px] border-t-[24px] border-l-transparent border-r-transparent border-t-saffron-500 mb-0 z-10 relative drop-shadow">
            </div>

            <!-- Wheel -->
            <canvas id="mainWheel" width="290" height="290" class="wheel-canvas rounded-full"
                onclick="triggerSpin()"></canvas>

            <p class="text-sm text-ink-400 mt-3 text-center" id="spinStatusMsg">Wheel pe tap karo ya neeche button dabaao!
            </p>

            <!-- Coins display -->
            <div class="flex items-center gap-2 mt-2 mb-4">
                <div class="flex items-center gap-1.5 bg-gold-50 border border-gold-200 rounded-full px-3 py-1">
                    <span class="text-xs font-bold text-gold-700">🪙 340 coins</span>
                </div>
                <div class="flex items-center gap-1.5 bg-saffron-50 border border-saffron-200 rounded-full px-3 py-1">
                    <span class="text-xs font-bold text-saffron-600" id="spinsCount">1 spin bacha</span>
                </div>
            </div>

            <!-- Spin Button -->
            <button id="spinMainBtn" onclick="triggerSpin()"
                class="w-full gradient-brand text-white font-display font-bold text-xl rounded-2xl py-4 shadow-lg btn-press mb-4">SPIN
                NOW 🎡</button>

            <!-- Bonus Row -->
            <p class="text-xs text-ink-400 font-semibold uppercase tracking-wide mb-2 self-start">Aur spin kamao:</p>
            <div class="grid grid-cols-2 gap-2.5 w-full mb-4">
                <button onclick="earnBonus('video')"
                    class="bg-white border border-ink-200 rounded-2xl p-3 flex flex-col items-center gap-1 hover:border-saffron-300 transition btn-press">
                    <span class="text-2xl">📹</span>
                    <p class="text-xs font-bold text-ink-700">Video dekho</p>
                    <p class="text-xs text-saffron-500 font-bold">+1 extra spin</p>
                </button>
                <button onclick="earnBonus('share')"
                    class="bg-white border border-ink-200 rounded-2xl p-3 flex flex-col items-center gap-1 hover:border-gold-300 transition btn-press">
                    <span class="text-2xl">📤</span>
                    <p class="text-xs font-bold text-ink-700">Share karo</p>
                    <p class="text-xs text-gold-600 font-bold">+20 coins</p>
                </button>
                <button onclick="earnBonus('review')"
                    class="bg-white border border-ink-200 rounded-2xl p-3 flex flex-col items-center gap-1 hover:border-forest-300 transition btn-press">
                    <span class="text-2xl">⭐</span>
                    <p class="text-xs font-bold text-ink-700">Review likho</p>
                    <p class="text-xs text-forest-600 font-bold">+15 coins</p>
                </button>
                <button onclick="earnBonus('photo')"
                    class="bg-white border border-ink-200 rounded-2xl p-3 flex flex-col items-center gap-1 hover:border-blue-300 transition btn-press">
                    <span class="text-2xl">📸</span>
                    <p class="text-xs font-bold text-ink-700">Photo daalo</p>
                    <p class="text-xs text-blue-600 font-bold">+10 coins</p>
                </button>
            </div>
        </div>

        <!-- WIN MODAL -->
        <div id="winModal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4"
            style="background:rgba(0,0,0,.7)">
            <div class="bg-white rounded-3xl w-full max-w-sm p-6 slide-up shadow-2xl">
                <div class="text-center mb-5">
                    <div class="text-6xl mb-3 pop-in" id="winEmojiEl">🎉</div>
                    <h2 class="font-display font-bold text-2xl text-ink-800">Badhai ho!</h2>
                    <p class="text-sm text-ink-400 mt-1">Shopkeeper ko yeh screen dikhao</p>
                </div>

                <div class="gradient-brand rounded-2xl p-5 text-center text-white mb-4 shadow-md">
                    <p class="text-xs font-semibold uppercase tracking-wide opacity-80 mb-1">Aapka Prize</p>
                    <p class="font-display font-bold text-xl" id="winPrizeText">20% Off Formal Shoes</p>
                    <p class="text-xs opacity-70 mt-1" id="winSubText">Aaj valid hai</p>
                </div>

                <div class="flex items-center justify-between bg-gold-50 border border-gold-200 rounded-2xl p-3 mb-6">
                    <div class="flex items-center gap-2 text-left">
                        <span class="text-2xl">🪙</span>
                        <div>
                            <p class="text-xs font-bold text-gold-700">Coins Earned</p>
                            <p class="text-xs text-ink-400">Wallet mein add ho gaye</p>
                        </div>
                    </div>
                    <span class="font-display font-bold text-xl text-forest-600">+10</span>
                </div>

                <button onclick="closeWin()"
                    class="w-full gradient-brand text-white font-display font-bold text-base rounded-2xl py-4 btn-press shadow-lg">
                    Shukriya! 🙌
                </button>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        const segments = [{
                label: '20% OFF',
                sub: 'formal shoes',
                color: '#FF6B35',
                emoji: '👟'
            },
            {
                label: '+15 COINS',
                sub: 'wallet mein',
                color: '#F59E0B',
                emoji: '🪙'
            },
            {
                label: 'FREE POLISH',
                sub: 'purchase pe',
                color: '#16A34A',
                emoji: '✨'
            },
            {
                label: 'TRY AGAIN',
                sub: 'phir try karo',
                color: '#6B7280',
                emoji: '😅'
            },
            {
                label: 'BUY 2 GET 1',
                sub: 'koi bhi pair',
                color: '#7C3AED',
                emoji: '🎁'
            },
            {
                label: '+10 COINS',
                sub: 'wallet mein',
                color: '#DC2626',
                emoji: '🪙'
            },
        ];
        let curAngle = 0,
            isSpinning = false,
            spinsLeft = 1;

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
                ctx.closePath();
                ctx.fillStyle = seg.color;
                ctx.fill();
                ctx.strokeStyle = 'rgba(255,255,255,0.8)';
                ctx.lineWidth = 2.5;
                ctx.stroke();
                ctx.save();
                ctx.translate(cx, cy);
                ctx.rotate(s + arc / 2);
                ctx.textAlign = 'right';
                ctx.fillStyle = 'rgba(255,255,255,0.95)';
                ctx.font = 'bold 11px "Baloo 2",sans-serif';
                ctx.fillText(seg.label, r - 10, 4);
                ctx.restore();
            });
            // center
            ctx.beginPath();
            ctx.arc(cx, cy, 24, 0, 2 * Math.PI);
            const g = ctx.createRadialGradient(cx, cy, 0, cx, cy, 24);
            g.addColorStop(0, '#fff');
            g.addColorStop(1, '#f3f4f6');
            ctx.fillStyle = g;
            ctx.fill();
            ctx.strokeStyle = '#FF6B35';
            ctx.lineWidth = 3;
            ctx.stroke();
            ctx.fillStyle = '#FF6B35';
            ctx.font = 'bold 12px "Baloo 2",sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText('JB', cx, cy);
        }

        function triggerSpin() {
            if (isSpinning) return;
            if (spinsLeft <= 0) {
                document.getElementById('spinStatusMsg').textContent = 'Spins khatam! Neeche se aur kamao 👇';
                return;
            }
            isSpinning = true;
            spinsLeft--;
            updateSpinUI();
            const btn = document.getElementById('spinMainBtn');
            btn.disabled = true;
            btn.textContent = '🌀 Spinning...';
            const extra = 5 + Math.floor(Math.random() * 5),
                rand = Math.random() * 2 * Math.PI;
            const target = curAngle + extra * 2 * Math.PI + rand,
                dur = 3500,
                t0 = performance.now(),
                a0 = curAngle;

            function frame(now) {
                const p = Math.min((now - t0) / dur, 1),
                    ease = 1 - Math.pow(1 - p, 4);
                curAngle = a0 + (target - a0) * ease;
                drawWheel(curAngle);
                if (p < 1) {
                    requestAnimationFrame(frame);
                } else {
                    curAngle = target % (2 * Math.PI);
                    isSpinning = false;
                    btn.disabled = false;
                    btn.textContent = spinsLeft > 0 ? 'SPIN AGAIN 🎡' : 'Spins khatam 😅';
                    showWin(curAngle);
                }
            }
            requestAnimationFrame(frame);
        }

        function showWin(angle) {
            const n = segments.length,
                arc = (2 * Math.PI) / n;
            const norm = ((-angle % (2 * Math.PI)) + (2 * Math.PI)) % (2 * Math.PI);
            const idx = Math.floor(norm / arc) % n,
                seg = segments[idx];
            document.getElementById('winPrizeText').textContent = seg.label + ' — ' + seg.sub;
            document.getElementById('winSubText').textContent = seg.label.includes('AGAIN') ? 'Kal phir try karo!' :
                'Shopkeeper ko dikhao — aaj valid';
            document.getElementById('winEmojiEl').textContent = seg.emoji;
            document.getElementById('spinStatusMsg').textContent = `🎉 ${seg.label} jeeta!`;
            document.getElementById('winModal').classList.remove('hidden');
        }

        function closeWin() {
            document.getElementById('winModal').classList.add('hidden');
            document.getElementById('spinStatusMsg').textContent = spinsLeft > 0 ? `${spinsLeft} spin bacha hai!` :
                'Neeche se aur spins kamao!';
        }

        function earnBonus(type) {
            const msgs = {
                video: '📹 Video dekha! +1 spin add kiya gaya.',
                share: '📤 Share kiya! +20 coins wallet mein.',
                review: '⭐ Review pe jayenge +15 coins milenge!',
                photo: '📸 Photo add karo +10 coins milenge!'
            };
            if (type === 'video') {
                spinsLeft++;
                updateSpinUI();
            }
            document.getElementById('spinStatusMsg').textContent = msgs[type];
        }

        function updateSpinUI() {
            const s = document.getElementById('spinsCount');
            if (s) s.textContent = `${spinsLeft} spin${spinsLeft!==1?'s':''} bacha`;
            const b = document.getElementById('spinsLeftBadge');
            if (b) b.textContent = `${spinsLeft} spin`;
        }
        setTimeout(() => drawWheel(0), 100);
    </script>
@endpush
