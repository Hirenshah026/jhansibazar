<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function showScreen(name, slug = null) {
        // Agar server root par chal raha hai to path khali rakho, 
        // warna apna prefix dalo

        const basePath = window.location.host == 'localhost' ? '/jhansibazar' : '';
        // alert(window.location.host)
        var fullPath = basePath + '/' + name;
        if (slug) {
            fullPath = fullPath + '/' + slug;
        }
        Turbo.visit(fullPath, {
            frame: 'main-content',
            action: 'advance'
        });

        window.history.pushState(null, '', fullPath);
    }
    $(document).on('turbo:load', function() {
        // Current URL lelo
        const currentPath = window.location.pathname;

        // Saare nav-pills se 'active' hatao
        $('.nav-pill').removeClass('active');

        // Check karo ki URL mein kaunsa keyword hai
        if (currentPath.includes('home')) {
            $('#nav-home').addClass('active');
        } else if (currentPath.includes('rozana')) {
            $('#nav-rozana').addClass('active');
        } else if (currentPath.includes('wallet')) {
            $('#nav-wallet').addClass('active');
        } else if (currentPath.includes('account')) {
            $('#nav-account').addClass('active');
        }
    });
</script>
<script>
    function goBack() {
        window.history.back();
    }

    function filterCat(btn, cat) {
        const container = btn.closest('.flex') || btn.parentElement;
        container.querySelectorAll('.cat-chip').forEach(c => {
            c.classList.remove('cat-active');
            c.classList.add('bg-white', 'text-ink-500', 'border-ink-200');
            c.classList.remove('border-saffron-200');
        });
        btn.classList.add('cat-active');
        btn.classList.remove('bg-white', 'text-ink-500');
        const items = document.querySelectorAll('#shopList .shop-card, .flex.flex-col.gap-3 [data-cat]');
        items.forEach(c => {
            c.style.display = (cat === 'all' || c.dataset.cat === cat) ? '' : 'none';
        });
    }

    function shopTab(btn, tab) {
        ['offers', 'items', 'service', 'reviews', 'info'].forEach(t => {
            const el = document.getElementById('shopTab-' + t);
            const tb = document.getElementById('tab-' + t);
            if (el) el.classList.toggle('hidden', t !== tab);
            if (tb) {
                tb.classList.toggle('tab-active', t === tab);
                tb.classList.toggle('text-ink-400', t !== tab);
            }
        });
    }
    const Toast = Swal.mixin({
        toast: true,
        position: 'bottom',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true,
        background: '#3595ff',
        color: '#fff'
    });

    $(document).ready(function() {
    
    // Modal Open Karne Ke Liye (Is class ko apne login button par add kar dena)
    $('.trigger-login').click(function() {
        $('#loginModal').removeClass('hidden').addClass('flex');
        $('body').addClass('overflow-hidden'); // Background scroll rokne ke liye
    });

    // Modal Close logic
    $('#closeLogin, .absolute.inset-0').click(function() {
        $('#loginModal').addClass('hidden').removeClass('flex');
        $('body').removeClass('overflow-hidden');
    });

    // AJAX Submission
    $('#ajaxLoginForm').on('submit', function(e) {
        e.preventDefault();

        const loginBtn = $('#loginBtn');
        const loginMsg = $('#loginMsg');
        
        // UI Feedback
        loginBtn.prop('disabled', true).addClass('opacity-70').html('<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...');

        $.ajax({
            url: 'api/login.php', // Tera endpoint
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if(res.status === 'success') {
                    loginMsg.addClass('text-green-600').text('Success! Redirecting...');
                    window.location.href = 'dashboard.php';
                } else {
                    loginMsg.addClass('text-red-500').text('Invalid Mobile or PIN');
                    resetBtn(loginBtn);
                }
            },
            error: function() {
                loginMsg.addClass('text-red-500').text('Connection error!');
                resetBtn(loginBtn);
            }
        });
    });

    function resetBtn(btn) {
        btn.prop('disabled', false).removeClass('opacity-70').html('Login Securely');
    }
});
</script>
