@extends('frontend::layouts.master')

@section('meta')
<!-- Basic Meta Tags -->
<title>{{ $data['name'] }} - Islamic Series | TI Channel</title>
<meta name="description" content="{{ $data['description'] }}">
<meta name="keywords" content="{{ $data['name'] }}, Islamic series, Islamic teachings, faith-based series, TI Channel, Voice of Islam, spirituality, educational content, Islamic programs">
<meta name="author" content="TI Channel">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">

<!-- Canonical Tag -->
<link rel="canonical" href="{{ url()->current() }}">

<!-- Open Graph Meta Tags (for social media) -->
<meta property="og:title" content="{{ $data['name'] }} - Islamic Series | TI Channel">
<meta property="og:description" content="{{ $data['description'] }}">
<meta property="og:image" content="{{ isset($data['thumbnail_image']) ? asset($data['thumbnail_image']) : asset('/images/icons/icon-512x512.png') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="video.series">
<meta property="og:site_name" content="TI Channel">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $data['name'] }} - Islamic Series | TI Channel">
<meta name="twitter:description" content="{{ $data['description'] }}">
<meta name="twitter:image" content="{{ isset($data['thumbnail_image']) ? asset($data['thumbnail_image']) : asset('/images/icons/icon-512x512.png') }}">
<meta name="twitter:site" content="@TI_Channel">
@endsection

