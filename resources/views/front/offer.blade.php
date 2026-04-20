@extends('front_layout.main')

@section('content')
    <style>
        :root {
            --primary: #10b981;
        }

        .scale-up {
            animation: scaleUp 0.12s ease-out;
        }

        @keyframes scaleUp {
            from {
                opacity: 0;
                transform: scale(0.99);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .loading-spinner {
            border: 2px solid rgba(16, 185, 129, 0.1);
            border-top: 2px solid var(--primary);
            border-radius: 50%;
            width: 12px;
            height: 12px;
            animation: spin-round 0.8s linear infinite;
            display: inline-block;
        }

        @keyframes spin-round {
            100% {
                transform: rotate(360deg);
            }
        }

        /* Toggle Switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 34px;
            height: 20px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #e2e8f0;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 14px;
            width: 14px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #10b981;
        }

        input:checked+.slider:before {
            transform: translateX(14px);
        }
    </style>

    <div class="max-w-md mx-auto bg-slate-50 min-h-screen pb-12 shadow-2xl">
        <div class="px-4 py-3 border-b bg-white flex items-center justify-between sticky top-0 z-20">
            <div class="flex items-center gap-3">
                <button type="button" onclick="window.history.back()" class="text-slate-400 hover:text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M15 19l-7-7 7-7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
                <h1 class="font-black text-[12px] text-slate-800 uppercase tracking-tighter">Offers Manager</h1>
            </div>
            <button type="button" id="trigger_add_modal"
                class="text-[9px] font-black px-3 py-1.5 bg-emerald-600 text-white rounded-lg shadow-sm active:scale-95 transition-all">+
                ADD Offer</button>
        </div>

        <div class="p-3">
            <div id="offerListContainer" class="space-y-1">
                @forelse($offers as $index => $offer)
                    <div
                        class="offer-item-card bg-white border border-slate-200 rounded-xl p-2 px-3 flex items-center justify-between mb-2 {{ $offer['is_active'] ?? true ? '' : 'opacity-60' }}">
                        <div class="flex items-center gap-3">
                            <span class="text-[10px] font-black text-slate-300 w-4">{{ $index + 1 }}</span>
                            <div
                                class="img-preview-box w-9 h-9 bg-slate-50 border border-slate-100 rounded-lg overflow-hidden flex items-center justify-center">
                                <img src="{{ $offer['image'] ?? asset('assets/img/gift.png') }}"
                                    class="w-full h-full object-cover"
                                    onerror="this.src='https://ui-avatars.com/api/?name=Offer&background=random'">
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-slate-700 tracking-tight">
                                    {{ $offer['text'] ?? 'No Description' }}</p>
                                <div class="flex flex-wrap items-center gap-2 mt-0.5">
                                    <span
                                        class="text-[8px] px-1.5 py-0.5 bg-slate-100 text-slate-500 rounded font-bold uppercase">Qty:
                                        {{ $offer['quantity'] ?? 0 }}</span>
                                    @if (isset($offer['expiry_date']))
                                        <span
                                            class="text-[8px] px-1.5 py-0.5 bg-amber-50 text-amber-600 rounded font-bold uppercase">Exp:
                                            {{ $offer['expiry_date'] }}</span>
                                    @endif
                                    <span
                                        class="text-[8px] uppercase font-black {{ $offer['is_active'] ?? true ? 'text-emerald-500' : 'text-red-400' }}">
                                        {{ $offer['is_active'] ?? true ? '● Active' : '○ Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-1">
                            <button type="button"
                                class="edit-offer-btn p-1.5 text-slate-400 hover:bg-emerald-50 hover:text-emerald-600 rounded-md"
                                data-index="{{ $index }}" data-text="{{ $offer['text'] ?? '' }}"
                                data-qty="{{ $offer['quantity'] ?? 0 }}" data-expiry="{{ $offer['expiry_date'] ?? '' }}"
                                data-active="{{ $offer['is_active'] ?? 1 }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                                        stroke-width="2"></path>
                                </svg>
                            </button>
                            <button type="button"
                                class="delete-offer-btn p-1.5 text-slate-400 hover:bg-red-50 hover:text-red-500 rounded-md"
                                data-index="{{ $index }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                        stroke-width="2.5"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="py-24 text-center opacity-20 text-[9px] font-black uppercase tracking-widest">No Offers
                        Active</div>
                @endforelse
            </div>
        </div>
    </div>

    <div id="appModal"
        class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-[2px] items-center justify-center px-6">
        <div class="bg-white w-full max-w-[380px] rounded-xl overflow-hidden shadow-2xl scale-up">
            <div class="px-4 py-3 border-b bg-slate-50 flex justify-between items-center">
                <h3 id="modal_title" class="font-bold text-[10px] text-slate-500 uppercase">Add Offer</h3>
                <button type="button" class="close-modal-trigger text-slate-400 text-xl">&times;</button>
            </div>

            <div class="p-5 max-h-[80vh] overflow-y-auto">
                <input type="hidden" id="offer_index_val">

                <div class="flex justify-between items-center mb-4 bg-slate-50 p-3 rounded-lg border border-slate-100">
                    <div class="text-[10px] font-black text-slate-500 uppercase tracking-tight">Offer Status (Active)</div>
                    <label class="switch">
                        <input type="checkbox" id="offer_active_toggle" checked>
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="mb-4 flex items-center gap-3">
                    <div id="img_select_box"
                        class="w-12 h-12 border border-dashed border-slate-300 rounded-lg flex items-center justify-center bg-slate-50 cursor-pointer overflow-hidden">
                        <img id="img_preview_tag" src="" class="hidden w-full h-full object-cover">
                        <span id="plus_icon" class="text-slate-300 text-lg">+</span>
                    </div>
                    <input type="file" id="offer_img_input" class="hidden" accept="image/*">
                    <div class="text-[9px] text-slate-400 font-bold uppercase">Offer Photo</div>
                </div>

                <div class="mb-3">
                    <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Offer Description</label>
                    <textarea id="offer_text_val" placeholder="e.g. Buy 1 Get 1 Free" rows="2"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold text-slate-700 outline-none focus:border-emerald-500"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3 mb-6">
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Quantity</label>
                        <input type="number" id="offer_qty_val" value="0" min="0"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold text-slate-700 outline-none">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase ml-1">Expiry Date</label>
                        <input type="date" id="offer_expiry_val"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold text-slate-700 outline-none"
                            value="{{ date('Y/m/d') }}" min="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="button"
                        class="close-modal-trigger flex-1 py-2.5 text-[10px] font-black text-slate-400 bg-slate-100 rounded-lg uppercase">Cancel</button>
                    <button type="button" id="main_save_btn"
                        class="flex-[1.5] py-2.5 text-[10px] font-black text-white bg-emerald-600 rounded-lg shadow-sm flex items-center justify-center gap-2 uppercase">
                        <span id="btn_label">Save Offer</span>
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
            // Past date disable logic
            const today = new Date().toISOString().split('T')[0];
            $('#offer_expiry_val').attr('min', today);

            // Open Modal for New
            $('#trigger_add_modal').on('click', function() {
                $('#modal_title').text('New Offer');
                $('#offer_index_val').val('');
                $('#offer_text_val').val('');
                $('#offer_qty_val').val(0);
                $('#offer_expiry_val').val('');
                $('#offer_active_toggle').prop('checked', true);
                $('#img_preview_tag').addClass('hidden');
                $('#plus_icon').removeClass('hidden');
                $('#appModal').removeClass('hidden').addClass('flex');
            });

            // Open Modal for Edit (Fix: All fields now populating)
            $(document).on('click', '.edit-offer-btn', function() {
                const btn = $(this);
                $('#modal_title').text('Update Offer');

                $('#offer_index_val').val(btn.data('index'));
                $('#offer_text_val').val(btn.data('text')); // Description
                $('#offer_qty_val').val(btn.data('qty')); // Quantity
                $('#offer_expiry_val').val(btn.data('expiry')); // Expiry
                $('#offer_active_toggle').prop('checked', btn.data('active') == 1);

                // Image handle
                const currentImg = btn.closest('.offer-item-card').find('img').attr('src');
                if (currentImg && !currentImg.includes('gift.png')) {
                    $('#img_preview_tag').attr('src', currentImg).removeClass('hidden');
                    $('#plus_icon').addClass('hidden');
                } else {
                    $('#img_preview_tag').addClass('hidden');
                    $('#plus_icon').removeClass('hidden');
                }

                $('#appModal').removeClass('hidden').addClass('flex');
            });

            $('.close-modal-trigger').on('click', function() {
                $('#appModal').addClass('hidden').removeClass('flex');
            });

            // Image Trigger
            $('#img_select_box').on('click', function() {
                $('#offer_img_input').click();
            });
            $('#offer_img_input').on('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        $('#img_preview_tag').attr('src', e.target.result).removeClass('hidden');
                        $('#plus_icon').addClass('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Save Logic
            $('#main_save_btn').on('click', function() {
                const text = $('#offer_text_val').val();
                if (!text) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops',
                        text: 'Offer text is required!'
                    });
                    return;
                }
                //alert($('#offer_qty_val').val())
                if ($('#offer_qty_val').val() <= 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops',
                        text: 'Quantity required!'
                    });
                    return;
                }
                const $btn = $(this);
                $btn.prop('disabled', true);
                $('#btn_spinner').removeClass('hidden');

                const formData = new FormData();
                formData.append('_token', "{{ csrf_token() }}");
                formData.append('offer_text', text);
                formData.append('quantity', $('#offer_qty_val').val());
                formData.append('expiry_date', $('#offer_expiry_val').val());
                formData.append('is_active', $('#offer_active_toggle').is(':checked') ? 1 : 0);
                formData.append('index', $('#offer_index_val').val());

                const file = $('#offer_img_input')[0].files[0];
                if (file) formData.append('offer_image', file);

                $.ajax({
                    url: "{{ route('shop.offers.save') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function() {
                        location.reload();
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error'
                        });
                        $btn.prop('disabled', false);
                        $('#btn_spinner').addClass('hidden');
                    }
                });
            });

            // Delete Logic
            $(document).on('click', '.delete-offer-btn', function() {
                const idx = $(this).data('index');
                Swal.fire({
                    title: 'Delete?',
                    icon: 'warning',
                    showCancelButton: true
                }).then((res) => {
                    if (res.isConfirmed) {
                        $.post("{{ route('shop.offers.delete') }}", {
                            _token: "{{ csrf_token() }}",
                            index: idx
                        }, () => location.reload());
                    }
                });
            });
        });
    </script>
@endpush
