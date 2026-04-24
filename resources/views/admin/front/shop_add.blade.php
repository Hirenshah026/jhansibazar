<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Merchant | JHS Admin</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .day-label { transition: all 0.2s ease; }
        /* Loader style */
        .btn-loader { border-top-color: transparent; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">

    <nav class="bg-indigo-900 text-white p-4 shadow-lg">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-indigo-900 font-black">JHS</div>
                <h1 class="font-bold tracking-tight">Marketplace Admin</h1>
            </div>
            <a href="{{ url('/admin/merchants') }}" class="bg-indigo-700 px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 hover:bg-indigo-600 transition-all">
                <i data-lucide="list" class="w-4 h-4"></i> View All Shops
            </a>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto py-10 px-4">
        <div class="mb-8">
            <h2 class="text-3xl font-black text-slate-800 italic uppercase">Onboard New Merchant</h2>
            <p class="text-slate-500 font-medium tracking-tight">Enter shop details to create a new registration in the database.</p>
        </div>

        <div id="ajaxAlert" class="hidden p-4 mb-6 rounded-xl border-l-4 shadow-sm"></div>

        <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
            <form id="shopForm">
                @csrf
                <div class="p-8 space-y-10">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-3 border-b border-slate-100 pb-2 flex items-center gap-2">
                            <i data-lucide="user-check" class="w-4 h-4 text-indigo-600"></i>
                            <h3 class="text-indigo-600 font-black text-[10px] uppercase tracking-[0.2em]">Merchant Identity</h3>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Shop Name</label>
                            <input type="text" name="shop_name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition-all" placeholder="e.g. Royal Fashion Hub">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Owner Name</label>
                            <input type="text" name="owner_name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition-all" placeholder="Full Name">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Phone Number</label>
                            <input type="tel" name="phone" maxlength="10" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition-all" placeholder="10 Digit Mobile">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Security PIN</label>
                            <input type="password" name="pin" maxlength="4" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition-all" placeholder="4 Digit PIN">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">WhatsApp Status</label>
                            <select name="is_whatsapp" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold outline-none focus:border-indigo-500 transition-all cursor-pointer">
                                <option value="1">Available on WhatsApp</option>
                                <option value="0">Not Available</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-3 border-b border-slate-100 pb-2 flex items-center gap-2">
                            <i data-lucide="clock" class="w-4 h-4 text-indigo-600"></i>
                            <h3 class="text-indigo-600 font-black text-[10px] uppercase tracking-[0.2em]">Shop Operations</h3>
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Full Address</label>
                            <textarea name="address" rows="2" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold outline-none focus:border-indigo-500 transition-all" placeholder="Shop No, Area, Near Landmark, Jhansi..."></textarea>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Opens At</label>
                            <input type="time" name="open_time" value="09:00" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold outline-none focus:border-indigo-500 transition-all">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Closes At</label>
                            <input type="time" name="close_time" value="21:00" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold outline-none focus:border-indigo-500 transition-all">
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 italic">Select Weekly Off Days</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                                <label class="day-label flex items-center px-5 py-2.5 bg-white border border-slate-200 rounded-xl cursor-pointer hover:border-indigo-300 shadow-sm transition-all">
                                    <input type="checkbox" name="off_days[]" value="{{ $day }}" class="hidden custom-checkbox">
                                    <span class="day-text text-xs font-black text-slate-500 uppercase tracking-tight">{{ $day }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 px-8 py-6 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
                    <button type="button" onclick="clearForm()" class="text-xs font-black text-red-400 hover:text-red-500 uppercase tracking-widest transition-all">
                        Reset All Fields
                    </button>
                    
                    <button type="submit" id="submitBtn" class="w-full md:w-auto bg-indigo-900 hover:bg-black text-white font-black px-16 py-4 rounded-2xl shadow-2xl shadow-indigo-200 transition-all flex items-center justify-center gap-3 active:scale-95">
                        <span id="btnText" class="flex items-center gap-2">CONFIRM & SAVE <i data-lucide="save" class="w-4 h-4"></i></span>
                        <div id="btnLoader" class="hidden w-5 h-5 border-2 border-white/20 border-t-white rounded-full animate-spin"></div>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        lucide.createIcons();

        // --- WEEKDAY FIX ---
        // jQuery use kar rahe hain taaki classes handle karna easy ho
        $('.custom-checkbox').on('change', function() {
            const label = $(this).closest('.day-label');
            const text = label.find('.day-text');
            
            if(this.checked) {
                label.addClass('bg-indigo-900 border-indigo-900');
                text.removeClass('text-slate-500').addClass('text-black');
            } else {
                label.removeClass('bg-indigo-900 border-indigo-900');
                text.removeClass('text-black').addClass('text-slate-500');
            }
        });

        // --- AJAX SUBMISSION ---
        $('#shopForm').on('submit', function(e) {
            e.preventDefault();
            
            const btn = $('#submitBtn');
            const text = $('#btnText');
            const loader = $('#btnLoader');
            const alertBox = $('#ajaxAlert');

            // UI Changes
            btn.prop('disabled', true).addClass('opacity-80');
            text.addClass('hidden');
            loader.removeClass('hidden');
            alertBox.addClass('hidden');

            $.ajax({
                url: "{{ url('/admin/store-shop') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    alertBox.removeClass('hidden bg-red-50 text-red-700 border-red-500')
                            .addClass('bg-green-50 text-green-700 border-green-500')
                            .html('<strong>Mubarak Ho!</strong> Merchant successfully save ho gaya.');
                    
                    clearForm(); // Form reset
                    setTimeout(() => { window.location.href = "{{ URL::Current() }}"; }, 2000);
                },
                error: function(xhr) {
                    let msg = "Kuch gadbad ho gayi!";
                    if(xhr.status === 422) {
                        msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                    }
                    alertBox.removeClass('hidden bg-green-50 text-green-700 border-green-500')
                            .addClass('bg-red-50 text-red-700 border-red-500')
                            .html('<strong>Error:</strong><br>' + msg);
                },
                complete: function() {
                    btn.prop('disabled', false).removeClass('opacity-80');
                    text.removeClass('hidden');
                    loader.addClass('hidden');
                }
            });
        });

        function clearForm() {
            const form = $('#shopForm')[0];
            form.reset();
            $('.custom-checkbox').prop('checked', false).trigger('change');
            $('#ajaxAlert').addClass('hidden');
        }
    </script>
</body>
</html>