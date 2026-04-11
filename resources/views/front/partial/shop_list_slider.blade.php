@push('css_or_link')
    <style>
        .spin-badge {
            background: #FFD700;
            color: #7D5A00;
            font-size: 10px;
            font-weight: 700;
            border-radius: 6px;
            padding: 2px 7px;
        }

        .bg-brand {
            --tw-bg-opacity: 1;
            background-color: rgb(192 57 43 / var(--tw-bg-opacity, 1));
        }

        .bg-openBg {
            --tw-bg-opacity: 1;
            background-color: rgb(232 248 240 / var(--tw-bg-opacity, 1));
        }

        .border-green-200 {
            --tw-border-opacity: 1;
            border-color: rgb(187 247 208 / var(--tw-border-opacity, 1));
        }

        .bg-surface {
            --tw-bg-opacity: 1;
            background-color: rgb(255 248 240 / var(--tw-bg-opacity, 1));
        }

        .card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgb(122 168 122 / 25%);
        }
    </style>
@endpush
@foreach ($shops as $sp)
    @php
        // Open/Closed Logic
        $isOpen = false;
        if ($sp->open_time && $sp->close_time) {
            $now = now();
            $start = \Carbon\Carbon::parse($sp->open_time);
            $end = \Carbon\Carbon::parse($sp->close_time);
            $isOpen = $now->between($start, $end);
        }

        // Categories decode (agar JSON hai)
        $cats = is_array($sp->categories) ? $sp->categories : json_decode($sp->categories, true);
        $catString = $cats ? implode(' & ', array_slice($cats, 0, 2)) : 'Shop';

        // Offers decode
        $offers = is_array($sp->offers) ? $sp->offers : json_decode($sp->offers, true);
        $slug = str_replace(' ', '-', strtolower($sp->shop_name));
        $itemPhotos = json_decode($sp->item_photos, true);
    @endphp


    <div class="shop-card bg-card rounded-2xl border border-border overflow-hidden card-hover cursor-pointer m-4"
        data-cat="Joote">
        <div class="relative h-40 overflow-hidden bg-blue-50" data-card-slider>
            <div class="card-img-track flex h-full transition-transform duration-400 ease-in-out">
                <div
                    class="card-img-slide min-w-full h-full flex items-center justify-center bg-blue-50 text-7xl select-none">
                    <img src="{{ $sp->shop_photo }}" alt="Red Velvet Cake"
                        class="card-img-slide min-w-full h-full flex items-center justify-center bg-blue-50 text-7xl select-none"
                        style="width:100%;object-fit:cover" loading="lazy"
                        onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($sp->shop_name) }}&background=random'">
                </div>

                @if ($itemPhotos)
                    @foreach ($itemPhotos as $pt=>$photo)
                        @if (!empty($photo))
                            <div
                                class="card-img-slide min-w-full h-full flex items-center justify-center bg-blue-50 text-7xl select-none">
                                <img src="{{ $photo['url'] }}" alt="Red Velvet Cake"
                                    class="card-img-slide min-w-full h-full flex items-center justify-center bg-blue-50 text-7xl select-none"
                                    style="width:100%;object-fit:cover" loading="lazy"
                                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($sp->shop_name) }}&background=random'">
                            </div>
                        @else
                            <div
                                class="card-img-slide min-w-full h-full flex items-center justify-center bg-blue-50 text-7xl select-none">
                                <img src="{{ $sp->shop_photo }}" alt="Red Velvet Cake"
                                    class="card-img-slide min-w-full h-full flex items-center justify-center bg-blue-50 text-7xl select-none"
                                    style="width:100%;object-fit:cover" loading="lazy"
                                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($sp->shop_name.' '.$pt) }}&background=random'">
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
            <span
                class="absolute top-2.5 left-2.5 bg-blue-600 text-white text-[10px] font-bold rounded-md px-2 py-0.5 z-10">SALE
                20%</span>
            <span class="absolute top-2.5 right-2.5 spin-badge z-10">🎡 Spin</span>
            <span
                class="absolute bottom-2.5 left-2.5 bg-openBg text-open text-[10px] font-bold rounded-md px-2 py-0.5 flex items-center z-10"><span
                    class="open-dot"></span>Open</span>
            <span
                class="absolute bottom-2.5 right-2.5 bg-black/40 text-white text-[10px] font-semibold rounded-md px-2 py-0.5 z-10">120m
                away</span>
            <div class="card-img-dots absolute bottom-2.5 left-1/2 -translate-x-1/2 flex gap-1 z-10"></div>

        </div>
        <div class="p-3.5">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="font-extrabold text-base text-textMain leading-tight">{{ ucwords($sp->shop_name) }}</h2>
                    <p class="text-xs text-muted mt-0.5">Footwear • {{ ucwords($sp->address) }}</p>
                </div>
                <button
                    class="w-8 h-8 rounded-full border border-border bg-surface flex items-center justify-center text-base shrink-0">🤍</button>
            </div>
            <div class="flex items-center gap-2 mt-2">
                <span class="star-filled text-xs">★★★★☆</span>
                <span class="font-bold text-xs text-textMain">4.5</span>
                <span class="text-xs text-muted">112 reviews</span>
                <span class="ml-auto text-xs text-muted">{{ date('g:i a', strtotime($sp->open_time)) }} - {{ date('g:i a', strtotime($sp->close_time)) }}</span>
            </div>
            <div class="flex gap-2 mt-2.5 flex-wrap">
                @if ($sp->tagline)
                    <span class="tag t-green">✨ {{ $sp->tagline }}</span>
                @endif
                @php $categories = json_decode($sp->categories, true); @endphp
                @if ($categories)
                    @foreach ($categories as $cat)
                        <span class="tag t-blue">{{ ucfirst($cat) }}</span>
                    @endforeach
                @endif
                <span
                    class="bg-blue-50 text-blue-700 border border-blue-100 text-[10px] font-semibold rounded-lg px-2.5 py-1">20%
                    off Formals</span>
                <span
                    class="bg-blue-50 text-blue-700 border border-blue-100 text-[10px] font-semibold rounded-lg px-2.5 py-1">🥿
                    Free Polish</span>
                <span
                    class="bg-blue-50 text-blue-700 border border-blue-100 text-[10px] font-semibold rounded-lg px-2.5 py-1">Buy
                    2 Get 1</span>
            </div>
            <div class="flex gap-2 mt-3">
                <button
                    class="flex-1 bg-surface border border-border rounded-xl py-2 text-xs font-bold text-textMain">📞
                    Call</button>
                <button class="flex-1 bg-openBg border border-green-200 rounded-xl py-2 text-xs font-bold text-open">💬
                    WhatsApp</button>
                <button class="flex-[2] bg-brand text-white rounded-xl py-2 text-xs font-bold">🎡 Spin &amp;
                    Win</button>
            </div>
        </div>
    </div>
