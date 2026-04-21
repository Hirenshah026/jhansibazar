<div class="p-6 bg-gray-50 border-b hidden">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">Manage Shop</h2>
            <p class="text-sm text-gray-500">Configure your digital storefront</p>
        </div>
        <button onclick='openEditModal(@json($shop))'
            class="flex items-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all active:scale-95 font-bold text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit Profile
        </button>
    </div>
</div>

<div id="updateShopModal"
    class="fixed inset-0 bg-slate-900/40 hidden z-50 flex items-center justify-center p-0 md:p-4 backdrop-blur-md transition-opacity duration-300">
    <div
        class="bg-white md:rounded-3xl shadow-2xl w-full max-w-2xl max-h-screen md:max-h-[85vh] overflow-hidden flex flex-col transform transition-all scale-100">

        
        <div class="px-6 py-5 border-b flex justify-between items-center bg-white">
            <div class="flex items-center gap-3">
                <div
                    class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 shadow-inner">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>

                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-lg font-extrabold text-gray-900 leading-tight">Shop Settings</h3>
                        <button type="button" onclick="viewShopID()"
                            class="flex items-center gap-1 bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-tighter hover:bg-amber-200 transition-colors border border-amber-200 shadow-sm">
                            <span>🪪</span> View ID Card
                        </button>
                    </div>
                    <p class="text-[11px] uppercase tracking-wider text-gray-400 font-bold">Jhansi Bazaar Partner</p>
                </div>
            </div>

            <button onclick="toggleModal()"
                class="w-9 h-9 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-red-50 hover:text-red-500 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="updateShopForm" class="overflow-y-auto p-6 space-y-6 bg-gray-50/50">
            @csrf
            <input type="hidden" name="shop_id" id="modal_shop_id">

            <div class="space-y-4">
                <h4 class="text-xs font-bold text-indigo-500 uppercase tracking-widest">Basic Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-600 ml-1">Shop Name</label>
                        <input type="text" name="shop_name" id="modal_shop_name" placeholder="e.g. Sharma Sweets"
                            class="w-full bg-white border border-gray-200 rounded-2xl p-3.5 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all shadow-sm">
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-600 ml-1">Owner Name</label>
                        <input type="text" name="owner_name" id="modal_owner_name" placeholder="Full Name"
                            class="w-full bg-white border border-gray-200 rounded-2xl p-3.5 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all shadow-sm">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-600 ml-1">Tagline</label>
                    <input type="text" name="tagline" id="modal_tagline"
                        placeholder="Slogan jo customer ko impress kare"
                        class="w-full bg-white border border-gray-200 rounded-2xl p-3.5 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all shadow-sm">
                </div>
            </div>

            <div class="space-y-4 pt-2">
                <h4 class="text-xs font-bold text-indigo-500 uppercase tracking-widest">Contact & Location</h4>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-600 ml-1">Shop Address</label>
                    <textarea name="address" id="modal_address" rows="2" placeholder="Complete address..."
                        class="w-full bg-white border border-gray-200 rounded-2xl p-3.5 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all shadow-sm"></textarea>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-600 ml-1">Phone (Contact Support to change)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">📞</span>
                        <input type="text" name="phone" id="modal_phone"
                            class="w-full bg-gray-100 border border-gray-200 rounded-2xl p-3.5 pl-10 text-gray-500 cursor-not-allowed font-medium"
                            readonly>
                    </div>
                </div>
            </div>

            <div class="space-y-4 pt-2">
                <h4 class="text-xs font-bold text-indigo-500 uppercase tracking-widest">Operational Hours & Status</h4>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-600 ml-1">Opening</label>
                        <input type="time" name="open_time" id="modal_open_time"
                            class="w-full bg-white border border-gray-200 rounded-2xl p-3.5 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all shadow-sm">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-600 ml-1">Closing</label>
                        <input type="time" name="close_time" id="modal_close_time"
                            class="w-full bg-white border border-gray-200 rounded-2xl p-3.5 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all shadow-sm">
                    </div>
                    <div class="space-y-1 col-span-2 md:col-span-1">
                        <label class="text-xs font-bold text-gray-600 ml-1">Account Status</label>
                        <select name="status" id="modal_status"
                            class="w-full bg-white border border-gray-200 rounded-2xl p-3.5 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all shadow-sm appearance-none font-bold text-indigo-600">
                            <option value="draft">Draft</option>
                            <option value="pending">Pending Review</option>
                            <option value="active">Active ✅</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t flex flex-col md:flex-row justify-end gap-3 pb-2">
                <button type="button" onclick="toggleModal()"
                    class="order-2 md:order-1 px-6 py-3.5 text-gray-500 hover:text-gray-700 font-bold transition-all">Dismiss</button>
                <button type="submit" id="saveBtn"
                    class="order-1 md:order-2 px-10 py-3.5 bg-indigo-600 text-white rounded-2xl hover:bg-indigo-700 font-black shadow-xl shadow-indigo-100 transition-all flex items-center justify-center gap-2 group">
                    <span id="btnText">Update Profile</span>
                    <svg id="loadingSpinner" class="hidden animate-spin h-5 w-5 text-white"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

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
