<div id="spinPopup"
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden p-4">
    <div class="bg-white w-full max-w-[320px] rounded-2xl shadow-xl overflow-hidden border border-gray-200 relative">

        <button id="closePopup" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
            <i data-lucide="x-circle" class="w-6 h-6"></i>
        </button>

        <div class="p-6">
            <div class="flex flex-col items-center text-center mb-6">
                <p class="text-xs text-gray-500 mt-1">Please enter your mobile number </p>
            </div>

            <div class="space-y-4">
                <div class="relative">
                    <input type="tel" id="userMobile" maxlength="10"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-center text-lg font-semibold tracking-widest transition-all"
                        placeholder="Enter Mobile Number">
                </div>

                <button id="spinBtn"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-lg transition-colors flex items-center justify-center gap-2">
                    <span>Proceed</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </div>

            <p class="text-center text-[10px] text-gray-400 mt-4">
                Your data is secure and will not be shared.
            </p>
        </div>
    </div>
</div>

<div id="followPopup"
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden p-4">
    <div class="bg-white w-full max-w-[320px] rounded-2xl shadow-xl overflow-hidden border border-gray-200 relative">

        <button id="closeFollowPopup" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
            <i data-lucide="x-circle" class="w-6 h-6"></i>
        </button>

        <div class="p-6">
            <div class="flex flex-col items-center text-center mb-6">
                <p class="text-xs text-gray-500 mt-1">Please enter your mobile number </p>
            </div>

            <div class="space-y-4">
                <div class="relative">
                    <input type="tel" id="userFollowMobile" maxlength="10"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-center text-lg font-semibold tracking-widest transition-all"
                        placeholder="Enter Mobile Number">
                </div>

                <button id="followPopupBtn"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-lg transition-colors flex items-center justify-center gap-2">
                    <span>Proceed</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </div>

            <p class="text-center text-[10px] text-gray-400 mt-4">
                Your data is secure and will not be shared.
            </p>
        </div>
    </div>
</div>

@push('script')

    <script>
        $(document).ready(function() {
            $('#userMobile').on('input', function() {
                // Sirf numbers rehne dega, baaki sab remove kar dega
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            $('#userFollowMobile').on('input', function() {
                // Sirf numbers rehne dega, baaki sab remove kar dega
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            // Agar local storage me number nahi hai, tabhi popup dikhao
            if (!localStorage.getItem('user_mobile')) {
                document.getElementById('spinPopup').classList.remove('hidden');
            }
        });

        document.getElementById('spinBtn').addEventListener('click', function() {
            const mobile = document.getElementById('userMobile').value;

            // Validation: Exactly 10 digits
            if (mobile.length !== 10) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Number',
                    text: 'Please enter a valid 10-digit mobile number.',
                    confirmButtonColor: '#4f46e5'
                });
                return;
            }

            // 1. LocalStorage me Save
            localStorage.setItem('user_mobile', mobile);

            // 2. DB me Save (Laravel AJAX)
            fetch("{{ route('save.mobile') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        mobile: mobile,
                        shopid:shopId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if(data.isFollowed)
                        {
                            alreadyFollowed = true;
                        }
                        document.getElementById('spinPopup').classList.add('hidden');
                        setTimeout(() => {
                            document.getElementById('spinStatusMsg').textContent = '🎉 Spin ho raha hai...';
                            triggerSpin();
                        }, 500);
                    }
                })
                .catch(err => console.error("Database error:", err));
        });
        $('#closePopup').on('click', function() {
            $('#spinPopup').addClass('hidden');
            
        });
        $('#closeFollowPopup').on('click', function() {
            $('#followPopup').addClass('hidden');
        });
    </script>
    @if (!Session::has('public_user'))
        <script>
            localStorage.removeItem('user_mobile');
            if (!localStorage.getItem('user_mobile')) {
                document.getElementById('spinPopup').classList.remove('hidden');
            }
        </script>
    @endif
@endpush
