@extends('frontend::layouts.auth_layout')

@section('content')
<div id="login">
    <div class="vh-100" style="background-image: url('https://tihd.tv/storage/streamit-laravel/login-background-67961ad3d3542.png')">
        <div class="container">
            <div class="row justify-content-center align-items-center height-self-center vh-100">
                <div class="col-lg-5 col-md-8 col-12 align-self-center">
                    <div class="user-login-card card">
                        <div class="text-end">
                            <a href="/">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="4" x2="6" y2="18"></line>
                                    <line x1="6" y1="4" x2="18" y2="18"></line>
                                </svg>
                            </a>
                        </div>

                        <div class="text-center auth-heading">
                            @php
                                $logo = GetSettingValue('dark_logo') ?? asset(setting('dark_logo'));
                            @endphp
                            <img src="{{ $logo }}" class="img-fluid logo h-4 mb-4" alt="Logo">
                            <h1 class="h5">{{ __('frontend.sign_in_title') }}</h1>
                            @if (session()->has('error'))
                                <span class="text-danger">{{ session()->get('error') }}</span>
                            @endif
                        </div>

                        <p class="text-danger" id="otp_error_message"></p>
                        <p class="text-success" id="otp_success_message"></p>
                        <p class="fs-14" id="otp_subtitle"></p>

                        <!-- Mobile Number Form -->
                        <div id="mobile-form">
                            <form id="send-otp-form" class="requires-validation" novalidate>
                                <div class="input-group mb-3">
                                    <input type="tel" id="mobile" value="" class="form-control"
                                        pattern="[0-9]{10,15}" placeholder="{{ __('frontend.enter_mobile') }}" required
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    <div class="invalid-feedback" id="mobile-error">Mobile number field is required.</div>
                                </div>
                                <div id="recaptcha-container" class="d-none"></div>
                                <div class="full-button text-center">
                                    <button type="button" id="send-otp-button" class="btn btn-primary w-100" onclick="sendCode()">
                                        <span id="send-button-text">
                                            <i class="fa-solid fa-paper-plane"></i> {{ __('frontend.send_otp') }}
                                        </span>
                                        <span id="send-button-spinner" class="d-none">
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            Loading...
                                        </span>
                                    </button>
                                </div>
                            </form>

                            <div class="text-center mt-5">
                                <a href="{{ route('auth.google') }}" class="d-block">
                                    <span id="google-login" class="btn btn-dark w-100">
                                        <svg class="me-1" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.4473 8.00005C3.4473 7.48042 3.5336 6.98224 3.68764 6.51496L0.991451 4.45605C0.465978 5.52296 0.169922 6.72515 0.169922 8.00005C0.169922 9.27387 0.465614 10.4753 0.990358 11.5415L3.68509 9.4786C3.53251 9.01351 3.4473 8.51715 3.4473 8.00005Z" fill="#FBBC05"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8.18202 3.27273C9.3109 3.27273 10.3305 3.67273 11.1317 4.32727L13.4622 2C12.042 0.763636 10.2213 0 8.18202 0C5.01608 0 2.29513 1.81055 0.992188 4.456L3.68838 6.51491C4.30962 4.62909 6.0805 3.27273 8.18202 3.27273Z" fill="#EB4335"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8.18202 12.7275C6.0805 12.7275 4.30962 11.3712 3.68838 9.48535L0.992188 11.5439C2.29513 14.1897 5.01608 16.0003 8.18202 16.0003C10.1361 16.0003 12.0016 15.3064 13.4018 14.0064L10.8425 12.0279C10.1204 12.4828 9.21112 12.7275 8.18202 12.7275Z" fill="#34A853"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M15.8289 7.99996C15.8289 7.52723 15.756 7.01814 15.6468 6.54541H8.18164V9.63632H12.4786C12.2638 10.6901 11.679 11.5003 10.8421 12.0276L13.4014 14.0061C14.8722 12.641 15.8289 10.6076 15.8289 7.99996Z" fill="#4285F4"/>
                                        </svg>
                                        {{ __('frontend.continue_with_google') }}
                                    </span>
                                </a>
                            </div>
                        </div>

                        <!-- OTP Verification Form -->
                        <div id="otp-form" style="display: none;">
                            <div id="verify-otp-form" class="requires-validation" novalidate>
                                <div class="input-group mb-3">
                                    <span class="input-group-text px-0"><i class="ph ph-lock-key"></i></span>
                                    <input type="text" name="otp" class="form-control" placeholder="{{ __('frontend.enter_otp') }}" id="otp" required>
                                    <div class="invalid-feedback" id="otp-error">OTP field is required.</div>
                                </div>
                                <div id="otp-timer" style="color: red; display: none;">You can resend the OTP in <span id="timer"></span> seconds.</div>
                                <div class="full-button text-center">
                                    <button type="button" id="verify-otp-button" class="btn btn-primary w-100" onclick="verifyCode()">
                                        <span id="button-text"><i class="fa-solid fa-floppy-disk"></i> {{ __('frontend.verify_otp') }}</span>
                                        <span id="button-spinner" class="d-none">
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            Loading...
                                        </span>
                                    </button>
                                    <div id="resend_code">
                                        <p class="mt-2 mb-0 fw-normal">{{ __('frontend.not_receive_otp') }}
                                            <a type="button" href="#" class="ms-1" id="resend-otp" onclick="resendCode()">{{ __('frontend.resend_otp') }}</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Registration Form -->
                        <div id="registerForm" style="display: none;">
                            <form action="{{ route('auth.otp-login-store') }}" method="post" class="requires-validation" novalidate>
                                @csrf
                                <div class="input-group mb-3">
                                    <span class="input-group-text px-0"><i class="ph ph-phone"></i></span>
                                    <input type="text" name="mobile" id="mobile_number" class="form-control" placeholder="{{ __('frontend.enter_mobile') }}" required readonly>
                                    <div class="invalid-feedback" id="mobile-error">Mobile number field is required.</div>
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text px-0"><i class="ph ph-user"></i></span>
                                    <input type="text" name="first_name" class="form-control" placeholder="{{ __('frontend.enter_fname') }}" required>
                                    <div class="invalid-feedback" id="first_name_error">First Name field is required</div>
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text px-0"><i class="ph ph-user"></i></span>
                                    <input type="text" name="last_name" class="form-control" placeholder="{{ __('frontend.enter_lname') }}" required>
                                    <div class="invalid-feedback" id="last_name_error">Last Name field is required</div>
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text px-0"><i class="ph ph-envelope"></i></span>
                                    <input type="text" name="email" class="form-control" placeholder="{{ __('frontend.enter_email') }}" required>
                                    <div class="invalid-feedback" id="email_error">Email field is required</div>
                                </div>

                                <div class="full-button text-center">
                                    <button type="submit" id="register-button" class="btn btn-primary w-100">{{ __('frontend.sign_up') }}</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>

