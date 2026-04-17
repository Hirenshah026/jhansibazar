<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Manage Shops</h2>
    <button onclick='openEditModal(@json($shop))' class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 shadow">
        Edit Shop Details
    </button>
</div>

<div id="updateShopModal" class="fixed inset-0 bg-black bg-opacity-60 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
        
        <div class="p-5 border-b flex justify-between items-center bg-gray-50">
            <div>
                <h3 class="text-xl font-bold text-gray-800">Shop Settings</h3>
                <p class="text-sm text-gray-500">Update your shop information on Jhansi Bazaar</p>
            </div>
            <button onclick="toggleModal()" class="text-gray-400 hover:text-gray-600 text-3xl">&times;</button>
        </div>

        <form id="updateShopForm" class="overflow-y-auto p-6 space-y-5">
            @csrf
            <input type="hidden" name="shop_id" id="modal_shop_id">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Shop Name</label>
                    <input type="text" name="shop_name" id="modal_shop_name" class="mt-1 w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700">Owner Name</label>
                    <input type="text" name="owner_name" id="modal_owner_name" class="mt-1 w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700">Phone Number</label>
                    <input type="text" name="phone" id="modal_phone" class="mt-1 w-full border border-gray-300 rounded-xl p-3 bg-gray-50 cursor-not-allowed" readonly>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700">Tagline (Optional)</label>
                    <input type="text" name="tagline" id="modal_tagline" class="mt-1 w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700">Shop Address</label>
                <textarea name="address" id="modal_address" rows="2" class="mt-1 w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 outline-none transition-all"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Open Time</label>
                    <input type="time" name="open_time" id="modal_open_time" class="mt-1 w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Close Time</label>
                    <input type="time" name="close_time" id="modal_close_time" class="mt-1 w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Profile Status</label>
                    <select name="status" id="modal_status" class="mt-1 w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                        <option value="draft">Draft</option>
                        <option value="pending">Pending</option>
                        <option value="active">Active</option>
                    </select>
                </div>
            </div>

            <div class="pt-6 border-t flex justify-end gap-4">
                <button type="button" onclick="toggleModal()" class="px-6 py-3 text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 font-semibold transition-all">Cancel</button>
                <button type="submit" id="saveBtn" class="px-8 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-semibold shadow-lg shadow-blue-200 transition-all flex items-center gap-2">
                    <span id="btnText">Save Changes</span>
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
                    if(response.status === 'success') {
                        // Success Toast/Alert
                        alert('Badhai ho! Shop details update ho gayi hain.');
                        toggleModal();
                        location.reload(); // Data refresh karne ke liye
                    }
                },
                error: function(xhr) {
                    $('#saveBtn').prop('disabled', false).removeClass('opacity-70');
                    $('#btnText').text('Save Changes');
                    
                    let errorMsg = xhr.responseJSON ? xhr.responseJSON.message : 'Kuch technical error hai!';
                    alert('Error: ' + errorMsg);
                }
            });
        });
    });
</script>
@endpush