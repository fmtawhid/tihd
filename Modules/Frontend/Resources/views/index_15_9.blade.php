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



    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>


@endsection


@section('content')

<style>
    /* Preloader CSS */
#preloader {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #000; /* Background color */
    z-index: 9999;
    display: flex;
    justify-content: center;
    align-items: center;
}

#preloader .loader {
    border: 6px solid #f3f3f3;
    border-top: 6px solid #ffcc00; /* Loader color */
    border-radius: 50%;
    width: 60px;
    height: 60px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}


/* Slider */
.popularMoviesSwiper .swiper-pagination {
    display: none !important;
}
.popularMoviesSwiper .swiper-button-next,
.popularMoviesSwiper .swiper-button-prev {
    opacity: 0;
    transition: opacity 0.3s ease;
}
.swiper-button-next .swiper-button-prev{
    display: none !important;
}
.popularMoviesSwiper:hover .swiper-button-next,
.popularMoviesSwiper:hover .swiper-button-prev {
    opacity: 1;
}

/* Arrow position কার্ডের বাইরে করতে */
.popularMoviesSwiper .swiper-button-next {
    right: -25px; /* প্রয়োজনে মান adjust করুন */
}

.popularMoviesSwiper .swiper-button-prev {
    left: -25px; /* প্রয়োজনে মান adjust করুন */
}


</style>
<!-- Preloader -->
<div id="preloader">
    <div class="loader"></div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var preloader = document.getElementById('preloader');
        if (preloader) {
            window.addEventListener('load', function() {
                preloader.style.display = 'none';
            });
        }
    });
