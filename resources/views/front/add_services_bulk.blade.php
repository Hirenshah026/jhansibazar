@extends('front_layout.main')
@push('css_or_link')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link
        href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;700;800&family=Nunito:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <style>
        /* --- TERA ORIGINAL CSS (UNCHANGED) --- */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Nunito', sans-serif
        }

        .fd {
            font-family: 'Baloo 2', sans-serif
        }

        body {
            background: #F4F6FF;
            min-height: 100vh
        }

        .inp {
            width: 100%;
            background: #fff;
            border: 1.5px solid #C7D7FF;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 14px;
            color: #1e293b;
            outline: none;
            transition: all .2s
        }

        .inp:focus {
            border-color: #3B5BDB;
            box-shadow: 0 0 0 3px rgba(59, 91, 219, .1)
        }

        .lbl {
            font-size: 11px;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: .6px;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 5px
        }

        .sec {
            background: #fff;
            border-radius: 18px;
            padding: 16px;
            border: 1.5px solid #C7D7FF;
            margin-bottom: 14px;
            box-shadow: 0 2px 10px rgba(59, 91, 219, .06)
        }

        .sec-title {
            font-size: 13px;
            font-weight: 800;
            color: #3B5BDB;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 10px;
            border-bottom: 1.5px solid #E8EDFF
        }

        .chip {
            border: 1.5px solid #C7D7FF;
            border-radius: 20px;
            padding: 7px 14px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all .15s;
            background: #fff;
            color: #475569;
            white-space: nowrap
        }

        .chip.on {
            background: #3B5BDB;
            color: #fff;
            border-color: #3B5BDB
        }

        .btn-p {
            background: #3B5BDB;
            color: #fff;
            border: none;
            border-radius: 13px;
            font-weight: 800;
            cursor: pointer;
            transition: all .15s
        }

        .toggle {
            width: 44px;
            height: 24px;
            border-radius: 12px;
            background: #CBD5E1;
            position: relative;
            transition: background .2s
        }

        .toggle.on {
            background: #3B5BDB
        }

        .toggle::after {
            content: '';
            position: absolute;
            top: 3px;
            left: 3px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #fff;
            transition: transform .2s
        }

        .toggle.on::after {
            transform: translateX(20px)
        }

        .ai-locked {
            background: linear-gradient(135deg, #1e1b4b, #3730a3);
            border-radius: 16px;
            padding: 14px;
            color: white;
            position: relative;
            overflow: hidden
        }

        .form-tab {
            padding: 9px 16px;
            background: transparent;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            color: #64748b;
            border-bottom: 2.5px solid transparent
        }

        .form-tab.active {
            color: #3B5BDB;
            border-bottom-color: #3B5BDB
        }

        /* Chips */
        .chp {
            border: 1.5px solid #C7D7FF;
            border-radius: 20px;
            padding: 7px 14px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all .15s;
            background: #fff;
            color: #475569;
            user-select: none;
            white-space: nowrap
        }

        .chp:hover {
            border-color: #3B5BDB;
            color: #3B5BDB;
            background: #EEF2FF
        }

        .chp.on {
            background: #3B5BDB;
            color: #fff;
            border-color: #3B5BDB
        }

        /* Category-specific field highlight */
        .cat-field {
            background: #FFFBEB;
            border-radius: 14px;
            padding: 12px;
            border: 1.5px solid #FDE68A;
            margin-bottom: 10px
        }

        .cat-field-title {
            font-size: 10px;
            font-weight: 800;
            color: #B45309;
            text-transform: uppercase;
            letter-spacing: .6px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 4px
        }
    </style>
    <style>
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .mb-3 {
            margin-bottom: 12px;
        }

        .mb-4 {
            margin-bottom: 16px;
        }

        .mt-4 {
            margin-top: 16px;
        }

        .btn_remove {
            color: #ef4444;
            border: 1px solid red;
            background: none;
            font-weight: 900;
            font-size: 10px;
            padding: 2px 11px;
            border-radius: 14px;
        }
    </style>
@endpush
@section('content')
    <div style="max-width:480px;margin:0 auto;min-height:100vh; background:#f8fafc; padding-bottom:100px">

        <div id="global-loader"
            style="display:none; position:fixed; inset:0; background:rgba(255,255,255,0.9); z-index:2000; flex-direction:column; align-items:center; justify-content:center">
            <div class="spinner"
                style="width:40px;height:40px;border:4px solid #f3f3f3;border-top:4px solid #16A34A;border-radius:50%;animation:spin 1s linear infinite">
            </div>
            <p style="margin-top:15px; font-weight:800; color:#16A34A">Saving Services...</p>
        </div>

        <div
            style="background:#16A34A;padding:13px 16px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100">
            <button onclick="window.history.back()"
                style="color:#fff; background:none; border:none; font-size:20px">←</button>
            <p style="color:#fff;font-size:16px;font-weight:800;margin:0">Bulk Service Entry</p>
            <div style="width:30px"><a href="{{ url('services/list') }}"
                    style="color:#fff; text-decoration:none;  font-weight:bold">List</a></div>
        </div>

        <div id="bulk-container">
            <div class="item-block card-item" id="item-row-1"
                style="background:#fff; border-bottom:4px solid #16A34A; margin-top:10px">
                <div style="padding:8px 16px; background:#f0fdf4; font-size:11px; font-weight:800; color:#16A34A">SERVICE #1
                </div>

                <div style="padding:16px">
                    <p style="font-size:12px; font-weight:700; margin-bottom:5px; color:#475569">Your Service Name *</p>
                    <input class="inp item-name" type="text" placeholder="e.g. Hair Cut"
                        style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px; margin-bottom:15px">

                    <div style="display:flex; gap:10px; margin-bottom:15px">
                        <div style="flex:1">
                            <p style="font-size:12px; font-weight:700; margin-bottom:5px; color:#475569">MRP Price *</p>
                            <input class="inp item-price" type="number" placeholder="₹"
                                style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px">
                        </div>
                        <div style="flex:1">
                            <p style="font-size:12px; font-weight:700; margin-bottom:5px; color:#475569">Discount Price</p>
                            <input class="inp item-discount" type="number" placeholder="₹"
                                style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px">
                        </div>
                    </div>

                    <p style="font-size:12px; font-weight:700; margin-bottom:8px; color:#475569">Category (Optional) <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 inset-ring inset-ring-green-600/20 cursor-pointer"
                            onclick="showCategoryForm()" class="cursor-pointer">+Add New</span></p>
                    <div class="category-group" style="display:flex; gap:7px; flex-wrap:wrap; margin-bottom:15px">
                        <button type="button" class="chp on" onclick="handleChip(this)">Hair</button>
                        <button type="button" class="chp" onclick="handleChip(this)">Facial</button>
                        <button type="button" class="chp" onclick="handleChip(this)">Makeup</button>
                    </div>

                    <div style="display:flex; gap:10px">
                        <div style="flex:1">
                            <p style="font-size:12px; font-weight:700; margin-bottom:5px; color:#475569">Duration (Min)</p>
                            <input class="inp item-duration" type="number" placeholder="30"
                                style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px">
                        </div>
                        <div style="flex:1">
                            <p style="font-size:12px; font-weight:700; margin-bottom:5px; color:#475569">For Whom</p>
                            <div class="gender-group" style="display:flex; gap:4px">
                                <button type="button" class="chp on" onclick="handleChip(this)"
                                    style="font-size:11px; padding:8px 5px">Women</button>
                                <button type="button" class="chp" onclick="handleChip(this)"
                                    style="font-size:11px; padding:8px 5px">Men</button>
                                <button type="button" class="chp" onclick="handleChip(this)"
                                    style="font-size:11px; padding:8px 5px">Unisex</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="padding:20px 16px; display:flex; flex-direction:column; gap:12px">
            <button onclick="addNewItem(1)"
                style="width:100%; padding:12px; border:2px dashed #16A34A; color:#16A34A; background:#fff; font-weight:800; border-radius:10px cursor:pointer">
                + Add Another Service
            </button>

            <button class="btn-p" id="submit-ajax-btn"
                style="width:100%; padding:16px; background:#16A34A; color:#fff; border:none; border-radius:10px; font-weight:800; font-size:16px; cursor:pointer">
                Confirm & Save All
            </button>
        </div>

        <div id="catPopUp"
            style="display:none; position:fixed; inset:0; background:rgba(15, 23, 42, 0.7); backdrop-filter: blur(4px); z-index:9999; align-items:center; justify-content:center; padding:20px;">

            <div
                style="background:#fff; width:100%; max-width:380px; border-radius:24px; overflow:hidden; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); border: 1px solid #e2e8f0;">

                <div style="background:#16A34A; padding:20px; color:#fff; text-align:center;">
                    <div
                        style="background:rgba(255,255,255,0.2); width:50px; height:50px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 12px auto;">
                        <span style="font-size:24px;">➕</span>
                    </div>
                    <h3
                        style="margin:0; font-size:18px; font-weight:800; letter-spacing:0.5px; font-family:'Baloo 2', cursive;">
                        NEW CATEGORY</h3>
                    <p style="margin:4px 0 0 0; font-size:12px; opacity:0.9; font-weight:600;">Add to your marketplace list
                    </p>
                </div>

                <div style="padding:24px;">
                    <div style="margin-bottom:20px;">
                        <label
                            style="display:block; font-size:11px; font-weight:800; color:#16A34A; text-transform:uppercase; margin-bottom:8px; padding-left:4px;">
                            Category Name
                        </label>
                        <input type="text" id="new-cat-name" placeholder="e.g. Mobile Repairing"
                            style="width:100%; background:#f8fafc; border:2px solid #e2e8f0; border-radius:14px; padding:14px; font-size:15px; font-weight:700; color:#1e293b; outline:none; transition:0.2s;"
                            onfocus="this.style.borderColor='#16A34A'; this.style.background='#fff';"
                            onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc';">
                    </div>

                    <div style="display:flex; gap:12px;">
                        <button onclick="hideCategoryForm()"
                            style="flex:1; background:#f1f5f9; color:#64748b; border:none; border-radius:14px; padding:15px; font-weight:800; font-size:14px; cursor:pointer; transition:0.2s;">
                            Cancel
                        </button>

                        <button onclick="saveQuickCategory()"
                            style="flex:2; background:#16A34A; color:#fff; border:none; border-radius:14px; padding:15px; font-weight:800; font-size:14px; cursor:pointer; box-shadow: 0 4px 15px rgba(22, 163, 74, 0.3); transition:0.2s;"
                            onmousedown="this.style.transform='scale(0.96)'" onmouseup="this.style.transform='scale(1)'">
                            Save Category
                        </button>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <style>
        .chp {
            padding: 8px 15px;
            border-radius: 20px;
            border: 1.5px solid #ddd;
            background: #fff;
            color: #666;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            flex: 1;
            text-align: center;
        }

        .chp.on {
            background: #f0fdf4;
            border-color: #16A34A;
            color: #16A34A;
        }

        .inp:focus {
            outline: none;
            border-color: #16A34A !important;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let itemCount = 1;

        function addNewItem(num) {
            for (let i = 0; i < num; i++) {
                itemCount++;
                const html = `
            <div class="item-block card-item" id="item-row-${itemCount}" style="background:#fff; border-bottom:4px solid #16A34A; margin-top:15px">
                <div style="padding:8px 16px; background:#f0fdf4; display:flex; justify-content:space-between; align-items:center">
                    <span style="font-size:11px; font-weight:800; color:#16A34A">SERVICE #${itemCount}</span>
                    <button type="button" onclick="removeItem(${itemCount})" style="color:#ef4444; border:none; background:none; font-weight:900; font-size:10px">❌ REMOVE</button>
                </div>
                <div style="padding:16px">
                    <p style="font-size:12px; font-weight:700; margin-bottom:5px; color:#475569">Your Service Name *</p>
                    <input class="inp item-name" type="text" placeholder="Service Name" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px; margin-bottom:15px">
                    <div style="display:flex; gap:10px; margin-bottom:15px">
                        <div style="flex:1">
                            <p style="font-size:12px; font-weight:700; margin-bottom:5px; color:#475569">MRP Price *</p>
                            <input class="inp item-price" type="number" placeholder="₹" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px">
                        </div>
                        <div style="flex:1">
                            <p style="font-size:12px; font-weight:700; margin-bottom:5px; color:#475569">Discount Price</p>
                            <input class="inp item-discount" type="number" placeholder="₹" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px">
                        </div>
                    </div>
                    <p style="font-size:12px; font-weight:700; margin-bottom:8px; color:#475569">Category (Optional)</p>
                    <div class="category-group" style="display:flex; gap:7px; flex-wrap:wrap; margin-bottom:15px">
                        <button type="button" class="chp on" onclick="handleChip(this)">Hair</button>
                        <button type="button" class="chp" onclick="handleChip(this)">Facial</button>
                        <button type="button" class="chp" onclick="handleChip(this)">Makeup</button>
                    </div>
                    <div style="display:flex; gap:10px">
                        <div style="flex:1">
                            <p style="font-size:12px; font-weight:700; margin-bottom:5px; color:#475569">Duration (Min)</p>
                            <input class="inp item-duration" type="number" placeholder="30" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px">
                        </div>
                        <div style="flex:1">
                            <p style="font-size:12px; font-weight:700; margin-bottom:5px; color:#475569">For Whom</p>
                            <div class="gender-group" style="display:flex; gap:4px">
                                <button type="button" class="chp on" onclick="handleChip(this)" style="font-size:11px; padding:8px 5px">Women</button>
                                <button type="button" class="chp" onclick="handleChip(this)" style="font-size:11px; padding:8px 5px">Men</button>
                                <button type="button" class="chp" onclick="handleChip(this)" style="font-size:11px; padding:8px 5px">Unisex</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
                $('#bulk-container').append(html);
            }
        }

        function handleChip(btn) {
            $(btn).parent().find('.chp').removeClass('on');
            $(btn).addClass('on');
        }

        function removeItem(id) {
            $(`#item-row-${id}`).fadeOut(300, function() {
                $(this).remove();
            });
            --itemCount;
        }

        $('#submit-ajax-btn').on('click', function() {
            let items = [];
            let isValid = true;

            // Validation Check
            $('.card-item').each(function() {
                let name = $(this).find('.item-name').val();
                let price = $(this).find('.item-price').val();

                if (!name || !price) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Opps...',
                        text: 'Bhai, Service Name aur Price bharna zaroori hai!',
                        confirmButtonColor: '#16A34A',
                    });
                    isValid = false;
                    return false;
                }

                items.push({
                    name: name,
                    price: price,
                    discount: $(this).find('.item-discount').val(),
                    category: $(this).find('.category-group .chp.on').text(),
                    duration: $(this).find('.item-duration').val(),
                    gender: $(this).find('.gender-group .chp.on').text()
                });
            });

            if (!isValid) return;

            // Confirm Before Save (App Style)
            Swal.fire({
                title: 'Kyu bhai, Save kar dein?',
                text: "Saari services check kar li hain na?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16A34A',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Haan, Save Karo!',
                cancelButtonText: 'Ruko, Check karna h'
            }).then((result) => {
                if (result.isConfirmed) {

                    // Loader dikhao (Global loader ki jagah SweetAlert loader bhi use kar sakte ho)
                    $('#global-loader').css('display', 'flex');

                    $.ajax({
                        url: "{{ route('services.bulk-store') }}",
                        type: "POST",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            services: items
                        },
                        success: function(response) {
                            $('#global-loader').hide();

                            if (response.status == 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Mubarak ho!',
                                    text: 'Saari services successfully save ho gayi hain.',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Kuch Gadbad Hui',
                                    text: response.message,
                                    confirmButtonColor: '#16A34A',
                                });
                            }
                        },
                        error: function(xhr) {
                            $('#global-loader').hide();
                            Swal.fire({
                                icon: 'error',
                                title: 'Server Error',
                                text: 'Bhai, lagta hai internet ya server mein issue hai.',
                                confirmButtonColor: '#16A34A',
                            });
                        }
                    });
                }
            });
        });
    </script>
    <script>
        // Bank softare jaisa window dikhane ke liye
        function showCategoryForm() {
            $('#catPopUp').css('display', 'flex');
        }

        // Window band karne ke liye
        function hideCategoryForm() {
            $('#catPopUp').hide();
            $('#new-cat-name').val('');
        }

        // Bina refresh save karne ke liye
        function saveQuickCategory() {
            let name = $('#new-cat-name').val();
            if (!name) return alert("Naam likho bhai!");

            $.ajax({
                url: "{{ route('categories.store') }}",
                type: "POST",
                data: {
                    name: name,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    if (res.status == 'success') {
                        // Dropdown mein naya data daalo
                        $('#category_id').append(
                            `<option value="${res.data.id}" selected>${res.data.name}</option>`);

                        // Window band kar do
                        hideCategoryForm();

                        Swal.fire({
                            icon: 'success',
                            title: 'Saved!',
                            timer: 1000,
                            showConfirmButton: false
                        });
                    }
                }
            });
        }
    </script>
@endpush
