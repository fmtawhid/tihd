@extends('frontend::layouts.master')

@section('meta')
<!-- Basic Meta Tags -->
<title>About Us - TI Channel | Voice of Islam</title>
<meta name="description" content="Learn more about TI Channel, the Voice of Islam. Discover our mission, values, and dedication to delivering inspiring Islamic programs and series that uplift faith and spirituality.">
<meta name="keywords" content="About TI Channel, Voice of Islam, Islamic OTT platform, Islamic programs, Islamic series, mission, values, Islamic content, faith-based platform, spirituality">
<meta name="author" content="TI Channel">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">

<!-- Canonical Tag -->
<link rel="canonical" href="{{ url()->current() }}">

<!-- Open Graph Meta Tags (for social media) -->
<meta property="og:title" content="About Us - TI Channel | Voice of Islam">
<meta property="og:description" content="Learn more about TI Channel, the Voice of Islam. Discover our mission, values, and dedication to delivering inspiring Islamic programs and series that uplift faith and spirituality.">
<meta property="og:image" content="{{ asset('/images/icons/icon-512x512.png') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="TI Channel">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="About Us - TI Channel | Voice of Islam">
<meta name="twitter:description" content="Learn more about TI Channel, the Voice of Islam. Discover our mission, values, and dedication to delivering inspiring Islamic programs and series that uplift faith and spirituality.">
<meta name="twitter:image" content="{{ asset('/images/icons/icon-512x512.png') }}">
<meta name="twitter:site" content="@TI_Channel">
@endsection


@section('content')
<div class="page-title">
        <h4 class="m-0 text-center">{{__('frontend.about_us')}}</h4>
</div>

<div class="section-spacing-bottom">
    <div class="container">
        @if(empty($content))
        <div class="text-center">
            <img src="{{ asset('img/NoData.png') }}" alt="No Data" class="img-fluid">
            <p>No data found</p>
        </div>
    @else
        <p>{!! $content !!}</p>
    @endif
    </div>
</div>
    <!-- <div class="col">
        @include('frontend::components.section.about_us',['about_us' => 'Discover Streamit Endless entertainment'])
    </div> -->

@endsection