</script>


    <!-- Main Banner -->
    @php
        $is_enable_banner = App\Models\MobileSetting::getValueBySlug('banner');
    @endphp






    <div id="banner-section" class="section-spacing-bottom px-0">
        @if ($is_enable_banner == 1)
            @include('frontend::components.section.banner', ['data' => $sliders ?? []])
        @endif
    </div>




    <div class="container-fluid padding-right-15">
        <div class="overflow-hidden">

            @php
                $is_enable_continue_watching = App\Models\MobileSetting::getValueBySlug('continue-watching');
            @endphp

            @if ($user_id != null && $is_enable_continue_watching == 1)
                <div id="continue-watch-section" class="section-wraper scroll-section section-hidden">

                    <div class="card-style-slider movie-shimmer">
                        <div class="row gy-4 row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 mt-3">
                            @for ($i = 0; $i < 6; $i++)
                                <div class="shimmer-container col mb-3">
                                    <div class="continue-watch-card shimmer border rounded-3 placeholder-glow">
                                        <div class="placeholder continue-watch-card-image position-relative">
                                            <div class="placeholder placeholder-glow">
                                                <a href="#" class="d-block image-link">
                                                    <div class="placeholder w-100 continue-watch-image"
                                                        style="height: 200px;"></div>
                                                </a>
                                                <div class="progress" role="progressbar" aria-label="Example 2px high"
                                                    aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                    <div class="placeholder placeholder-glow"
                                                        style="height: 8px; width: 50%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="continue-watch-card-content">
                                            <div class="placeholder placeholder-glow title-wrapper">
                                                <h5 class="mb-1 font-size-18 title line-count-1 placeholder"
                                                    style="height: 20px; width: 80%;"></h5>
                                            </div>
                                            <p class="font-size-14 fw-semibold placeholder"
                                                style="height: 14px; width: 60%;"></p>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                </div>
            @endif




            @if (isenablemodule('movie') == 1)
                <div id="top-10-moive-section" class="section-wraper scroll-section section-hidden">
                    <div class="card-style-slider movie-shimmer">
                        <div class="row gy-4 row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 mt-3">
                            @for ($i = 0; $i < 6; $i++)
                                <div class="shimmer-container col mb-3">
                                    @include('components.card_shimmer_movieList')
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>


                <div id="latest-moive-section" class="section-wraper scroll-section section-hidden">
                    <div class="card-style-slider movie-shimmer">
                        <div class="row gy-4 row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 mt-3">
                            @for ($i = 0; $i < 6; $i++)
                                <div class="shimmer-container col mb-3">
                                    @include('components.card_shimmer_movieList')
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            @endif

            <div id="language-section" class="section-wraper scroll-section section-hidden">
                <div class="card-style-slider movie-shimmer">
                    <div class="row gy-4 row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 mt-3">
                        @for ($i = 0; $i < 6; $i++)
                            <div class="shimmer-container col mb-3">
                                @include('components.card_shimmer_languageList')
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Popular Movies -->
             
            <!-- @if (isenablemodule('movie') == 1)
            
                <div id="popular-moive-section" class="section-wraper scroll-section section-hidden">
                    <div class="card-style-slider movie-shimmer">
                        <div class="row gy-4 row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 mt-3">
                            @for ($i = 0; $i < 6; $i++)
                                <div class="shimmer-container col mb-3">
                                    @include('components.card_shimmer_movieList')
                                    
                                    
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            @endif -->
                    @if (isenablemodule('movie') == 1 && isset($mobileSettings['popular-movies']))
                        <div id="popular-movie-section" class="section-wraper">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h4 class="mb-0">{{ __('Popular Movies') }}</h4>

                                <a href="{{ route('movies') }}" 
                                class="btn btn-sm btn-outline-primary">
                                    {{ __('See All') }}
                                </a>
                            </div>

                            <div class="swiper popularMoviesSwiper">
                                <div class="swiper-wrapper" style="height: 300px; margin: 30px;">
                                    @foreach ($mobileSettings['popular-movies'] as $movie)
                                        <div class="swiper-slide">
                                            <div class="iq-card card-hover entainment-slick-card">
                                                <div class="block-images position-relative w-100">
                                                    <a href="{{ route('movie-details', $movie->id) }}" 
                                                    class="position-absolute top-0 bottom-0 start-0 end-0"></a>

                                                    <div class="image-box w-100">
                                                        <img src="{{ $movie->poster_url 
                                                                    ? url('storage/streamit-laravel/' . $movie->poster_url) 
                                                                    : asset('images/default-poster.jpg') }}"
                                                            alt="{{ $movie->name }}"
                                                            class="img-fluid object-cover w-100 d-block border-0">

                                                        @if($movie->movie_access == 'paid')
                                                            @php
                                                                $current_user_plan = auth()->user() ? auth()->user()->subscriptionPackage : null;
                                                                $current_plan_level = $current_user_plan->level ?? 0;
                                                            @endphp

                                                            @if($movie->plan_level > $current_plan_level)
                                                                <button type="button" class="product-premium border-0" 
                                                                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Premium">
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
                                                                    <i class="ph ph-clock"></i>
                                                                    {{ $movie->duration ? formatDuration($movie->duration) : '--' }}
                                                                </div>
                                                                <div class="movie-language d-flex align-items-center gap-1">
                                                                    <i class="ph ph-translate"></i>
                                                                    <small>{{ $movie->language ?? '--' }}</small>
                                                                </div>
                                                            </div>

                                                            <div class="d-flex align-items-center gap-3 mt-3">
                                                                <x-watchlist-button :entertainment-id="$movie->id" 
                                                                                    :in-watchlist="$movie->is_watch_list" 
                                                                                    customClass="watch-list-btn" />

                                                                <div class="flex-grow-1">
                                                                    <a href="{{ route('movie-details', $movie->id) }}" 
                                                                    class="btn btn-primary w-100">
                                                                        {{ __('frontend.watch_now') }}
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Pagination dots -->
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    @endif






            @if (isenablemodule('livetv') == 1)
                <div id="topchannel-section" class="section-wraper scroll-section section-hidden">
                    <div class="card-style-slider shimmer-container">
                        <div class="row gy-4 row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 mt-3">
                            @for ($i = 0; $i < 6; $i++)
                                <div class="shimmer-container col mb-3">
                                    @include('components.card_shimmer_channel')
                                    
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            @endif

            <!-- Popular Series -->
            <!-- @if (isenablemodule('tvshow') == 1)
                <div id="popular-tvshow-section" class="section-wraper scroll-section section-hidden">
                    <div class="card-style-slider movie-shimmer">
                        <div class="row gy-4 row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 mt-3">
                            @for ($i = 0; $i < 6; $i++)
                                <div class="shimmer-container col mb-3">
                                    @include('components.card_shimmer_movieList')
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            @endif -->
            @if (isenablemodule('tvshow') == 1)
                <div id="popular-tvshow-section" class="section-wraper">
                    <!-- <h4 class="mb-3">{{ __('Popular Series') }}</h4> -->
                     <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="mb-0">{{ __('Popular Series') }}</h4>

                        <a href="{{ route('tv-shows') }}" 
                        class="btn btn-sm btn-outline-primary">
                            {{ __('See All') }}
                        </a>
                    </div>

                    <div class="swiper popularMoviesSwiper">
                        <div class="swiper-wrapper" style="height: 300px; margin: 30px;">
                            @foreach ($mobileSettings['popular-tvshows'] as $movie)
                            
                                <div class="swiper-slide ">
                                    
                                    <div class="iq-card card-hover entainment-slick-card">
                                        <div class="block-images position-relative w-100">
                                            <a href="{{ route('movie-details', $movie->id) }}" class="position-absolute top-0 bottom-0 start-0 end-0"></a>

                                            <div class="image-box w-100">
                                                <img src="{{ $movie->poster_url 
                                                            ? url('storage/streamit-laravel/' . $movie->poster_url) 
                                                            : asset('images/default-poster.jpg') }}"
                                                    alt="{{ $movie->name }}"
                                                    class="img-fluid object-cover w-100 d-block border-0">

                                                @if($movie->movie_access == 'paid')
                                                    @php
                                                        $current_user_plan = auth()->user() ? auth()->user()->subscriptionPackage : null;
                                                        $current_plan_level = $current_user_plan->level ?? 0;
                                                    @endphp

                                                    @if($movie->plan_level > $current_plan_level)
                                                        <button type="button" class="product-premium border-0" 
                                                                data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Premium">
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
                                                            <i class="ph ph-clock"></i>
                                                            {{ $movie->duration ? formatDuration($movie->duration) : '--' }}
                                                        </div>
                                                        <div class="movie-language d-flex align-items-center gap-1">
                                                            <i class="ph ph-translate"></i>
                                                            <small>{{ $movie->language ?? '--' }}</small>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex align-items-center gap-3 mt-3">
                                                        <x-watchlist-button :entertainment-id="$movie->id" 
                                                                            :in-watchlist="$movie->is_watch_list" 
                                                                            customClass="watch-list-btn" />

                                                        <div class="flex-grow-1">
                                                            <a href="{{ route('movie-details', $movie->id) }}" class="btn btn-primary w-100">
                                                                {{ __('frontend.watch_now') }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    </div>
                                @endforeach
                            </div>

                            <!-- Arrow buttons -->
                            <!-- <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div> -->

                        </div>
                    </div>
            @endif
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    new Swiper(".popularMoviesSwiper", {
                        slidesPerView: 6,
                        spaceBetween: 15,
                        navigation: {
                            nextEl: ".swiper-button-next", 
                            prevEl: ".swiper-button-prev",
                        },
                        pagination: {
                            el: ".swiper-pagination",
                            clickable: true,
                        },
                        grabCursor: true,
                        breakpoints: {
                            1200: { slidesPerView: 6 },
                            992: { slidesPerView: 4 },
                            768: { slidesPerView: 3 },
                            576: { slidesPerView: 3 },
                            0: { slidesPerView: 3 },
                        }
                    });
                });
            </script>

            

            <!-- <div id="favorite-personality" class="section-wraper scroll-section section-hidden">
                <div class="card-style-slider shimmer-container">
                    <div class="row gy-4 row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 row-cols-xl-7 mt-3">
                        @for ($i = 0; $i < 7; $i++)
                            <div class="shimmer-container col mb-3">
                                @include('components.card_shimmer_crew')
                            </div>
                        @endfor
                    </div>
                </div>
            </div> -->


            @if (isset($mobileSettings['your-favorite-personality']) && $mobileSettings['your-favorite-personality']->count())
                <div id="popular-personalities-section" class="section-wraper">
                    <h4 class="mb-3">{{ __('Popular Personalities') }}</h4>

                    <div class="swiper popularPersonalitiesSwiper">
                        <div class="swiper-wrapper" style="height: 170px; margin: 30px;">
                            @foreach ($mobileSettings['your-favorite-personality'] as $personality)
                                <div class="swiper-slide">
                                    <div class="iq-card entainment-slick-card">
                                        <div class="block-images position-relative w-100">
                                            <a href="javascript:void(0);" 
                                            class="position-absolute top-0 bottom-0 start-0 end-0"></a>

                                            <div class="image-box w-100 text-center">
                                                <img src="{{ $personality->file_url 
                                                            ? url('storage/streamit-laravel/' . $personality->file_url) 
                                                            : asset('images/default-avatar.jpg') }}"
                                                    alt="{{ $personality->name }}"
                                                    class="img-fluid object-cover mx-auto border-0"
                                                    style="width: 170px; height: 170px; object-fit: cover;">
                                            </div>

                                            <div class="card-description text-center mt-3">
                                                <h6 class="iq-title text-capitalize mb-1">
                                                    {{ $personality->name ?? '--' }}
                                                </h6>

                                                <!-- @if(!empty($personality->designation))
                                                    <p class="small text-muted line-count-1">
                                                        {{ $personality->designation }}
                                                    </p>
                                                @endif -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Arrow buttons -->
                        <!-- <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div> -->

                      
                    </div>
                </div>
            @endif

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    new Swiper(".popularPersonalitiesSwiper", {
                        slidesPerView: 6,
                        spaceBetween: 20,
                        navigation: {
                            nextEl: ".swiper-button-next", 
                            prevEl: ".swiper-button-prev",
                        },
                        pagination: {
                            el: ".swiper-pagination",
                            clickable: true,
                        },
                        grabCursor: true,
                        breakpoints: {
                            1200: { slidesPerView: 6 },
                            992: { slidesPerView: 4 },
                            768: { slidesPerView: 3 },
                            576: { slidesPerView: 4 },
                            0: { slidesPerView: 3 },
                        }
                    });
                });
            </script>

            

            @if (isenablemodule('movie') == 1)
                <div id="free-movie-section" class="section-wraper scroll-section section-hidden">
                    <div class="card-style-slider movie-shimmer">
                        <div class="row gy-4 row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 mt-3">
                            @for ($i = 0; $i < 6; $i++)
                                <div class="shimmer-container col mb-3">
                                    @include('components.card_shimmer_movieList')
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            @endif

            <!-- <div id="genres-section" class="section-wraper scroll-section section-hidden">
                <div class="card-style-slider shimmer-container">
                    <div class="row gy-4 row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 mt-3">
                        @for ($i = 0; $i < 6; $i++)
                            <div class="shimmer-container col mb-3">
                                @include('components.card_shimer_genres')
                            </div>
                        @endfor
                    </div>
                </div>
            </div> -->
            @if (isset($mobileSettings['genre']) && $mobileSettings['genre']->count())
    <div id="genres-section" class="section-wraper">
        <h4 class="mb-3">Genres</h4>

        <div class="swiper genresSwiper">
            <div class="swiper-wrapper" style="height: 170px; margin: 30px;">
                @foreach ($mobileSettings['genre'] as $genre)
                    <div class="swiper-slide">
                        <div class="iq-card entainment-slick-card">
                            <div class="block-images position-relative w-100">
                                <a href="javascript:void(0);" 
                                   class="position-absolute top-0 bottom-0 start-0 end-0"></a>

                                <div class="image-box w-100 text-center">
                                    <img src="{{ $genre->file_url 
                                                ? url('storage/streamit-laravel/' . $genre->file_url) 
                                                : asset('images/default-avatar.jpg') }}"
                                         alt="{{ $genre->name }}"
                                         class="img-fluid object-cover mx-auto border-0"
                                         style="width: 150px; height: 150px; object-fit: cover; border-radius: 10px;">
                                </div>

                                <div class="card-description text-center mt-3">
                                    <h6 class="iq-title text-capitalize mb-1">
                                        {{ $genre->name ?? '--' }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Optional arrows -->
            <!-- <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div> -->
        </div>
    </div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Swiper(".genresSwiper", {
            slidesPerView: 6,
            spaceBetween: 20,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            grabCursor: true,
            breakpoints: {
                1200: { slidesPerView: 6 },
                992: { slidesPerView: 4 },
                768: { slidesPerView: 3 },
                576: { slidesPerView: 2 },
                0: { slidesPerView: 1 },
            }
        });
    });
