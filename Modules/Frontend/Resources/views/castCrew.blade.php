@extends('frontend::layouts.master')

@section('meta')
<!-- Basic Meta Tags -->
<title>Speakers - TI Channel | Voice of Islam</title>
<meta name="description" content="Explore the renowned speakers on TI Channel, the Voice of Islam. Discover their inspiring talks, teachings, and contributions to Islamic knowledge and spirituality.">
<meta name="keywords" content="Islamic speakers, Voice of Islam, TI Channel speakers, renowned scholars, inspiring talks, Islamic teachings, faith leaders, spirituality, Islamic knowledge, Islamic lectures">
<meta name="author" content="TI Channel">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">

<!-- Canonical Tag -->
<link rel="canonical" href="{{ url()->current() }}">

<!-- Open Graph Meta Tags (for social media) -->
<meta property="og:title" content="Speakers - TI Channel | Voice of Islam">
<meta property="og:description" content="Explore the renowned speakers on TI Channel, the Voice of Islam. Discover their inspiring talks, teachings, and contributions to Islamic knowledge and spirituality.">
<meta property="og:image" content="{{ asset('/images/icons/icon-512x512.png') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="TI Channel">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Speakers - TI Channel | Voice of Islam">
<meta name="twitter:description" content="Explore the renowned speakers on TI Channel, the Voice of Islam. Discover their inspiring talks, teachings, and contributions to Islamic knowledge and spirituality.">
<meta name="twitter:image" content="{{ asset('/images/icons/icon-512x512.png') }}">
<meta name="twitter:site" content="@TI_Channel">
@endsection


@section('content')
<div class="list-page section-spacing-bottom px-0">
    <div class="page-title">

        <h4 class="m-0 text-center">{{__('frontend.personality_list')}}</h4>
    </div>
    <div class="movie-lists">
        <div class="container-fluid">
            <div class="row gy-4 row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 row-cols-xl-7" id="entertainment-list">

            </div>
            <div class="card-style-slider shimmer-container">
                <div class="row gy-4 row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 row-cols-xl-7 mt-3">
                        @for ($i = 0; $i < 21; $i++)
                            <div class="shimmer-container col mb-3">
                                    @include('components.card_shimmer_crew')
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
const movie_id = "{{ $entertainment_id ?? '' }}";
const type_value = "{{ $type ?? '' }}";
const shimmerContainer = document.querySelector('.shimmer-container');
const EntertainmentList = document.getElementById('entertainment-list');
let currentPage = 1;
let isLoading = false;
let hasMore = true;
let actor_id= null;
let moive_id=movie_id;
let type=type_value;
let per_page=21;
const baseUrl = document.querySelector('meta[name="baseUrl"]').getAttribute('content');
const apiUrl = `${baseUrl}/api/castcrew-list`;

</script>

@endsection
