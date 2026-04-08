@extends('front_layout.main')

@push('css_or_link')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;700;800&family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Nunito', sans-serif; }
        body { background: #f8fafc; min-height: 100vh; color: #1e293b; }
        
        /* Jhansi Bazaar Green Theme */
        .bg-green-main { background: #16A34A; }
        .text-green-main { color: #16A34A; }
        
        .header-nav {
            padding: 15px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        /* Service Cards */
        .service-card {
            background: #fff;
            border-bottom: 3px solid #16A34A;
            padding: 15px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        .img-box {
            width: 50px;
            height: 50px;
            background: #f0fdf4;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #dcfce7;
        }

        /* Inputs */
        .inp {
            width: 100%;
            background: #fff;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px;
            font-size: 14px;
            outline: none;
            transition: all 0.2s;
        }
        .inp:focus { border-color: #16A34A; box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1); }

        /* Modal */
        .modal-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.7); 
            z-index: 2000; display: none; align-items: center; justify-content: center; padding: 20px;
        }
        .modal-content {
            background: #fff; width: 100%; max-width: 400px;
            border-radius: 20px; overflow: hidden; animation: slideUp 0.3s ease-out;
        }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        .btn-green {
            background: #16A34A; color: #fff; border: none; padding: 14px;
            border-radius: 10px; font-weight: 800; width: 100%; cursor: pointer; font-size: 15px;
        }
        .btn-green:active { transform: scale(0.98); }

        .status-badge {
            font-size: 9px; font-weight: 900; padding: 2px 8px; border-radius: 4px;
            background: #f0fdf4; color: #16A34A; text-transform: uppercase;
        }
    </style>
@endpush

@section('content')
<div style="max-width:480px; margin:0 auto; min-height:100vh; padding-bottom:100px;">
    
    <div class="header-nav bg-green-main">
        <button onclick="window.location.href='{{ url('/') }}'" style="color:#fff; background:none; border:none; font-size:22px; cursor:pointer;">←</button>
        <p style="color:#fff; font-size:17px; font-weight:800; margin:0">Manage Services</p>
        <a href="{{ url('service-register') }}" style="color:#fff; text-decoration:none; font-size:26px; font-weight:bold">+</a>
    </div>

    <div style="padding:16px; background:#fff; margin-bottom:10px;">
        <input type="text" id="serviceSearch" class="inp" placeholder="Search your services...">
    </div>

    <div id="services-wrapper" style="padding:0 12px;">
        <div id="list-loader" style="text-align:center; padding:60px 0;">
            <div style="color:#16A34A; font-weight:800; font-size:14px;">Fetching Services...</div>
        </div>
    </div>
</div>

<div id="editModal" class="modal-overlay">
    <div class="modal-content">
        <div class="bg-green-main" style="padding:15px; color:#fff; display:flex; justify-content:space-between; align-items:center;">
            <span style="font-weight:800; font-size:14px; letter-spacing:1px;">UPDATE SERVICE</span>
            <button onclick="closeModal()" style="color:#fff; background:none; border:none; font-size:24px; cursor:pointer;">&times;</button>
        </div>
        
        <form id="editForm" style="padding:20px;">
            <input type="hidden" id="edit_id">
            
            <div style="margin-bottom:15px;">
                <label style="font-size:11px; font-weight:800; color:#64748b; display:block; margin-bottom:5px;">ITEM NAME</label>
                <input type="text" id="edit_name" name="item_name" class="inp" required>
            </div>

            <div style="display:flex; gap:12px; margin-bottom:20px;">
                <div style="flex:1;">
                    <label style="font-size:11px; font-weight:800; color:#64748b; display:block; margin-bottom:5px;">PRICE (₹)</label>
                    <input type="number" id="edit_price" name="mrp_price" class="inp" required>
                </div>
                <div style="flex:1;">
                    <label style="font-size:11px; font-weight:800; color:#64748b; display:block; margin-bottom:5px;">MINS</label>
                    <input type="number" id="edit_duration" name="service_duration" class="inp" required>
                </div>
            </div>

            <button type="submit" class="btn-green shadow-sm">Save Changes</button>
        </form>
    </div>
</div>
@endsection

@push('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        const BASE_URL = "{{ url('/') }}";

        // Initial fetch call
        fetchServices();

        // 1. Fetch function
        function fetchServices() {
            $.get(`${BASE_URL}/api/services-list`, function(data) {
                $('#list-loader').hide();
                let html = '';
                
                if(data && data.length > 0) {
                    data.forEach(function(item) {
                        html += `
                        <div class="service-card" id="row-${item.id}">
                            <div class="img-box">
                                <span style="font-size:20px">📦</span>
                            </div>
                            <div style="flex:1">
                                <div style="display:flex; align-items:center; gap:8px">
                                    <p style="font-weight:800; font-size:14px; margin:0">${item.item_name}</p>
                                    <span class="status-badge">${item.status || 'Active'}</span>
                                </div>
                                <p style="font-size:12px; color:#64748b; margin-top:2px">
                                    ${item.category || 'General'} • ${item.service_duration} Min
                                </p>
                                <p style="font-size:15px; font-weight:900; color:#16A34A; margin-top:4px">₹${item.mrp_price}</p>
                            </div>
                            <div style="display:flex; gap:12px">
                                <button onclick="openEdit(${item.id})" style="border:none; background:none; font-size:18px; cursor:pointer">✏️</button>
                                <button onclick="deleteService(${item.id})" style="border:none; background:none; font-size:18px; cursor:pointer">🗑️</button>
                            </div>
                        </div>`;
                    });
                    $('#services-wrapper').html(html);
                } else {
                    $('#services-wrapper').html('<div style="text-align:center; padding:50px; color:#94a3b8; font-weight:bold;">No services found. Add some!</div>');
                }
            });
        }

        // 2. Modal Open
        window.openEdit = function(id) {
            $.get(`${BASE_URL}/services/fetch/${id}`, function(res) {
                $('#edit_id').val(res.id);
                $('#edit_name').val(res.item_name);
                $('#edit_price').val(res.mrp_price);
                $('#edit_duration').val(res.service_duration);
                $('#editModal').css('display', 'flex');
            });
        };

        // 3. Modal Close
        window.closeModal = function() {
            $('#editModal').hide();
        };

        // 4. Form Submit (AJAX)
        $('#editForm').on('submit', function(e) {
            e.preventDefault();
            let id = $('#edit_id').val();
            
            $.ajax({
                url: `${BASE_URL}/services/update/${id}`,
                type: "POST",
                data: $(this).serialize() + "&_token={{ csrf_token() }}",
                success: function(response) {
                    closeModal();
                    fetchServices();
                    Swal.fire({ 
                        icon: 'success', 
                        title: 'Updated', 
                        timer: 1000, 
                        showConfirmButton: false,
                        background: '#fff'
                    });
                }
            });
        });

        // 5. Delete Function
        window.deleteService = function(id) {
            Swal.fire({
                title: 'Delete karein?',
                text: "Ye service wapas nahi aayegi!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#16A34A',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Haan, delete!',
                cancelButtonText: 'Nahi'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `${BASE_URL}/services/delete/${id}`,
                        type: "DELETE",
                        data: { _token: "{{ csrf_token() }}" },
                        success: function() { 
                            $(`#row-${id}`).fadeOut(300);
                        }
                    });
                }
            });
        };

        // 6. Search Filter
        $("#serviceSearch").on("keyup", function() {
            let value = $(this).val().toLowerCase();
            $(".service-card").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
@endpush