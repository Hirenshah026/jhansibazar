<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Merchant Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            transition: all 0.2s;
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen">

    <nav class="bg-indigo-900 text-white p-4 shadow-lg">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-indigo-900 font-black">
                    JHS</div>
                <h1 class="font-bold tracking-tight">LISTEE Marketplace Admin</h1>
            </div>
            <div class="flex gap-4">
                <button class="text-sm font-semibold opacity-80 hover:opacity-100">Support</button>
                <button class="bg-indigo-700 px-4 py-2 rounded-lg text-sm font-bold" onclick="location.assign('{{url('/admin/login')}}')">Logout</button>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-10 px-4">
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
            <div>
                <h2 class="text-3xl font-black text-slate-800">Verified Merchants</h2>
                <p class="text-slate-500 font-medium">Manage business identities and digital ID generation</p>
            </div>
            <div class="flex gap-3">
                <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 min-w-[120px] stats-card">
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Total Shops</p>
                    <p class="text-2xl font-black text-indigo-600">{{ $shops->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">
                                Merchant Details</th>
                            <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">
                                Representative</th>
                            <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">Login
                                Detail</th>
                            <th
                                class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest text-right">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($shops as $shop)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <i data-lucide="store" class="w-5 h-5 text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 leading-none mb-1 capitalize">{{ $shop->shop_name }}</p>
                                        <p class="text-xs text-slate-500 italic truncate max-w-[200px]">
                                            {{ $shop->address }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-sm font-semibold text-slate-700">{{ $shop->owner_name }}</p>
                                <p class="text-xs text-slate-600 font-mono">+91 {{ $shop->phone }}</p>
                            </td>
                            <td class="px-6 py-5 space-y-2">
                                <div class="flex items-center gap-2">
                                    <span id="userId-{{ $shop->id }}"
                                        class="bg-indigo-50 text-indigo-700 text-[10px] font-bold px-2.5 py-1 rounded-md border border-indigo-100 flex items-center gap-2">
                                        User Id - {{ $shop->phone }}
                                    </span>
                                    <button onclick="copyToClipboard('{{ $shop->phone }}', this)"
                                        class="text-indigo-400 hover:text-indigo-700 transition-colors"
                                        title="Copy User ID">
                                        <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>

                                <div class="flex items-center gap-2">
                                    <span id="pin-{{ $shop->id }}"
                                        class="bg-indigo-50 text-indigo-700 text-[10px] font-bold px-2.5 py-1 rounded-md border border-indigo-100 flex items-center gap-2">
                                        Pin - {{ $shop->pin }}
                                    </span>
                                    <button onclick="copyToClipboard('{{ $shop->pin }}', this)"
                                        class="text-indigo-400 hover:text-indigo-700 transition-colors"
                                        title="Copy Pin">
                                        <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ url('/admin/merchant-id-preview/'.$shop->id) }}" target="_blank"
                                        class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-900 hover:text-white text-slate-700 rounded-lg text-xs font-bold transition-all">
                                        <i data-lucide="eye" class="w-4 h-4"></i> View ID
                                    </a>

                                    <button
                                        onclick="downloadQRWithName('{{ $shop->shop_name }}', '{{ url('/shopprofile-details/' . str_replace(' ', '-', $shop->shop_name)) }}')"
                                        class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold shadow-md shadow-indigo-200 transition-all">
                                        <i data-lucide="download" class="w-4 h-4"></i> Download QR
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($shops->isEmpty())
            <div class="py-20 text-center">
                <i data-lucide="database-zap" class="w-12 h-12 text-slate-300 mx-auto mb-4"></i>
                <p class="text-slate-500 font-medium">No merchants registered in the system yet.</p>
            </div>
            @endif
        </div>
    </main>

    <canvas id="qrCanvas" style="display:none;"></canvas>

    <script>
        lucide.createIcons();

        function downloadQRWithName(shopName, profileUrl) {
            const canvas = document.getElementById('qrCanvas');
            const ctx = canvas.getContext('2d');
            const qrSize = 500;
            const bottomSpace = 80;
            canvas.width = qrSize;
            canvas.height = qrSize + bottomSpace;
            const qrImg = new Image();
            qrImg.crossOrigin = "anonymous";
            // QuickChart API for QR generation
            qrImg.src = "https://quickchart.io/qr?text=" + encodeURIComponent(profileUrl) + "&size=" + qrSize +
                "&margin=1";
            qrImg.onload = function() {
                // Background
                ctx.fillStyle = "white";
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                // Draw QR
                ctx.drawImage(qrImg, 0, 0, qrSize, qrSize);
                // Add Shop Name
                ctx.fillStyle = "#1e3a8a"; // Indigo-900
                ctx.font = "bold 26px Inter, Arial";
                ctx.textAlign = "center";
                ctx.fillText(shopName.toUpperCase(), qrSize / 2, qrSize + 40);
                // Trigger Download
                const link = document.createElement('a');
                link.download = `QR_${shopName.replace(/\s+/g, '_')}.png`;
                link.href = canvas.toDataURL("image/png");
                link.click();
            };
        }
    </script>
    <script>
        function copyToClipboard(text, button) {
            // Text copy logic
            navigator.clipboard.writeText(text).then(() => {
                // Icon change to 'Check' for feedback
                const originalIcon = button.innerHTML;
                button.innerHTML = '<i data-lucide="check" class="w-3.5 h-3.5 text-emerald-500"></i>';
                lucide.createIcons(); // Naye icon ko render karne ke liye
                // 2 second baad wapas purana icon
                setTimeout(() => {
                    button.innerHTML = originalIcon;
                    lucide.createIcons();
                }, 2000);
            }).catch(err => {
                console.error('Copy failed: ', err);
            });
        }
    </script>
</body>

</html>