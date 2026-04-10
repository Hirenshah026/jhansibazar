@extends('front_layout.main')

@push('css_or_link')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet" />
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 0;
        }

        body {
            background: #f8fafc;
            min-height: 100vh;
        }

        .header-bar {
            background: #16A34A;
            padding: 13px 16px;
            display: flex;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .pin-container {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            border: 1.5px solid #C7D7FF;
            margin: 20px 16px;
            text-align: center;
        }

        .pin-input {
            width: 100%;
            letter-spacing: 15px;
            text-align: center;
            font-size: 24px;
            font-weight: 800;
            background: #fff;
            border: 2px solid #ddd;
            border-radius: 12px;
            padding: 12px;
            outline: none;
            transition: 0.2s;
        }

        .pin-input:focus {
            border-color: #16A34A;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
        }

        .btn-p {
            background: #16A34A;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 800;
            cursor: pointer;
            padding: 14px 20px;
            width: 100%;
            margin-top: 20px;
            font-size: 16px;
        }

        #global-loader {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.8);
            z-index: 2000;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #16A34A;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush

@section('content')
    <div style="max-width:480px; margin:0 auto;">

        <div id="global-loader">
            <div class="spinner"></div>
            <p style="margin-top:10px; font-weight:800; color:#16A34A">Updating PIN...</p>
        </div>

        <div class="header-bar">
            <button onclick="window.history.back()"
                style="color:#fff; background:none; border:none; font-size:20px;">←</button>
            <p style="color:#fff; font-size:16px; font-weight:800; margin-left:15px">Security Settings</p>
        </div>

        <div class="pin-container">
            <div style="margin-bottom: 20px;">
                <span style="font-size: 40px;">🔐</span>
                <h2 style="font-weight: 800; color: #1e293b; margin-top: 10px;">Set Your PIN</h2>
                <p style="color: #64748b; font-size: 14px;">Apne account ko secure karne ke liye 4 digit ka PIN set karein.
                </p>
            </div>

            <input type="password" id="user-pin" class="pin-input" maxlength="6" inputmode="numeric" placeholder="****">

            <button onclick="updatePin()" class="btn-p">Save Security PIN</button>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function updatePin() {
            let pin = $('#user-pin').val();

            if (pin.length < 4 || isNaN(pin)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid PIN',
                    text: 'Bhai,  4 - 6 digits ka PIN dalo.',
                    confirmButtonColor: '#16A34A'
                });
                return;
            }

            $('#global-loader').css('display', 'flex');

            $.ajax({
                url: "{{ route('settings.update-pin') }}",
                type: "POST",
                data: {
                    pin: pin
                },
                success: function(res) {
                    $('#global-loader').hide();
                    if (res.status == 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'PIN Updated!',
                            text: 'Aapka PIN save ho gaya hai.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        let previousPage = document.referrer;

                        // Agar pichla page 'set-pin-shop' tha, toh '/account' bhej do
                        if (previousPage.includes('set-pin-shop')) {
                            window.location.href = "{{ url('/account') }}";
                        }
                        // Warna jahan se aaya tha wahin wapas bhej do
                        else {
                            window.location.href = previousPage || "{{ url('/account') }}";
                        }
                    }
                },
                error: function() {
                    $('#global-loader').hide();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: 'Server par dikkat aa rahi hai.'
                    });
                }
            });
        }
    </script>
@endpush
