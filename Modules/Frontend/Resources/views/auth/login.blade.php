@extends('frontend::layouts.auth_layout')

@section('content')

<div id="login" >

    <div class="vh-100" style="background: url('../img/web-img/authbg.png'); background-size: cover; background-repeat: no-repeat; position: relative;min-height:500px">
        <div class="container">
            <div class="row justify-content-center align-items-center height-self-center vh-100">
                <div class="col-lg-5 col-md-8 col-11 align-self-center">
                    <div class="user-login-card card my-5">
                        <div class="text-center auth-heading">
                            <h5>{{ __('frontend.sign_in_title') }}</h5>
                            <p class="fs-14">{{ __('frontend.sign_in_sub_title') }}</p>
                            @if(session()->has('error'))
                                <span class="text-danger">{{session()->get('error')}}</span>
                            @endif
                        </div>
                        <p class="text-danger" id="login_error_message"></p>
                        <form action="post" id="login-form" class="requires-validation" data-toggle="validator" novalidate>
                            <div class="input-group">
                                <span class="input-group-text px-0"><i class="ph ph-envelope"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="{{__('frontend.enter_email')}}"  aria-describedby="basic-addon1" required>
                                <div class="invalid-feedback" id="name-error">Email field is required.</div>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text px-0"><i class="ph ph-lock-key"></i></span>
                                <input type="password" name="password" class="form-control" id="password" placeholder="{{__('messages.enter_password')}}" aria-describedby="basic-addon1" required>
                                <span class="input-group-text px-0" id="togglePassword"> <i class="ph ph-eye"></i></span>
                                <div class="invalid-feedback" id="password-error">Password field is required.</div>
                            </div>
                            <div class="d-flex flex-wrap align-items-center justify-content-between">
                                <label class="list-group-item d-flex align-items-center"><input class="form-check-input m-0 me-2" type="checkbox">{{__('frontend.remember_me')}}</label>
                                <a href="/forget-password" >{{__('frontend.forgot_password')}}</a>
                            </div>
                            <div class="full-button text-center">
                                <button type="submit"  id="login-button" class="btn btn-primary w-100">
                                    {{__('frontend.sign_in')}}
                                </button>
                                <p class="mt-2 mb-0 fw-normal">{{__('frontend.not_have_account')}}<a href="{{route('register-page')}}" class="ms-1">{{__('frontend.sign_up')}}</a></p>
                            </div>

                            <div class="border-style">
                                <span>Or</span>
                            </div>

                            <div class="full-button text-center">

                                <a href="{{route('auth.google')}}" class="d-block">
                                    <span  id="google-login" class="btn btn-dark w-100">
                                    <svg class="me-2" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M3.4473 8.00005C3.4473 7.48042 3.5336 6.98224 3.68764 6.51496L0.991451 4.45605C0.465978 5.52296 0.169922 6.72515 0.169922 8.00005C0.169922 9.27387 0.465614 10.4753 0.990358 11.5415L3.68509 9.4786C3.53251 9.01351 3.4473 8.51715 3.4473 8.00005Z" fill="#FBBC05"/>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8.18202 3.27273C9.3109 3.27273 10.3305 3.67273 11.1317 4.32727L13.4622 2C12.042 0.763636 10.2213 0 8.18202 0C5.01608 0 2.29513 1.81055 0.992188 4.456L3.68838 6.51491C4.30962 4.62909 6.0805 3.27273 8.18202 3.27273Z" fill="#EB4335"/>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8.18202 12.7275C6.0805 12.7275 4.30962 11.3712 3.68838 9.48535L0.992188 11.5439C2.29513 14.1897 5.01608 16.0003 8.18202 16.0003C10.1361 16.0003 12.0016 15.3064 13.4018 14.0064L10.8425 12.0279C10.1204 12.4828 9.21112 12.7275 8.18202 12.7275Z" fill="#34A853"/>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.8289 7.99996C15.8289 7.52723 15.756 7.01814 15.6468 6.54541H8.18164V9.63632H12.4786C12.2638 10.6901 11.679 11.5003 10.8421 12.0276L13.4014 14.0061C14.8722 12.641 15.8289 10.6076 15.8289 7.99996Z" fill="#4285F4"/>
                                    </svg>
                                    {{__('frontend.google_login')}}
                                    </span>
                                </a>

                                <a href="{{route('login')}}" class="d-block mt-3">
                                    <span  id="otp-login" class="btn btn-dark w-100">
                                    {{__('frontend.login_with_otp')}}
                                    </span>
                                </a>

                                <a href="{{route('auth.apple')}}" class="d-block mt-3">
                                    <span  id="apple-login" class="btn btn-dark w-100">
                                        <svg class="me-2" width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.28668 2.70328C9.58072 2.36438 9.80491 1.97075 9.94639 1.54496C10.0879 1.11918 10.1439 0.669645 10.1111 0.222168C9.20841 0.295036 8.37046 0.7196 7.77779 1.40439C7.49398 1.73259 7.27891 2.11441 7.14531 2.52722C7.01171 2.94004 6.9623 3.37547 7.00001 3.80772C7.44035 3.81139 7.87562 3.71369 8.27214 3.52217C8.66866 3.33065 9.0158 3.05046 9.28668 2.70328ZM11.2467 8.48995C11.2519 7.89396 11.4089 7.30914 11.7028 6.79066C11.9968 6.27218 12.418 5.83715 12.9267 5.52661C12.6056 5.06402 12.1812 4.68259 11.6871 4.41258C11.193 4.14257 10.6427 3.9914 10.08 3.97106C8.86668 3.84661 7.74668 4.67883 7.10112 4.67883C6.45557 4.67883 5.54557 3.98661 4.53446 4.00217C3.87345 4.02394 3.22936 4.21666 2.66503 4.56154C2.10069 4.90641 1.63537 5.39165 1.31446 5.96995C-0.0544317 8.34995 0.964457 11.8888 2.33335 13.8099C2.95557 14.7511 3.73335 15.8166 4.76001 15.7777C5.78668 15.7388 6.12112 15.1399 7.31112 15.1399C8.50112 15.1399 8.86668 15.7777 9.87779 15.7544C10.8889 15.7311 11.6045 14.7899 12.2578 13.8488C12.7206 13.1656 13.0821 12.419 13.3311 11.6322C12.7147 11.3693 12.1888 10.9316 11.8184 10.3732C11.4479 9.81476 11.2492 9.16006 11.2467 8.48995Z" fill="white"/>
                                        </svg>
                                       {{__('frontend.apple_login')}}
                                    </span>
                                </a>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/auth.min.js') }}" defer></script>


@endsection




