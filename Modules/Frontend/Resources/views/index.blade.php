@extends('frontend::layouts.master')


@section('meta')
    <!-- Basic Meta Tags -->
    <title>TI Channel - Voice of Islam</title>
    <meta name="description" content="Discover TI Channel, the Voice of Islam. Enjoy inspiring Islamic programs, enlightening series, and enriching content anytime, anywhere.">
    <meta name="keywords" content="Islamic OTT platform, Voice of Islam, TI Channel, Islamic programs, Islamic series, Islamic content, online Islamic platform, faith-based shows, Islamic TV platform">
    <meta name="author" content="TI Channel">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <!-- Canonical Tag -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph Meta Tags (for social media) -->
    <meta property="og:title" content="TI Channel - Voice of Islam">
    <meta property="og:description" content="Discover TI Channel, the Voice of Islam. Enjoy inspiring Islamic programs, enlightening series, and enriching content anytime, anywhere.">
    <meta property="og:image" content="{{ asset('/images/icons/icon-512x512.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="TI Channel">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="TI Channel - Voice of Islam">
    <meta name="twitter:description" content="Discover TI Channel, the Voice of Islam. Enjoy inspiring Islamic programs, enlightening series, and enriching content anytime, anywhere.">
    <meta name="twitter:image" content="{{ asset('/images/icons/icon-512x512.png') }}">
    <meta name="twitter:site" content="@TI_Channel">


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>


@endsection
@section('content')
<!-- ================== Preloader ================== -->
<!-- <div id="preloader">
    <div class="loader"></div>
</div> -->
<style>
    #preloader {position: fixed; top:0; left:0; right:0; bottom:0; background-color:#000; z-index:9999; display:flex; justify-content:center; align-items:center;}
    #preloader .loader {border:6px solid #f3f3f3; border-top:6px solid #ffcc00; border-radius:50%; width:60px; height:60px; animation:spin 1s linear infinite;}
    @keyframes spin {0%{transform:rotate(0deg);}100%{transform:rotate(360deg);}}
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.addEventListener('load', function() {
            document.getElementById('preloader').style.display = 'none';
        });
    });
</script>

<!-- ================== Banner Section ================== -->
@php $is_enable_banner = App\Models\MobileSetting::getValueBySlug('banner'); @endphp
@if($is_enable_banner == 1)
<div id="banner-section" class="section-spacing-bottom px-0">
    @include('frontend::components.section.banner', ['data' => $sliders ?? []])
</div>
@endif
<!-- ================== Popular Movies Section ================== -->
@if (isenablemodule('movie') == 1 && isset($mobileSettings['popular-movies']))
<div id="popular-movie-section" class="section-wraper container">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">{{ __('Popular Programs') }}</h4>
        <a href="{{ route('movies') }}" 
        class="" 
        title="{{ __('See All') }}"
        style="display: inline-flex; align-items: center; color: red;">
            <span style="font-size: 24px;">
                <i class="ph ph-caret-right ph-bold"></i>
            </span>
        </a>

    </div>

    <div class="swiper popularMoviesSwiper">
        <div class="swiper-wrapper custom_height custom_class_movie" style="height: 300px; margin:30px;">
            @foreach ($mobileSettings['popular-movies'] as $movie)
            <div class="swiper-slide">
                <div class="iq-card card-hover entainment-slick-card">
                    <div class="block-images position-relative w-100">
                        <a href="{{ route('movie-details', $movie->id) }}" class="position-absolute top-0 bottom-0 start-0 end-0"></a>
                        <div class="image-box w-100">
                            <img src="{{ $movie->poster_url ? url('storage/streamit-laravel/' . $movie->poster_url) : asset('images/default-poster.jpg') }}" alt="{{ $movie->name }}" class="img-fluid object-cover w-100 d-block border-0">
                            @if($movie->movie_access == 'paid')
                                @php
                                    $current_user_plan = auth()->user() ? auth()->user()->subscriptionPackage : null;
                                    $current_plan_level = $current_user_plan->level ?? 0;
                                @endphp
                                @if($movie->plan_level > $current_plan_level)
                                    <button type="button" class="product-premium border-0" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Premium">
                                        <i class="ph ph-crown-simple"></i>
                                    </button>
                                @endif
                            @endif
                        </div>
                        <div class="card-description with-transition">
                            <div class="position-relative w-100">
                                <ul class="genres-list ps-0 mb-2 d-flex align-items-center gap-5">
                                    @foreach(collect($movie->genres)->slice(0, 2) as $genre)
                                        <li class="small">{{ $genre['name'] ?? '--' }}</li>
                                    @endforeach
                                </ul>
                                <h5 class="iq-title text-capitalize line-count-1">{{ $movie->name ?? '--' }}</h5>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="movie-time d-flex align-items-center gap-1 small">
                                        <i class="ph ph-clock"></i> {{ $movie->duration ? formatDuration($movie->duration) : '--' }}
                                    </div>
                                    <div class="movie-language d-flex align-items-center gap-1">
                                        <i class="ph ph-translate"></i> <small>{{ $movie->language ?? '--' }}</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-3 mt-3">
                                    <x-watchlist-button :entertainment-id="$movie->id" :in-watchlist="$movie->is_watch_list" customClass="watch-list-btn" />
                                    <div class="flex-grow-1">
                                        <a href="{{ route('movie-details', $movie->id) }}" class="btn btn-primary w-100">{{ __('frontend.watch_now') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            new Swiper(".popularMoviesSwiper",{
                slidesPerView:6,
                spaceBetween:10,
                grabCursor:true,
                breakpoints:{
                    1200:{slidesPerView:7},
                    992:{slidesPerView:5},
                    768:{slidesPerView:4},
                    576:{slidesPerView:3},
                    0:{slidesPerView:3}
                },
            });
        });
    </script>
