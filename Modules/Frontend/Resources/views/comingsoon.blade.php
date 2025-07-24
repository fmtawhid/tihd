@extends('frontend::layouts.master')

@section('meta')
<!-- Basic Meta Tags -->
<title>Coming Soon - TI Channel | Voice of Islam</title>
<meta name="description" content="TI Channel - Voice of Islam is launching soon! Stay tuned for a world of inspiring Islamic programs, live TV, and educational content. Your destination for faith-based entertainment.">
<meta name="keywords" content="Coming Soon, TI Channel, Voice of Islam, Islamic OTT platform, Islamic programs, Islamic content, live TV, faith-based entertainment, Islamic streaming platform">
<meta name="author" content="TI Channel">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">

<!-- Canonical Tag -->
<link rel="canonical" href="{{ url()->current() }}">

<!-- Open Graph Meta Tags (for social media) -->
<meta property="og:title" content="Coming Soon - TI Channel | Voice of Islam">
<meta property="og:description" content="TI Channel - Voice of Islam is launching soon! Stay tuned for a world of inspiring Islamic programs, live TV, and educational content. Your destination for faith-based entertainment.">
<meta property="og:image" content="{{ asset('/images/icons/icon-512x512.png') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="TI Channel">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Coming Soon - TI Channel | Voice of Islam">
<meta name="twitter:description" content="TI Channel - Voice of Islam is launching soon! Stay tuned for a world of inspiring Islamic programs, live TV, and educational content. Your destination for faith-based entertainment.">
<meta name="twitter:image" content="{{ asset('/images/icons/icon-512x512.png') }}">
<meta name="twitter:site" content="@TI_Channel">
@endsection


@section('content')
<div class="list-page section-spacing-bottom px-0 pt-5">
    <div class="page-title" id="page_title">
        <h4 class="m-0 text-center">{{__('frontend.coming_soon')}}</h4>
    </div>
    <div id="comingsoon-card-list pt-5">
        <div class="container-fluid">
            <div class="row gy-5 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5" id="coming-soon">
            </div>
            <div class="card-style-slider shimmer-container mt-5">
                <div class="row gy-5 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
                    @for ($i = 0; $i < 5; $i++)
                    <div class="shimmer-container col mb-3">
                            @include('components.card_shimmer_commingSoon')
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/entertainment.min.js') }}" defer></script>
<script>
    const noDataImageSrc = '{{ asset('img/NoData.png') }}';
    const shimmerContainer = document.querySelector('.shimmer-container');
    const EntertainmentList = document.getElementById('coming-soon');
    const pageTitle = document.getElementById('page_title');
    let currentPage = 1;
    let isLoading = false;
    let hasMore = true;
    let actor_id= null;
    let movie_id=null;
    let type=null;
    let per_page=12;
    const baseUrl = document.querySelector('meta[name="baseUrl"]').getAttribute('content');
    const apiUrl = `${baseUrl}/api/coming-soon`;
    const csrf_token='{{ csrf_token() }}'


</script>


@endsection
