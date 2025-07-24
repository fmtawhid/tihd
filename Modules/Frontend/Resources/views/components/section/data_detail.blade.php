<div class="detail-page-info section-spacing">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="movie-detail-content">
                    <div class="row align-items-center mb-3">
                        <div class="col-md-7">
                            <div class="d-flex align-items-center">
                                @if($data['is_restricted'] == 1)
                                    <span class="movie-badge rounded fw-bold font-size-12 px-2 py-1 me-3">{{__('frontend.age_restriction')}}</span>
                                @endif
                                @if(isset($data['genres']) && is_array($data['genres']))
                                  @foreach($data['genres'] as $genre)
                                      <li class="position-relative fw-semibold">{{ $genre['name'] }}</li>
                                  @endforeach
                                @endif
                            </div>
                        </div>
                        @if($data['your_review'] == null)
                            <div class="col-md-5 mt-md-0 mt-4 text-md-end" id="addratingbtn">
                                @if(Auth::check())
                                <button class="btn btn-dark"
                                        data-bs-toggle="modal"
                                        data-bs-target="#rattingModal"
                                        data-entertainment-id="{{ $data['id'] }}">
                                    <span class="d-flex align-items-center justify-content-center gap-2">
                                        <span class="text-warning"><i class="ph-fill ph-star"></i></span>
                                        <span>{{ __('frontend.rate_this') }}</span>
                                    </span>
                                </button>
                                @else
                                    <a href="{{ url('/login') }}" class="btn btn-dark">
                                        <span class="d-flex align-items-center justify-content-center gap-2">
                                            <span class="text-warning"><i class="ph-fill ph-star"></i></span>
                                            <span>{{ __('frontend.rate_this') }}</span>
                                        </span>
                                    </a>
                                @endif
                            </div>
                            {{-- @else
                            @if(Auth::check())
                            <div class="col-md-5 mt-md-0 mt-4 text-md-end d-none"  id="addratingbtn">
                                <button
                                class="btn btn-dark"
                                data-bs-toggle="modal"
                                data-bs-target="#rattingModal"
                                data-entertainment-id="{{ $data['id'] }}">
                                    <span class="d-flex align-items-center justify-content-center gap-2">
                                        <span class="text-warning"><i class="ph-fill ph-star"></i></span>
                                        <span>{{ __('frontend.rate_this') }}</span>
                                    </span>
                                </button>
                            </div>
                            @endif --}}
                        @endif
                    </div>
                    <h4>{{ $data['name'] }}</h4>
                    <p class="font-size-14">{!! $data['description'] !!}</p>
                    <ul class="list-inline mt-4 mb-0 mx-0 p-0 d-flex align-items-center flex-wrap gap-3 movie-metalist">
                        <li>
                            <span class="d-flex align-items-center gap-2">
                                <span class="fw-medium">{{ \Carbon\Carbon::parse($data['release_date'])->format('Y') }}</span>
                            </span>
                        </li>
                        <li>
                            <span class="d-flex align-items-center gap-2">
                                <span><i class="ph ph-translate lh-base"></i></span>
                                <span class="fw-medium">{{ $data['language'] }}</span>
                            </span>
                        </li>
                        <li>
                            <span class="d-flex align-items-center gap-2">
                                <span><i class="ph ph-clock lh-base"></i></span>
                                {{ $data['duration'] ? formatDuration($data['duration']) : '--' }}
                            </span>
                        </li>
                        <li>
                          <!--  @if($data['imdb_rating'])
                                <span class="d-flex align-items-center gap-2">
                                    <span><i class="ph ph-star lh-base"></i></span>
                                    <span class="fw-medium">{{ $data['imdb_rating'] }} (IMDb)</span>
                                </span>
                            @endif -->
                        </li>
                    </ul>
                    @php

                   $qualityOptions = [];
                        if($data['type']=='movie'){
                          $videoLinks = $data['video_links'];
                          $episode_id='';
                          $type=$data['video_upload_type'];
                          $video_url= $data['video_upload_type']=== 'Local' ? $data['video_url_input'] : $data['video_url_input'] ;

                         if($data['enable_quality']==1){

                            foreach($videoLinks as $link) {
                              $qualityOptions[$link->quality] = [
                                  'value' => $link->type === 'Local' ? setBaseUrlWithFileName($link->url) : Crypt::encryptString($link->url),
                                  'type' => $link->type // Add the type here
                              ];
                            }

                         }

                        }else{

                             $episodeData=$data['episodeData'];
                             $videoLinks = $episodeData['video_links'];
                             $episode_id = $episodeData['id'];
                             $type=$episodeData['video_upload_type'];
                             $video_url= $episodeData['video_upload_type']=== 'Local' ? ($episodeData['video_url_input']) : Crypt::encryptString($episodeData['video_url_input']) ;

                          foreach($videoLinks as $link) {
                            $qualityOptions[$link->quality] = [
                                'value' => $link->type === 'Local' ? setBaseUrlWithFileName($link->url) : Crypt::encryptString($link->url),
                                'type' => $link->type // Add the type here
                            ];
                          }


                        }


                        $qualityOptionsJson = json_encode($qualityOptions);

                      @endphp


                    <style>
                        @media (max-width: 400px) {
                            .custom-responsive-wrap {
                                flex-direction: column !important;
                                align-items: flex-start !important;
                            }
                        }
                    </style>
                    <div class="d-flex align-items-center flex-wrap gap-4 mt-5 custom-responsive-wrap">
                        <div class="play-button-wrapper">
                            <button
                            class="btn btn-primary"
                            id="watchNowButton"
                            data-type="{{ $type }}"
                            data-entertainment-id="{{ $data['id'] }}"
                            data-entertainment-type="{{ $data['type'] }}"
                            data-video-url="{{ $video_url }}"
                            data-movie-access="{{ $data['movie_access'] }}"
                            data-plan-id="{{ $data['plan_id'] }}"
                            data-user-id="{{ auth()->id() }}"
                            data-profile-id="{{ getCurrentProfile(auth()->id(),request()) }}"
                            data-episode-id="{{ $episode_id }}"
                            data-first-episode-id="1"
                            data-quality-options= {{ $qualityOptionsJson }}
                        >
                            <span class="d-flex align-items-center justify-content-center gap-2">
                                <span><i class="ph-fill ph-play"></i></span>
                                <span>{{ __('frontend.watch_now') }}</span>
                            </span>
                        </button>

                        </div>
                        <ul class="actions-list list-inline m-0 p-0 d-flex align-items-center flex-wrap gap-3">
                            <li>
                                <x-watchlist-button :entertainment-id="$data['id']" :in-watchlist="$data['is_watch_list']" />
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
        window.successSnackbar('Link copied.');

        document.execCommand("copy");

        document.body.removeChild(tempInput);

        this.style.display = 'none';

        var feedback = document.getElementById('copyFeedback');
        feedback.style.display = 'inline';

        setTimeout(() => {
            feedback.style.display = 'none';
            this.style.display = 'inline';
        }, 1000);
    });
</script>