</div>
@endif


<!-- ================== Popular TV Shows Section ================== -->
@if (isenablemodule('tvshow') == 1)
<div id="popular-tvshow-section" class="section-wraper container">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">{{ __('Popular Series') }}</h4>
        <!-- <a href="{{ route('tv-shows') }}" class="btn btn-sm btn-outline-primary">{{ __('See All') }}</a> -->
<a href="{{ route('tv-shows') }}" 
   class="" 
   title="{{ __('See All') }}"
   style="display: inline-flex; align-items: center; color: red;">
    <span style="font-size: 24px;">
        <i class="ph ph-caret-right ph-bold"></i>
    </span>
</a>
    </div>
    <div class="swiper popularTVShowsSwiper">
        <div class="swiper-wrapper custom_height custom_class_series" style="height: 300px; margin:30px;">
            @foreach ($mobileSettings['popular-tvshows'] as $movie)
            <div class="swiper-slide">
                <div class="iq-card card-hover entainment-slick-card">
                    <div class="block-images position-relative w-100">
                        <a href="{{ route('tvshow-details', ['id' => $movie->id]) }}" class="position-absolute top-0 bottom-0 start-0 end-0"></a>
                        <div class="image-box w-100">
                            <img src="{{ $movie->poster_url ? url('storage/streamit-laravel/'.$movie->poster_url) : asset('images/default-poster.jpg') }}" alt="{{ $movie->name }}" class="img-fluid object-cover w-100 d-block border-0">
                            @if($movie->movie_access=='paid')
                                @php
                                    $current_user_plan = auth()->user() ? auth()->user()->subscriptionPackage : null;
                                    $current_plan_level = $current_user_plan->level ?? 0;
                                @endphp
                                @if($movie->plan_level > $current_plan_level)
                                    <button type="button" class="product-premium border-0" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Premium"><i class="ph ph-crown-simple"></i></button>
                                @endif
                            @endif
                        </div>
                        <div class="card-description with-transition">
                            <div class="position-relative w-100">
                                <ul class="genres-list ps-0 mb-2 d-flex align-items-center gap-5">
                                    @foreach(collect($movie->genres)->slice(0,2) as $genre)
                                        <li class="small">{{ $genre['name'] ?? '--' }}</li>
                                    @endforeach
                                </ul>
                                <h5 class="iq-title text-capitalize line-count-1">{{ $movie->name ?? '--' }}</h5>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="movie-time d-flex align-items-center gap-1 small"><i class="ph ph-clock"></i> {{ $movie->duration ? formatDuration($movie->duration) : '--' }}</div>
                                    <div class="movie-language d-flex align-items-center gap-1"><i class="ph ph-translate"></i> <small>{{ $movie->language ?? '--' }}</small></div>
                                </div>
                                <div class="d-flex align-items-center gap-3 mt-3">
                                    <x-watchlist-button :entertainment-id="$movie->id" :in-watchlist="$movie->is_watch_list" customClass="watch-list-btn" />
                                    <div class="flex-grow-1">
                                        <a href="{{ route('tvshow-details', ['id' => $movie->id]) }}" class="btn btn-primary w-100">{{ __('frontend.watch_now') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            new Swiper(".popularTVShowsSwiper",{
                slidesPerView:6, spaceBetween:10, grabCursor:true,
                breakpoints:{1200:{slidesPerView:7}, 992:{slidesPerView:5}, 768:{slidesPerView:4}, 576:{slidesPerView:3}, 0:{slidesPerView:3}},
            });
        });
    </script>
