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
                <form id="loginForm" autocomplete="off">
                    <div class="mb-5">
                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Mobile
                            Number</label>
                        <input type="text" id="username" maxlength="10" inputmode="numeric"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="10 digit number"
                            class="w-full px-6 py-4 bg-gray-100 border-none rounded-2xl focus:ring-2 focus:ring-[#2e7d32] transition-all outline-none text-gray-800 font-semibold"
                            required>
                    </div>

                    <div class="mb-8">
                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Secure
                            Pin</label>
                        <input type="password" id="password" maxlength="6" inputmode="numeric"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="Enter Pin"
                            class="w-full px-6 py-4 bg-gray-100 border-none rounded-2xl focus:ring-2 focus:ring-[#2e7d32] transition-all outline-none text-gray-800 font-semibold tracking-[5px]"
                            required>
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
            timer: 2000,
            timerProgressBar: true,
            customClass: {
                popup: 'app-toast'
            }
        });

        $(document).ready(function() {
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                let mobile = $('#username').val();
                let pin = $('#password').val();

                // Chota validation
                if (mobile.length < 10) {
                    AppToast.fire({
                        icon: 'warning',
                        title: 'Number sahi daalein'
                    });
                    return;
                }

                // Button Loading State (App Style Spinner)
                const btn = $('#submitBtn');
                btn.html(`
                <svg class="animate-spin h-6 w-6 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            `).prop('disabled', true);

                // AJAX Call
                $.ajax({
                    url: "{{ url('/shop-login-ajax') }}",
                    type: 'POST',
                    data: {
                        mobile: mobile,
                        pin: pin,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if(response.success)
                        {
                            AppToast.fire({
                                icon: 'success',
                                title: 'Login Success!',
                                text: response.message
                            });

                            // --- TURBO REDIRECT YAHAN HAI ---
                            setTimeout(() => {
                                if (typeof Turbo !== 'undefined') {
                                    // Agar Turbo installed hai toh ye best hai
                                    Turbo.visit("{{ url('/') }}");
                                } else {
                                    // Backup agar Turbo load na hua ho
                                    window.location.href = "{{ url('/') }}";
                                }
                            }, 1500);
                        }
                        else
                        {
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

                        // Reset Button
                        btn.html('Login Now').prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush
