@extends('frontend::layouts.master')

@section('meta')
<!-- Basic Meta Tags -->
<title>Islamic Series - TI Channel | Voice of Islam</title>
<meta name="description" content="Explore a rich collection of Islamic series on TI Channel, the Voice of Islam. Enjoy inspiring and educational content that nurtures faith, spirituality, and knowledge. Watch now to deepen your connection with Islam.">
<meta name="keywords" content="Islamic series, TI Channel, Voice of Islam, faith-based series, Islamic education, spirituality, inspiring content, Islamic programs, online Islamic streaming, Muslim series">
<meta name="author" content="TI Channel">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">

<!-- Canonical Tag -->
<link rel="canonical" href="{{ url()->current() }}">

<!-- Open Graph Meta Tags (for social media) -->
<meta property="og:title" content="Islamic Series - TI Channel | Voice of Islam">
<meta property="og:description" content="Explore a rich collection of Islamic series on TI Channel. Enjoy inspiring and educational content that nurtures faith, spirituality, and knowledge.">
<meta property="og:image" content="{{ asset('/images/icons/icon-512x512.png') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="TI Channel">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Islamic Series - TI Channel | Voice of Islam">
<meta name="twitter:description" content="Explore a rich collection of Islamic series on TI Channel. Enjoy inspiring and educational content that nurtures faith, spirituality, and knowledge.">
<meta name="twitter:image" content="{{ asset('/images/icons/icon-512x512.png') }}">
<meta name="twitter:site" content="@TI_Channel">
@endsection



@section('content')

<div class="list-page">
    <div class="page-title" id="page_title">
        <h4 class="m-0 text-center">{{__('frontend.tvshows')}}</h4>
    </div>
    <div class="movie-lists section-spacing-bottom">
        <div class="container-fluid">
            <div class="row gy-4 row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6" id="entertainment-list">

            </div>
            <div class="card-style-slider shimmer-container">
                <div class="row gy-4 row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 mt-3">
                        @for ($i = 0; $i < 12; $i++)
                        <div class="shimmer-container col mb-3">
                            @include('components.card_shimmer_movieList')
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
    const EntertainmentList = document.getElementById('entertainment-list');
    const pageTitle = document.getElementById('page_title');
    let currentPage = 1;
    let isLoading = false;
    let hasMore = true;
    let movie_id= null;
    let actor_id= null;
    let type=null;
    let per_page=12;

    const baseUrl = document.querySelector('meta[name="baseUrl"]').getAttribute('content');

    const apiUrl = `${baseUrl}/api/tvshow-list`;
    const csrf_token='{{ csrf_token() }}'
</script>
@endsection
