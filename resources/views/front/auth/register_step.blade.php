@extends('front_layout.main')
@push('css_or_link')
    <style>
        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
        }
    </style>
@endpush
@section('content')
    <div class="container mt-5">
        <div class="card p-4 shadow-sm">
            <h4 class="mb-3 text-center">रजिस्टर करें</h4>
            <a href="{{url('/register')}}" class="btn  btn-sm btn-success">Register Simple</a>
            <form id="registerForm">

                <!-- Step 1 -->
                <div class="form-step step-1 active">
                    <label class="form-label">नाम:</label>
                    <input type="text" class="form-control" name="name" required>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="button" class="btn btn-sm btn-primary next-btn">आगे बढ़ें</button>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="form-step step-2">
                    <label class="form-label">श्रेणी:</label>
                    <select class="form-select" name="category" required>
                        <option value="">-- चुनें --</option>
                        <option value="auto">ऑटो ड्राइवर</option>
                        <option value="car">कार ड्राइवर</option>
                    </select>
                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-sm btn-secondary back-btn">पीछे जाएं</button>
                        <button type="button" class="btn btn-sm btn-primary next-btn">आगे बढ़ें</button>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="form-step step-3">
                    <label class="form-label">WhatsApp नंबर:</label>
                    <input type="text" class="form-control" name="whatsapp" required>
                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-sm btn-secondary back-btn">पीछे जाएं</button>
                        <button type="button" class="btn btn-sm btn-primary next-btn">आगे बढ़ें</button>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="form-step step-4">
                    <label class="form-label">शहर:</label>
                    <input type="text" class="form-control" name="city" required>
                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-sm btn-secondary back-btn">पीछे जाएं</button>
                        <button type="button" class="btn btn-sm btn-primary next-btn">आगे बढ़ें</button>
                    </div>
                </div>

                <!-- Step 5 -->
                <div class="form-step step-5">
                    <label class="form-label">इलाका:</label>
                    <select class="form-select" name="area_id" required>
                        <option value="">-- चुनें --</option>
                        <option value="1">रेलवे स्टेशन</option>
                        <option value="2">बस स्टैंड</option>
                        <option value="3">बड़ा बाजार</option>
                    </select>
                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-sm btn-secondary back-btn">पीछे जाएं</button>
                        <button type="button" class="btn btn-sm btn-primary next-btn">आगे बढ़ें</button>
                    </div>
                </div>

                <!-- Step 6 -->
                <div class="form-step step-6">
                    <label class="form-label">संपर्क नंबर:</label>
                    <input type="text" class="form-control" name="contact_number" required>
                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-sm btn-secondary back-btn">पीछे जाएं</button>
                        <button type="submit" class="btn btn-sm btn-success">रजिस्टर करें</button>
                    </div>
                </div>

            </form>

        </div>
    </div>
@endsection
@push('script')
   <script>
   $(document).ready(function () {
    let currentStep = 1;
    const totalSteps = $('.form-step').length;
    loadUserData();

    function showStep(step) {
        $('.form-step').removeClass('active');
        $('.step-' + step).addClass('active');
    }

    // Step 1 ka data turant save karo aur baki data load karo
    function loadUserData() {
        $.ajax({
            url: '{{ url("multi.step.fetch") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                device_id: deviceId
            },
            success: function(res) {
                if(res.success && res.data) {
                    // Agar step 1 ka data hai to fill karo aur currentStep ko aage badhao
                    if(res.data.name) {
                        $('[name="name"]').val(res.data.name);
                        currentStep = 2;
                    }
                    // Baaki fields fill karo (optional, agar mile to)
                    const fields = ['category', 'whatsapp', 'city', 'area_id', 'contact_number'];
                    fields.forEach(field => {
                        if(res.data[field]) {
                            $('[name="'+field+'"]').val(res.data[field]);
                        }
                    });
                }
                showStep(currentStep);
            }
        });
    }

    function saveStep1Data(callback) {
        let nameVal = $('[name="name"]').val();
        if(!nameVal) {
            alert('कृपया नाम दर्ज करें');
            callback(false);
            return;
        }
        $.ajax({
            url: "{{ url('/api/user-register') }}",
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                device_id: deviceId,
                name: nameVal
            },
            success: function(res) {
                if(res.success) {
                    callback(true);
                } else {
                    alert('स्टेप 1 डेटा सहेजने में समस्या आई। पुनः प्रयास करें।');
                    callback(false);
                }
            },
            error: function() {
                alert('सर्वर त्रुटि, बाद में पुनः प्रयास करें।');
                callback(false);
            }
        });
    }

    $('.next-btn').click(function () {
        if(currentStep === 1) {
            // Step 1 save karo turant
            saveStep1Data(function(success) {
                if(success) {
                    currentStep++;
                    showStep(currentStep);
                }
            });
        } else {
            // Baaki steps pe validation karo aur next step dikhao (save nahi karenge abhi)
            const currentDiv = $('.step-' + currentStep);
            const inputs = currentDiv.find('input, select');
            for(let i=0; i<inputs.length; i++) {
                if(!inputs[i].checkValidity()) {
                    inputs[i].reportValidity();
                    return;
                }
            }
            currentStep++;
            showStep(currentStep);
        }
    });

    $('.back-btn').click(function () {
        if(currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    });

    $('#registerForm').submit(function(e) {
        e.preventDefault();

        // Final submit pe poora form validate karo
        const allInputs = $('#registerForm').find('input, select');
        for(let i=0; i<allInputs.length; i++) {
            if(!allInputs[i].checkValidity()) {
                allInputs[i].reportValidity();
                return;
            }
        }

        // Final data collect karo (except name, kyunki wo already saved hai)
        let formData = {
            _token: '{{ csrf_token() }}',
            device_id: deviceId
        };
        ['category', 'whatsapp', 'city', 'area_id', 'contact_number'].forEach(field => {
            formData[field] = $('[name="'+field+'"]').val();
        });

        $.ajax({
            url: "{{ url('/api/user-register-save-final') }}",
            method: 'POST',
            data: formData,
            success: function(res) {
                if(res.success) {
                    alert('पंजीकरण सफल रहा!');
                   location.assign("{{url(/my-account)}}");
                } else {
                    alert('डेटा सहेजने में समस्या आई। पुनः प्रयास करें।');
                }
            },
            error: function() {
                alert('सर्वर त्रुटि, बाद में पुनः प्रयास करें।');
            }
        });
    });
});

</script>

@endpush
