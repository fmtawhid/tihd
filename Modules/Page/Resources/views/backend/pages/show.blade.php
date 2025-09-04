@extends('frontend::layouts.master')
@section('meta')
<!-- Basic Meta Tags -->
<title>{{$page->name}} - TI Channel | Voice of Islam</title>
<meta name="description" content="Read TI Channel's {{$page->name}} to understand how we collect, use, and protect your personal information while you enjoy our Islamic content and services.">
<meta name="keywords" content="{{$page->name}}, TI Channel, Voice of Islam, data protection, personal information, online privacy, Islamic OTT platform, user privacy, Islamic TV platform">
<meta name="author" content="TI Channel">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">

<!-- Canonical Tag -->
<link rel="canonical" href="{{ url()->current() }}">

<!-- Open Graph Meta Tags (for social media) -->
<meta property="og:title" content="{{$page->name}} - TI Channel | Voice of Islam">
<meta property="og:description" content="Read TI Channel's {{$page->name}} to understand how we collect, use, and protect your personal information while you enjoy our Islamic content and services.">
<meta property="og:image" content="{{ asset('/images/icons/icon-512x512.png') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="TI Channel">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{$page->name}} - TI Channel | Voice of Islam">
<meta name="twitter:description" content="Read TI Channel's {{$page->name}} to understand how we collect, use, and protect your personal information while you enjoy our Islamic content and services.">
<meta name="twitter:image" content="{{ asset('/images/icons/icon-512x512.png') }}">
<meta name="twitter:site" content="@TI_Channel">
@endsection

@section('content')
<div class="page-title">
        <h4 class="m-0 text-center">{{$page->name}}</h4>
</div>

<div class="section-spacing-bottom">

    <div class="container">
        @if(empty($page->description))
        <div class="text-center">
            <img src="{{ asset('img/NoData.png') }}" alt="No Data" class="img-fluid">
            <p>No data found</p>
        </div>
    @else
        <p>{!! $page->description !!}</p>
    @endif
    </div>
</div>

@endsection