@section('content')
<div class="tvshowscroll">
    <!-- Thumbnail Section -->
    <div id="thumbnail-section">
        @include('frontend::components.section.thumbnail',  ['data' => $data['trailer_url'] ,'type'=>$data['trailer_url_type'],'thumbnail_image'=>$data['thumbnail_image']])
    </div>

    <!-- Detail Section -->
    <div id="detail-section">
        <div id="tvshow-id">
            @include('frontend::components.section.data_detail',  ['data' => $data])
        </div>
    </div>

    <!-- Short Menu -->
    <div class="short-menu mb-5">
        <div class="container-fluid">
            <div class="py-4 px-md-5 px-3 movie-detail-menu rounded">
                <div class="d-flex align-items-center gap-2">
                    <div class="left">
                        <i class="ph ph-caret-left align-middle"></i>
                    </div>
                    <div class="custom-nav-slider">
                        <ul class="list-inline m-0 p-0 d-flex align-items-center">
                            <li class="flex-shrink-0">
                                <a href="#seasons" class="link-body-emphasis">
                                    <span class="d-inline-flex align-items-center gap-2">
                                        <span><i class="ph ph-film-reel align-middle"></i></span>
                                        <span class="font-size-18">{{ __('frontend.episodes') }}</span>
                                    </span>
                                </a>
                            </li>
                            @if($data['casts'] != null || $data['directors'] !=null )
                            <li class="flex-shrink-0">
                                <a href="#movie-cast" class="link-body-emphasis">
                                    <span class="d-inline-flex align-items-center gap-2">
                                        <span><i class="ph ph-user-circle-gear align-middle"></i></span>
                                        <span class="font-size-18">{{ __('frontend.casts') }} & {{ __('frontend.directors') }}</span>
                                    </span>
                                </a>
                            </li>
                            @endif

                            @if(count($data['three_reviews']) !=0 )
                            <li class="flex-shrink-0">
                                <a href="#review-list" class="link-body-emphasis">
                                    <span class="d-inline-flex align-items-center gap-2">
                                        <span><i class="ph ph-star align-middle"></i></span>
                                        <span class="font-size-18">{{__('frontend.reviews')}}</span>
                                    </span>
                                </a>
                            </li>
                            @endif
                            @if($data['more_items'] != null)
                            <li class="flex-shrink-0">
                                <a href="#more-like-this" class="link-body-emphasis">
                                    <span class="d-inline-flex align-items-center gap-2">
                                        <span><i class="ph ph-dots-three-circle align-middle"></i></span>
                                        <span class="font-size-18">{{__('frontend.more_like_this')}}</span>
                                    </span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                    <div class="right">
                        <i class="ph ph-caret-right align-middle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll Buttons -->
    <!--<button class="scroll-btn up-btn" onclick="scrollUp()">-->
    <!--    <i class="ph ph-arrow-up"></i>-->
    <!--</button>-->
    <!--<button class="scroll-btn down-btn" onclick="scrollDown()">-->
    <!--    <i class="ph ph-arrow-down"></i> -->
    <!--</button>-->

    <!-- Seasons Section -->
    <div class="container-fluid">
        <div id="seasons">
            @include('frontend::components.section.seasons',  ['data' => $data['tvShowLinks']])
        </div>
    </div>

    <div class="container-fluid padding-right-0">
        <div class="overflow-hidden">
            <div id="movie-cast" class="half-spacing">
                @include('frontend::components.section.castcrew',  ['data' => $data['casts']->toArray(request()), 'title'=> __('frontend.casts'),'entertainment_id' =>$data['id'], 'type'=>'actor', 'slug'=>''])
            </div>

            <div id="favorite-personality">
                @include('frontend::components.section.castcrew',  ['data' => $data['directors']->toArray(request()),'title'=> __('frontend.directors'),'entertainment_id' =>$data['id'],'type'=>'director', 'slug'=>''])
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div id="add-review">
            @include('frontend::components.section.add_review',  ['addreview' => 'Add Review'])
        </div>

        @if($data['three_reviews'] != null)
            <div id="review-list">
                @include('frontend::components.section.review_list',  ['data' => $data['three_reviews']->toArray(request()), 'your_review'=> $data['your_review'], 'title'=> $data['name'], 'total_review'=>count($data['reviews'])])
            </div>
        @endif
    </div>

    <div class="container-fluid padding-right-0">
        <div class="overflow-hidden">
            @if($data['more_items'] != null)
                <div id="more-like-this">
                    @include('frontend::components.section.entertainment',  ['data' => $data['more_items'], 'title'=>__('frontend.more_like_this'),'type' => $data['type'],'slug'=>''])
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="DeviceSupport" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content position-relative">
                <div class="modal-body user-login-card m-0 p-4 position-relative">
                    <button type="button" class="btn btn-primary custom-close-btn rounded-2" data-bs-dismiss="modal">
                        <i class="ph ph-x text-white fw-bold align-middle"></i>
                    </button>

                    <div class="modal-body">
                        {{__('frontend.device_not_support')}}
                    </div>

                    <div class="d-flex align-items-center justify-content-center">
                        <a href="{{ Auth::check() ? route('subscriptionPlan') : route('login') }}" class="btn btn-primary mt-5" >{{__('frontend.upgrade')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- CSS for Scroll Buttons -->
<style>
    .tvshowscroll {
        position: relative;
        width: 100%;
        height: 100%;
        overflow-y: auto;
    }

    /* Style for Scroll Buttons */
    .scroll-btn {
        position: fixed;
        right: 20px;
        z-index: 1000;
        padding: 10px;
        background-color: #E50B17;
        color: white;
        border: none;
        cursor: pointer;
        width: 100px;
        border-radius: 5px;
        font-size: 16px;
        display: none;  /* Initially hide the buttons */
    }

    /* Position the Up and Down Buttons */
    .up-btn {
        top: 20%;
    }

    .down-btn {
        top: 30%;
    }

    /* Show buttons only if screen height is less than 990px */
    @media (max-width: 990px) {
        .scroll-btn {
            display: block; /* Show buttons on screens smaller than 990px in height */
        }
    }

    /* Hide buttons on mobile and tablet screens */
    @media (max-width: 768px) {
        .scroll-btn {
            display: none; /* Hide buttons on smaller screens */
        }
    }

</style>

<!-- JavaScript for Scroll Actions -->
<script>
// Function to scroll the content up
function scrollUp() {
    window.scrollBy(0, -100); // Scroll up by 100px
}

// Function to scroll the content down
function scrollDown() {
    window.scrollBy(0, 100); // Scroll down by 100px
}
</script>

@endsection
