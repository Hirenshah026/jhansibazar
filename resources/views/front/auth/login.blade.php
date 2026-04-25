<!DOCTYPE html>
@extends('front_layout.main')
@push('css_or_link')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        /* App Style Toast */
        .app-toast {
            border-radius: 20px !important;
            font-size: 14px !important;
        }

        /* Eye icon button */
        .eye-btn {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            color: #9ca3af;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }

        .eye-btn:hover {
            color: #2e7d32;
        }
    </style>
@endpush
@section('content')
    <div class="flex items-center justify-center min-h-[80vh] px-4 py-10">

        <div class="w-full max-w-md bg-white rounded-[40px] shadow-[0_20px_50px_rgba(0,0,0,0.1)] overflow-hidden">
            <div class="bg-[#2e7d32] p-12 text-center text-white">
                <div
                    class="w-20 h-20 bg-white/20 backdrop-blur-lg rounded-[25px] mx-auto flex items-center justify-center mb-4 border border-white/30 shadow-inner hidden">
                    <span class="text-4xl">JB</span>
                </div>
                <h1 class="text-2xl font-bold tracking-tight">Jhansi Bazaar</h1>
                <p class="text-green-100 text-[10px] font-bold tracking-[3px] uppercase mt-1 opacity-60">Digital Store</p>
            </div>

            <div class="p-8 pb-12">
                {{-- autocomplete="off" form par bhi lagaya --}}
                <form id="loginForm" autocomplete="off">
                    <div class="mb-5">
                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Mobile
                            Number</label>
                        <input type="text" id="username" maxlength="10" inputmode="numeric"
                            autocomplete="off"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="10 digit number"
                            class="w-full px-6 py-4 bg-gray-100 border-none rounded-2xl focus:ring-2 focus:ring-[#2e7d32] transition-all outline-none text-gray-800 font-semibold"
                            required>
                    </div>

                    <div class="mb-8">
                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Secure
                            Pin</label>
                        {{-- Wrapper div position relative ke saath --}}
                        <div class="relative">
                            <input type="password" id="password" maxlength="6" inputmode="numeric"
                                autocomplete="new-password"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="Enter Pin"
                                class="w-full px-6 py-4 pr-14 bg-gray-100 border-none rounded-2xl focus:ring-2 focus:ring-[#2e7d32] transition-all outline-none text-gray-800 font-semibold tracking-[5px]"
                                required>
                            {{-- Eye Icon Button --}}
                            <button type="button" class="eye-btn" id="togglePin" tabindex="-1">
                                {{-- Eye (show) icon --}}
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{-- Eye-off (hide) icon - default hidden --}}
                                <svg id="eyeOffIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none;">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 012.087-3.36M6.53 6.53A9.97 9.97 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.97 9.97 0 01-4.073 5.002M6.53 6.53L3 3m3.53 3.53l11.94 11.94M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" id="submitBtn"
                        class="w-full bg-[#2e7d32] hover:bg-[#1b5e20] text-white font-bold py-4 rounded-2xl shadow-lg shadow-green-900/30 active:scale-[0.96] transition-all text-lg">
                        Login Now
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-gray-400 text-sm">
                        Account nahi hai? <a href="{{url('/shop-register')}}" class="text-[#2e7d32] font-bold">Register</a>
                    </p>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('script')
    <script>
        // App Style Toast Configuration
        const AppToast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 5500,
            timerProgressBar: true,
            customClass: {
                popup: 'app-toast'
            }
        });

        $(document).ready(function() {

            // --- Eye Icon Toggle ---
            $('#togglePin').on('click', function() {
                const passInput = $('#password');
                const eyeIcon = $('#eyeIcon');
                const eyeOffIcon = $('#eyeOffIcon');

                if (passInput.attr('type') === 'password') {
                    passInput.attr('type', 'text');
                    eyeIcon.hide();
                    eyeOffIcon.show();
                } else {
                    passInput.attr('type', 'password');
                    eyeIcon.show();
                    eyeOffIcon.hide();
                }
            });

            // --- Login Form Submit ---
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                let mobile = $('#username').val();
                let pin = $('#password').val();

                if (mobile.length < 10) {
                    AppToast.fire({
                        icon: 'warning',
                        title: 'Number sahi daalein'
                    });
                    return;
                }

                const btn = $('#submitBtn');
                btn.html(`
                <svg class="animate-spin h-6 w-6 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            `).prop('disabled', true);

                $.ajax({
                    url: "{{ url('/shop-login-ajax') }}",
                    type: 'POST',
                    data: {
                        mobile: mobile,
                        pin: pin,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            AppToast.fire({
                                icon: 'success',
                                title: 'Login Success!',
                                text: response.message
                            });

                            setTimeout(() => {
                                if (typeof Turbo !== 'undefined') {
                                    Turbo.visit("{{ url('/') }}");
                                } else {
                                    window.location.href = "{{ url('/') }}";
                                }
                            }, 50);
                        } else {
                            AppToast.fire({
                                icon: 'error',
                                title: 'Login Fail',
                                text: response.message
                            });
                            btn.html('Login Now').prop('disabled', false);
                        }
                    },
                    error: function(err) {
                        AppToast.fire({
                            icon: 'error',
                            title: 'Login Fail',
                            text: 'Mobile ya Pin galat hai!'
                        });
                        btn.html('Login Now').prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush