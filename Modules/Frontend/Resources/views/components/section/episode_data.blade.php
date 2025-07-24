<div class="detail-page-info section-spacing">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="movie-detail-content">
                    <div class="d-flex align-items-center mb-3">
                        @if($data['is_restricted']==1)
                        <span class="movie-badge rounded fw-bold font-size-12 px-2 py-1 me-3">{{__('frontend.age_restriction')}}</span>
                        @endif
                        <ul class="p-0 mb-0 list-inline d-flex flex-wrap align-items-center movie-tags">
                            @foreach($data['genres'] as $gener)
                            <li class="position-relative fw-semibold">{{ $gener['name'] }}</li>
                         @endforeach
                        </ul>
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
                            @if($data['imdb_rating'] )
                            <span class="d-flex align-items-center gap-2">
                                <span><i class="ph ph-star lh-base"></i></span>
                                <span class="fw-medium">{{ $data['imdb_rating'] }} (IMDb)</span>
                            </span>
                            @endif
                        </li>
                        <li>
                            @if($data['content_rating'])
                                <span class="d-flex align-items-center gap-2">
                                    <span><i class="ph ph-star lh-base"></i></span>
                                    <span class="fw-medium">{{ $data['content_rating'] }}</span>
                                </span>
                            @endif
                        </li>
                    </ul>

                    @php

                    $qualityOptions = [];

                    if($data['enable_quality']==1){

                     $videoLinks = $data['video_links'];

                     foreach($videoLinks as $link) {
                            $qualityOptions[$link->quality] = [
                                'value' => $link->type === 'Local' ? setBaseUrlWithFileName($link->url) : Crypt::encryptString($link->url),
                                'type' => $link->type // Add the type here
                            ];
                          }


                }

                $qualityOptionsJson = json_encode($qualityOptions);




                 @endphp

                    <div class="d-flex align-items-center flex-wrap gap-4 mt-5">
                        <div class="play-button-wrapper">
                            <button
                                class="btn btn-primary"
                                id="watchNowButton"
                                data-entertainment-id="{{ $data['entertainment_id'] }}"
                                data-entertainment-type="tvshow"
                                data-type="{{ $data['video_upload_type'] }}"
                                data-video-url="{{ $data['video_url_input'] }}"
                                data-movie-access="{{ $data['access'] }}"
                                data-plan-id="{{ $data['plan_id'] }}"
                                data-user-id="{{ auth()->id() }}"
                                data-profile-id="{{ getCurrentProfile(auth()->id(),request()) }}"
                                data-episode-id="{{ $data['id'] }}"
                                data-first-episode-id="1"
                                data-quality-options= {{ $qualityOptionsJson }}
                            >
                                <span class="d-flex align-items-center justify-content-center gap-2">
                                    <span><i class="ph-fill ph-play"></i></span>
                                    <span>{{ __('frontend.watch_now') }}</span>
                                </span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
