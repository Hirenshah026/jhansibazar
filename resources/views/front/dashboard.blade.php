@extends('front_layout.main')

@push('css_or_link')
    <style>
        .profile-circle-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        .progress-ring-container {
            position: relative;
            width: 130px;
            height: 130px;
        }

        .progress-ring {
            transform: rotate(-90deg);
            position: absolute;
            top: 0;
            left: 0;
        }

        .progress-ring__background {
            fill: transparent;
            stroke: #e6e6e6;
        }

        .progress-ring__circle {
            fill: transparent;
            stroke: #4caf50;
            stroke-linecap: round;
            transition: stroke-dashoffset 0.35s;
        }

        .profile-img-wrapper {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 112px;
            height: 112px;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #ddd;
            background: #fff;
            cursor: pointer;
        }

        .profile-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .edit-icon {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #4caf50;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 16px;
            border: 2px solid white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .edit-icon:hover {
            background-color: #388e3c;
        }

        .progress-text {
            position: absolute;
            bottom: -25px;
            width: 100%;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
            color: #444;
        }

        .profile-box {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
        }

        .profile-row {
            margin-bottom: 15px;
        }

        .label {
            font-weight: bold;
        }
    </style>
@endpush

@section('content')
    <div class="container mt-5">
        <div class="card p-4 shadow-sm">

            <h4 class="mb-3 text-center">मेरा प्रोफ़ाइल</h4>

            <!-- Circular Profile Image + Progress -->
            <div class="profile-circle-wrapper">
                <div class="progress-ring-container">
                    <svg class="progress-ring" width="130" height="130">
                        <circle class="progress-ring__background" cx="65" cy="65" r="60" stroke-width="5" />
                        <circle class="progress-ring__circle" cx="65" cy="65" r="60" stroke-width="5" />
                    </svg>
                    <div class="profile-img-wrapper" id="profileImgWrapper" title="प्रोफ़ाइल फोटो बदलें">
                        <img src="https://shammtech.in/front_assets/images/logo/logo_new.png" alt="User" id="profileImage"  onerror="this.onerror=null; this.src='https://shammtech.in/front_assets/images/logo/logo_new.png';">
                        <div class="edit-icon" id="editIcon" title="अपलोड करें">&#9998;</div>
                        <input type="file" id="profileImageInput" accept="image/*" style="display:none;">
                    </div>
                    <div class="progress-text" id="profilePercent">0%</div>
                </div>
            </div>

            <!-- Editable Profile Form -->
            <form id="updateProfileForm">
                <div class="profile-box">
                    <div class="profile-row">
                        <label class="label" for="name">नाम:</label>
                        <input type="text" name="name" id="name" class="form-control form-control-sm" required>
                    </div>
                    <div class="profile-row">
                        <label class="label" for="category">श्रेणी:</label>
                        <select name="category" id="category" class="form-select form-select-sm" required>
                            <option value="">-- चुनें --</option>
                            <option value="1">ऑटो ड्राइवर</option>
                            <option value="2">कार ड्राइवर</option>
                        </select>
                    </div>
                    <div class="profile-row">
                        <label class="label" for="whatsapp">WhatsApp नंबर:</label>
                        <input type="text" name="whatsapp" id="whatsapp" class="form-control form-control-sm" required>
                    </div>
                    <div class="profile-row">
                        <label class="label" for="city">शहर:</label>
                        <input type="text" name="city" id="city" class="form-control form-control-sm" required>
                    </div>
                    <div class="profile-row">
                        <label class="label" for="area_id">इलाका:</label>
                        <select name="area_id" id="area_id" class="form-select form-select-sm" required>
                            <option value="">-- चुनें --</option>
                            <option value="1">रेलवे स्टेशन</option>
                            <option value="2">बस स्टैंड</option>
                            <option value="3">बड़ा बाजार</option>
                        </select>
                    </div>
                    <div class="profile-row">
                        <label class="label" for="contact_number">संपर्क नंबर:</label>
                        <input type="text" name="contact_number" id="contact_number" class="form-control form-control-sm"
                            required>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success btn-sm">Update</button>
                    <a href="{{url('/')}}/" class="btn btn-danger btn-sm">Back</a>
                </div>
            </form>

        </div>
    </div>
@endsection

@push('script')
    
    <script>
        
        let profileData = {};

        function setProfileCompletion(percent) {
            const circle = document.querySelector('.progress-ring__circle');
            const radius = circle.r.baseVal.value;
            const circumference = 2 * Math.PI * radius;

            circle.style.strokeDasharray = `${circumference} ${circumference}`;
            const offset = circumference - (percent / 100) * circumference;
            circle.style.strokeDashoffset = offset;

            document.getElementById('profilePercent').innerText = `${percent}%`;
        }

        function calculateCompletion(data) {
            const fields = ['name', 'category', 'whatsapp', 'city', 'area_id', 'contact_number', 'profile_image'];
            let filled = 0;
            fields.forEach(f => {
                if (data[f] !== undefined && data[f] !== null && data[f] !== '') {
                    filled++;
                }
            });
            return Math.round((filled / fields.length) * 100);
        }

        function fillDashboard(data) {
            profileData = data;
            $('#name').val(data.name || '');
            $('#category').val(data.category_id || '');
            $('#whatsapp').val(data.whatsapp_number || '');
            $('#city').val(data.city || '');
            $('#area_id').val(data.area_id || '');
            $('#contact_number').val(data.contact_number || '');

            let imageUrl = data.profile_image ? '/storage/' + data.profile_image : '/images/default-user.png';
            $('#profileImage').attr('src', imageUrl);

            let percent = calculateCompletion(data);
            setProfileCompletion(percent);
        }

        function fetchDashboard() {
            $.ajax({
                url: "{{ url('/api/user-fetch') }}",
                method: 'GET',
                data: {
                    device_id: deviceId
                },
                success: function(res) {
                    if (res.data) {
                        fillDashboard(res.data);
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching dashboard:', xhr.responseText);
                }
            });
        }

        
        setTimeout(() => {
            fetchDashboard();
        }, 2000);
        // Handle profile image click to open file selector
        $('#profileImgWrapper').on('click', function() {
            $('#profileImageInput').click();
        });

        // Handle image selection
        $('#profileImageInput').on('change', function(e) {
            let file = e.target.files[0];
            if (!file) return;

            // Preview image locally
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#profileImage').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);

            // Upload image via AJAX
            let formData = new FormData();
            formData.append('profile_image', file);
            formData.append('device_id', deviceId);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: '{{ url('multi.step.uploadImage') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    alert('प्रोफ़ाइल फोटो सफलतापूर्वक अपडेट हो गई।');
                    profileData.profile_image = res.data.profile_image;
                    let percent = calculateCompletion(profileData);
                    setProfileCompletion(percent);
                },
                error: function(xhr) {
                    alert('इमेज अपलोड में त्रुटि हुई।');
                    console.error(xhr.responseText);
                }
            });
        });

        // Handle profile update form submit
        $('#updateProfileForm').submit(function(e) {
            e.preventDefault();

            let formData = $(this).serializeArray();
            formData.push({
                name: 'device_id',
                value: deviceId
            });

            $.ajax({
                url: '{{ url('multi.step.update') }}',
                method: 'POST',
                data: formData,
                success: function(res) {
                    alert('प्रोफ़ाइल सफलतापूर्वक अपडेट हो गई!');
                    fillDashboard(res.data);
                },
                error: function(xhr) {
                    alert('प्रोफ़ाइल अपडेट में त्रुटि हुई।');
                    console.error(xhr.responseText);
                }
            });
        });
    </script>
@endpush
