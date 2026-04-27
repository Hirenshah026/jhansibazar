<style>
    .gallery-card { transition: all 0.3s ease; }
    .gallery-card:hover { transform: translateY(-5px); }
    .cropper-container { max-height: 400px !important; }
    /* Scroll smooth karne ke liye */
    html { scroll-behavior: smooth; }
</style>

<div class="max-w-7xl mx-auto px-2 py-6">
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
        @php
            $photos = is_array($shop->item_photos) ? $shop->item_photos : json_decode($shop->item_photos ?? '[]', true);
        @endphp

        @for ($i = 1; $i <= 6; $i++)
            @php $currentPhoto = $photos[$i-1] ?? null; @endphp
            
            <div class="gallery-card bg-white rounded-[2rem] p-2 shadow-sm border border-gray-100 flex flex-col gap-2">
                <div class="relative aspect-square rounded-[1.5rem] overflow-hidden bg-gray-50 border {{ $currentPhoto ? 'border-transparent' : 'border-dashed border-gray-300' }}">
                    @if($currentPhoto)
                        <img src="{{ $currentPhoto['url'] }}" class="h-full w-full object-cover">
                    @else
                        <label class="flex items-center justify-center h-full w-full cursor-pointer hover:bg-blue-50 transition-colors">
                            <input type="file" class="hidden image-input" data-index="{{ $i }}" accept="image/*">
                            <div class="p-3 bg-blue-50 text-blue-500 rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                            </div>
                        </label>
                    @endif
                </div>

                <div class="flex gap-2 {{ $currentPhoto ? 'opacity-100' : 'opacity-0 pointer-events-none' }}">
                    <a href="{{ $currentPhoto['url'] ?? '#' }}" target="_blank" class="flex-1 py-2 flex items-center justify-center bg-gray-50 text-gray-500 rounded-xl hover:bg-blue-600 hover:text-white transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    </a>
                    <button type="button" onclick="deleteImage('{{ $currentPhoto['public_id'] ?? '' }}', {{ $i }})" class="flex-1 py-2 flex items-center justify-center bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
            </div>
        @endfor
    </div>
</div>

<div id="cropper1model1" class="fixed inset-0 z-[999] hidden flex justify-center items-start bg-black/80 p-4 backdrop-blur-sm overflow-y-auto">
    <div class="relative mt-[20vh] sm:mt-[30vh] bg-white rounded-[2.5rem] overflow-hidden w-full max-w-sm shadow-2xl transition-all">
        <div class="p-5 bg-gray-50 flex justify-between items-center border-b">
            <span class="font-bold text-gray-700">Perfect Crop</span>
            <button onclick="closemodel1()" class="text-3xl text-gray-400 hover:text-red-500">&times;</button>
        </div>

        <div class="p-5">
            <div class="aspect-square w-full overflow-hidden rounded-2xl bg-gray-100 shadow-inner">
                <img id="imageToCrop" src="" class="block max-w-full">
            </div>
        </div>

        <div class="p-5 flex gap-3 border-t">
            <button onclick="closemodel1()" class="flex-1 py-3 bg-gray-100 rounded-xl font-bold text-gray-500">Cancel</button>
            <button id="saveCropBtn" class="flex-1 py-3 bg-blue-600 rounded-xl font-bold text-white shadow-lg">Save</button>
        </div>
    </div>
</div>

@push('script')
<script>
    let cropperInstance;
    let selectedIndex;

    $('.image-input').on('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            selectedIndex = $(this).data('index');
            const reader = new FileReader();

            reader.onload = function(event) {
                const modal = document.getElementById('cropper1model1');
                const img = document.getElementById('imageToCrop');

                if (img) {
                    // 1. Modal dikhao
                    modal.classList.remove('hidden');
                    img.src = event.target.result;

                    // 2. AUTO SCROLL TO TOP: Taki modal seetha dikhe
                    window.scrollTo({ top: 0, behavior: 'smooth' });

                    // 3. Initialize Cropper
                    if (cropperInstance) cropperInstance.destroy();
                    
                    setTimeout(() => {
                        cropperInstance = new Cropper(img, {
                            aspectRatio: 1,
                            viewMode: 2,
                            guides: false,
                            autoCropArea: 1
                        });
                    }, 100); // Thoda delay taki modal render ho jaye
                }
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    function closemodel1() {
        document.getElementById('cropper1model1').classList.add('hidden');
        if (cropperInstance) cropperInstance.destroy();
        $('.image-input').val(''); 
    }

    $('#saveCropBtn').on('click', function() {
        if (!cropperInstance) return;

        const canvas = cropperInstance.getCroppedCanvas({ width: 600, height: 600 });
        
        canvas.toBlob(function(blob) {
            let formData = new FormData();
            formData.append(`item_photo_${selectedIndex}`, blob, 'item.jpg');
            formData.append('shop_id', '{{ $shop->id }}');
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: "{{ route('shop.photos.update') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    Swal.fire({ title: 'Uploading...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                },
                success: function() {
                    localStorage.setItem('activeTab', 'gallery');
                    location.reload();
                    
                },
                error: function() {
                    Swal.fire('Error', 'Upload fail ho gaya bhai!', 'error');
                }
            });
        }, 'image/jpeg', 0.8);
    });

    function deleteImage(publicId, index) {
        Swal.fire({
            title: 'Delete karun?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Haan, uda do!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('shop.photos.delete') }}",
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        public_id: publicId,
                        index: index,
                        shop_id: '{{ $shop->id }}'
                    },
                    success: function() {
                        localStorage.setItem('activeTab', 'gallery');
                        location.reload();
                    }
                });
            }
        });
    }
    
    $(document).ready(function() {
        let activeTab = localStorage.getItem('activeTab');
        if (activeTab === 'gallery') {
            // Gallery tab button ko dhoondo aur click karwa do
            $('#tab-gallery').trigger('click');
            
            // Ek baar click hone ke baad clear kar do taki har baar na khule
            localStorage.removeItem('activeTab');
        }
    });
</script>
@endpush