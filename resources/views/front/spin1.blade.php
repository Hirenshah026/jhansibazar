@extends('front_layout.main')

@section('content')
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

        <div id="winModal"
            class="hidden fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/70 backdrop-blur-sm">
            <div class="bg-white rounded-3xl w-full max-w-sm p-6 relative slide-up shadow-2xl">

                <button onclick="closeWin()"
                    class="absolute -top-3 -right-3 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center text-gray-500 hover:text-black border border-gray-100">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="text-center mb-5">
                    <div class="text-6xl mb-3" id="winEmojiEl">🎉</div>
                    <h2 class="font-display font-bold text-2xl text-ink-800">Badhai ho!</h2>
                    <p class="text-sm text-ink-400 mt-1">Shopkeeper ko yeh screen dikhao</p>
                </div>

                {{-- <div class="gradient-brand rounded-2xl p-5 text-center text-white mb-6 shadow-md">
                    <p class="text-xs font-semibold uppercase tracking-wide opacity-80 mb-1">Aapka Prize</p>
                    <p class="font-display font-bold text-xl" id="winPrizeText">---</p>
                </div> --}}
                <div class="bg-gradient-to-br from-blue-600 to-indigo-900 rounded-2xl p-2 mb-6 shadow-xl">
                    <div class="border-2 border-dashed border-white/40 rounded-xl p-4 text-center text-white">
                        <p class="text-[10px] font-bold uppercase tracking-widest opacity-80 mb-1">Aapka Prize</p>
                        <p class="font-bold text-4xl tracking-tighter" id="winPrizeText">23</p>
                    </div>
                </div>

                <button onclick="closeWin()"
                    class="w-full gradient-brand text-white font-display font-bold text-base rounded-2xl py-4 shadow-lg active:scale-95 transition-transform">
                    Shukriya! 🙌
                </button>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

            // Aapka original custom modal
            document.getElementById('winPrizeText').textContent = seg.label;
            document.getElementById('winEmojiEl').textContent = seg.emoji;
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
