
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
@push('script')
<script>
$(document).ready(function() {
    
    let pendingFollowBtn = null;

    // 1. Follow/Unfollow Logic (Using Class instead of ID for multiple buttons)
    $(document).on('click', '#followBtn', function(e) {
        e.preventDefault();
        let btn = $(this);
        let userId = btn.data('userid');
        let shopId = btn.data('shopid');

        // UI toggle
        toggleFollowUI(btn);

        $.ajax({
            url: "{{ url('/follow-user') }}",
            method: 'POST',
            data: {
                user_id: userId,
                shopId: shopId,
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                if (res.status == 'unfollowed' || res.status == 'error') {
                    pendingFollowBtn = btn;
                    resetFollowUI(btn);
                    
                    if (!localStorage.getItem('user_mobile')) {
                        $('#spinPopup').removeClass('hidden');
                    }
                }
                
            },
            error: function(xhr) {
                console.error("Route Error:", xhr.responseText);
                resetFollowUI(btn);
            }
        });
    });

    // 2. Mobile Login Logic with Loader
    $('#spinBtn').on('click', function() {
        const mobile = $('#userMobile').val();
        const $btn = $(this);

        if (mobile.length !== 10) {
            Swal.fire({ icon: 'error', text: 'Please enter 10 digits' });
            return;
        }

        $.ajax({
            url: "{{ route('save.mobile') }}",
            type: "POST",
            data: { _token: "{{ csrf_token() }}", mobile: mobile },
            beforeSend: function() {
                $btn.prop('disabled', true);
                $('#btnLoader').removeClass('hidden');
                $('#btnIcon').addClass('hidden');
                $('#btnText').text('Processing...');
            },
            success: function(data) {
                if (data.success) {
                    localStorage.setItem('user_mobile', mobile);
                    $('#spinPopup').fadeOut(300);

                    // Re-trigger following
                    if (pendingFollowBtn) {
                        pendingFollowBtn.trigger('click');
                        pendingFollowBtn = null;
                    }
                }
            },
            complete: function() {
                $btn.prop('disabled', false);
                $('#btnLoader').addClass('hidden');
                $('#btnIcon').removeClass('hidden');
                $('#btnText').text('Proceed');
            }
        });
    });

    // --- Helpers ---
    function toggleFollowUI(btn) {
        if (!btn.hasClass('is-following')) {
            btn.addClass('is-following bg-green-700 text-white').removeClass('bg-black');
            btn.text('Following');
        } else {
            btn.removeClass('is-following bg-green-700 text-white').addClass('bg-black text-white');
            btn.text('Follow');
        }
    }

    function resetFollowUI(btn) {
        btn.removeClass('is-following bg-green-700 text-white').addClass('bg-black text-white');
        btn.text('Follow');
    }

    $('#closePopup').on('click', function() { $('#spinPopup').addClass('hidden'); pendingFollowBtn = null; });
    $('#userMobile').on('input', function() { this.value = this.value.replace(/[^0-9]/g, ''); });
});
</script>
@if (!Session::has('public_user'))
        <script>
            localStorage.removeItem('user_mobile');
            
        </script>
    @endif
@endpush