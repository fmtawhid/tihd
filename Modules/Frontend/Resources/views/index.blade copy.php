@extends('frontend::layouts.master')

@section('content')
    <!-- Main Banner -->
    <div id="banner-section" class="section-spacing-bottom px-0">
        @include('frontend::components.section.banner', ['data' => $data['slider'] ?? []])
    </div>

    <div class="container-fluid padding-right-0">
        <div class="overflow-hidden">
            @if(!empty($user_id) && !empty($data['continue_watch']))
                <!-- Continue Watch -->
                <div id="continue-watch-section" class="section-wraper scroll-section section-hidden">
                    @include('frontend::components.section.continue_watch',  ['continuewatchData' => $data['continue_watch']])
                </div>
            @endif

            <!-- Top 10 Watch -->
            @if(isenablemodule('movie')==1 &&  !empty($data['top_10']))
                <div id="top-10-moive-section" class="section-wraper scroll-section section-hidden">
                    @include('frontend::components.section.top_10_movie',  ['top10' => $data['top_10']])
                </div>
            @endif

            <!-- Latest Movie -->
            @if(isenablemodule('movie')==1 && !empty($data['latest_movie']) && !$data['latest_movie']->isEmpty()  )
                <div id="latest-moive-section" class="section-wraper scroll-section section-hidden">
                    @include('frontend::components.section.entertainment',  ['data' => $data['latest_movie'],'title' =>__('frontend.latest_movie'),'type' => 'movie'])
                </div>
            @endif

            <!-- Native Tongue -->
            @if(!empty($data['popular_language']))
                <div id="language-section" class="section-wraper scroll-section section-hidden">
                    @include('frontend::components.section.language',  ['popular_language' => $data['popular_language'],'title' => __('frontend.popular_language')])
                </div>
            @endif

            <!-- Popular Movies -->
            @if(isenablemodule('movie')==1 && !empty($data['popular_movie']) && !$data['popular_movie']->isEmpty())
                <div class="section-wraper scroll-section section-hidden">
                    @include('frontend::components.section.entertainment',  ['data' => $data['popular_movie'],'title' => __('frontend.popular_movie'),'type' => 'movie'])
                </div>
            @endif

            <!-- Top Channel -->
            @if(isenablemodule('livetv')==1 && !empty($data['top_channel']))
                <div id="topchannel-section" class="section-wraper scroll-section section-hidden">
                    @include('frontend::components.section.tvchannel',  ['top_channel' => $data['top_channel'],'title' => __('frontend.top_tvchannel')])
                </div>
            @endif

            <!-- Popular TVshow -->
            @if(isenablemodule('tvshow')==1 && !empty($data['popular_tvshow']) && !$data['popular_tvshow']->isEmpty())
                <div class="section-wraper scroll-section section-hidden">
                    @include('frontend::components.section.entertainment',  ['data' => $data['popular_tvshow'],'title' => __('frontend.popular_tvshow'),'type' => 'tvshow'])
                </div>
            @endif

            <!-- Favorite Personality -->
            @if(!empty($data['personality']))
                <div id="favorite-personality" class="section-wraper scroll-section section-hidden">
                    @include('frontend::components.section.castcrew',  ['data' => $data['personality'],'title' => __('frontend.personality'),'entertainment_id' => 'all', 'type'=>'actor'])
                </div>
            @endif

            <!-- Free Movie -->
            @if(isenablemodule('movie')==1 && !empty($data['free_movie']) && !$data['free_movie']->isEmpty())
                <div class="section-wraper scroll-section section-hidden">
                    @include('frontend::components.section.entertainment',  ['data' => $data['free_movie'],'title' => __('frontend.free_movie'),'type' =>'movie' ])
                </div>
            @endif

            <!-- Genres -->
            @if(!empty($data['genres']))
                <div id="genres-section" class="section-wraper scroll-section section-hidden">
                    @include('frontend::components.section.geners',  ['genres' => $data['genres'],'title' => __('frontend.genres')])
                </div>
            @endif

            <!-- Videos -->
            @if(isenablemodule('video')==1 && !empty($data['popular_videos'])  && !$data['popular_videos']->isEmpty())
                <div id="video-section" class="section-wraper scroll-section section-hidden">
                    @include('frontend::components.section.video',  ['data' => $data['popular_videos'],'title' => __('frontend.popular_videos')])
                </div>
            @endif

            @if(!empty($data['based_on_last_watch']) && !$data['based_on_last_watch']->isEmpty() && isenablemodule('movie')==1)
                <div class="section-wraper">
                    @include('frontend::components.section.entertainment',  ['data' => $data['based_on_last_watch'],'title' => __('frontend.because_you_watch'),'type' =>'movie' ])
                </div>
            @endif

            <!-- Based on like -->
            @if(!empty($data['likedMovies']) && !$data['likedMovies']->isEmpty() && isenablemodule('movie') == 1)
                <div>
                    @include('frontend::components.section.entertainment',  ['data' => $data['likedMovies'],'title' => __('frontend.liked_movie'),'type' =>'movie' ])
                </div>
            @endif
            <!-- Based on view -->
            @if(!empty($data['viewedMovies']) && !$data['viewedMovies']->isEmpty() && isenablemodule('movie')==1)
                <div>
                    @include('frontend::components.section.entertainment',  ['data' => $data['viewedMovies'],'title' => __('frontend.viewed_movie'),'type' =>'movie' ])
                </div>
            @endif

            @if(!empty($data['trendingMovies']) && !$data['trendingMovies']->isEmpty() && isenablemodule('movie')==1)
                <div>
                    @include('frontend::components.section.entertainment', ['data' => $data['trendingMovies'], 'title' => __('frontend.trending_movies_country'), 'type' => 'movie'])
                </div>
            @endif

            @if(!empty($data['favorite_gener']) )
            <div id="genres-section" class="section-wraper scroll-section section-hidden">
                @include('frontend::components.section.geners',  ['genres' => $data['favorite_gener'],'title' => __('frontend.favroite_geners')])
            </div>
           @endif


           @if(!empty($data['favorite_personality']))
           <div id="favorite-personality" class="section-wraper scroll-section section-hidden">
               @include('frontend::components.section.castcrew',  ['data' => $data['favorite_personality'],'title' => __('frontend.favorite_personality'),'entertainment_id' => 'all', 'type'=>'actor'])
           </div>
       @endif

        </div>
    </div>

@endsection

@push('after-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
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

