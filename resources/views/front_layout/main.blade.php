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
        <div class="w-full max-w-md mx-auto bg-white overflow-hidden">
          <div class="pt-4 pb-8 px-4">
            
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="font-bold text-lg">Civil Lines, Jhansi</span>
              </div>
                @if (Session::has('shopuser') || Session::has('public_user'))
                    <button class="hover:opacity-80" onclick="Turbo.visit('{{ url('/shop-logout') }}')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                    </button>
                @endif
            </div>

            <div class="mt-5 w-full mb-3 z-[9999] absolute left-0 px-4">
                <div class="bg-white rounded-2xl shadow-lg border border-ink-100 px-4 py-3 flex items-center gap-3">
                    <svg class="w-4 h-4 text-ink-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.35-4.35" />
                    </svg>
                    <input type="text" placeholder="Dukan, service ya offer dhundho..."
                        class="flex-1 text-sm text-ink-600 bg-transparent border-none focus:outline-none placeholder-ink-300" />
                    <button class="w-7 h-7 gradient-brand rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <path d="M3 6h18M6 12h12M10 18h4" />
                        </svg>
                    </button>
                </div>
            </div>

          </div>
        </div>
        <div class="gradient-brand px-5 py-2 flex items-center justify-between sticky top-0 z-50">
            <span class="text-white text-xs font-semibold invisible">9:41</span>
            <div class="flex items-center gap-1.5">
                <button onclick="showScreen('notifications')" class="relative notification-dot invisible">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path
                            d="M15 17H20L18.595 15.595C18.21 15.21 18 14.702 18 14.172V11C18 8.386 16.301 6.165 14 5.342V5C14 3.895 13.105 3 12 3C10.895 3 10 3.895 10 5V5.342C7.699 6.165 6 8.386 6 11V14.172C6 14.702 5.79 15.21 5.405 15.595L4 17H9M15 17H9M15 17V18C15 19.657 13.657 21 12 21C10.343 21 9 19.657 9 18V17" />
                    </svg>
                </button>

                
            </div>
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
