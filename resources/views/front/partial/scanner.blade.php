<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merchant Verification Scanner | JHS</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>

    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #f1f5f9; /* Light Slate Blue Background */
        }
        
        /* Official Security Pattern (ID Card se match karta hua) */
        .gov-pattern {
            background-image: radial-gradient(#cbd5e1 0.5px, transparent 0.5px), radial-gradient(#cbd5e1 0.5px, #f8fafc 0.5px);
            background-size: 20px 20px;
        }

        .scanner-line {
            position: absolute; width: 100%; height: 2px;
            background: #f97316; box-shadow: 0 0 15px #f97316;
            animation: scan 2s linear infinite; z-index: 10;
        }
        @keyframes scan { 0% { top: 0; } 100% { top: 100%; } }

        #reader video { 
            object-fit: cover !important; 
            border-radius: 1.5rem;
            border: 4px solid white;
        }

        .tab-active { 
            background: white; 
            color: #1e3a8a; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body class="gov-pattern min-h-screen flex flex-col items-center p-6">

    <div class="mt-8 mb-8 text-center">
        <div class="flex items-center justify-center gap-3 mb-2">
            <div class="w-10 h-10 bg-indigo-900 rounded-xl flex items-center justify-center text-white font-black shadow-md">JHS</div>
            <div class="text-left">
                <h2 class="text-[10px] font-black text-indigo-900 uppercase tracking-widest leading-none">Marketplace Authority</h2>
                <p class="text-lg font-black text-slate-800 tracking-tight">Security Scanner</p>
            </div>
        </div>
    </div>

    <div class="flex bg-slate-200/60 p-1 rounded-2xl border border-slate-300 mb-8 w-full max-w-[340px]">
        <button onclick="switchTab('live')" id="liveBtn" class="flex-1 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all tab-active flex items-center justify-center gap-2">
            <i data-lucide="camera" class="w-4 h-4"></i> Live
        </button>
        <button onclick="switchTab('upload')" id="uploadBtn" class="flex-1 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all text-slate-500 flex items-center justify-center gap-2">
            <i data-lucide="upload" class="w-4 h-4"></i> Gallery
        </button>
    </div>

    <div class="w-full max-w-[340px] relative">
        <div class="absolute -top-3 -left-3 w-12 h-12 border-t-4 border-l-4 border-indigo-900 rounded-tl-2xl z-20"></div>
        <div class="absolute -bottom-3 -right-3 w-12 h-12 border-b-4 border-r-4 border-orange-500 rounded-br-2xl z-20"></div>

        <div id="liveSection" class="relative bg-white rounded-[2.5rem] aspect-square overflow-hidden shadow-2xl border border-slate-200 p-2">
            <div class="scanner-line"></div>
            <div id="reader" class="w-full h-full"></div>
        </div>

        <div id="uploadSection" class="hidden">
            <div onclick="document.getElementById('qr-input').click()" class="bg-white rounded-[2.5rem] aspect-square flex flex-col items-center justify-center border-4 border-dashed border-slate-200 hover:border-indigo-500 transition-all cursor-pointer shadow-xl">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                    <i data-lucide="image-plus" class="w-8 h-8 text-indigo-600"></i>
                </div>
                <p class="text-slate-700 font-bold text-xs">Select ID Card Image</p>
                <p class="text-slate-400 text-[9px] uppercase tracking-widest mt-1">PNG, JPG or Screenshot</p>
                <input type="file" id="qr-input" accept="image/*" class="hidden">
                <div id="uploadStatus" class="mt-4 text-emerald-600 text-[10px] font-black uppercase hidden animate-pulse italic">Verifying QR Data...</div>
            </div>
        </div>
    </div>

    <div class="mt-12 max-w-[300px]">
        <div class="bg-indigo-50 border border-indigo-100 p-4 rounded-2xl">
            <div class="flex items-start gap-3">
                <i data-lucide="shield-check" class="w-5 h-5 text-indigo-600 shrink-0"></i>
                <div>
                    <p class="text-[10px] font-black text-indigo-900 uppercase">Authenticated Access</p>
                    <p class="text-[9px] text-indigo-700/70 leading-relaxed font-medium mt-0.5">Please align the QR code within the frame for instant merchant verification.</p>
                </div>
            </div>
        </div>
        <p class="text-center text-[8px] font-bold text-slate-400 mt-6 uppercase tracking-[0.2em]">© 2026 Listee Merchant Registry</p>
    </div>

    <script>
        lucide.createIcons();
        let html5QrCode;

        window.onload = () => {
            html5QrCode = new Html5Qrcode("reader");
            startLiveScan();
        };

        function startLiveScan() {
            const config = { fps: 25, qrbox: { width: 220, height: 220 } };
            html5QrCode.start({ facingMode: "environment" }, config, (text) => {
                if (navigator.vibrate) navigator.vibrate(100);
                window.location.href = text;
            });
        }

        function switchTab(type) {
            const liveBtn = document.getElementById('liveBtn');
            const uploadBtn = document.getElementById('uploadBtn');
            const liveSec = document.getElementById('liveSection');
            const uploadSec = document.getElementById('uploadSection');

            if(type === 'live') {
                liveBtn.classList.add('tab-active');
                uploadBtn.classList.remove('tab-active');
                liveSec.classList.remove('hidden');
                uploadSec.classList.add('hidden');
                startLiveScan();
            } else {
                uploadBtn.classList.add('tab-active');
                liveBtn.classList.remove('tab-active');
                uploadSec.classList.remove('hidden');
                liveSec.classList.add('hidden');
                html5QrCode.stop();
            }
        }

        document.getElementById('qr-input').addEventListener('change', e => {
            if (e.target.files.length === 0) return;
            const status = document.getElementById('uploadStatus');
            status.innerText = "Verifying QR Data...";
            status.classList.remove('hidden', 'text-red-600');
            status.classList.add('text-emerald-600');

            html5QrCode.scanFile(e.target.files[0], true)
                .then(text => window.location.href = text)
                .catch(() => {
                    status.innerText = "Error: Invalid QR Code";
                    status.classList.replace('text-emerald-600', 'text-red-600');
                });
        });
    </script>
</body>
</html>