

<!-- ============================================================ -->
<!-- BOTTOM NAVIGATION -->
<!-- ============================================================ -->
<div class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-md bg-white border-t border-ink-100 z-50 shadow-[0_-10px_25px_-5px_rgba(0,0,0,0.1)]">
    <div class="flex items-center">
        <button onclick="showScreen('home')" id="nav-home"
            class="nav-pill active flex-1 flex flex-col items-center gap-0.5 py-2.5 rounded-none transition-all">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                <path d="M9 22V12h6v10" />
            </svg>
            <span class="text-xs font-semibold">Home</span>
        </button>
        <button onclick="showScreen('rozana')" id="nav-rozana"
            class="nav-pill flex-1 flex flex-col items-center gap-0.5 py-2.5 text-ink-400 rounded-none transition-all">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="3" />
                <path
                    d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83" />
            </svg>
            <span class="text-xs font-semibold">Rozana</span>
        </button>
        <!-- Center Spin Button -->
        <button onclick="showScreen('spin')" id="nav-spin" class="flex flex-col items-center -mt-5 mx-1">
            <div
                class="gradient-brand w-14 h-14 rounded-full flex items-center justify-center shadow-lg border-4 border-white btn-press">
                <span class="text-2xl">🎡</span>
            </div>
            <span class="text-xs font-semibold text-saffron-500 mt-0.5">Spin</span>
        </button>
        <button onclick="showScreen('wallet')" id="nav-wallet"
            class="nav-pill flex-1 flex flex-col items-center gap-0.5 py-2.5 text-ink-400 rounded-none transition-all">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <rect x="2" y="5" width="20" height="14" rx="2" />
                <path d="M2 10h20" />
                <circle cx="16" cy="15" r="1.5" fill="currentColor" />
            </svg>
            <span class="text-xs font-semibold">Wallet</span>
        </button>
        @if (Session::has('shopuser'))
            <button onclick="showScreen('account')" id="nav-account"
                class="nav-pill flex-1 flex flex-col items-center gap-0.5 py-2.5 text-ink-400 rounded-none transition-all">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                <span class="text-xs font-semibold">Account</span>
            </button>
        @else
        {{Session::has('shopuser')}}
            <button onclick="showScreen('login')" id="nav-account"
                class="nav-pill flex-1 flex flex-col items-center gap-0.5 py-2.5 text-ink-400 rounded-none transition-all">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                <span class="text-xs font-semibold">Login</span>
            </button>
        @endif
    </div>
</div>
