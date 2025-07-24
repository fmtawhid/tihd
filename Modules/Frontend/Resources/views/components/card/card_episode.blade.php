<div class="season-card p-4 rounded-3">
    <div class="d-flex flex-sm-row flex-column gap-5">
        <div class="season-image flex-shrink-0">

            <img src="{{ $data['poster_image'] }}" alt="movie image" class="object-fit-cover rounded">

            @php

                  $qualityOptions = [];

                   $videoLinks = $data['video_links'];

                   foreach($videoLinks as $link) {
                      $qualityOptions[$link->quality] = $link->url;
                   }

                 $qualityOptionsJson = json_encode($qualityOptions);

               @endphp


            <button class="season-watch-btn" id="seasonWatchBtn_{{  $data['id'] }}"
                            data-entertainment-id="{{ $data['entertainment_id'] }}"
                            data-entertainment-type="tvshow"
                            data-video-url="{{ Crypt::encryptString($data['video_url_input']) }}"
                            data-movie-access="{{ $data['access'] }}"
                            data-plan-id="{{ $data['plan_id'] }}"
                            data-user-id="{{ auth()->id() }}"
                            data-profile-id="{{ getCurrentProfile(auth()->id(),request()) }}"
                            data-episode-id="{{ $data['id'] }}"
                            data-first-episode-id="{{ $index+1 }}"
                            data-quality-options={{ $qualityOptionsJson }}>
                <span class="d-flex align-items-center justify-content-center gap-2">
                    <span><i class="ph-fill ph-play"></i></span>
                    {{__('frontend.watch_now')}}
                </span>
            </button>
        </div>
        <div class="season-content flex-grow-1">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0">{{  $data['name'] }}</h5>
            </div>
            <ul class="list-inline mt-3 mb-3 mx-0 p-0 d-flex align-items-center season-meta-list flex-wrap">
                <li class="season-meta-list-item">
                    <span class="season-meta">E{{ $index+1 }}</span>
                </li>
                <li class="season-meta-list-item">

                    {{ $data['duration'] ? formatDuration($data['duration']) : '--' }}

                </li>
                <li class="season-meta-list-item">
                    <span class="season-meta">{{  $data['release_date'] ? formatDate($data['release_date']) : '-' }}</span>
                </li>
            </ul>
            <p class="mt-0 mb-3 font-size-14">
               {!! $data['description'] !!}
            </p>
            <a href="{{ route('episode-details', ['id' => $data['id']]) }} " class="fw-semibold">{{__('frontend.view_more_info')}}</a>
        </div>
    </div>
</div>
