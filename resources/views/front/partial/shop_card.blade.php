<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Merchant ID - {{ $shop->shop_name }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            -webkit-print-color-adjust: exact; 
        }
        
        /* Official Security Pattern Background */
        .gov-pattern {
            background-color: #ffffff;
            background-image: radial-gradient(#cbd5e1 0.5px, transparent 0.5px), radial-gradient(#cbd5e1 0.5px, #ffffff 0.5px);
            background-size: 16px 16px;
            background-position: 0 0, 8px 8px;
        }

        /* Govt Style Top and Bottom Borders */
        .id-border {
            border-top: 10px solid #1e3a8a; /* Deep Govt Blue */
            border-bottom: 6px solid #f97316; /* Saffron Touch */
        }

        @media print {
            .no-print { display: none !important; }
            body { background: white; padding: 0; }
            .shadow-2xl { box-shadow: none !important; border: 1px solid #e2e8f0; }
            .mesh-gradient { background: #fff !important; }
        }
    </style>
</head>
<body class="bg-slate-200 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white w-full max-w-[420px] rounded-2xl shadow-2xl overflow-hidden border border-slate-300 id-border relative gov-pattern">
        
        <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between bg-slate-50/80">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-indigo-900 rounded-lg flex items-center justify-center text-white font-black text-sm shadow-md">
                    JB
                </div>
                <div>
                    <h2 class="text-[10px] font-black text-indigo-900 uppercase tracking-tighter leading-none">Marketplace Regulatory Authority</h2>
                    <p class="text-[16px] font-extrabold text-slate-800 tracking-tight">Jhansi Bazaar Verified Merchant</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-[8px] font-bold text-slate-400 uppercase leading-none tracking-widest">Issue Year</p>
                <p class="text-[11px] font-mono font-black text-indigo-600 leading-none mt-1">2026</p>
            </div>
        </div>

        <div class="p-8">
            <div class="flex gap-8 mb-8">
                <div class="flex-shrink-0 text-center">
                    <div class="p-1.5 border-2 border-slate-900 rounded-xl bg-white shadow-sm">
                        @php
                            // URL generation for QR
                            $cleanName = str_replace(' ', '-', $shop->shop_name ?? 'shop');
                            $profileUrl = url('/shopprofile-details/' . $cleanName);
                            $qrUrl = "https://quickchart.io/qr?text=" . urlencode($profileUrl) . "&size=300&margin=1";
                        @endphp
                        <img src="{{ $qrUrl }}" class="w-32 h-32" alt="Merchant QR">
                    </div>
                    <div class="mt-3 inline-flex items-center gap-1.5 bg-slate-900 px-3 py-1 rounded text-[8px] font-bold text-white uppercase tracking-widest">
                        <i data-lucide="scan" class="w-3 h-3 text-indigo-300"></i>
                        Scan Profile
                    </div>
                </div>

                <div class="flex-grow space-y-4 pt-1">
                    <div>
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">Business Entity</label>
                        <p class="text-lg font-black text-slate-900 uppercase leading-tight">{{ $shop->shop_name }}</p>
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">Primary Representative</label>
                        <p class="text-[13px] font-bold text-slate-700">{{ $shop->owner_name }}</p>
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">System ID</label>
                        <p class="text-[12px] font-mono font-bold text-indigo-700 uppercase">JB-PRT-{{ str_pad($shop->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-indigo-50/40 rounded-xl p-5 border border-indigo-100 relative overflow-hidden shadow-inner">
                <div class="absolute inset-0 flex items-center justify-center opacity-[0.04] pointer-events-none">
                    <span class="text-5xl font-black rotate-12 uppercase tracking-[0.2em]">JHANSI BAZAAR</span>
                </div>

                <div class="relative z-10 grid grid-cols-2 gap-y-5">
                    <div class="col-span-2">
                        <label class="text-[8px] font-black text-indigo-400 uppercase tracking-widest block mb-1">Registered Business Address</label>
                        <p class="text-[11px] font-bold text-slate-600 leading-normal uppercase italic tracking-tight">{{ $shop->address }}</p>
                    </div>
                    <div>
                        <label class="text-[8px] font-black text-indigo-400 uppercase tracking-widest block mb-1">Contact</label>
                        <p class="text-[11px] font-bold text-slate-700">+91 {{ $shop->phone }}</p>
                    </div>
                    <div class="text-right">
                        <label class="text-[8px] font-black text-indigo-400 uppercase tracking-widest block mb-1">Registration Date</label>
                        <p class="text-[11px] font-bold text-slate-700">
                            {{ $shop->created_at ? \Carbon\Carbon::parse($shop->created_at)->format('d M Y') : date('d M Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-8 py-5 bg-slate-50 border-t border-slate-200 flex justify-between items-center">
            <div>
                <p class="text-[8px] font-bold text-emerald-600 uppercase flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    Verified Business Partner
                </p>
                <p class="text-[7px] font-medium text-slate-400 mt-1 uppercase tracking-tighter italic">Digitally issued by JhansiBazaar.com Merchant Portal</p>
            </div>
            <div class="text-center">
                <div class="w-24 border-b-2 border-slate-300 h-6 mb-1 mx-auto"></div>
                <p class="text-[8px] font-black text-slate-800 uppercase tracking-widest">Authority Seal</p>
            </div>
        </div>
    </div>

    <div class="fixed bottom-10 right-10 no-print">
        <button onclick="window.print()" 
            class="group bg-indigo-900 text-white px-8 py-4 rounded-2xl font-black shadow-[0_20px_50px_rgba(30,58,138,0.4)] flex items-center gap-3 hover:bg-slate-800 hover:-translate-y-1 transition-all active:scale-95 border border-white/10">
            
            <i data-lucide="download-cloud" class="w-6 h-6 text-indigo-300"></i>
            
            <span>Download Official ID</span>
        </button>
    </div>

    <script>
        lucide.createIcons();
    </script>

</body>
</html>