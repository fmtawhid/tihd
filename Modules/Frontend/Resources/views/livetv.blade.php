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

    <div id="livetvthumbnail-section">
        @include('frontend::components.section.livetvthumbnail',  ['livetvthumbnail' => $responseData['slider']])
    </div>

<div id="comingsoon-card-list">
        <div class="container-fluid">
            <div class="row gy-5 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5" id="coming-soon">
            </div>
            <div class="card-style-slider shimmer-container">
                <div class="row gy-5 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
                    @for ($i = 0; $i < 5; $i++)
                    <div class="shimmer-container col mb-3">
                         
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>

		<div style="display: flex; justify-content: center;">
          <img src="https://tihd.tv/img/NoData.png" />
		</div>

    <div class="container-fluid padding-right-0">
        <div class="overflow-hidden">
            <div id="more-infinity-section">
             
                @include('frontend::components.section.livetv_category',  ['moreinfinity' => $responseData['category_data']])
            </div>
        </div>
    </div>
   

@endsection
