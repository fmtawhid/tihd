@extends('frontend::layouts.auth_layout')

@section('content')

    <div>
        <div class="vh-100" style="background: url('../web/web-img/authbg.png'); background-size: cover; background-repeat: no-repeat; position: relative;min-height:500px">
            <div class="container">
                <div class="row justify-content-center align-items-center height-self-center vh-100">
                    <div class="col-lg-5 col-md-12 align-self-center">
                        <div class="user-login-card card my-5">
                            <div class="text-center auth-heading">
                                <h5>{{__('frontend.forgot_password')}}</h5>
                                <p class="fs-14">{!!__('frontend.email_prompt')!!}</p>
                            </div>
                            <form action="post">
                                <div class="input-group">
                                    <span class="input-group-text px-0"><i class="ph ph-envelope"></i></span>
                                    <input type="email" class="form-control" placeholder="smithjohn@gmail.com" aria-label="lastname" aria-describedby="basic-addon1">
                                </div>
                                <div class="full-button text-center">
                                    <button type="submit" class="btn btn-primary w-100">
                                        {{__('frontend.continue')}}
                                    </button>
                                </div>
                                <div class="border p-4 rounded mt-5">
                                    <h6>{{__('frontend.link_sent_to_email')}}!</h6>
                                    <small class="mb-0">{{__('frontend.check_inbox')}}.</small>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
