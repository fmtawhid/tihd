@extends('frontend::layouts.master')
@section('meta')
<!-- Basic Meta Tags -->
<title>FAQ - TI Channel | Voice of Islam</title>
<meta name="description" content="Find answers to common questions about TI Channel, including account setup, subscription plans, device support, downloading content, parental controls, and more. Get all the information you need to enjoy our Islamic programs seamlessly.">
<meta name="keywords" content="TI Channel FAQ, Voice of Islam, Islamic streaming, subscription plans, account management, watchlist, download content, parental controls, support, Islamic OTT platform, online Islamic content">
<meta name="author" content="TI Channel">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">

<!-- Canonical Tag -->
<link rel="canonical" href="{{ url()->current() }}">

<!-- Open Graph Meta Tags (for social media) -->
<meta property="og:title" content="FAQ - TI Channel | Voice of Islam">
<meta property="og:description" content="Find answers to common questions about TI Channel, including account setup, subscription plans, device support, downloading content, parental controls, and more. Get all the information you need to enjoy our Islamic programs seamlessly.">
<meta property="og:image" content="{{ asset('/images/icons/icon-512x512.png') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="TI Channel">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="FAQ - TI Channel | Voice of Islam">
<meta name="twitter:description" content="Find answers to common questions about TI Channel, including account setup, subscription plans, device support, downloading content, parental controls, and more. Get all the information you need to enjoy our Islamic programs seamlessly.">
<meta name="twitter:image" content="{{ asset('/images/icons/icon-512x512.png') }}">
<meta name="twitter:site" content="@TI_Channel">
@endsection

@section('content')
<div class="faq-block section-spacing-bottom">
    <div class="page-title">
        <h4 class="m-0 text-center">{{__('frontend.faq')}}</h4>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-1 d-lg-block d-none"></div>
            <div class="col-lg-10">
                @if($content->isEmpty())
                    <div class="text-center">
                        <img src="{{ asset('img/NoData.png') }}" alt="No Data" class="img-fluid">
                        <p>No data found</p>
                    </div>
                @else
                    <div class="accordion" id="faq">
                        @foreach($content as $key => $value)
                            <div class="accordion-item custom-accordion rounded">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button custom-accordion-button gap-3 p-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne_{{ $value->id }}" aria-expanded="true" aria-controls="collapseOne">
                                        {{ $value->question }}
                                    </button>
                                </h2>
                                <div id="collapseOne_{{ $value->id }}" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faq">
                                    <div class="accordion-body custom-accordion-body p-0">
                                        <span> {{ $value->answer }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="col-lg-1 d-lg-block d-none"></div>
        </div>
    </div>
</div>
@endsection
