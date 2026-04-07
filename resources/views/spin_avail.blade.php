@extends('front_layout.main')

@section('content')
<turbo-frame id="main_content">
    <div class="bg-[#F4F7F5] min-h-screen pb-20 font-sans antialiased">
        
        <div class="bg-white/90 backdrop-blur-md px-4 py-3 sticky top-0 z-40 border-b border-emerald-100 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 bg-emerald-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v10"/><path d="M16 8l-4 4-4-4"/><path d="M12 22v-3"/></svg>
                </div>
                <div>
                    <h1 class="text-[15px] font-black text-slate-800 leading-none uppercase tracking-tighter">Jhansi Bazaar</h1>
                    <span class="text-[10px] text-emerald-500 font-bold uppercase tracking-widest">Spin & Win</span>
                </div>
            </div>
            <div class="flex items-center gap-3 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <div class="w-8 h-8 rounded-full bg-emerald-100 border border-emerald-200 flex items-center justify-center text-[10px] font-bold text-emerald-700">RK</div>
            </div>
        </div>

        <div class="px-4 py-2 bg-white border-b border-emerald-50 flex gap-3 overflow-x-auto no-scrollbar">
            <div class="flex items-center gap-1.5 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-100 flex-shrink-0">
                <span class="text-xs">🪙</span>
                <span class="text-[10px] font-bold text-emerald-800">340 COINS</span>
            </div>
            <div class="flex items-center gap-1.5 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-100 flex-shrink-0">
                <span class="text-xs">🎡</span>
                <span class="text-[10px] font-bold text-slate-600">2 SPINS</span>
            </div>
        </div>

        <div class="px-3 py-4 space-y-2.5">
            @forelse($shops as $shop)
                @php
                    $offers = json_decode($shop->offers, true) ?? [];
                    $cleanOffers = array_filter($offers, fn($v) => !empty(trim((string)$v)));
                    $hasSpin = count($cleanOffers) > 0;
                    // Generating Clean Slug for URL
                    $shopSlug = str_replace(' ', '-', strtolower($shop->shop_name));
                @endphp

                <div class="bg-white rounded-2xl p-2.5 border border-emerald-50 shadow-[0_2px_10px_-3px_rgba(0,0,0,0.04)] flex items-center gap-3 relative transition-all active:scale-[0.97]">
                    
                    <div class="relative w-14 h-14 rounded-xl overflow-hidden flex-shrink-0 border border-emerald-50 bg-emerald-50">
                        @php
                            $shopPhoto = $shop->shop_photo;
                            $photoData = is_string($shopPhoto) ? json_decode($shopPhoto, true) : null;
                            if (is_array($photoData) && !empty($photoData['url'])) {
                                // Cloudinary format: {"url":"...","public_id":"..."}
                                $photoUrl = $photoData['url'];
                            } elseif (is_string($shopPhoto) && !empty($shopPhoto)) {
                                // Old local format: plain filename string
                                $photoUrl = asset('uploads/shops/' . $shopPhoto);
                            } else {
                                $photoUrl = null;
                            }
                        @endphp

                        @if($photoUrl)
                            <img src="{{ $photoUrl }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-xl grayscale opacity-30">🏪</div>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-0.5">
                            <h3 class="text-[13px] font-bold text-slate-800 truncate uppercase tracking-tight">{{ $shop->shop_name }}</h3>
                            <div class="flex items-center gap-0.5 text-amber-500">
                                <span class="text-[10px] font-black italic">4.2</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1.7l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14l-5-4.87 6.91-1.31L12 1.7z"/></svg>
                            </div>
                        </div>
                        
                        <p class="text-[10px] text-slate-400 truncate mb-2 flex items-center gap-1 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            {{ $shop->address ?? 'Jhansi, UP' }}
                        </p>

                        <div class="flex items-center justify-between">
                            <div class="flex gap-1.5">
                                @if($hasSpin)
                                    <span class="bg-emerald-100 text-emerald-700 text-[9px] font-black px-1.5 py-0.5 rounded uppercase">
                                        {{ count($cleanOffers) }} Prizes
                                    </span>
                                @else
                                    <span class="bg-slate-100 text-slate-400 text-[9px] font-bold px-1.5 py-0.5 rounded uppercase">Inactive</span>
                                @endif
                            </div>

                            @if($hasSpin)
                                <a href="{{ url('/spin/'.$shopSlug) }}" 
                                   data-turbo-action="advance"
                                   class="bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-black px-4 py-1.5 rounded-lg shadow-md shadow-emerald-100 flex items-center gap-1 tracking-wider uppercase">
                                    SPIN <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-20 opacity-40">
                    <div class="w-16 h-16 bg-slate-200 rounded-full mb-2"></div>
                    <p class="text-xs font-bold text-slate-500">No stores found</p>
                </div>
            @endforelse
        </div>

        <div class="fixed bottom-4 left-1/2 -translate-x-1/2 bg-white/90 backdrop-blur-xl border border-emerald-100 px-6 py-3 rounded-2xl shadow-2xl flex items-center gap-10 z-50">
            <button class="text-emerald-600"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></button>
            <button class="text-slate-300"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg></button>
            <button class="text-slate-300"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></button>
        </div>
    </div>
</turbo-frame>

<style>
    /* Professional Clean Adjustments */
    body { -webkit-font-smoothing: antialiased; scroll-behavior: smooth; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    /* Loading Progress Bar Color for Turbo */
    .turbo-progress-bar { background-color: #059669; height: 3px; }

    /* Modern List Animation */
    @keyframes slideInUp {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .space-y-2.5 > div { 
        animation: slideInUp 0.5s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; 
    }
</style>
@endsection