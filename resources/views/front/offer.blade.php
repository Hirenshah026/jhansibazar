@extends('front_layout.main')

@section('content')
<style>
    :root { --primary: #10b981; --bg-slate: #f8fafc; }
    .scale-up { animation: scaleUp 0.12s ease-out; }
    @keyframes scaleUp { from { opacity: 0; transform: scale(0.99); } to { opacity: 1; transform: scale(1); } }
    
    .loading-spinner {
        border: 2px solid rgba(16, 185, 129, 0.1);
        border-top: 2px solid var(--primary);
        border-radius: 50%;
        width: 12px; height: 12px;
        animation: spin-round 0.8s linear infinite;
        display: inline-block;
    }
    @keyframes spin-round { 100% { transform: rotate(360deg); } }

    /* Custom SweetAlert Styling to match your emerald theme */
    .swal2-popup { font-family: inherit; border-radius: 15px !important; padding: 1.5rem !important; }
    .swal2-styled.swal2-confirm { background-color: #10b981 !important; border-radius: 8px !important; font-size: 13px !important; font-weight: 800 !important; text-transform: uppercase !important; }
    .swal2-styled.swal2-cancel { border-radius: 8px !important; font-size: 13px !important; font-weight: 800 !important; text-transform: uppercase !important; }
</style>

<div class="max-w-md mx-auto bg-slate-50 min-h-screen pb-12 shadow-2xl">
    <div class="px-4 py-3 border-b bg-white flex items-center justify-between sticky top-0 z-20">
        <div class="flex items-center gap-3">
            <button type="button" onclick="window.history.back()" class="text-slate-400 hover:text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            </button>
            <h1 class="font-black text-[12px] text-slate-800 uppercase tracking-tighter">Offers Manager</h1>
        </div>
        
        <button type="button" id="trigger_add_modal" class="text-[9px] font-black px-3 py-1.5 bg-emerald-600 text-white rounded-lg shadow-sm active:scale-95 transition-all">
            + ADD New Offer
        </button>
    </div>

    <div class="p-3">
        <div id="offerListContainer" class="space-y-1">
            @forelse($offers as $index => $offer)
                <div class="offer-item-card bg-white border border-slate-200 rounded-xl p-2 px-3 flex items-center justify-between mb-2">
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] font-black text-slate-300 w-4">{{ $index + 1 }}</span>
                        <div class="img-preview-box w-9 h-9 bg-slate-50 border border-slate-100 rounded-lg overflow-hidden flex items-center justify-center">
                             <img src="{{ (is_array($offer) && isset($offer['image'])) ? $offer['image'] : asset('assets/img/gift.png') }}" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name={{ is_array($offer) ? $offer['text'] : $offer }}&background=random'">
                        </div>
                        <p class="text-[11px] font-bold text-slate-700 tracking-tight">{{ is_array($offer) ? $offer['text'] : $offer }}</p>
                    </div>

                    <div class="flex gap-1">
                        <button type="button" class="edit-offer-btn p-1.5 text-slate-400 hover:bg-emerald-50 hover:text-emerald-600 rounded-md" 
                                data-index="{{ $index }}" 
                                data-text="{{ is_array($offer) ? $offer['text'] : $offer }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-width="2"></path></svg>
                        </button>
                        <button type="button" class="delete-offer-btn p-1.5 text-slate-400 hover:bg-red-50 hover:text-red-500 rounded-md" data-index="{{ $index }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2.5"></path></svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="py-24 text-center opacity-20 text-[9px] font-black uppercase tracking-widest">No Offers Active</div>
            @endforelse
        </div>
    </div>
</div>

<div id="appModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-[2px] items-center justify-center px-8">
    <div class="bg-white w-full max-w-[380px] rounded-xl overflow-hidden shadow-2xl scale-up border border-slate-100">
        <div class="px-4 py-3 border-b bg-slate-50/50 flex justify-between items-center">
            <h3 id="modal_title" class="font-bold text-[10px] text-slate-500 uppercase tracking-widest">Add Offer</h3>
            <button type="button" class="close-modal-trigger text-slate-400 text-lg">&times;</button>
        </div>

        <div class="p-5">
            <input type="hidden" id="offer_index_val">
            <div class="mb-4 flex items-center gap-3">
                <div id="img_select_box" class="w-12 h-12 border border-dashed border-slate-300 rounded-lg flex items-center justify-center bg-slate-50 cursor-pointer overflow-hidden relative">
                    <img id="img_preview_tag" src="" class="hidden w-full h-full object-cover">
                    <span id="plus_icon" class="text-slate-300 text-lg">+</span>
                </div>
                <input type="file" id="offer_img_input" class="hidden" accept="image/*">
                <div class="text-[9px] text-slate-400 font-bold uppercase">Image<br><span class="text-emerald-500">(Optional)</span></div>
            </div>

            <div class="mb-6">
                <input type="text" id="offer_text_val" placeholder="Offer Label (e.g. 50% OFF)" 
                    class="w-full px-3 py-3 bg-slate-50 border border-slate-200 rounded-lg text-xl font-bold text-slate-700 outline-none focus:border-emerald-500 transition-all">
            </div>
            
            <div class="flex gap-2">
                <button type="button" class="close-modal-trigger flex-1 py-2.5 text-[10px] font-black text-slate-400 bg-slate-100 rounded-lg uppercase">Cancel</button>
                <button type="button" id="main_save_btn" class="flex-[1.5] py-2.5 text-[10px] font-black text-white bg-emerald-600 rounded-lg shadow-sm flex items-center justify-center gap-2 hover:bg-emerald-700 uppercase transition-all active:scale-95">
                    <span id="btn_label">Save</span>
                    <span id="btn_spinner" class="loading-spinner hidden" style="border-top-color: #fff"></span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function() {
    // Modal Logic
    $('#trigger_add_modal').on('click', function() {
        $('#modal_title').text('New Offer Slot');
        $('#offer_index_val').val('');
        $('#offer_text_val').val('');
        $('#offer_img_input').val('');
        $('#img_preview_tag').addClass('hidden').attr('src', '');
        $('#plus_icon').removeClass('hidden');
        $('#appModal').removeClass('hidden').addClass('flex');
    });

    $(document).on('click', '.edit-offer-btn', function() {
        $('#modal_title').text('Update Offer Slot');
        $('#offer_index_val').val($(this).data('index'));
        $('#offer_text_val').val($(this).data('text'));
        $('#appModal').removeClass('hidden').addClass('flex');
    });

    $('.close-modal-trigger').on('click', function() {
        $('#appModal').addClass('hidden').removeClass('flex');
    });

    // Image Trigger Logic
    $('#img_select_box').on('click', function(e) {
        e.preventDefault();
        $('#offer_img_input').trigger('click');
    });

    $('#offer_img_input').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#img_preview_tag').attr('src', e.target.result).removeClass('hidden');
                $('#plus_icon').addClass('hidden');
            }
            reader.readAsDataURL(file);
        }
    });

    // Save Logic with SweetAlert
    $('#main_save_btn').on('click', function() {
        const text = $('#offer_text_val').val();
        if(!text) {
            Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Offer text is required!', confirmButtonText: 'OKAY' });
            return;
        }

        const $btn = $(this);
        $btn.prop('disabled', true).addClass('opacity-50');
        $('#btn_spinner').removeClass('hidden');
        $('#btn_label').text('SAVING');

        const formData = new FormData();
        formData.append('_token', "{{ csrf_token() }}");
        formData.append('offer_text', text);
        formData.append('index', $('#offer_index_val').val());
        const file = $('#offer_img_input')[0].files[0];
        if(file) formData.append('offer_image', file);

        $.ajax({
            url: "{{ route('shop.offers.save') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function() { 
                Swal.fire({ icon: 'success', title: 'Success', text: 'Offer updated successfully!', showConfirmButton: false, timer: 1500 });
                setTimeout(() => { location.reload(); }, 1300);
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Failed', text: 'Something went wrong!' });
                $btn.prop('disabled', false).removeClass('opacity-50');
                $('#btn_spinner').addClass('hidden');
                $('#btn_label').text('SAVE');
            }
        });
    });

    // Delete Logic with SweetAlert
    $(document).on('click', '.delete-offer-btn', function() {
        const idx = $(this).data('index');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "This slot will be removed permanently!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'YES, DELETE',
            cancelButtonText: 'CANCEL',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("{{ route('shop.offers.delete') }}", {
                    _token: "{{ csrf_token() }}",
                    index: idx
                }, function() { 
                    Swal.fire({ icon: 'success', title: 'Deleted!', showConfirmButton: false, timer: 1000 });
                    setTimeout(() => { location.reload(); }, 1000);
                });
            }
        });
    });
});
</script>
@endpush