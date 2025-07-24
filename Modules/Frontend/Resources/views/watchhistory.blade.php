@extends('frontend::layouts.master')

@section('meta')
<!-- Basic Meta Tags -->
<title>Watch History - TI Channel | Voice of Islam</title>
<meta name="description" content="Your watch history on TI Channel. Explore the movies and TV shows you've watched.">
<meta name="keywords" content="watch history, TI Channel, Voice of Islam, Islamic series, faith-based shows">
<meta name="author" content="TI Channel">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">

<!-- Canonical Tag -->
<link rel="canonical" href="{{ url()->current() }}">

<!-- Open Graph Meta Tags (for social media) -->
<meta property="og:title" content="Watch History - TI Channel | Voice of Islam">
<meta property="og:description" content="Your watch history on TI Channel. Explore the movies and TV shows you've watched.">
<meta property="og:image" content="{{ asset('/images/icons/icon-512x512.png') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="TI Channel">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Watch History - TI Channel | Voice of Islam">
<meta name="twitter:description" content="Your watch history on TI Channel. Explore the movies and TV shows you've watched.">
<meta name="twitter:image" content="{{ asset('/images/icons/icon-512x512.png') }}">
<meta name="twitter:site" content="@TI_Channel">
@endsection

@section('content')
<div class="list-page section-spacing-bottom px-0">
    <div class="page-title" id="page_title">
        <h4 class="m-0 text-center">{{__('frontend.watch_history')}}</h4>
    </div>
    <div class="movie-lists">
        <div class="container-fluid">
              @if(auth()->user()->is_subscribe==0)
          <p class="text-center">To continue enjoying unlimited access to our content, please subscribe to one of our plans.
                  Upgrade now for an enhanced viewing experience!</p>
                <div class="d-flex align-items-center justify-content-center">
                    <a href="https://tihd.tv/subscription-plan" class="btn btn-primary mt-5">Upgrade</a>
                </div>
              @else
          
            @if($watch->isEmpty())
                <div class="col-12 text-center">
                    <p>No watch history found.</p>
                </div>
            @else
               <div class="row">
    @foreach($watch as $entry)
        <div class="col-md-2 mb-4">
            <div class="iq-card card-hover entainment-slick-card">
                <div class="block-images position-relative w-100">
                    <a href="{{ route($entry->entertainment_type === 'movie' ? 'movie-details' : 'tvshow-details', $entry->entertainment_id) }}" class="position-absolute top-0 bottom-0 start-0 end-0"></a>
                    <div class="image-box w-100">
                        <img src="{{ asset('storage/streamit-laravel/' . $entry->entertainment->thumbnail_url) }}" alt="movie-card" class="img-fluid object-cover w-100 d-block border-0">
                    </div>
                    <div class="card-description with-transition">
                        <div class="position-relative w-100">
                            <ul class="genres-list ps-0 mb-2 ">
                                <li class="small">{{ $entry->entertainment_type === 'movie' ? 'Movie' : 'TV Show' }}</li>
                        <div class="d-flex align-items-center gap-3 mt-3">
    <small class="text-muted">
        <i class="ph ph-calendar"></i>
        Watched on: {{ \Carbon\Carbon::parse($entry->watch_date)->format('F j, Y') }}
    </small>
</div>
                            </ul>
                            <h5 class="iq-title text-capitalize line-count-1">{{ $entry->entertainment->title }}</h5>
                            <div class="d-flex align-items-center gap-3">
                                <div class="movie-time d-flex align-items-center gap-1 small">
                                    <i class="ph ph-clock"></i>
                                    {{ $entry->entertainment->duration }}
                                </div>
                                <div class="movie-language d-flex align-items-center gap-1">
                                    <i class="ph ph-translate"></i>
                                    <small>{{ $entry->entertainment->language }}</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3 mt-3">
                                <button id="watchlist-btn-{{ $entry->entertainment_id }}" class="action-btn btn btn-dark watch-list-btn" data-entertainment-id="{{ $entry->entertainment_id }}" data-in-watchlist="false" data-entertainment-type="{{ $entry->entertainment_type }}" data-bs-toggle="tooltip" data-bs-title="Add watchlist" data-bs-placement="top">
                                    <i class="ph ph-plus"></i>
                                </button>
                                <div class="flex-grow-1">
                                    <a href="{{ route($entry->entertainment_type === 'movie' ? 'movie-details' : 'tvshow-details', $entry->entertainment_id) }}" class="btn btn-primary w-100">
                                        Watch now
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $(document).on('click', '.watch-list-btn', function(event) {
            event.preventDefault();
            var $this = $(this);
            if ($this.prop('disabled')) return;
            $this.prop('disabled', true);

            var isInWatchlist = $this.data('in-watchlist');
            var entertainmentId = $this.data('entertainment-id');
            var entertainmentType = $this.data('entertainment-type');
            const baseUrl = document.querySelector('meta[name="baseUrl"]').getAttribute('content');

            let action = isInWatchlist == '1' ? 'delete' : 'save';
            var data = isInWatchlist == '1'
                ? { id: [entertainmentId], _token: '{{ csrf_token() }}' }
                : { entertainment_id: entertainmentId, type: entertainmentType, _token: '{{ csrf_token() }}' };

            $.ajax({
                url: action === 'save' ? `${baseUrl}/api/save-watchlist` : `${baseUrl}/api/delete-watchlist?is_ajax=1`,
                method: 'POST',
                data: data,
                success: function(response) {
                    window.successSnackbar(response.message);
                    $this.find('i').toggleClass('ph-check ph-plus');
                    $this.toggleClass('btn-primary btn-dark');
                    $this.data('in-watchlist', !isInWatchlist ? 1 : 0);

                    let newInWatchlist = !isInWatchlist ? 'true' : 'false';
                    var newTooltip = newInWatchlist === 'true' ? 'Remove Watchlist' : 'Add Watchlist';

                    if ($this.tooltip) {
                        $this.tooltip('dispose');
                        $this.attr('data-bs-title', newTooltip);
                        $this.tooltip();
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        window.location.href = `${baseUrl}/login`;
                    } else {
                        alert('An error occurred. Please try again.');
                        console.error(xhr);
                    }
                },
                complete: function() {
                    $this.prop('disabled', false);
                }
            });
        });
    });
</script>
            @endif
        @endif
        </div>
    </div>
</div>

<script src="{{ asset('js/entertainment.min.js') }}" defer></script>
@endsection