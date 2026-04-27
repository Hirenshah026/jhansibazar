<div class="max-w-7xl mx-auto p-6 bg-white rounded-3xl shadow-sm border border-gray-100">
    <div class="mb-8">
        <h3 class="text-2xl font-bold text-gray-800">Item Gallery</h3>
        <p class="text-gray-500">Manage your 6 shop photos (Square Crop Enabled)</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        @php
            $photos = is_array($shop->item_photos) ? $shop->item_photos : json_decode($shop->item_photos ?? '[]', true);
        @endphp

        @for ($i = 1; $i <= 6; $i++)
            @php $currentPhoto = $photos[$i-1] ?? null; @endphp
            
            <div class="flex flex-col gap-3">
                <div class="relative aspect-square rounded-3xl overflow-hidden border-2 {{ $currentPhoto ? 'border-transparent shadow-lg' : 'border-dashed border-gray-300 bg-gray-50' }}">
                    @if($currentPhoto)
                        <img src="{{ $currentPhoto['url'] }}" class="h-full w-full object-cover">
                    @else
                        <label class="flex flex-col items-center justify-center h-full w-full cursor-pointer hover:bg-blue-50 transition-all group">
                            <input type="file" class="hidden image-input" data-index="{{ $i }}" accept="image/*">
                            <div class="p-4 bg-white rounded-2xl shadow-sm text-blue-500 group-hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            </div>
                            <span class="mt-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Slot {{ $i }}<br>Empty</span>
                        </label>
                    @endif
                </div>

                <div class="flex gap-2 {{ $currentPhoto ? 'opacity-100' : 'opacity-0 pointer-events-none' }}">
                    <a href="{{ $currentPhoto['url'] ?? '#' }}" target="_blank" class="flex-1 flex items-center justify-center py-3 bg-gray-50 text-gray-600 rounded-2xl hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    </a>
                    <button type="button" onclick="deleteImage('{{ $currentPhoto['public_id'] ?? '' }}', {{ $i }})" class="flex-1 flex items-center justify-center py-3 bg-red-50 text-red-500 rounded-2xl hover:bg-red-500 hover:text-white transition-all shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
            </div>
        @endfor
    </div>
</div>

<div id="cropper1model1" class="fixed inset-0 z-[999] hidden flex items-center justify-center bg-black/90 p-4 backdrop-blur-sm">
    <div class="bg-white rounded-[2rem] overflow-hidden max-w-xl w-full shadow-2xl">
        <div class="p-6 border-b flex justify-between items-center">
            <h4 class="text-xl font-bold text-gray-800">Crop Your Image</h4>
            <button onclick="closemodel1()" class="text-gray-400 hover:text-gray-600 text-3xl">&times;</button>
        </div>
        <div class="p-6">
            <div class="aspect-square w-full overflow-hidden bg-gray-100 rounded-2xl">
                <img id="imageToCrop" src="" class="block max-w-full">
            </div>
        </div>
        <div class="p-6 border-t flex gap-4">
            <button onclick="closemodel1()" class="flex-1 py-4 border-2 border-gray-100 rounded-2xl font-bold text-gray-500 hover:bg-gray-50 transition-all">Cancel</button>
            <button id="saveCropBtn" class="flex-1 py-4 bg-blue-600 rounded-2xl font-bold text-white shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all">Save & Upload</button>
        </div>
    </div>
</div>

@push('script')
<script>
    let cropperInstance; // Iska naam badal diya
    let selectedIndex;
    const myModal = document.getElementById('cropper1model1');
    const imageToCrop = document.getElementById('imageToCrop');

    // 1. File Selection & Open Modal
    $('.image-input').on('change', function(e) {
        if (e.target.files && e.target.files.length > 0) {
            selectedIndex = $(this).data('index');
            const file = e.target.files[0];
            const reader = new FileReader();
            
            reader.onload = function(event) {
                imageToCrop.src = event.target.result;
                myModal.classList.remove('hidden');
                
                // Pehle wala cropper destroy karo agar hai toh
                if (cropperInstance) {
                    cropperInstance.destroy();
                }
                
                // FIX: Yahan 'new Cropper' hona chahiye (Library ka sahi naam)
                cropperInstance = new Cropper(imageToCrop, {
                    aspectRatio: 1, 
                    viewMode: 2,
                    guides: true,
                    autoCropArea: 1,
                    responsive: true,
                });
            };
            reader.readAsDataURL(file);
        }
    });

    function closemodel1() {
        myModal.classList.add('hidden');
        if (cropperInstance) {
            cropperInstance.destroy();
        }
        $('.image-input').val(''); 
    }

    // 2. Crop & AJAX Upload
    $('#saveCropBtn').on('click', function() {
        if (!cropperInstance) return;

        const canvas = cropperInstance.getCroppedCanvas({
            width: 800,
            height: 800
        });

        canvas.toBlob(function(blob) {
            let formData = new FormData();
            formData.append(`item_photo_${selectedIndex}`, blob, 'item.jpg');
            formData.append('shop_id', '{{ $shop->id }}');
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: "{{ url('your.route.here') }}", // Sahi route name check kar lena
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    Swal.fire({
                        title: 'Uploading...',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading() }
                    });
                },
                success: function(res) {
                    Swal.fire({ icon: 'success', title: 'Updated!', timer: 1000, showConfirmButton: false })
                    .then(() => location.reload());
                },
                error: function() {
                    Swal.fire('Error', 'Upload failed. File size check karein.', 'error');
                }
            });
        }, 'image/jpeg', 0.9);
    });

    // 3. Delete Function (Ab isme AJAX dal sakte ho)
    function deleteImage(publicId, index) {
        Swal.fire({
            title: 'Delete this photo?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Yahan apna delete wala AJAX call ayega
                console.log("Deleting:", publicId);
            }
        });
    }
</script>
@endpush