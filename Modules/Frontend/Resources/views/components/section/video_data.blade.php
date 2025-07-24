<div class="detail-page-info section-spacing">
    @php

    $qualityOptions = [];

     $videoLinks = $data['video_links'];

     foreach($videoLinks as $link) {
        $qualityOptions[$link->quality] = $link->url;
     }

   $qualityOptionsJson = json_encode($qualityOptions);

 @endphp
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="movie-detail-content">
                    <div class="d-flex align-items-center mb-3">
                        @if($data['is_restricted']==1)
                        <span class="movie-badge rounded fw-bold font-size-12 px-2 py-1 me-3">{{__('frontend.age_restriction')}}</span>
                        @endif
                        {{-- @if(!empty($data['genres']))
                        <ul class="p-0 mb-0 list-inline d-flex flex-wrap align-items-center movie-tags">
                            @foreach($data['genres'] as $gener)
                            <li class="position-relative fw-semibold">{{ $gener['name'] }}</li>
                         @endforeach
                        </ul>
                        @endif --}}
                    </div>
                    <h4>{{ $data['name'] }}</h4>
                    <p class="font-size-14">{!! $data['description'] !!}
                    </p>
                    <ul class="list-inline mt-4 mb-0 mx-0 p-0 d-flex align-items-center flex-wrap gap-3 movie-metalist">
                        <li>
                            <span class="d-flex align-items-center gap-2">
                                <span class="fw-medium">{{ \Carbon\Carbon::parse($data['release_date'])->format('Y') }}</span>
                            </span>
                        </li>
                        {{-- <li>
                            <span class="d-flex align-items-center gap-2">
                                <span><i class="ph ph-translate lh-base"></i></span>
                                <span class="fw-medium">{{ $data['language'] }}</span>
                            </span>
                        </li> --}}
                        <li>
                            <span class="d-flex align-items-center gap-2">
                                <span><i class="ph ph-clock lh-base"></i></span>
                                {{ $data['duration'] ? formatDuration($data['duration']) : '--' }}
                            </span>
                        </li>
                        <li>
                            @if($data['imdb_rating'] )
                            <span class="d-flex align-items-center gap-2">
                                <span><i class="ph ph-star lh-base"></i></span>
                                <span class="fw-medium">{{ $data['imdb_rating'] }} (IMDb)</span>
                            </span>
                            @endif
                        </li>
                    </ul>
                    <div class="d-flex align-items-center flex-wrap gap-4 mt-5">
                        <div class="play-button-wrapper">
                            <button
                            class="btn btn-primary"
                            id="watchNowButton"
                            data-entertainment-id="{{ $data['id'] }}"
                            data-entertainment-type="{{ $data['type'] }}"
                            data-video-url="{{ $data['video_url_input'] }}"
                            data-movie-access="{{ $data['access'] }}"
                            data-plan-id="{{ $data['plan_id'] }}"
                            data-user-id="{{ auth()->id() }}"
                            data-profile-id="{{ getCurrentProfile(auth()->id(),request()) }}"
                            data-quality-options= {{ $qualityOptionsJson }}>
                                <span class="d-flex align-items-center justify-content-center gap-2">
                                    <span><i class="ph-fill ph-play"></i></span>
                                    <span>{{ __('frontend.watch_now') }}</span>
                                </span>
                            </button>
                        </div>
                        <ul class="actions-list list-inline m-0 p-0 d-flex align-items-center flex-wrap gap-3">
                            <li>
                                <x-watchlist-button :entertainment-id="$data['id']" :in-watchlist="$data['is_watch_list']" :entertainmentType="$entertainmentType"  />
                            </li>
                            <li class="position-relative share-button dropend dropdown">
                                <button type="button" class="action-btn btn btn-dark" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ph ph-share-network"></i>
                                </button>
                                <div class="share-wrapper">
                                    <div class="share-box dropdown-menu">
                                        <svg width="15" height="40" viewBox="0 0 15 40" class="share-shape" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M14.8842 40C6.82983 37.2868 1 29.3582 1 20C1 10.6418 6.82983 2.71323 14.8842 0H0V40H14.8842Z" fill="currentColor"></path>
                                        </svg>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <a href="https://www.facebook.com/sharer?u={{ urlencode(Request::url()) }}" target="_blank" rel="noopener noreferrer" class="share-ico"><i class="ph ph-facebook-logo"></i></a>
                                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($data['name']) }}&url={{ urlencode(Request::url()) }}" target="_blank" rel="noopener noreferrer" class="share-ico"><i class="ph ph-x-logo"></i></a>
                                            <a href="#" data-link="{{ Request::url() }}" class="share-ico iq-copy-link" id="copyLink"><i class="ph ph-link"></i></a>

                                            <span id="copyFeedback" style="display: none; margin-left: 10px;">{{ __('frontend.copied') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <!-- <li>
                                <button class="action-btn btn btn-dark">
                                    <i class="ph ph-download-simple"></i>
                                </button>
                            </li> -->
                            <li>
                            <x-like-button :entertainmentId="$data['id']" :isLiked="$data['is_likes']" :type="$data['type']"/>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            {{-- @if($data['your_review']== null)
            <div class="col-lg-6 mt-lg-0 mt-4 text-lg-end">
                <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#rattingModal">
                    <span class="d-flex align-items-center justify-content-center gap-2">
                        <span class="text-warning"><i class="ph-fill ph-star"></i></span>
                        <span>{{ __('frontend.rate_this') }}</span>
                    </span>
                </button>
            </div>
            @endif --}}
        </div>
    </div>
</div>

<script>
    document.getElementById('copyLink').addEventListener('click', function (e) {
        e.preventDefault();

        var url = this.getAttribute('data-link');

        var tempInput = document.createElement('input');
        tempInput.value = url;
        document.body.appendChild(tempInput);
        tempInput.select();
        tempInput.setSelectionRange(0, 99999);

        document.execCommand("copy");

        document.body.removeChild(tempInput);

        this.style.display = 'none';
        window.successSnackbar('Link copied.');
        var feedback = document.getElementById('copyFeedback');
        feedback.style.display = 'inline';

        setTimeout(() => {
            feedback.style.display = 'none';
            this.style.display = 'inline';
        }, 1000);
    });
</script>
