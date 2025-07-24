<?php

namespace Modules\Frontend\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MobileSetting;
use Modules\Constant\Models\Constant;
use Modules\Entertainment\Models\Entertainment;
use Modules\Entertainment\Transformers\MoviesResource;
use Illuminate\Support\Facades\Cache;
use Modules\LiveTV\Models\LiveTvChannel;
use Modules\LiveTV\Transformers\LiveTvChannelResource;
use Modules\Entertainment\Transformers\TvshowResource;
use Modules\CastCrew\Models\CastCrew;
use Modules\Genres\Transformers\GenresResource;
use Modules\Genres\Models\Genres;
use Modules\Video\Transformers\VideoResource;
use Modules\Video\Models\Video;
use App\Services\RecommendationService;
use Modules\Entertainment\Models\ContinueWatch;
use Modules\Entertainment\Transformers\ContinueWatchResource;
use Auth;
use Modules\CastCrew\Transformers\CastCrewListResource;

class DashboardController extends Controller
{
  
    protected $recommendationService;


    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;

    }
  



    public function Top10Movies()
    {
        $cacheKey = 'top_10_movie';
        $top_10 = Cache::get($cacheKey);

         $html='';

        if (!$top_10) {
            $top_10=[];
            $topMovieIds = MobileSetting::getValueBySlug('top-10');
            if($topMovieIds != null){
                $topMovies = Entertainment::whereIn('id', json_decode($topMovieIds))->where('status', 1)->get();
                $top_10 = MoviesResource::collection($topMovies);
                $top_10 = $top_10->toArray(request());

              }


            Cache::put($cacheKey, $top_10);
        }

        if(!empty($top_10)){
          $html = view('frontend::components.section.top_10_movie', ['top10' => $top_10])->render();
        }

        return response()->json(['html' => $html]);
    }


    public function LatestMovies()
    {
         $cacheKey = 'latest_movie';
         $latest_movie = Cache::get($cacheKey);
         $html='';
         if(!$latest_movie){
            $latest_movie=[];
            $latestMovieIds = MobileSetting::getValueBySlug('latest-movies');
            if($latestMovieIds != null){
               $latestMovie = Entertainment::whereIn('id',json_decode($latestMovieIds))->where('status',1)->get();
               $latest_movie = MoviesResource::collection($latestMovie);
             }

            Cache::put($cacheKey, $latest_movie);
         }

      if(!empty($latest_movie)){
        $html = view('frontend::components.section.entertainment', ['data' =>  $latest_movie , 'title' =>__('frontend.latest_movie'),'type' => 'movie','slug'=>'latest_movie'] )->render();
       }

        return response()->json(['html' => $html]);
    }

    public function FetchLanguages()
    {
       $cacheKey = 'popular_language';
       $popular_language = Cache::get($cacheKey);

       $html='';

       if(!$popular_language){

         $popular_language=[];

          $languageIds = MobileSetting::getValueBySlug('enjoy-in-your-native-tongue');

         if($languageIds != null){
            $popular_language = Constant::whereIn('id', json_decode($languageIds))->get();
          }

          Cache::put($cacheKey, $popular_language);
       }

       if(!empty($popular_language)){

        $html = view('frontend::components.section.language', ['popular_language' =>  $popular_language , 'title' =>__('frontend.popular_language')]) ->render();

       }

     return response()->json(['html' => $html]);

    }

    public function PopularMovies()
    {
         $cacheKey = 'popular_movie';
         $popular_movie = Cache::get($cacheKey);
         $html='';
         if(!$popular_movie){
            $popular_movie=[];
            $popularMovieIds = MobileSetting::getValueBySlug(slug: 'popular-movies');
            if($popularMovieIds != null){
               $popular_movie = Entertainment::whereIn('id',json_decode($popularMovieIds))->where('status',1)->get();
               $popular_movie = MoviesResource::collection($popular_movie);
             }

            Cache::put($cacheKey, $popular_movie);
         }

      if(!empty($popular_movie)){
        $html = view('frontend::components.section.entertainment', ['data' =>  $popular_movie , 'title' => __('frontend.popular_movie'),'type' => 'movie','slug'=>'popular_movie'])->render();
       }

        return response()->json(['html' => $html]);
    }


    public function TopChannels()
    {
        $cacheKey = 'top_channel';
        $top_channel = Cache::get($cacheKey);

       $html='';

       if(!$top_channel){

         $top_channel=[];

         $channelIds = MobileSetting::getValueBySlug('top-channels');

         if($channelIds != null){
            $channels = LiveTvChannel::whereIn('id',json_decode($channelIds))->where('status',1)->get();
            $top_channel = LiveTvChannelResource::collection($channels);
            $top_channel = $top_channel->toArray(request());
          }
          Cache::put($cacheKey, $top_channel);
       }

       if(!empty($top_channel)){

         $html = view('frontend::components.section.tvchannel',  ['top_channel' => $top_channel,'title' => __('frontend.top_tvchannel')]) ->render();

       }

     return response()->json(['html' => $html]);

    }


    public function PopularTVshows()
    {
         $cacheKey = 'popular_tvshow';
         $popular_tvshow = Cache::get($cacheKey);
         $html='';
         if(!$popular_tvshow){
            $popular_tvshow=[];
            $popular_tvshowIds = MobileSetting::getValueBySlug(slug: 'popular-tvshows');
            if($popular_tvshowIds != null){
               $popular_tvshow = Entertainment::whereIn('id',json_decode($popular_tvshowIds))->where('status',1)->get();
               $popular_tvshow = TvshowResource::collection($popular_tvshow);
             }

            Cache::put($cacheKey, $popular_tvshow);
         }

      if(!empty($popular_tvshow)){
        $html = view('frontend::components.section.entertainment', ['data' =>  $popular_tvshow , 'title' => __('frontend.popular_tvshow'),'type' => 'tvshow','slug'=>'popular_tvshow'])->render();
       }

        return response()->json(['html' => $html]);
    }

    public function favoritePersonality()
    {
         $cacheKey = 'personality';
         $personality = Cache::get($cacheKey);

       $html='';

       if(!$personality){

         $personality=[];

         $castIds =  MobileSetting::getValueBySlug('your-favorite-personality');

         if($castIds != null){
            $casts = CastCrew::whereIn('id',json_decode($castIds))->get();

            $personality = [];
            foreach ($casts as $key => $value) {
                $personality[] = [
                    'id' => $value->id,
                    'name' => $value->name,
                    'type' => $value->type,
                    'profile_image' => setBaseUrlWithFileName($value->file_url),
                ];
            }
          }

          Cache::put($cacheKey, $personality);
       }

       if(!empty($personality)){

        $html = view('frontend::components.section.castcrew',  ['data' => $personality,'title' => __('frontend.personality'),'entertainment_id' => 'all', 'type'=>'actor','slug'=>'favorite_personality']) ->render();

       }

     return response()->json(['html' => $html]);

    }

    public function FreeMovies()
    {
         $cacheKey = 'free_movie';
        $free_movies = Cache::get($cacheKey);

         $html='';
         if(!$free_movies ){
            $free_movies =[];
            $movieIds = MobileSetting::getValueBySlug('500-free-movies');

            if($movieIds != null){
                $free_movies= Entertainment::whereIn('id',json_decode($movieIds))->where('status',1)->get();
                $free_movies = MoviesResource::collection($free_movies);
                $free_movies =  $free_movies->toArray(request());

             }

             Cache::put($cacheKey, $free_movies);

         }

      if(!empty($free_movies)){
        $html = view('frontend::components.section.entertainment',  ['data' => $free_movies,'title' => __('frontend.free_movie'),'type' =>'movie','slug'=>'free_movie'])->render();
       }

        return response()->json(['html' => $html]);
    }

    public function GetGener()
    {
        $cacheKey = 'genres';
        $genres = Cache::get($cacheKey);

       $html='';

       if(!$genres){

         $genres=[];

          $genreIds = MobileSetting::getValueBySlug(slug: 'genre');

         if($genreIds != null){
            $genres = Genres::whereIn('id',json_decode($genreIds))->where('status',1)->get();
            $genres = GenresResource::collection($genres);
            $genres = $genres->toArray(request());
            Cache::put($cacheKey, $genres);
          }

       }

       if(!empty($genres)){

         $html = view('frontend::components.section.geners',  ['genres' => $genres,'title' => __('frontend.genres'),'slug'=>'gener-section']) ->render();

        }

       return response()->json(['html' => $html]);

    }

    public function GetVideo()
    {
        $cacheKey = 'popular_videos';
        $popular_videos = Cache::get($cacheKey);

       $html='';

       if(!$popular_videos){

         $genres=[];

         $videoIds = MobileSetting::getValueBySlug(slug: 'popular-videos');

         if($videoIds != null){
            $popular_videos = Video::whereIn('id',json_decode($videoIds))->where('status',1)->get();
            $popular_videos = VideoResource::collection($popular_videos);
            Cache::put($cacheKey, $popular_videos);
          }

       }

       if(!empty($popular_videos)){

         $html = view('frontend::components.section.video',  ['data' => $popular_videos,'title' => __('frontend.popular_videos')]) ->render();

        }

       return response()->json(['html' => $html]);

    }


    public function  GetLastWatchContent(Request $request){

        $html='';
        $user=Auth::user();
        $profile_id=getCurrentProfile($user->id, $request);

        $based_on_last_watch = $this->recommendationService->recommendByLastHistory($user,$profile_id);

        $Lastwatchrecommendation = MoviesResource::collection($based_on_last_watch );

       if(!empty($Lastwatchrecommendation)){

         $html = view('frontend::components.section.entertainment',  ['data' => $Lastwatchrecommendation,'title' => __('frontend.because_you_watch'),'type' =>'movie','slug'=>'based_on_last_watch'])->render();

       }

      return response()->json(['html' => $html]);

    }

    public function MostLikeMoive(Request $request){

        $user=Auth::user();
        $profile_id=getCurrentProfile($user->id, $request);

        $html='';
        $likedMovies = $this->recommendationService->getLikedMovies($user, $profile_id);
        $likedMovies = MoviesResource::collection($likedMovies);
       if(!empty($likedMovies)){

         $html = view('frontend::components.section.entertainment',  ['data' => $likedMovies,'title' => __('frontend.liked_movie'),'type' =>'movie','slug'=>'most-like'])->render();

       }

      return response()->json(['html' => $html]);

    }



      public function MostviewMoive(Request $request){

        $user=Auth::user();
        $profile_id=getCurrentProfile($user->id, $request);

        $html='';
        $viewedMovies = $this->recommendationService->getEntertainmentViews($user, $profile_id);
        $viewedMovies = MoviesResource::collection($viewedMovies);
       if(!empty($viewedMovies)){

         $html = view('frontend::components.section.entertainment',  ['data' => $viewedMovies,'title' => __('frontend.viewed_movie'),'type' =>'movie','slug'=>'most-view'])->render();

       }

        return response()->json(['html' => $html]);

    }


    public function TrandingInCountry(Request $request){

        $user=Auth::user();
        $profile_id=getCurrentProfile($user->id, $request);

        $html='';
        $trendingMovies = $this->recommendationService->getTrendingMoviesByCountry($user, $request);
        $trendingMovies = MoviesResource::collection($trendingMovies);
       if(!empty($trendingMovies)){

         $html = view('frontend::components.section.entertainment',  ['data' => $trendingMovies,'title' => __('frontend.trending_movies_country'),'type' =>'movie','slug'=>'tranding-in-country'])->render();

       }

      return response()->json(['html' => $html]);

    }

    public function FavoriteGenres(Request $request){

        $user=Auth::user();
        $profile_id=getCurrentProfile($user->id, $request);

        $html='';

        $favorite_gener = $this->recommendationService->getFavoriteGener($user, $profile_id);
        $FavoriteGener = GenresResource::collection($favorite_gener);
        $FavoriteGener = $FavoriteGener->toArray(request());

       if(!empty($FavoriteGener)){

         $html = view('frontend::components.section.geners',  ['genres' => $FavoriteGener,'title' => __('frontend.favroite_geners'),'slug'=>'favorite-genres'])->render();

       }

      return response()->json(['html' => $html]);

    }

    public function UserfavoritePersonality(Request $request){

        $user=Auth::user();
        $profile_id=getCurrentProfile($user->id, $request);

        $html='';

        $favorite_personality = $this->recommendationService->getFavoritePersonality($user, $profile_id);
        $favorite_personality = CastCrewListResource::collection($favorite_personality);
        $favorite_personality = $favorite_personality->toArray(request());

        if(!empty($favorite_personality)){

            $html = view('frontend::components.section.castcrew',  ['data' => $favorite_personality,'title' => __('frontend.favorite_personality'),'entertainment_id' => 'all', 'type'=>'actor' ,'slug'=>'user-favorite_personality']) ->render();

           }
      return response()->json(['html' => $html]);

    }

    public function ContinuewatchList(Request $request){

      $user = auth()->guard('web')->user();
	  dd($user);


        $profile_id=getCurrentProfile($user->id, $request);

        $html='';

        $continueWatchList = ContinueWatch::where('user_id', $user->id)
        ->whereNotNull('watched_time')
        ->whereNotNull('total_watched_time')
        ->where('profile_id', $profile_id)
        ->whereHas('entertainment', function ($query) {
            $query->where('status', 1);
        })
        ->with(['entertainment', 'episode', 'video'])
        ->orderBy('id', 'desc')
        ->get();
         $continue_watch = $continueWatchList->map(function ($item) {
             return new ContinueWatchResource($item);
         })->toArray();

        if(!empty($continue_watch)){

            $html = view('frontend::components.section.continue_watch',  ['continuewatchData' =>  $continue_watch])->render();

           }
      return response()->json(['html' => $html]);

    }

}

























