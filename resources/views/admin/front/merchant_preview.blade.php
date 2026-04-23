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
        @media print { .no-print { display: none !important; } body { background: #fff; } }
    </style>
</head>
<body class="bg-slate-200 min-h-screen flex flex-col items-center justify-center p-4">

    <div class="no-print mb-4 flex gap-4">
        <a href="{{ route('admin.merchants') }}" class="bg-slate-800 text-white px-4 py-2 rounded text-sm font-bold">Back to List</a>
        <button onclick="window.print()" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm font-bold">Print/Download</button>
    </div>

    <div class="bg-white w-[420px] rounded-2xl shadow-2xl overflow-hidden border border-slate-300 id-border relative gov-pattern">
        <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between bg-slate-50/80">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-indigo-900 rounded-lg flex items-center justify-center text-white font-black text-sm">JHS</div>
                <div>
                    <h2 class="text-[10px] font-black text-indigo-900 uppercase leading-none">Marketplace Regulatory Authority</h2>
                    <p class="text-[16px] font-extrabold text-slate-800">Listee Verified Merchant</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-[11px] font-mono font-black text-indigo-600">2026</p>
            </div>
        </div>

        <div class="p-8 flex gap-6">
            <div class="text-center">
                <div class="p-1 border-2 border-slate-900 rounded-lg bg-white">
                    @php
                        $profileUrl = url('/shopprofile-details/' . str_replace(' ', '-', $shop->shop_name));
                        $qrUrl = "https://quickchart.io/qr?text=" . urlencode($profileUrl) . "&size=200";
                    @endphp
                    <img src="{{ $qrUrl }}" class="w-28 h-28" alt="QR">
                </div>
                <p class="text-[8px] mt-2 font-bold uppercase tracking-widest bg-slate-900 text-white py-1 rounded">Scan Profile</p>
            </div>

            <div class="flex-grow space-y-3">
                <div>
                    <label class="text-[8px] font-bold text-slate-400 uppercase">Business Entity</label>
                    <p class="text-md font-black text-slate-900 leading-tight">{{ $shop->shop_name }}</p>
                </div>
                <div>
                    <label class="text-[8px] font-bold text-slate-400 uppercase">Representative</label>
                    <p class="text-[12px] font-bold text-slate-700">{{ $shop->owner_name }}</p>
                </div>
                <div>
                    <label class="text-[8px] font-bold text-slate-400 uppercase">System ID</label>
                    <p class="text-[11px] font-mono font-bold text-indigo-700">JB-PRT-{{ str_pad($shop->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>

        <div class="mx-8 mb-8 p-4 bg-indigo-50/50 rounded-xl border border-indigo-100">
            <label class="text-[7px] font-black text-indigo-400 uppercase">Address</label>
            <p class="text-[10px] font-bold text-slate-600 italic uppercase">{{ $shop->address }}</p>
            <div class="flex justify-between mt-3">
                <div>
                    <label class="text-[7px] font-black text-indigo-400 uppercase">Contact</label>
                    <p class="text-[10px] font-bold">+91 {{ $shop->phone }}</p>
                </div>
                <div class="text-right">
                    <label class="text-[7px] font-black text-indigo-400 uppercase">Date</label>
                    <p class="text-[10px] font-bold">{{ date('d M Y') }}</p>
                </div>
            </div>
        </div>

        <div class="px-8 py-4 bg-slate-50 border-t flex justify-between items-center text-[8px] font-bold uppercase tracking-widest text-slate-400">
            <span>Verified Partner</span>
            <span class="text-slate-800">Authority Seal</span>
        </div>
    </div>
    <script>
        lucide.createIcons();
        // Automatic print trigger
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('print')) {
                window.print();
            }
        };
    </script>
</body>
</html>