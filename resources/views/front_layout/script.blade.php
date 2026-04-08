<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function showScreen(name,slug=null) {
        // Agar server root par chal raha hai to path khali rakho, 
        // warna apna prefix dalo
       
        const basePath = window.location.host == 'localhost' ? '/jhansibazar' : '';
        // alert(window.location.host)
        var fullPath = basePath + '/' + name;
        if(slug) 
        {
            fullPath=fullPath + '/' + slug;
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
        ['offers', 'items','service', 'reviews', 'info'].forEach(t => {
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
</script>
