@extends('frontend::layouts.master')

@section('meta')
@php
    $episodeName = $data['name'];
    $episodeDescription = strip_tags($data['description']);
    $releaseYear = \Carbon\Carbon::parse($data['release_date'])->format('Y');
    $genres = collect($data['genres'])->pluck('name')->implode(', ');
    $metaTitle = "Watch " . $episodeName . " (" . $releaseYear . ") | TI Channel";
    $ogUrl = url()->current();
    $ogImage = asset($data['poster_image']);
@endphp

<title>{{ $metaTitle }}</title>
<meta name="description" content="{{ Str::limit($episodeDescription, 155) }}">
<meta name="keywords" content="{{ $episodeName }}, TV Show, Episode, {{ $genres }}, {{ $releaseYear }}, Islamic content, TI Channel">
<meta name="author" content="TI Channel">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">

<link rel="canonical" href="{{ $ogUrl }}">

<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ Str::limit($episodeDescription, 200) }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:url" content="{{ $ogUrl }}">
<meta property="og:type" content="video.episode">
<meta property="og:site_name" content="TI Channel">
<meta property="og:video:series" content="{{ $data['tvShowLinks'][0]['series_name'] ?? 'TI Channel TV Show' }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $metaTitle }}">
<meta name="twitter:description" content="{{ Str::limit($episodeDescription, 200) }}">
<meta name="twitter:image" content="{{ $ogImage }}">
<meta name="twitter:site" content="@TI_Channel">
@endsection



@section('content')

<div id="thumbnail-section">
    @include('frontend::components.section.thumbnail',  ['data' => $data['trailer_url'] ,'type'=>$data['trailer_url_type'],'thumbnail_image'=>$data['poster_image']])
</div>

<div id="detail-section">
    @include('frontend::components.section.episode_data',  ['data' => $data])
</div>

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
                                    <span class="font-size-18">{{__('frontend.episodes')}}</span>
                                </span>
                            </a>
                        </li>

                        <li class="flex-shrink-0">
                            <a href="#more-like-this" class="link-body-emphasis">
                                <span class="d-inline-flex align-items-center gap-2">
                                    <span><i class="ph ph-dots-three-circle align-middle"></i></span>
                                    <span class="font-size-18">{{__('frontend.more_like_this')}}</span>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="right">
                    <i class="ph ph-caret-right align-middle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="seasons">
    @include('frontend::components.section.episodes',  ['data' => $data['tvShowLinks']])
</div>

<div class="container-fluid padding-right-0">
    <div class="overflow-hidden">
        @if($data['more_items'] !=null)
            <div id="more-like-this">
                @include('frontend::components.section.entertainment',  ['data' => $data['more_items'], 'title'=>__('frontend.more_like_this'),'type'=>'tvshow', 'slug'=>''])
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


@endsection