</script>


            @if (isenablemodule('video') == 1)
                <div id="video-section" class="section-wraper scroll-section section-hidden">
                    <div class="card-style-slider movie-shimmer">
                        <div class="row gy-4 row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 mt-3">
                            @for ($i = 0; $i < 6; $i++)
                                <div class="shimmer-container col mb-3">
                                    @include('components.card_shimmer_movieList')
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            @endif


            @if ($user_id != null && isenablemodule('movie') == 1)
                <!--

                   <div id="base-on-last-watch-section" class="section-wraper scroll-section section-hidden">
                     <div class="card-style-slider movie-shimmer">
                        <div class="row gy-4 row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 mt-3">
                           @for ($i = 0; $i < 6; $i++)
    <div class="shimmer-container col mb-3">
                                 @include('components.card_shimmer_movieList')
                             </div>
    @endfor
                        </div>
                      </div>
                   </div>

                 


                  <div id="most-like-section" class="section-wraper scroll-section section-hidden">
                    <div class="card-style-slider movie-shimmer">
                       <div class="row gy-4 row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 mt-3">
                          @for ($i = 0; $i < 6; $i++)
    <div class="shimmer-container col mb-3">
                                @include('components.card_shimmer_movieList')
                            </div>
    @endfor
                       </div>
                     </div>
                  </div>

                  <div id="most-view-section" class="section-wraper scroll-section section-hidden">
                    <div class="card-style-slider movie-shimmer">
                       <div class="row gy-4 row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 mt-3">
                          @for ($i = 0; $i < 6; $i++)
    <div class="shimmer-container col mb-3">
                                @include('components.card_shimmer_movieList')
                            </div>
    @endfor
                       </div>
                     </div>
                  </div>

                  <div id="tranding-in-country-section" class="section-wraper scroll-section section-hidden">
                    <div class="card-style-slider movie-shimmer">
                       <div class="row gy-4 row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 mt-3">
                          @for ($i = 0; $i < 6; $i++)
    <div class="shimmer-container col mb-3">
                                @include('components.card_shimmer_movieList')
                            </div>
    @endfor
                       </div>
                     </div>
                  </div> -->
            @endif

            @if ($user_id != null)
                <div id="favorite-genres-section" class="section-wraper scroll-section section-hidden">
                    <div class="card-style-slider shimmer-container">
                        <div class="row gy-4 row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 mt-3">
                            @for ($i = 0; $i < 7; $i++)
                                <div class="shimmer-container col mb-3">
                                    @include('components.card_shimer_genres')
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <div id="user-favorite-personality" class="section-wraper scroll-section section-hidden">
                    <div class="card-style-slider shimmer-container">
                        <div class="row gy-4 row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 row-cols-xl-7 mt-3">
                            @for ($i = 0; $i < 7; $i++)
                                <div class="shimmer-container col mb-3">
                                    @include('components.card_shimmer_crew')
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            @endif





        </div>

    </div>
