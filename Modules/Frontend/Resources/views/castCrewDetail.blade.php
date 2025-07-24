@extends('frontend::layouts.master')

@section('meta')
<!-- Basic Meta Tags -->
<title>{{ $data['name'] }} - Speaker | TI Channel | Voice of Islam</title>
<meta name="description" content="Learn more about {{ $data['name'] }} on TI Channel. Explore their inspiring talks, Islamic teachings, and contributions to faith and spirituality.">
<meta name="keywords" content="{{ $data['name'] }}, Islamic speaker, Voice of Islam, TI Channel speaker Islamic teachings, inspiring talks, faith, spirituality, Islamic scholar">
<meta name="author" content="TI Channel">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">

<!-- Canonical Tag -->
<link rel="canonical" href="{{ url()->current() }}">

<!-- Open Graph Meta Tags (for social media) -->
<meta property="og:title" content="{{ $data['name'] }} - Speaker | TI Channel | Voice of Islam">
<meta property="og:description" content="Learn more about {{ $data['name'] }} on TI Channel. Explore their inspiring talks, Islamic teachings, and contributions to faith and spirituality.">
<meta property="og:image" content="{{ $data['profile_image'] ? asset($data['profile_image']) : asset('/images/icons/icon-512x512.png') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="profile">
<meta property="og:site_name" content="TI Channel">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $data['name'] }} - Speaker | TI Channel | Voice of Islam">
<meta name="twitter:description" content="Learn more about {{ $data['name'] }} on TI Channel. Explore their inspiring talks, Islamic teachings, and contributions to faith and spirituality.">
<meta name="twitter:image" content="{{ $data['profile_image'] ? asset($data['profile_image']) : asset('/images/icons/icon-512x512.png') }}">
<meta name="twitter:site" content="@TI_Channel">
@endsection


@section('content')
<div class="section-spacing p-0">
    <div class="container-fluid">
        <div class="page-title">
            <h4 class="m-0 text-center">{{ $data['name'] }}</h4>
        </div>

        <div id="castcrewdetail-section">
        @include('frontend::components.card.card_castcrewdetail',['data' => $data])
        </div>
    </div>
</div>


@if($more_items !=null)
<div class="section-spacing px-0">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between my-2">
            <h5 class="main-title text-capitalize mb-0">{{__('frontend.cast_movies_tvshows')}} {{  $data['name'] }}</h5>
        </div>
        <div class="row gy-4 row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5" id="entertainment-list">

        </div>
        <div class="card-style-slider shimmer-container">
            <div class="row gy-4 row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 mt-3">
                    @for ($i = 0; $i < 5; $i++)
                        <div class="shimmer-container col mb-3">
                                @include('components.card_shimmer_movieList')
                        </div>
                    @endfor
            </div>
        </div>
    </div>
</div>
@endif
<script src="{{ asset('js/entertainment.min.js') }}" defer></script>

<script>
const noDataImageSrc = '{{ asset('img/NoData.png') }}';
const castId = "{{ $data['id'] }}";
const shimmerContainer = document.querySelector('.shimmer-container');
const EntertainmentList = document.getElementById('entertainment-list');
let currentPage = 1;
let isLoading = false;
let hasMore = true;
let type=null;
let actor_id= castId;
let movie_id=null;
let per_page=5;
const baseUrl = document.querySelector('meta[name="baseUrl"]').getAttribute('content');

const apiUrl = `${baseUrl}/api/movie-list`;

</script>

@endsection
