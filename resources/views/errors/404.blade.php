@extends('frontend::layouts.auth_layout')
@section('meta')
<title>404 Page Not Found - TI Channel</title>
<meta name="description" content="The page you are looking for does not exist. Please check the URL or return to the homepage of TI Channel.">
<meta name="keywords" content="404, not found, page error, invalid URL, TI Channel, Voice of Islam">
<meta name="author" content="TI Channel">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">

<!-- Robots Meta Tag to prevent indexing by search engines -->

<meta name="robots" content="noindex, nofollow">

<!-- Canonical Tag (optional for 404, but good practice) -->

<link rel="canonical" href="{{ url()->current() }}">

<!-- Open Graph Meta Tags (for social media) -->

<meta property="og:title" content="404 Page Not Found - TI Channel">
<meta property="og:description" content="The page you are looking for does not exist. Please check the URL or return to the homepage of TI Channel.">
<meta property="og:image" content="{{ asset('default-image/404.png') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="TI Channel">

<!-- Twitter Card Meta Tags -->

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="404 Page Not Found - TI Channel">
<meta name="twitter:description" content="The page you are looking for does not exist. Please check the URL or return to the homepage of TI Channel.">
<meta name="twitter:image" content="{{ asset('default-image/404.png') }}">
<meta name="twitter:site" content="@TI_Channel">
@endsection



@section('content')
<div class="error-page" style="background-image: url('{{ asset('default-image/404-background.jpg') }}')">
<div class="container error-page-container">
    <div class="row no-gutters height-self-center flex-column justify-content-center error-page-row">
       <div class="col-sm-12 text-center align-self-center">
          <div class="iq-error position-relative my-5">
                <img src="{{ asset('default-image/404.png') }}" class="img-fluid iq-error-img iq-error-img-dark mx-auto" alt="">
                <h2 class="mb-0 mt-4">Oops! This Page is Not Found.</h2>
                <p>The requested page does not exist.</p>
                <a class="btn btn-primary d-inline-flex align-items-center mt-3" href="{{route('user.login')}}"><i class="ri-home-4-line"></i>Back to Home</a>
          </div>
       </div>
    </div>
</div>
</div>

@endsection
