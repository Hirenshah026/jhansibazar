{{-- ── FOLLOW LOGIN POPUP ── --}}
<div id="spinPopup"
    style="display:none; position:fixed; inset:0; z-index:9999; 
           background:rgba(15,23,42,0.6); backdrop-filter:blur(4px);
           align-items:center; justify-content:center; padding:16px">
    <div style="background:#fff; width:100%; max-width:320px; border-radius:20px; 
                box-shadow:0 20px 60px rgba(0,0,0,0.3); overflow:hidden; 
                border:1px solid #E0E8FF; position:relative">

        <button id="closePopup"
            style="position:absolute; top:12px; right:12px; background:#F1F5F9; 
                   border:none; border-radius:50%; width:28px; height:28px; 
                   cursor:pointer; font-size:16px; color:#64748b; 
                   display:flex; align-items:center; justify-content:center">✕</button>

        <div style="padding:24px">
            <div style="text-align:center; margin-bottom:20px">
                <div style="font-size:36px; margin-bottom:8px">👤</div>
                <p style="font-size:15px; font-weight:800; color:#1e293b">Quick Login</p>
                <p style="font-size:12px; color:#94a3b8; margin-top:4px">Enter your mobile to follow this shop</p>
            </div>

            <input type="tel" id="userMobile" maxlength="10"
                style="width:100%; padding:12px 16px; background:#F8FAFF; 
                       border:1.5px solid #E0E8FF; border-radius:12px; 
                       font-size:16px; font-weight:700; text-align:center; 
                       letter-spacing:0.1em; outline:none; 
                       font-family:'Nunito',sans-serif; color:#1e293b;
                       box-sizing:border-box; margin-bottom:12px"
                placeholder="10-digit Mobile Number">

            <div id="mobileError" 
                style="display:none; color:#EF4444; font-size:11px; 
                       font-weight:700; text-align:center; margin-bottom:8px"></div>

            <button id="proceedBtn"
                style="width:100%; background:#3B5BDB; color:#fff; border:none; 
                       border-radius:12px; padding:13px; font-size:14px; 
                       font-weight:800; cursor:pointer; font-family:'Nunito',sans-serif;
                       transition:opacity .15s; display:flex; align-items:center; 
                       justify-content:center; gap:8px">
                <span id="proceedBtnText">Proceed & Follow</span>
                <svg id="proceedBtnIcon" width="16" height="16" viewBox="0 0 24 24" 
                     fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
                <svg id="proceedBtnLoader" 
                    style="display:none; animation:spin-slow 1s linear infinite" 
                    width="16" height="16" viewBox="0 0 24 24" fill="none" 
                    stroke="currentColor" stroke-width="2.5">
                    <path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0"/>
                </svg>
            </button>

            <p style="text-align:center; font-size:10px; color:#cbd5e1; margin-top:12px">
                🔒 Your data is secure and will not be shared
            </p>
        </div>
    </div>
</div>

@push('script')
<script>
$(document).ready(function () {

    // ── State ──
    let pendingShopId = null;

    // ── Show popup as flex ──
    function showPopup(shopId) {
        pendingShopId = shopId;
        $('#userMobile').val('');
        $('#mobileError').hide();
        $('#spinPopup').css('display', 'flex');
    }

    function hidePopup() {
        $('#spinPopup').css('display', 'none');
        pendingShopId = null;
    }

    // ── Close popup ──
    $('#closePopup').on('click', hidePopup);
    $('#spinPopup').on('click', function (e) {
        if ($(e.target).is('#spinPopup')) hidePopup();
    });

    // ── Mobile input: numbers only ──
    $('#userMobile').on('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
        $('#mobileError').hide();
    });

    // ── Follow button click ──
    $(document).on('click', '#followBtn', function (e) {
        e.preventDefault();
        const btn      = $(this);
        const shopId   = btn.data('shopid');
        const userId   = btn.data('userid');
        const isLogged = btn.data('islogged') === 'true';

        // Not logged in → show popup first
        if (!isLogged || !userId || userId == 0) {
            showPopup(shopId);
            return;
        }

        // Logged in → call follow API directly
        doFollow(shopId, userId, btn);
    });

    // ── Proceed button (after mobile entry) ──
    $('#proceedBtn').on('click', function () {
        const mobile = $('#userMobile').val().trim();

        if (mobile.length !== 10) {
            $('#mobileError').text('Please enter a valid 10-digit number').show();
            return;
        }

        const $btn = $(this);
        $btn.prop('disabled', true);
        $('#proceedBtnText').text('Processing…');
        $('#proceedBtnIcon').hide();
        $('#proceedBtnLoader').show();

        $.ajax({
            url: "{{ route('save.mobile') }}",
            type: 'POST',
            data: {
                _token:  '{{ csrf_token() }}',
                mobile:  mobile,
                shopid:  pendingShopId   // ← lowercase matches controller
            },
            success: function (data) {
                if (data.success) {
                    hidePopup();

                    if (data.isFollowed) {
                        // Already following → just reload to show "Followed" state
                        location.reload();
                        return;
                    }

                    // Update button with real user ID, mark as logged
                    const $followBtn = $('#followBtn');
                    $followBtn.attr('data-userid', data.id);
                    $followBtn.attr('data-islogged', 'true');

                    // Now do the actual follow (no re-click, direct call)
                    doFollow(pendingShopId, data.id, $followBtn);
                } else {
                    $('#mobileError').text(data.message || 'Something went wrong.').show();
                }
            },
            error: function () {
                $('#mobileError').text('Server error. Please try again.').show();
            },
            complete: function () {
                $btn.prop('disabled', false);
                $('#proceedBtnText').text('Proceed & Follow');
                $('#proceedBtnIcon').show();
                $('#proceedBtnLoader').hide();
            }
        });
    });

    // ── Core follow API call ──
    function doFollow(shopId, userId, btn) {
        // Optimistic UI update
        setFollowingUI(btn, true);

        $.ajax({
            url:    "{{ url('/follow-user') }}",
            method: 'POST',
            data: {
                _token:  '{{ csrf_token() }}',
                user_id: userId,
                shopId:  shopId
            },
            success: function (res) {
                if (res.status === 'followed') {
                    // Reload so server-rendered "Followed" label shows
                    setTimeout(() => location.reload(), 800);
                } else if (res.status === 'unfollowed') {
                    setFollowingUI(btn, false);
                } else {
                    // Error (user_id was 0 etc.)
                    setFollowingUI(btn, false);
                }
            },
            error: function () {
                setFollowingUI(btn, false);
            }
        });
    }

    // ── Button UI helpers ──
    function setFollowingUI(btn, isFollowing) {
        if (isFollowing) {
            btn.text('✓ Following')
               .css({
                   background: '#16A34A',
                   color: '#fff',
                   opacity: '0.85'
               });
        } else {
            btn.text('+ Follow')
               .css({
                   background: '#3B5BDB',
                   color: '#fff',
                   opacity: '1'
               });
        }
    }
});
</script>

@if (!Session::has('public_user'))
<script>
    // Clear stale mobile from localStorage if session expired
    localStorage.removeItem('user_mobile');
</script>
@endif
@endpush