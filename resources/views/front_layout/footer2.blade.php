<!-- ============================================================ -->
<!-- BOTTOM NAVIGATION -->
<!-- ============================================================ -->
<div
    class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-md bg-white border-t border-ink-100 z-50 shadow-[0_-10px_25px_-5px_rgba(0,0,0,0.1)]">
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
            class="nav-pill flex-1 flex flex-col hidden items-center gap-0.5 py-2.5 text-ink-400 rounded-none transition-all">
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
            class="nav-pill flex-1 hidden flex flex-col items-center gap-0.5 py-2.5 text-ink-400 rounded-none transition-all">
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
        @elseif (Session::has('public_user'))
            <button onclick="showScreen('user-profile')" id="nav-account"
                class="nav-pill flex-1 flex flex-col items-center gap-0.5 py-2.5 text-ink-400 rounded-none transition-all">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                <span class="text-xs font-semibold">Profile</span>
            </button>
        @else
            {{ Session::has('shopuser') }}
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

<div id="loginModal" class="fixed inset-0 z-[9999] flex items-end sm:items-center justify-center hidden p-0 sm:p-4">
    <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-[2px]"></div>

    <div
        class="relative bg-white w-full sm:max-w-[360px] rounded-t-2xl sm:rounded-2xl shadow-xl transform transition-all animate-slide-up overflow-hidden">

        <div class="h-1 w-10 bg-gray-200 rounded-full mx-auto mt-3 mb-2 sm:hidden"></div>

        <div class="px-6 py-4">
            <h3 class="text-lg font-bold text-gray-800">Login</h3>
            <p class="text-xs text-gray-500 mb-5">Enter details to access your account</p>

            <form id="ajaxLoginForm" class="space-y-4">
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-gray-400 text-sm font-medium">+91</span>
                    <input type="tel" id="mobile" name="mobile" required maxlength="10"
                        class="w-full pl-12 pr-3 py-2.5 bg-gray-50 border border-gray-100 rounded-lg text-sm outline-none focus:border-blue-500 focus:bg-white transition-all"
                        placeholder="Mobile Number">
                </div>

                <div>
                    <input type="password" id="pin" name="pin" maxlength="4" required
                        class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-lg text-sm outline-none focus:border-blue-500 focus:bg-white transition-all"
                        placeholder="4-digit Security PIN">
                </div>

                <button type="submit" id="loginBtn"
                    class="w-full bg-black text-white text-sm font-semibold py-3 rounded-lg hover:bg-gray-800 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                    Proceed
                </button>

                <p id="loginMsg" class="text-[11px] text-center h-4"></p>
            </form>

            <button id="closeLogin" class="w-full text-[11px] text-gray-400 font-medium py-2 mt-1">
                CLOSE
            </button>
        </div>
    </div>
</div>
<button id="openLogin" class="trigger-login hidden">Login Karne Ke Liye Dabayein</button>
