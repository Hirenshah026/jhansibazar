<!DOCTYPE html>
<html lang="en">

<head>
    @include('front_layout.head')
    @stack('css_or_link')
</head>
@php
    $shops_list = DB::table('shops')->orderBy('id', 'DESC')->get();
@endphp
<body class="bg-ink-100 flex items-start justify-center min-h-screen md:py-4">

    <div class="relative w-full max-w-md shadow-2xl md:rounded-3xl overflow-hidden bg-white min-h-screen" id="appRoot">

        <!-- STATUS BAR -->
        <div class="gradient-brand px-5 py-2 flex items-center justify-between sticky top-0 z-50">
            <span class="text-white text-xs font-semibold invisible">9:41</span>
            <div class="flex items-center gap-1.5">
                <span class="text-white font-display font-bold text-base tracking-wide">LISTEE</span>
                <span class="badge-new">.org</span>
            </div>
            <div class="flex items-center gap-1.5">
                <button onclick="showScreen('notifications')" class="relative notification-dot invisible">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path
                            d="M15 17H20L18.595 15.595C18.21 15.21 18 14.702 18 14.172V11C18 8.386 16.301 6.165 14 5.342V5C14 3.895 13.105 3 12 3C10.895 3 10 3.895 10 5V5.342C7.699 6.165 6 8.386 6 11V14.172C6 14.702 5.79 15.21 5.405 15.595L4 17H9M15 17H9M15 17V18C15 19.657 13.657 21 12 21C10.343 21 9 19.657 9 18V17" />
                    </svg>
                </button>
                @if (Session::has('shopuser') || Session::has('public_user'))
                    <button onclick="Turbo.visit('{{ url('/shop-logout') }}')"
                        class="active:scale-90 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                    </button>
                @else
                    <span class="text-white text-xs">▮▮▮</span>
                @endif

                
            </div>
        </div>

        <!-- TICKER -->
        <div class="bg-ink-800 overflow-hidden py-1.5 px-4">
            <p class="ticker text-xs text-gold-300 font-medium whitespace-nowrap">
                @foreach ($shops_list as $sp1)
                    🎡 {{ucwords($sp1->shop_name)}}   &nbsp;•&nbsp;
                @endforeach
                
            </p>
        </div>



        <turbo-frame id="main-content">
            @yield('content')
        </turbo-frame>

        @include('front_layout.footer2')


        
    </div>
    @include('front_layout.script')

</body>
@stack('script')

</html>
