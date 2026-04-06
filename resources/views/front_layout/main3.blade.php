<!DOCTYPE html>
<html lang="en">

<head>
    @include('front_layout.head')
    @stack('css_or_link')
</head>

<body class="bg-ink-100 flex items-start justify-center min-h-screen py-4">

    <!-- PHONE WRAPPER -->
    <div class="relative w-full max-w-sm shadow-2xl rounded-3xl overflow-hidden bg-white" style="min-height:100vh"
        id="appRoot">

        <!-- STATUS BAR -->
        <div class="gradient-brand px-5 py-2 flex items-center justify-between sticky top-0 z-50">
            <span class="text-white text-xs font-semibold">9:41</span>
            <div class="flex items-center gap-1.5">
                <span class="text-white font-display font-bold text-base tracking-wide">Jhansi Bazaar</span>
                <span class="badge-new">BETA</span>
            </div>
            <div class="flex items-center gap-1.5">
                <button onclick="showScreen('notifications')" class="relative notification-dot">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path
                            d="M15 17H20L18.595 15.595C18.21 15.21 18 14.702 18 14.172V11C18 8.386 16.301 6.165 14 5.342V5C14 3.895 13.105 3 12 3C10.895 3 10 3.895 10 5V5.342C7.699 6.165 6 8.386 6 11V14.172C6 14.702 5.79 15.21 5.405 15.595L4 17H9M15 17H9M15 17V18C15 19.657 13.657 21 12 21C10.343 21 9 19.657 9 18V17" />
                    </svg>
                </button>
                <span class="text-white text-xs">▮▮▮</span>
            </div>
        </div>

        <!-- TICKER -->
        <div class="bg-ink-800 overflow-hidden py-1.5 px-4">
            <p class="ticker text-xs text-gold-300 font-medium whitespace-nowrap">
                🔥 Flash Deal: Sharma Sweets — Free Samosa on ₹200 &nbsp;•&nbsp; 🎡 Raj Shoe Store: Spin aur 20% OFF
                jeeto
                &nbsp;•&nbsp; 💊 City Hospital Health Card — ₹300 mein hamesha 20% off &nbsp;•&nbsp; ✂️ Glamour Salon:
                Navratri
                Special ₹499 Package &nbsp;•&nbsp; 🛒 Ramesh Kirana: Aaj ke fresh rates available
            </p>
        </div>



        <turbo-frame id="main-content">
            @yield('content')
        </turbo-frame>

        @include('front_layout.footer')
    </div>
    @include('front_layout.script')
    
</body>
@stack('script')

</html>
