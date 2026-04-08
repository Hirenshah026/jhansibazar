@extends('front_layout.main')

@push('css_or_link')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet" />
    <style>
        * { box-sizing: border-box; font-family: 'Nunito', sans-serif; margin: 0; padding: 0; }
        body { background: #f8fafc; min-height: 100vh; }
        
        /* Header Style */
        .header-bar {
            background: #16A34A; padding: 13px 16px; display: flex; 
            align-items: center; position: sticky; top: 0; z-index: 100; box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        /* Input Style */
        .inp-group { background: #fff; border-radius: 16px; padding: 16px; border: 1.5px solid #C7D7FF; margin: 16px; }
        .inp {
            width: 100%; background: #fff; border: 1.5px solid #ddd;
            border-radius: 12px; padding: 12px 14px; font-size: 14px; outline: none; transition: 0.2s;
        }
        .inp:focus { border-color: #16A34A; box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1); }

        /* Button Style */
        .btn-p {
            background: #16A34A; color: #fff; border: none; border-radius: 10px;
            font-weight: 800; cursor: pointer; padding: 12px 20px; width: 100%; margin-top: 10px;
        }

        /* List Style */
        .cat-list-container { padding: 0 16px; }
        .cat-card {
            background: #fff; padding: 14px 16px; border-radius: 14px;
            margin-bottom: 12px; border: 1px solid #e2e8f0;
            display: flex; justify-content: space-between; align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .cat-info p { font-weight: 700; color: #1e293b; font-size: 14px; }
        .cat-actions { display: flex; gap: 15px; align-items: center; }
        
        .action-btn { background: none; border: none; cursor: pointer; font-size: 16px; }
        .edit-btn { color: #3B5BDB; }
        .delete-btn { color: #ef4444; }

        /* Loader */
        #global-loader {
            display:none; position:fixed; inset:0; background:rgba(255,255,255,0.8); 
            z-index:2000; flex-direction:column; align-items:center; justify-content:center;
        }
        .spinner {
            width:40px; height:40px; border:4px solid #f3f3f3; border-top:4px solid #16A34A; 
            border-radius:50%; animation:spin 1s linear infinite;
        }
        @keyframes spin { 100% { transform: rotate(360deg); } }
    </style>
@endpush

@section('content')
    <div style="max-width:480px; margin:0 auto; padding-bottom:100px">
        
        <div id="global-loader">
            <div class="spinner"></div>
            <p style="margin-top:10px; font-weight:800; color:#16A34A">Processing...</p>
        </div>

        <div class="header-bar">
            <button onclick="window.history.back()" style="color:#fff; background:none; border:none; font-size:20px;">←</button>
            <p style="color:#fff; font-size:16px; font-weight:800; margin-left:15px">Category Manager</p>
        </div>

        <div class="inp-group">
            <p style="font-size:11px; font-weight:800; color:#16A34A; text-transform:uppercase; margin-bottom:8px">Create New Category</p>
            <input type="text" id="cat-name-input" class="inp" placeholder="Enter Category Name (e.g. Spa)">
            <button onclick="saveCategory()" class="btn-p">Save Category</button>
        </div>

        <div class="cat-list-container">
            <p style="font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; margin-bottom:12px; padding-left:5px">All Categories</p>
            
            <div id="category-list-wrapper">
                @foreach($categories as $cat)
                <div class="cat-card" id="cat-row-{{ $cat->id }}">
                    <div class="cat-info">
                        <p id="name-text-{{ $cat->id }}">{{ $cat->name }}</p>
                    </div>
                    <div class="cat-actions">
                        <button class="action-btn edit-btn" onclick="editCategory({{ $cat->id }}, '{{ $cat->name }}')">✏️</button>
                        <button class="action-btn delete-btn" onclick="deleteCategory({{ $cat->id }})">🗑️</button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    
@endsection

@push('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // AJAX Setup for CSRF
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // --- ADD CATEGORY ---
    function saveCategory() {
        let name = $('#cat-name-input').val();
        if(!name) {
            Swal.fire({ icon: 'warning', title: 'Khali hai!', text: 'Bhai, category ka naam to dalo.', confirmButtonColor: '#16A34A' });
            return;
        }

        $('#global-loader').css('display', 'flex');

        $.ajax({
            url: "{{ route('categories.store') }}",
            type: "POST",
            data: { name: name },
            success: function(res) {
                $('#global-loader').hide();
                if(res.status == 'success') {
                    $('#cat-name-input').val('');
                    
                    let newHtml = `
                        <div class="cat-card" id="cat-row-${res.data.id}">
                            <div class="cat-info">
                                <p id="name-text-${res.data.id}">${res.data.name}</p>
                            </div>
                            <div class="cat-actions">
                                <button class="action-btn edit-btn" onclick="editCategory(${res.data.id}, '${res.data.name}')">✏️</button>
                                <button class="action-btn delete-btn" onclick="deleteCategory(${res.data.id})">🗑️</button>
                            </div>
                        </div>`;
                    
                    $('#category-list-wrapper').prepend(newHtml);
                    Swal.fire({ icon: 'success', title: 'Done!', text: 'Category add ho gayi.', timer: 1500, showConfirmButton: false });
                }
            },
            error: function() { 
                $('#global-loader').hide();
                Swal.fire({ icon: 'error', title: 'Error', text: 'Server response nahi de raha.' });
            }
        });
    }

    // --- UPDATE CATEGORY ---
    function editCategory(id, oldName) {
        Swal.fire({
            title: 'Edit Category Name',
            input: 'text',
            inputValue: oldName,
            showCancelButton: true,
            confirmButtonText: 'Update Karo',
            confirmButtonColor: '#16A34A',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.value) {
                $('#global-loader').css('display', 'flex');
                $.ajax({
                    url: "{{ route('categories.update') }}", // Make sure this route exists
                    type: "POST",
                    data: { id: id, name: result.value },
                    success: function(res) {
                        $('#global-loader').hide();
                        $(`#name-text-${id}`).text(result.value);
                        Swal.fire({ icon: 'success', title: 'Updated!', timer: 1000, showConfirmButton: false });
                    }
                });
            }
        });
    }

    // --- DELETE CATEGORY ---
    function deleteCategory(id) {
        Swal.fire({
            title: 'Delete kar dein?',
            text: "Wapas nahi aayega ye data!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Haan, Delete!',
            cancelButtonText: 'Nahi'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('categories.delete') }}",
                    type: "POST",
                    data: { id: id },
                    success: function(res) {
                        $(`#cat-row-${id}`).fadeOut(400, function() { $(this).remove(); });
                    }
                });
            }
        });
    }
</script>
@endpush