<div style="padding:0 12px 20px">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px">
        <p class="fd" style="font-size:16px; font-weight:800; color:#1e293b">Shop Collection</p>

    </div>

    <div id="itemsGrid" style="display:grid; grid-template-columns:1fr 1fr; gap:12px">

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
            @endphp



            <a href="{{ url('/shopprofile-details') }}/{{ str_replace(' ', '-', strtolower($sp->shop_name ?? 's')) }}">
                <div class="p-card fu" style="animation-delay: 0s;">
                    <div style="position:relative;overflow:hidden">
                        <img src="{{ asset('shop_photo/' . $sp->shop_photo) }}" alt="Red Velvet Cake"
                            style="width:100%;height:130px;object-fit:cover" loading="lazy"
                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($sp->shop_name) }}&background=random'">
                        <div
                            style="position:absolute;top:7px;left:7px;background:#EF4444;color:#fff;font-size:10px;font-weight:800;padding:2px 7px;border-radius:10px">
                            -25%</div>
                        <div style="position:absolute;top:7px;right:7px"><span class="tag t-red"
                                style="font-size:9px;padding:2px 6px">🔥 Best Seller</span></div>

                    </div>
                    <div style="padding:10px">
                        <p class="fd"
                            style="font-size:13px;font-weight:800;color:#1e293b;line-height:1.3;margin-bottom:4px">{{ ucwords($sp->shop_name) }}</p>
                        <div style="display:flex;align-items:center;gap:4px;margin-bottom:7px">
                            <span style="color:#FCD34D;font-size:11px">★</span>
                            <span style="font-size:11px;font-weight:700;color:#1e293b">4.9</span>
                            <span style="color:#CBD5E1;font-size:10px">•</span>
                            <span style="font-size:10px;color:#64748b">87 reviews</span>
                        </div>
                        <div style="display:flex;align-items:center;justify-content:space-between" class="hidden">
                            <div>
                                <span class="fd" style="font-size:16px;font-weight:800;color:#3B5BDB">₹450</span>
                                <span
                                    style="font-size:11px;color:#94a3b8;text-decoration:line-through;margin-left:4px">₹600</span>
                            </div>
                            <button
                                style="background:#3B5BDB;color:#fff;border:none;border-radius:9px;padding:6px 11px;font-size:11px;font-weight:800;cursor:pointer;font-family:'Nunito',sans-serif"
                                onclick="event.stopPropagation()">+ Add</button>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
@push('css_or_link')
    <style>
        .p-card {
            background: #fff;
            border-radius: 18px;
            overflow: hidden;
            border: 1.5px solid #E0E8FF;
        }
    </style>
@endpush
