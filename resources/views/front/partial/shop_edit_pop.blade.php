<div id="updateShopModal"
    class="fixed inset-0 bg-black/60 hidden z-[100] flex items-end md:items-center justify-center p-0 md:p-4 backdrop-blur-sm transition-all duration-300">
    
    <div class="bg-white rounded-t-[2rem] md:rounded-2xl shadow-2xl w-full max-w-xl max-h-[92vh] md:max-h-[85vh] overflow-hidden flex flex-col transform transition-all">

        <div class="w-12 h-1 bg-gray-200 rounded-full mx-auto mt-3 mb-1 md:hidden"></div>

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center text-blue-600">
                    <i data-lucide="store" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-800">Edit Shop Details</h3>
                    <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">Merchant Dashboard</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <button type="button" onclick="viewShopID()" 
                    class="bg-blue-50 text-blue-600 p-2 rounded-lg hover:bg-blue-100 transition-all border border-blue-100" title="View ID Card">
                    <i data-lucide="id-card" class="w-5 h-5"></i>
                </button>

                <button onclick="toggleModal()" 
                    class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-red-50 hover:text-red-500 transition-all">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        <form id="updateShopForm" class="overflow-y-auto p-6 space-y-6 bg-gray-50/30 flex-grow">
            @csrf
            <input type="hidden" name="shop_id" id="modal_shop_id">

            <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-gray-400 ml-1 uppercase">Shop Name</label>
                        <input type="text" name="shop_name" id="modal_shop_name" class="w-full bg-white border border-gray-200 rounded-xl p-3 text-sm font-semibold focus:border-blue-500 outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-gray-400 ml-1 uppercase">Owner Name</label>
                        <input type="text" name="owner_name" id="modal_owner_name" class="w-full bg-white border border-gray-200 rounded-xl p-3 text-sm font-semibold focus:border-blue-500 outline-none">
                    </div>
                </div>
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-gray-400 ml-1 uppercase">Tagline</label>
                    <input type="text" name="tagline" id="modal_tagline" class="w-full bg-white border border-gray-200 rounded-xl p-3 text-sm font-semibold focus:border-blue-500 outline-none">
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm space-y-4">
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-gray-400 ml-1 uppercase">Address</label>
                    <textarea name="address" id="modal_address" rows="2" class="w-full bg-white border border-gray-200 rounded-xl p-3 text-sm font-semibold focus:border-blue-500 outline-none"></textarea>
                </div>
                <div class="bg-gray-50 p-3 rounded-xl flex items-center justify-between border border-gray-200 border-dashed">
                    <div class="flex items-center gap-2">
                        <i data-lucide="phone" class="w-4 h-4 text-gray-400"></i>
                        <input type="text" id="modal_phone" class="bg-transparent text-sm font-bold text-gray-500" readonly>
                    </div>
                    <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">VERIFIED</span>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm space-y-4 mb-4">
                <div class="grid grid-cols-2 gap-4">
                    <input type="time" name="open_time" id="modal_open_time" class="w-full border border-gray-200 rounded-xl p-3 text-sm font-bold">
                    <input type="time" name="close_time" id="modal_close_time" class="w-full border border-gray-200 rounded-xl p-3 text-sm font-bold">
                </div>
                <select name="status" id="modal_status" class="w-full border border-gray-200 rounded-xl p-3 text-sm font-bold text-blue-600 outline-none">
                    <option value="draft">Draft</option>
                    <option value="pending">Pending Review</option>
                    <option value="active">Active ✅</option>
                </select>
            </div>
        </form>

        <div class="p-4 border-t border-gray-100 bg-white flex items-center gap-3">
            <button type="button" onclick="toggleModal()" 
                class="flex-1 py-3.5 text-gray-600 font-bold text-sm hover:bg-gray-100 rounded-xl transition-all border border-gray-200">
                Cancel
            </button>
            <button type="submit" form="updateShopForm" id="saveBtn" 
                class="flex-[2] py-3.5 bg-[#fb641b] text-white rounded-xl font-bold text-sm shadow-lg shadow-orange-100 active:scale-95 transition-all">
                Update Store
            </button>
        </div>
    </div>
</div>


@push('css_or_link')
  <style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
    
    @keyframes slideUp {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }
    @media (max-width: 768px) {
        #updateShopModal > div { animation: slideUp 0.3s ease-out; }
    }
</style>
@endpush

@push('script')
    <script>
        // Modal ko kholne aur data bharne ke liye
        function openEditModal(shop) {
            $('#modal_shop_id').val(shop.id);
            $('#modal_shop_name').val(shop.shop_name);
            $('#modal_owner_name').val(shop.owner_name);
            $('#modal_phone').val(shop.phone);
            $('#modal_tagline').val(shop.tagline);
            $('#modal_address').val(shop.address);
            $('#modal_open_time').val(shop.open_time);
            $('#modal_close_time').val(shop.close_time);
            $('#modal_status').val(shop.status);

            // Show Modal
            $('#updateShopModal').removeClass('hidden');
        }

        // Modal band karne ke liye
        function toggleModal() {
            $('#updateShopModal').addClass('hidden');
        }

        // AJAX Form Submission
        $(document).ready(function() {
            $('#updateShopForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                $('#saveBtn').prop('disabled', true).addClass('opacity-70');
                $('#btnText').text('Updating...');

                $.ajax({
                    url: "{{ route('shops.update') }}", // Ensure this route exists in web.php
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status === 'success') {
                            // Success Toast/Alert
                            alert('Badhai ho! Shop details update ho gayi hain.');
                            toggleModal();
                            location.reload(); // Data refresh karne ke liye
                        }
                    },
                    error: function(xhr) {
                        $('#saveBtn').prop('disabled', false).removeClass('opacity-70');
                        $('#btnText').text('Save Changes');

                        let errorMsg = xhr.responseJSON ? xhr.responseJSON.message :
                            'Kuch technical error hai!';
                        alert('Error: ' + errorMsg);
                    }
                });
            });
        });

        function viewShopID() {
            let shopId = $('#modal_shop_id').val();
            if (shopId) {
                // Naye tab mein ID Card khulega
                let url = "{{ url('/shop/id-card') }}/" + shopId;
                window.open(url, '_blank');
            } else {
                alert('Pehle shop select karein!');
            }
        }
    </script>
@endpush