</div>
@endif
<!-- ================== Popular Personalities Section ================== -->
@if(isset($mobileSettings['your-favorite-personality']) && $mobileSettings['your-favorite-personality']->count())
<div id="popular-personalities-section" class="section-wraper container">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">{{ __('Popular Personalities') }}</h4>
        <!-- <a href="{{ route('castcrewList') }}" class="btn btn-sm btn-outline-primary">{{ __('See All') }}</a> -->
        <a href="{{ route('castcrewList') }}" 
            class="" 
            title="{{ __('See All') }}"
            style="display: inline-flex; align-items: center; color: red;">
                <span style="font-size: 24px;">
                    <i class="ph ph-caret-right ph-bold"></i>
                </span>
        </a>
    </div>
    <div class="swiper popularPersonalitiesSwiper">
        <div class="swiper-wrapper custom_height custom_class_personalities" style="height:170px; margin:30px;">
            @foreach($mobileSettings['your-favorite-personality'] as $personality)
            <div class="swiper-slide">
                <div class="iq-card entainment-slick-card">
                    <div class="block-images position-relative w-100 text-center">
                        <a href="{{ route('castcrew-detail', $personality->id) }}" class="position-absolute top-0 bottom-0 start-0 end-0"></a>
                        <div class="image-box w-100">
                            <img src="{{ $personality->file_url ? url('storage/streamit-laravel/'.$personality->file_url) : asset('images/default-avatar.jpg') }}" 
                                 alt="{{ $personality->name }}" class="img-fluid object-cover mx-auto border-0" style="width:170px;height:170px;object-fit:cover;">
                        </div>
                        <div class="card-description mt-3">
                            <h6 class="iq-title text-capitalize mb-1">{{ $personality->name ?? '--' }}</h6>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            new Swiper(".popularPersonalitiesSwiper",{
                slidesPerView:6, spaceBetween:10, grabCursor:true,
                breakpoints:{1200:{slidesPerView:8},992:{slidesPerView:6},768:{slidesPerView:5},576:{slidesPerView:4},0:{slidesPerView:3}}
            });
        });
    </script>
</div>
@endif


<!-- ================== Genres Section ================== -->
@if(isset($mobileSettings['genre']) && $mobileSettings['genre']->count())
<div id="genres-section" class="section-wraper container">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">{{ __('Popular Genres') }}</h4>
        <a href="{{ route('movies.genre', ['genre_id' => $mobileSettings['genre']->first()->id ?? 0]) }}" class="btn btn-sm btn-outline-primary">{{ __('See All') }}</a>
    <a href="{{ route('movies.genre', ['genre_id' => $mobileSettings['genre']->first()->id ?? 0]) }}" 
   class="" 
   title="{{ __('See All') }}"
   style="display: inline-flex; align-items: center; color: red;">
    <span style="font-size: 24px;">
        <i class="ph ph-caret-right ph-bold"></i>
    </span>