<script>
    // Initialize country code input
    var input = document.querySelector("#mobile");
    var iti = window.intlTelInput(input, {
        initialCountry: "bd",
        separateDialCode: true,
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
    });

    let timerInterval;

    function startOtpTimer() {
        let timeLeft = 60;
        $('#otp-timer').show();
        $('#resend_code').addClass('d-none');
        $('#timer').text(timeLeft);

        timerInterval = setInterval(function() {
            timeLeft--;
            $('#timer').text(timeLeft);

            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                $('#otp-timer').hide();
                $('#resend_code').removeClass('d-none');
            }
        }, 1000);
    }

    // function sendCode() {
    //     let number = iti.getNumber().replace(/\D/g,'');

    //     $.post('{{ route("send.otp") }}', { mobile: number, _token: '{{ csrf_token() }}' })
    //     .done(function(res){
    //         $('#mobile-form').hide();
    //         $('#otp-form').show();
    //         startOtpTimer();
    //     })
    //     .fail(function(err){
    //         $('#otp_error_message').text(err.responseJSON.message);
    //     });
    // }

    // function verifyCode() {
    //     let number = iti.getNumber().replace(/\D/g,'');
    //     let otp = $('#otp').val();

    //     $.post('{{ route("verify.otp") }}', { mobile: number, otp: otp, _token: '{{ csrf_token() }}' })
    //     .done(function(res){
    //         if(res.action == 'login') window.location = res.url;
    //         if(res.action == 'register') {
    //             $('#otp-form').hide();
    //             $('#registerForm').show();
    //             $('#mobile_number').val(number);
    //         }
    //     })
    //     .fail(function(err){
    //         if(err.responseJSON && err.responseJSON.message){
    //             $('#otp-error').text(err.responseJSON.message).show(); // <-- এখানে UI তে দেখাচ্ছে
    //         } else {
    //             $('#otp-error').text('Something went wrong').show();
    //         }
    //     });
    // }
    function sendCode() {
        // $.post('{{ route("send.otp") }}', { _token: '{{ csrf_token() }}' })
        let number = iti.getNumber().replace(/\D/g,'');
        $.post('{{ route("send.otp") }}', { mobile: number, _token: '{{ csrf_token() }}' })
        .done(function(res){
            $('#mobile-form').hide();
            $('#otp-form').show();
            startOtpTimer();
        })
        .fail(function(err){
            $('#otp_error_message').text(err.responseJSON.message).show();
        });
    }

    function verifyCode() {
        let otp = $('#otp').val();

        $.post('{{ route("verify.otp") }}', { otp: otp, _token: '{{ csrf_token() }}' })
        .done(function(res){
            if(res.action == 'login') window.location = res.url;
            if(res.action == 'register') {
                $('#otp-form').hide();
                $('#registerForm').show();
            }
        })
        .fail(function(err){
            $('#otp-error').text(err.responseJSON.message).show();
        });
    }


    function resendCode() {
        sendCode();
    }

    setTimeout(function() {
        $('#otp_error_message').hide();
        $('#otp_success_message').hide();
    }, 2000);
    
</script>
@endsection