<h1 class="h4 text-center">Ti Channel - Tihd TV</h1>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection

@push('after-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('.scroll-section');

            const options = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1 // Trigger when 10% of the section is in view
            };

            const callback = (entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.remove('section-hidden');
                        entry.target.classList.add('section-visible');
                    }
                });
            };

            const observer = new IntersectionObserver(callback, options);

            sections.forEach(section => {
                observer.observe(section);
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const envURL = document.querySelector('meta[name="baseUrl"]').getAttribute('content');

            // Observer for scrolling
            const sections = document.querySelectorAll('.scroll-section');
            const options = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };
            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.remove('section-hidden');
                        entry.target.classList.add('section-visible');
                        if (entry.target.id === 'continue-watch-section') {
                            fetchContinueWatch();
                        } else if (entry.target.id === 'top-10-moive-section') {
                            fetchTop10Movies();
                        } else if (entry.target.id === 'latest-moive-section') {
                            fetchLatestMovies();
                        } else if (entry.target.id === 'language-section') {
                            fetchLanguages();
                        } else if (entry.target.id === 'popular-moive-section') {
                            fetchPopularMovies();
                        } else if (entry.target.id === 'topchannel-section') {
                            fetchTopChannels();
                        } else if (entry.target.id === 'popular-tvshow-section') {
                            fetchPopularTvshows();
                        } else if (entry.target.id === 'favorite-personality') {
                            fetchfavoritePersonality();
                        } else if (entry.target.id === 'free-movie-section') {
                            fetchFreeMovie();
                        } else if (entry.target.id === 'genres-section') {
                            fetchGenerData();
                        } else if (entry.target.id === 'video-section') {
                            fetchVideoData();
                        } else if (entry.target.id === 'base-on-last-watch-section') {
                            fetchBaseonlastwatch();
                        } else if (entry.target.id === 'most-like-section') {
                            fetchMostLikeMoive();
                        } else if (entry.target.id === 'most-view-section') {
                            fetchMostViewMoive();
                        } else if (entry.target.id === 'tranding-in-country-section') {
                            fetchCountryTraingingMoive();
                        } else if (entry.target.id === 'favorite-genres-section') {
                            fetchFavoriteGenerData();
                        } else if (entry.target.id === 'user-favorite-personality') {
                            fetchUserfavoritePersonality();
                        }

                        observer.unobserve(entry.target);
                    }
                });
            }, options);


            sections.forEach(section => {
                observer.observe(section);
            });


            ;

            function fetchContinueWatch() {
                fetch(`${envURL}/api/web-continuewatch-list`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('continue-watch-section').innerHTML = data.html;
                        slickGeneral('slick-general-continue-watch');
                    })
                    .catch(error => {
                        console.error('Error fetching Top 10 Movies:', error);
                    });
            }

            // Fetch Top 10 Movies
            function fetchTop10Movies() {
                fetch(`${envURL}/api/top-10-movie`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('top-10-moive-section').innerHTML = data.html;
                        slickGeneral('slick-general-top-10');
                    })
                    .catch(error => {
                        console.error('Error fetching Top 10 Movies:', error);
                    });
            }

            function fetchLatestMovies() {
                fetch(`${envURL}/api/latest-movie`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('latest-moive-section').innerHTML = data.html;
                        slickGeneral('slick-general-latest-movie');
                    })
                    .catch(error => {
                        console.error('Error fetching Latest Movies:', error);
                    });
            }


            function fetchLanguages() {
                fetch(`${envURL}/api/fetch-languages`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('language-section').innerHTML = data.html;
                        slickGeneral('slick-general-language');
                    })
                    .catch(error => {
                        console.error('Error fetching Language:', error);
                    });
            }

            function fetchPopularMovies() {
                fetch(`${envURL}/api/popular-movie`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('popular-moive-section').innerHTML = data.html;
                        slickGeneral('slick-general-popular-movie');
                    })
                    .catch(error => {
                        console.error('Error fetching Popular Movies:', error);
                    });
            }


            function fetchTopChannels() {
                fetch(`${envURL}/api/top-channels`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('topchannel-section').innerHTML = data.html;
                        slickGeneral('slick-general-topchannel');
                    })
                    .catch(error => {
                        console.error('Error fetching Top channel:', error);
                    });
            }

            function fetchPopularTvshows() {
                fetch(`${envURL}/api/popular-tvshows`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('popular-tvshow-section').innerHTML = data.html;
                        slickGeneral('slick-general-popular-tvshow');
                    })
                    .catch(error => {
                        console.error('Error fetching popular Tvshows:', error);
                    });
            }

            function fetchfavoritePersonality() {
                fetch(`${envURL}/api/favorite-personality`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('favorite-personality').innerHTML = data.html;
                        slickGeneral('slick-general-castcrew');
                    })
                    .catch(error => {
                        console.error('Error fetching favorite personality:', error);
                    });
            }

            function fetchFreeMovie() {
                fetch(`${envURL}/api/free-movie`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('free-movie-section').innerHTML = data.html;
                        slickGeneral('slick-general-free-movie');
                    })
                    .catch(error => {
                        console.error('Error fetching Free Movie:', error);
                    });
            }


            function fetchGenerData() {
                fetch(`${envURL}/api/get-gener`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('genres-section').innerHTML = data.html;
                        slickGeneral('slick-general-gener-section');
                    })
                    .catch(error => {
                        console.error('Error fetching Gener:', error);
                    });
            }


            function fetchVideoData() {
                fetch(`${envURL}/api/get-video`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('video-section').innerHTML = data.html;
                        slickGeneral('slick-general-video-section');
                    })
                    .catch(error => {
                        console.error('Error fetching Video:', error);
                    });
            }

            function fetchBaseonlastwatch() {
                fetch(`${envURL}/api/base-on-last-watch-movie`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('base-on-last-watch-section').innerHTML = data.html;
                        slickGeneral('slick-general-last-watch');
                    })
                    .catch(error => {
                        console.error('Error fetching Video:', error);
                    });
            }


            function fetchMostLikeMoive() {
                fetch(`${envURL}/api/most-like-movie`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('most-like-section').innerHTML = data.html;
                        slickGeneral('slick-general-most-like');
                    })
                    .catch(error => {
                        console.error('Error fetching Video:', error);
                    });
            }

            function fetchMostViewMoive() {
                fetch(`${envURL}/api/most-view-movie`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('most-view-section').innerHTML = data.html;
                        slickGeneral('slick-general-most-view');
                    })
                    .catch(error => {
                        console.error('Error fetching Video:', error);
                    });
            }

            function fetchCountryTraingingMoive() {
                fetch(`${envURL}/api/country-tranding-movie`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('tranding-in-country-section').innerHTML = data.html;
                        slickGeneral('slick-general-tranding-country');
                    })
                    .catch(error => {
                        console.error('Error fetching Video:', error);
                    });
            }

            function fetchFavoriteGenerData() {
                fetch(`${envURL}/api/favorite-genres`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('favorite-genres-section').innerHTML = data.html;
                        slickGeneral('slick-general-favorite-genres');
                    })
                    .catch(error => {
                        console.error('Error fetching Video:', error);
                    });
            }

            function fetchUserfavoritePersonality() {
                fetch(`${envURL}/api/user-favorite-personality`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('user-favorite-personality').innerHTML = data.html;
                        slickGeneral('slick-general-favorite-personality');
                    })
                    .catch(error => {
                        console.error('Error fetching Video:', error);
                    });
            }


        });


        // Slick General function to initialize the sliders
        function slickGeneral(className) {
            jQuery(`.${className}`).each(function() {


                let slider = jQuery(this);

                let slideSpacing = slider.data("spacing");

                function addSliderSpacing(spacing) {
                    slider.css('--spacing', `${spacing}px`);
                }

                addSliderSpacing(slideSpacing);

                slider.slick({
                    slidesToShow: slider.data("items"),
                    slidesToScroll: 1,
                    speed: slider.data("speed"),
                    autoplay: slider.data("autoplay"),
                    centerMode: slider.data("center"),
                    infinite: slider.data("infinite"),
                    arrows: slider.data("navigation"),
                    dots: slider.data("pagination"),
                    prevArrow: "<span class='slick-arrow-prev'><span class='slick-nav'><i class='ph ph-caret-left'></i></span></span>",
                    nextArrow: "<span class='slick-arrow-next'><span class='slick-nav'><i class='ph ph-caret-right'></i></span></span>",
                    responsive: [{
                            breakpoint: 1600, // screen size below 1600
                            settings: {
                                slidesToShow: slider.data("items-desktop"),
                            }
                        },
                        {
                            breakpoint: 1400, // screen size below 1400
                            settings: {
                                slidesToShow: slider.data("items-laptop"),
                            }
                        },
                        {
                            breakpoint: 1200, // screen size below 1200
                            settings: {
                                slidesToShow: slider.data("items-tab"),
                            }
                        },
                        {
                            breakpoint: 768, // screen size below 768
                            settings: {
                                slidesToShow: slider.data("items-mobile-sm"),
                            }
                        },
                        {
                            breakpoint: 576, // screen size below 576
                            settings: {
                                slidesToShow: slider.data("items-mobile"),
                            }
                        }
                    ]
                });

                let active = slider.find(".slick-active");
                let slideItems = slider.find(".slick-track .slick-item");
                active.first().addClass("first");
                active.last().addClass("last");

                slider.on('afterChange', function(event, slick, currentSlide, nextSlide) {
                    let active = slider.find(".slick-active");
                    slideItems.removeClass("first last");
                    active.first().addClass("first");
                    active.last().addClass("last");
                });
            });
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
    const swiper = new Swiper(".popularMoviesSwiper", {
        slidesPerView: 6,
        spaceBetween: 15,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        grabCursor: true,
        breakpoints: {
            1200: { slidesPerView: 6 },
            992: { slidesPerView: 4 },
            768: { slidesPerView: 3 },
            576: { slidesPerView: 2 },
            0: { slidesPerView: 1 },
        },
        on: {
            init: function () {
                updateZoomSlides(this);
            },
            slideChange: function () {
                updateZoomSlides(this);
            },
        }
    });

    function updateZoomSlides(swiper) {
        const slides = swiper.slides;
        slides.forEach(slide => {
            slide.classList.remove('first-slide-animate', 'last-slide-animate');
        });
        if (slides.length > 0) {
            slides[0].classList.add('first-slide-animate'); // প্রথম কার্ড
            slides[slides.length - 1].classList.add('last-slide-animate'); // শেষ কার্ড
        }
    }
});
document.addEventListener('DOMContentLoaded', function () {
    const swiper = new Swiper(".popularMoviesSwiper", {
        slidesPerView: 6,
        spaceBetween: 15,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        grabCursor: true,
        breakpoints: {
            1200: { slidesPerView: 6 },
            992: { slidesPerView: 4 },
            768: { slidesPerView: 3 },
            576: { slidesPerView: 2 },
            0: { slidesPerView: 1 },
        },
        on: {
            init: function () {
                updateZoomSlides(this);
            },
            slideChange: function () {
                updateZoomSlides(this);
            },
            resize: function () {
                updateZoomSlides(this);
            }
        }
    });

    function updateZoomSlides(swiper) {
        const slides = swiper.slides;

        // সব স্লাইড থেকে ক্লাস মুছে ফেল
        slides.forEach(slide => {
            slide.classList.remove('first-slide-animate', 'last-slide-animate');
        });

        // ভিউতে থাকা active slides
        const activeSlides = swiper.slides.slice(swiper.activeIndex, swiper.activeIndex + swiper.params.slidesPerView);

        if (activeSlides.length > 0) {
            activeSlides[0].classList.add('first-slide-animate'); // ভিউর প্রথম
            activeSlides[activeSlides.length - 1].classList.add('last-slide-animate'); // ভিউর শেষ
        }
    }
});

    </script>
@endpush
@push('after-styles')
    <style>
        /* Add to your CSS file */
        .section-hidden {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        }

        .section-visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
@endpush
