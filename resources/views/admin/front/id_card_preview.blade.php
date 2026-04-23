<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ID Card - {{ $shop->shop_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Inter', sans-serif; -webkit-print-color-adjust: exact; }
        .gov-pattern { background-color: #fff; background-image: radial-gradient(#cbd5e1 0.5px, transparent 0.5px), radial-gradient(#cbd5e1 0.5px, #ffffff 0.5px); background-size: 16px 16px; background-position: 0 0, 8px 8px; }
        .id-border { border-top: 10px solid #1e3a8a; border-bottom: 6px solid #f97316; }
        @media print { .no-print { display: none !important; } body { background: white; } .card-container { box-shadow: none !important; border: 1px solid #ddd; } }
    </style>
</head>
<body class="bg-slate-200 min-h-screen flex flex-col items-center justify-center p-4">

    <div class="no-print mb-6 flex gap-3">
        <button onclick="window.history.back()" class="bg-slate-800 text-white px-4 py-2 rounded-lg font-bold text-sm flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back
        </button>
        <button onclick="window.print()" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold text-sm shadow-lg flex items-center gap-2">
            <i data-lucide="printer" class="w-4 h-4"></i> Print / Save PDF
        </button>
    </div>

    <div class="card-container bg-white w-full max-w-[420px] rounded-2xl shadow-2xl overflow-hidden border border-slate-300 id-border relative gov-pattern">
        <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between bg-slate-50/80">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-indigo-900 rounded-lg flex items-center justify-center text-white font-black text-sm">JHS</div>
                <div>
                    <h2 class="text-[10px] font-black text-indigo-900 uppercase leading-none">Marketplace Regulatory Authority</h2>
                    <p class="text-[16px] font-extrabold text-slate-800 tracking-tight">Listee Verified Merchant</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-[11px] font-mono font-black text-indigo-600 mt-1">2026</p>
            </div>
        </div>

        <div class="p-8">
            <div class="flex gap-8 mb-8">
                <div class="flex-shrink-0 text-center">
                    <div class="p-1.5 border-2 border-slate-900 rounded-xl bg-white shadow-sm">
                        @php
                            $profileUrl = url('/shopprofile-details/' . str_replace(' ', '-', $shop->shop_name));
                            $qrUrl = "https://quickchart.io/qr?text=" . urlencode($profileUrl) . "&size=300&margin=1";
                        @endphp
                        <img src="{{ $qrUrl }}" class="w-32 h-32" alt="QR">
                    </div>
                </div>

                <div class="flex-grow space-y-4 pt-1">
                    <div>
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">Business Entity</label>
                        <p class="text-lg font-black text-slate-900 uppercase leading-tight">{{ $shop->shop_name }}</p>
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">Representative</label>
                        <p class="text-[13px] font-bold text-slate-700">{{ $shop->owner_name }}</p>
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">System ID</label>
                        <p class="text-[12px] font-mono font-bold text-indigo-700">JB-PRT-{{ str_pad($shop->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-indigo-50/40 rounded-xl p-5 border border-indigo-100 relative overflow-hidden">
                <div class="relative z-10 grid grid-cols-2 gap-y-5">
                    <div class="col-span-2">
                        <label class="text-[8px] font-black text-indigo-400 uppercase block">Registered Address</label>
                        <p class="text-[11px] font-bold text-slate-600 uppercase italic leading-tight">{{ $shop->address }}</p>
                    </div>
                    <div>
                        <label class="text-[8px] font-black text-indigo-400 uppercase">Contact</label>
                        <p class="text-[11px] font-bold text-slate-700">+91 {{ $shop->phone }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-8 py-5 bg-slate-50 border-t flex justify-between items-center">
            <p class="text-[8px] font-bold text-emerald-600 uppercase italic">Verified Business Partner</p>
            <div class="text-center">
                <div class="w-20 border-b-2 border-slate-300 mb-1 mx-auto"></div>
                <p class="text-[8px] font-black text-slate-800 uppercase">Seal</p>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
        // Agar 'download' link se aaye hain toh auto-print khulega
        @if(isset($autoPrint))
            window.onload = function() {
                setTimeout(() => { window.print(); }, 500);
            }
        @endif
    </script>
</body>
</html>