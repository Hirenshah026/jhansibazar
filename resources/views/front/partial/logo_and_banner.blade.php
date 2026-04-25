@push('css_or_link')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; }
        .gov-pattern {
            background-color: #ffffff;
            background-image: radial-gradient(#cbd5e1 0.5px, transparent 0.5px), radial-gradient(#cbd5e1 0.5px, #ffffff 0.5px);
            background-size: 16px 16px;
            background-position: 0 0, 8px 8px;
        }
        #crop-modal-overlay { 
            display: none; position: fixed; inset: 0; 
            background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(4px);
            z-index: 99999; align-items: center; justify-content: center; padding: 20px;
        }
        #crop-modal-overlay.active { display: flex; }
        #crop-modal-content {
            background: white; width: 100%; max-width: 500px;
            border-radius: 1.5rem; overflow: hidden; border-top: 8px solid #1e3a8a;
        }
        .cropper-box { height: 320px; background: #f8fafc; overflow: hidden; }
        @media (max-width: 640px) { .cropper-box { height: 280px; } }
        .btn-gov { transition: all 0.2s; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 800; font-size: 10px; }
    </style>
@endpush

<div class="max-w-md mx-auto p-4 space-y-6 pb-12">
    <div class="bg-white rounded-2xl p-5 border border-slate-300 border-t-[8px] border-t-[#1e3a8a] gov-pattern shadow-sm text-center">
        <h2 class="text-[10px] font-black text-indigo-900 uppercase tracking-widest">Marketplace Regulatory Authority</h2>
        <p class="text-lg font-black text-slate-800">Branding Studio</p>
    </div>

    <div class="bg-white rounded-2xl overflow-hidden border border-slate-300 shadow-sm">
        <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/80 flex justify-between">
            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Shop Banner (3:1)</span>
        </div>
        <div class="p-4 space-y-4">
            <div class="relative aspect-[3/1] rounded-xl overflow-hidden border-2 border-slate-200 group cursor-pointer" onclick="document.getElementById('banner-input').click()">
                <img id="banner-preview" src="{{ Session::get('shopuser')->banner ?? 'https://placehold.co/600x200' }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-indigo-900/20 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all">
                    <i data-lucide="image-plus" class="text-white w-6 h-6"></i>
                </div>
            </div>
            <input type="file" id="banner-input" class="hidden" accept="image/*">
            <form action="{{ route('shop.update.banner.local') }}" method="POST" class="ajax-form">
                @csrf
                <input type="hidden" name="cropped_banner" id="cropped_banner_input">
                <div class="flex gap-2">
                    <button type="button" onclick="location.reload()" class="btn-gov flex-1 py-3 bg-slate-100 text-slate-400 rounded-xl">Discard</button>
                    <button type="submit" class="btn-gov flex-[2] py-3 bg-indigo-900 text-white rounded-xl shadow-lg">Update Banner</button>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl overflow-hidden border border-slate-300 shadow-sm border-b-[6px] border-b-[#f97316]">
        <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/80">
            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Official Brand Logo</span>
        </div>
        <div class="p-6 flex flex-col items-center space-y-5">
            <div class="relative w-28 h-28 rounded-3xl overflow-hidden border-4 border-slate-50 shadow-xl group cursor-pointer" onclick="document.getElementById('logo-input').click()">
                <img id="logo-preview" src="{{ Session::get('shopuser')->logo ?? 'https://ui-avatars.com/api/?name=Shop' }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-indigo-900/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all">
                    <i data-lucide="camera" class="text-white w-6 h-6"></i>
                </div>
            </div>
            <input type="file" id="logo-input" class="hidden" accept="image/*">
            <form action="{{ route('shop.update.logo.local') }}" method="POST" class="ajax-form w-full">
                @csrf
                <input type="hidden" name="cropped_logo" id="cropped_logo_input">
                <div class="flex gap-2">
                    <button type="button" onclick="location.reload()" class="btn-gov flex-1 py-3 bg-slate-100 text-slate-400 rounded-xl">Cancel</button>
                    <button type="submit" class="btn-gov flex-[2] py-3 bg-[#f97316] text-white rounded-xl shadow-lg">Save Identity</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="crop-modal-overlay">
    <div id="crop-modal-content">
        <div class="px-5 py-4 border-b flex justify-between items-center">
            <span class="text-[10px] font-black uppercase text-indigo-900">Crop Asset</span>
            <button type="button" onclick="closeModal()" class="text-slate-300 hover:text-red-500"><i data-lucide="x-circle"></i></button>
        </div>
        <div class="cropper-box"><img id="image-to-crop"></div>
        <div class="p-5 bg-slate-50 flex gap-3">
            <button type="button" onclick="closeModal()" class="btn-gov flex-1 py-3 bg-white border border-slate-200 rounded-xl">Exit</button>
            <button type="button" id="crop-apply" class="btn-gov flex-[2] py-3 bg-indigo-900 text-white rounded-xl">Apply</button>
        </div>
    </div>
</div>

@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
    let cropper, currentType = '';
    const overlay = document.getElementById('crop-modal-overlay');
    const imageElement = document.getElementById('image-to-crop');

    function startCropping(file, type, ratio) {
        currentType = type;
        const reader = new FileReader();
        reader.onload = (e) => {
            imageElement.src = e.target.result;
            overlay.classList.add('active');
            if (cropper) cropper.destroy();
            cropper = new Cropper(imageElement, { aspectRatio: ratio, viewMode: 1 });
        };
        reader.readAsDataURL(file);
    }

    document.getElementById('logo-input').onchange = (e) => startCropping(e.target.files[0], 'logo', 1);
    document.getElementById('banner-input').onchange = (e) => startCropping(e.target.files[0], 'banner', 3/1);

    document.getElementById('crop-apply').onclick = () => {
        const canvas = cropper.getCroppedCanvas({ width: currentType === 'logo' ? 500 : 1200 });
        const base64 = canvas.toDataURL('image/jpeg', 0.8);
        document.getElementById(`${currentType}-preview`).src = base64;
        document.getElementById(`cropped_${currentType}_input`).value = base64;
        closeModal();
    };

    function closeModal() { overlay.classList.remove('active'); if(cropper) cropper.destroy(); }

    // AJAX Submission Logic
    document.querySelectorAll('.ajax-form').forEach(form => {
        form.onsubmit = async (e) => {
            e.preventDefault();
            const submitBtn = form.querySelector('button[type="submit"]');
            const data = new FormData(form);
            
            if(!data.get(`cropped_${currentType}`)) return Swal.fire('Error', 'Please crop first', 'warning');

            submitBtn.disabled = true;
            submitBtn.innerText = 'Saving...';

            try {
                const res = await axios.post(form.action, data);
                Swal.fire('Success', res.data.message, 'success');
            } catch (err) {
                Swal.fire('Error', 'Upload failed', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerText = 'Updated';
            }
        };
    });
</script>
@endpush