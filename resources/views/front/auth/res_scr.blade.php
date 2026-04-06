<script>
    
    const OTP_TIMEOUT = 1 * 60 * 1000; // 5 minutes
    let countdownInterval = null;
    var cur_otp = 0;
    // Load state on page load
    $(document).ready(function() {
        $('.form-control').removeClass('is-invalid'); 
        $('.invalid-feedback').remove(); 
        const start = localStorage.getItem('otpStart');
        if (start && Date.now() - start < OTP_TIMEOUT) {
            $('#otpModal').modal('show');
            startCountdown();
        }
    });

    // Open Register Modal
    function openRegister() {
        $('.form-control').removeClass('is-invalid'); 
        $('.invalid-feedback').remove(); 
        const start = localStorage.getItem('otpStart');
        $('#loginModal').modal('hide');
        if (start && Date.now() - start < OTP_TIMEOUT) {
            $('#otpModal').modal('show');
            startCountdown();
            return;
        }
        $('#registerModal').modal('show');
        $('#registerError').addClass('d-none');
    }

    // Register AJAX
    $('#registerBtn').on('click', function() {
        const name = $('#name').val().trim();
        const email = $('#email').val().trim();
        const whatsapp = $('#whatsapp').val().trim();

        if ( !whatsapp) {
            $('#whatsapp').addClass('is-invalid').focus().after('<div class="invalid-feedback">Please Enter Whatsapp no</div>');
            return;
        }
       
        $('#registerBtn')
        .prop('disabled', true)
        .html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Processing...');

        $.ajax({
            url: "{{ url('/') }}/api/user-register",
            method: 'POST',
            data: {
                name,
                email,
                whatsapp,
                visitorId: visitorIdGlobal || null,
               
            },
            success: function(res) {
                if (res.status === 'success') {
                    localStorage.setItem('otpStart', Date.now());
                    $('#registerModal').modal('hide');
                    $('#otpModal').modal('show');
                    notyf.success('✅ OTP sent!');
                    startCountdown();
                    cur_otp = 1;
                    user_portal_id=res.portal_id??null;
                } else {
                    $('#registerError').text(res.message || 'Registration failed').removeClass(
                        'd-none');
                }
            },
            error: function(xhr, status) {
                // Clear previous errors
                $('.form-control').removeClass('is-invalid'); // remove red borders
                $('.invalid-feedback').remove(); // remove old messages

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function(field, messages) {
                        let input = $('#' + field );
                        // alert(field)

                        // Add red border
                        input.addClass('is-invalid').focus();

                        // Add error message below the input
                        input.after('<div class="invalid-feedback">' + messages[0] +
                            '</div>');
                    });
                } else {
                    $('#registerError').text('Error occurred during registration.').removeClass(
                        'd-none');
                    alert(status);
                }
            },
            complete: function() {
                $('#registerBtn').prop('disabled', false).html('Register');
            }
        });
    });

    // Verify OTP AJAX
    $('#verifyBtn').on('click', function() {
        const otp = $('#otp').val().trim();
        const whatsapp = $('#whatsapp').val().trim();

        if (!otp) {
            $('#otpError').text('Please enter OTP').removeClass('d-none');
            return;
        }

        $.ajax({
            url: "{{ url('/') }}/api/user/verify-otp",
            method: 'POST',
            data: {
                otp,
                whatsapp
            },
            success: function(res) {
                if (res.status === 'success') {
                    notyf.success(res.message??'OTP Verified Successfully!');
                    localStorage.removeItem('otpStart');
                    $('#otpModal').modal('hide');
                    
                } else {
                    $('#otpError').text(res.message || 'Incorrect OTP').removeClass('d-none');
                }
            },
            error: function() {
                $('#otpError').text('Error verifying OTP.').removeClass('d-none');
            },
            complete: function() {
                $('#verifyBtn').prop('disabled', false);
            }
        });
    });

    // Countdown Function
    function startCountdown() {
        clearInterval(countdownInterval);
        countdownInterval = setInterval(function() {
            const start = localStorage.getItem('otpStart');
            const now = Date.now();
            const remaining = OTP_TIMEOUT - (now - start);

            if (remaining <= 0) {
                clearInterval(countdownInterval);
                localStorage.removeItem('otpStart');
                if (cur_otp == 0) {
                    $('#otpModal').modal('hide');
                }
                //alert("Time expired! Please register again.");
            } else {
                const minutes = Math.floor(remaining / 60000);
                const seconds = Math.floor((remaining % 60000) / 1000);
                $('#countdown').text(`Time left: ${minutes}m ${seconds}s`);
            }
        }, 1000);
    }
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('loginPassword');
        const icon = document.getElementById('eyeIcon');
        const isPassword = passwordInput.type === 'password';

        passwordInput.type = isPassword ? 'text' : 'password';
        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
    });

    $('.btn_login').on('click', function() {
        const mobileno = $('#mobileno').val().trim();
        const password = $('#loginPassword').val().trim();
        $('.form-control').removeClass('is-invalid'); 
        $('.invalid-feedback').remove(); 

        if ( !mobileno) {
            $('#mobileno').addClass('is-invalid').focus().after('<div class="invalid-feedback">Please Enter Whatsapp no</div>');
            return;
        }
       
        $('.btn_login')
        .prop('disabled', true)
        .html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Processing...');

        $.ajax({
            url: "{{ url('/') }}/api/user-login",
            method: 'POST',
            data: {
                mobileno,
                password,               
                visitorId: visitorIdGlobal || null,
               
            },
            success: function(res) {
                if (res.status === 'success') {
                    
                    $('#loginModal').modal('hide');                    
                    notyf.success(res.message??'✅ Logged!');
                    
                    
                    user_portal_id=res.portal_id??null;
                    
                } else {
                    $('#loginError').text(res.message || 'Registration failed').removeClass(
                        'd-none');
                }
            },
            error: function(xhr, status) {
                // Clear previous errors               

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function(field, messages) {
                        let input = $('#' + field );
                        // alert(field)

                        // Add red border
                        input.addClass('is-invalid').focus();

                        // Add error message below the input
                        input.after('<div class="invalid-feedback">' + messages[0] +
                            '</div>');
                    });
                } else {
                    $('#loginError').text('Error occurred during Login.').removeClass(
                        'd-none');
                    alert(status);
                }
            },
            complete: function() {
                $('.btn_login').prop('disabled', false).html('Login');
                check_user_logged();
            }
        });
    });
    function check_user_logged()
    {
        if(user_portal_id)
        {
            $('.b1-register, .b1-login').addClass('d-none');
            $('.my-acc').removeClass('d-none');
        }
        else
        {
            $('.b1-register, .b1-login').removeClass('d-none');
            $('.my-acc').addClass('d-none');
        }
        return user_portal_id;
    }
</script>

<!-- Bootstrap JS -->
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}
<script>
    const notyf = new Notyf({
        duration: 3000, // milliseconds
        ripple: true,
        dismissible: true
    });
    $(document).on('input', '.form-control', function () {
      $(this).removeClass('is-invalid');
      $(this).next('.invalid-feedback').remove();
  });
  $('#whatsapp').on('input', function () {        
    this.value = this.value.replace(/\D/g, '').slice(0, 10);

    if (this.value.length === 10) {
        $('#name').focus();
    }
  });
  $('#mobileno').on('input', function () {        
    this.value = this.value.replace(/\D/g, '').slice(0, 10);

    if (this.value.length === 10) {
        $('#loginPassword').focus();
    }
  });
  $('#otp').on('input', function () {        
    this.value = this.value.replace(/\D/g, '').slice(0, 4);

    if (this.value.length === 4) {
        $('#verifyBtn').focus();
    }
  });

</script>