@endforeach
@push('script')
    <script>
        $(document).ready(function() {
            // Har shop-card ko independently loop karenge
            $('.shop-card').each(function() {
                const $card = $(this);
                const $track = $card.find('.card-img-track');
                const $slides = $card.find('.card-img-slide');
                const $dotsContainer = $card.find('.card-img-dots');

                let currentIndex = 0;
                const totalSlides = $slides.length;
                let slideInterval;

                // 1. Dots setup (Jitne slides utne dots)
                $dotsContainer.empty();
                $slides.each(function(i) {
                    $dotsContainer.append(
                        `<div class="dot h-1 w-1.5 rounded-full bg-white/50 transition-all duration-300" data-index="${i}"></div>`
                    );
                });

                // 2. Slider move karne ka function
                function moveSlider() {
                    const offset = -(currentIndex * 100);
                    $track.css('transform', `translateX(${offset}%)`);

                    // Dots update
                    $card.find('.dot').removeClass('bg-white w-3').addClass('bg-white/50 w-1.5');
                    $card.find('.dot').eq(currentIndex).addClass('bg-white w-3').removeClass(
                        'bg-white/50 w-1.5');
                }

                // 3. Auto-play logic
                function startAuto() {
                    slideInterval = setInterval(() => {
                        currentIndex = (currentIndex + 1) % totalSlides;
                        moveSlider();
                    }, 2000); // 3 seconds interval
                }

                function stopAuto() {
                    clearInterval(slideInterval);
                }

                // 4. Events
                $card.on('mouseenter', stopAuto); // Hover par ruk jayega
                $card.on('mouseleave', startAuto); // Hatne par fir shuru

                // Initial Start
                moveSlider();
                startAuto();
            });
        });
    </script>
@endpush
