@extends('frontend::layouts.master')

@section('meta')
<!-- Basic Meta Tags -->
<title>Live TV - TI Channel | Voice of Islam</title>
<meta name="description" content="Watch Live TV on TI Channel, your Islamic OTT platform. Enjoy 24/7 streaming of inspiring Islamic content, live events, and educational programs. Stay connected with your faith in real time.">
<meta name="keywords" content="Live TV, TI Channel, Islamic Live TV, Voice of Islam, 24/7 Islamic streaming, Islamic events, faith-based live programs, Islamic spirituality, online Islamic TV, live Islamic programs">
<meta name="author" content="TI Channel">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">

<!-- Canonical Tag -->
<link rel="canonical" href="{{ url()->current() }}">

<!-- Open Graph Meta Tags (for social media) -->
<meta property="og:title" content="Live TV - TI Channel | Voice of Islam">
<meta property="og:description" content="Watch Live TV on TI Channel, your Islamic OTT platform. Enjoy 24/7 streaming of inspiring Islamic content, live events, and educational programs. Stay connected with your faith in real time.">
<meta property="og:image" content="{{ asset('/images/icons/icon-512x512.png') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="TI Channel">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Live TV - TI Channel | Voice of Islam">
<meta name="twitter:description" content="Watch Live TV on TI Channel, your Islamic OTT platform. Enjoy 24/7 streaming of inspiring Islamic content, live events, and educational programs. Stay connected with your faith in real time.">
<meta name="twitter:image" content="{{ asset('/images/icons/icon-512x512.png') }}">
<meta name="twitter:site" content="@TI_Channel">
@endsection



@section('content')

<div class="list-page">
    <div class="page-title">
        <h4 class="m-0 text-center">{{__('frontend.tv_channels')}}</h4>
    </div>

    <div class="section-spacing-bottom">
        <div class="container-fluid">
            <div class="row mt-3 gy-4 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4">
                @foreach($data as $tvchannels)
                    <div class="col">
                        <a href="{{ route('livetv-details', ['id' => $tvchannels['id']]) }}" class="livetv-card d-block position-relative">
                            <img src="{{ $tvchannels['poster_image'] }}" alt="{{ $tvchannels['name'] }}" class="livetv-img object-cover img-fluid w-100 rounded">
                            <span class="live-card-badge">
                                <span class="live-badge fw-semibold text-uppercase">{{__('frontend.live')}}</span>
                            </span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
   
</div>
 
@endsection
