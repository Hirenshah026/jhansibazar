<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | JHS Marketplace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top right, #1e3a8a, #0f172a);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-[400px]">
        <div class="text-center mb-8">
            <div
                class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-indigo-900 font-black text-2xl mx-auto shadow-2xl mb-4">
                JHS
            </div>
            <h1 class="text-white text-2xl font-black tracking-tight">Authority Portal</h1>
            <p class="text-slate-400 text-sm font-medium">Marketplace Regulatory Authority Admin</p>
        </div>

        <div class="bg-white rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.3)] overflow-hidden border border-white/10">
            <div class="p-8">
                <form action="{{ url('/admin/login-post') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest block mb-2">Admin
                            Identifier</label>
                        <div class="relative">
                            <i data-lucide="user"
                                class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                            <input type="text" name="email" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 pl-10 pr-4 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                placeholder="admin@jhs.org">
                        </div>
                    </div>

                    <div>
                        <label
                            class="text-[11px] font-black text-slate-400 uppercase tracking-widest block mb-2">Security
                            Passkey</label>
                        <div class="relative">
                            <i data-lucide="lock"
                                class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                            <input type="password" id="password" name="password" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 pl-10 pr-12 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                placeholder="••••••••">
                            <button type="button" onclick="togglePassword()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600">
                                <i data-lucide="eye" id="eyeIcon" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox"
                                class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-xs font-bold text-slate-600">Trust this device</span>
                        </label>
                        <a href="#" class="text-xs font-bold text-indigo-600 hover:underline">Forgot?</a>
                    </div>

                    <button type="submit" id="submitBtn"
                        class="w-full bg-indigo-900 hover:bg-indigo-800 text-white font-black py-4 rounded-xl shadow-lg shadow-indigo-200 transition-all active:scale-[0.98] flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">

                        <span id="btnText" class="flex items-center gap-2">
                            AUTHENTICATE <i data-lucide="shield-check" class="w-5 h-5"></i>
                        </span>

                        <span id="btnLoader" class="hidden">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </span>
                    </button>
                </form>
            </div>

            <div class="bg-slate-50 px-8 py-4 border-t border-slate-100 text-center">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter italic">
                    Secure encrypted connection active
                </p>
            </div>
        </div>

        <p class="text-center text-slate-500 text-[11px] mt-8 font-medium">
            &copy; 2026 JHS Marketplace Authority. All Rights Reserved.
        </p>
    </div>

    <script>
        lucide.createIcons();

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                passwordInput.type = 'password';
                eyeIcon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }
        // Form submission handler
        document.querySelector('form').addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnLoader = document.getElementById('btnLoader');
            // 1. Button disable karo taaki double click na ho
            btn.disabled = true;
            // 2. Text chhupao aur Loader dikhao
            btnText.classList.add('hidden');
            btnLoader.classList.remove('hidden');
            // Form submit hone do...
        });
    </script>
</body>

</html>