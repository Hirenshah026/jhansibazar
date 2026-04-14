<div id="spinPopup"
    class="fixed inset-0 z-[999] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden p-4">
    <div class="bg-white w-full max-w-[320px] rounded-2xl shadow-xl overflow-hidden border border-gray-200">

        <div class="p-6">
            <div class="flex flex-col items-center text-center mb-6">
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-3">
                    <i class="fa-solid fa-shield-halved text-xl"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-900">Verification Required</h2>
                <p class="text-xs text-gray-500 mt-1">Please enter your mobile number to continue to the lucky spin.</p>
            </div>

            <div class="space-y-4">
                <div class="relative">
                    <input type="tel" id="userMobile"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-center text-lg font-semibold tracking-widest transition-all"
                        placeholder="Enter Mobile Number">
                </div>

                <button id="spinBtn"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-lg transition-colors flex items-center justify-center gap-2">
                    <span>Proceed to Spin</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
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
                        mobile: mobile
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('spinPopup').classList.add('hidden');
                        // Success feedback
                        Swal.fire({
                            icon: 'success',
                            title: 'Verified',
                            timer: 1000,
                            showConfirmButton: false
                        });
                        // Aapka spin code yahan se start hoga
                    }
                })
                .catch(err => console.error("Database error:", err));
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