</a>
    </div>
    <div class="swiper genresSwiper">
        <div class="swiper-wrapper custom_height custom_class_genres" style="height:170px; margin:30px;">
            @foreach($mobileSettings['genre'] as $genre)
            <div class="swiper-slide">
                <div class="iq-card entainment-slick-card">
                    <div class="block-images position-relative w-100 text-center">
                        <a href="{{ route('movies.genre', $genre->id) }}" class="position-absolute top-0 bottom-0 start-0 end-0"></a>
                        <div class="image-box w-100">
                            <img src="{{ $genre->file_url ? url('storage/streamit-laravel/'.$genre->file_url) : asset('images/default-avatar.jpg') }}" alt="{{ $genre->name }}" class="img-fluid object-cover mx-auto border-0" style="width:170px;height:170px;object-fit:cover;">
                        </div>
                        <div class="card-description mt-3">
                            <h6 class="iq-title text-capitalize mb-1">{{ $genre->name ?? '--' }}</h6>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            new Swiper(".genresSwiper",{
                slidesPerView:6, spaceBetween:10, grabCursor:true,
                breakpoints:{1200:{slidesPerView:8},992:{slidesPerView:6},768:{slidesPerView:5},576:{slidesPerView:4},0:{slidesPerView:3}}
            });
        });
    </script>
</div>
@endif

<h1 class="h4 text-center">Ti Channel - Tihd TV</h1>


<style>
    /* ================== Global Swiper & Section Styles ================== */
.section-wraper {
    /* margin-bottom: 40px; */
}

.swiper-wrapper {
    display: flex;
    align-items: stretch;
}

/* ================== Card Images ================== */
.iq-card .image-box img {
    width: 100%;
    height: auto;
    object-fit: cover;
    border-radius: 8px;
}

/* ================== Popular Movies & TV Shows ================== */
@media screen and (max-width: 1200px) {
    .popularMoviesSwiper .swiper-slide,
    .popularTVShowsSwiper .swiper-slide {
        flex: 0 0 auto;
    }
}

@media screen and (max-width: 992px) {
    .popularMoviesSwiper .swiper-slide,
    .popularTVShowsSwiper .swiper-slide {
        width: 180px;
    }
}

@media screen and (max-width: 768px) {
    .popularMoviesSwiper .swiper-slide,
    .popularTVShowsSwiper .swiper-slide {
        width: 140px;
    }
    .popularMoviesSwiper .swiper-wrapper,
    .popularTVShowsSwiper .swiper-wrapper {
        margin: 15px 0;
    }
}

@media screen and (max-width: 576px) {
    .popularMoviesSwiper .swiper-slide,
    .popularTVShowsSwiper .swiper-slide {
        width: 120px;
    }
    .popularMoviesSwiper h4,
    .popularTVShowsSwiper h4 {
        font-size: 16px;
    }
    .custom_height {
        height: 30vh !important;
    }
}
@media screen and (max-width: 480px) {
    .custom_height {
        height: 19vh !important;
        margin: 0px !important;
    }
    
    .section-wraper {
        margin-bottom: 12px !important;
    }
    .custom_class_personalities {
        height: 19vh !important;
        margin: 0px !important;
    }

    .custom_class_genres {
        height: 19vh !important;
        margin: 0px !important;
    }
    .iq-card .block-images .card-description{
        padding: 0px !important;
    }
    .card-description .iq-title {
        font-size: 10px !important;
    }
    .section-spacing-bottom{
        padding: 0px !important;
    }
    .section-wraper h4{
        font-size: 18px !important;
    }
}
/* ================== Personalities & Genres ================== */
@media screen and (max-width: 768px) {
    .popularPersonalitiesSwiper .swiper-slide img,
    .genresSwiper .swiper-slide img {
        width: 120px !important;
        height: 120px !important;
    }
    .popularPersonalitiesSwiper h6,
    .genresSwiper h6 {
        font-size: 14px;
    }
}

@media screen and (max-width: 576px) {
    .popularPersonalitiesSwiper .swiper-slide img,
    .genresSwiper .swiper-slide img {
        width: 100px !important;
        height: 115px !important;
    }
    .popularPersonalitiesSwiper h6,
    .genresSwiper h6 {
        font-size: 12px;
    }
}

/* ================== Button & Spacing Adjustments ================== */
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.section-wraper h4 {
    font-size: 26px;
    margin-bottom: 3px;
}

.card-description .iq-title {
    font-size: 14px;
}

.card-description .movie-time,
.card-description .movie-language {
    font-size: 12px;
}

</style>
@endsection