<?php

namespace Modules\Entertainment\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Entertainment\Models\Entertainment;
use Modules\Entertainment\Transformers\MoviesResource;
use Modules\Entertainment\Transformers\MovieDetailDataResource;
use Modules\Entertainment\Transformers\TvshowResource;
use Modules\Entertainment\Transformers\TvshowDetailResource;
use Modules\Entertainment\Models\Watchlist;
use Modules\Entertainment\Models\Like;
use Modules\Entertainment\Models\EntertainmentDownload;
use Modules\Episode\Models\Episode;
use Modules\Entertainment\Transformers\EpisodeResource;
use Modules\Entertainment\Transformers\EpisodeDetailResource;
use Modules\Entertainment\Transformers\SearchResource;
use Modules\Entertainment\Transformers\ComingSoonResource;
use Carbon\Carbon;
use Modules\Entertainment\Models\UserReminder;
use Modules\Entertainment\Models\EntertainmentView;
use Modules\Entertainment\Models\ContinueWatch;
use Illuminate\Support\Facades\Cache;
use Modules\Genres\Models\Genres;
use Modules\Video\Models\Video;
use Modules\Video\Transformers\VideoResource;
use Illuminate\Support\Facades\Auth;
use App\Models\UserSearchHistory;
use Modules\Season\Models\Season;
use Modules\Entertainment\Transformers\SeasonResource;
use Modules\CastCrew\Models\CastCrew;
use Modules\CastCrew\Transformers\CastCrewListResource;



class EntertainmentsController extends Controller
{
    public function movieList(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $movieList = Entertainment::where('status', 1);
        if (empty($request->language) && empty($request->genre_id )  && empty($request->actor_id )) {
            $movieList = $movieList->where('type','movie');
        }
        $movieList = $movieList->where('status', 1)
        ->whereDate('release_date', '<=', Carbon::now())  // Check release date is less than current date
        ->with([
            'entertainmentGenerMappings',
            'plan',
            'entertainmentReviews',
            'entertainmentTalentMappings',
            'entertainmentStreamContentMappings',
            'entertainmentDownloadMappings'
        ]);

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $movieList->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', "%{$searchTerm}%");
            });
        }
        if ($request->filled('genre_id')) {
            $genreId = $request->genre_id;
            $movieList->whereHas('entertainmentGenerMappings', function ($query) use ($genreId) {
                $query->where('genre_id', $genreId);
            });
        }
        if ($request->filled('actor_id')) {

            $actorId = $request->actor_id;

            $isMovieModuleEnabled = isenablemodule('movie');
            $isTVShowModuleEnabled = isenablemodule('tvshow');

            $movies = $movieList->where(function ($query) use ($actorId, $isMovieModuleEnabled, $isTVShowModuleEnabled) {
                if ($isMovieModuleEnabled && $isTVShowModuleEnabled) {

                    $query->where('type', 'movie')
                          ->orWhere('type', 'tvshow');
                } elseif ($isMovieModuleEnabled) {
                    $query->where('type', 'movie');
                } elseif ($isTVShowModuleEnabled) {
                    $query->where('type', 'tvshow');
                }
            })
            ->whereHas('entertainmentTalentMappings', function ($query) use ($actorId) {
                $query->where('talent_id', $actorId);
            });
        }
        if ($request->filled('language')) {
            $movieList->where('language', $request->language);
        }
        $movies = $movieList->orderBy('id', 'desc')->paginate($perPage);
        $responseData = MoviesResource::collection($movies);

        if ($request->has('is_ajax') && $request->is_ajax == 1) {
            $html = '';
            foreach ($responseData->toArray($request) as $movieData) {

             if(isenablemodule($movieData['type'])==1){

                $userId = auth()->id();
                if($userId) {
                    $isInWatchList = WatchList::where('entertainment_id', $movieData['id'])
                    ->where('user_id', $userId)
                    ->exists();

                $movieData['is_watch_list'] = $isInWatchList ? true : false;

                }
                $html .= view('frontend::components.card.card_entertainment', ['value' => $movieData])->render();

             }

            }

            $hasMore = $movies->hasMorePages();

            return response()->json([
                'status' => true,
                'html' => $html,
                'message' => __('movie.movie_list'),
                'hasMore' => $hasMore,
            ], 200);
        }

        return response()->json([
            'status' => true,
            'data' => $responseData,
            'message' => __('movie.movie_list'),
        ], 200);
    }

    public function movieDetails(Request $request)
    {

        $movieId = $request->movie_id;

        $cacheKey = 'movie_' . $movieId . '_'.$request->profile_id;

        $responseData = Cache::get($cacheKey);

        if (!$responseData) {

            $movie = Entertainment::where('id', $movieId)->with('entertainmentGenerMappings', 'plan', 'entertainmentReviews', 'entertainmentTalentMappings', 'entertainmentStreamContentMappings', 'entertainmentDownloadMappings')->first();
            $movie['reviews'] = $movie->entertainmentReviews ?? null;

            if ($request->has('user_id')) {

                $user_id = $request->user_id;
                $movie['is_watch_list'] = WatchList::where('entertainment_id', $movieId)->where('user_id', $user_id)->where('profile_id', $request->profile_id)->exists();
                $movie['is_likes'] = Like::where('entertainment_id', $movieId)->where('user_id', $user_id)->where('profile_id', $request->profile_id)->where('is_like', 1)->exists();
                $movie['is_download'] = EntertainmentDownload::where('entertainment_id', $movieId)->where('device_id',$request->device_id)->where('user_id', $user_id)
                ->where('entertainment_type', 'movie')->where('is_download', 1)->exists();
                $movie['your_review'] = $movie->entertainmentReviews ? optional($movie->entertainmentReviews)->where('user_id', $user_id)->first() : null;

                if ($movie['your_review']) {
                    $movie['reviews'] = $movie['reviews']->where('user_id', '!=', $user_id);
                }

                $continueWatch = ContinueWatch::where('entertainment_id', $movie->id)->where('user_id', $user_id)->where('profile_id', $request->profile_id)->where('entertainment_type', 'movie')->first();
                $movie['continue_watch'] = $continueWatch;
            }
            $responseData = new MovieDetailDataResource($movie);
            Cache::put($cacheKey, $responseData);
        }


        return response()->json([
            'status' => true,
            'data' => $responseData,
            'message' => __('movie.movie_details'),
        ], 200);
    }

    public function tvshowList(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $tvshowList = Entertainment::query()
        ->with('entertainmentGenerMappings', 'plan', 'entertainmentReviews', 'entertainmentTalentMappings', 'season', 'episode')
        ->where('type', 'tvshow')
        ->whereDate('release_date', '<=', Carbon::now())
        ->whereHas('episode');

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $tvshowList->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', "%{$searchTerm}%");
            });
        }

        $tvshowList = $tvshowList->where('status', 1);

        $tvshows = $tvshowList->orderBy('id', 'desc');
        $tvshows = $tvshows->paginate($perPage);

        $responseData = TvshowResource::collection($tvshows);


        if ($request->has('is_ajax') && $request->is_ajax == 1) {
            $html = '';

            foreach($responseData->toArray($request) as $tvShowData) {
                $userId = auth()->id();
                if($userId) {
                    $isInWatchList = WatchList::where('entertainment_id', $tvShowData['id'])
                    ->where('user_id', $userId)
                    ->exists();

                // Set the flag in the movie data
                $tvShowData['is_watch_list'] = $isInWatchList ? true : false;
                }
                $html .= view('frontend::components.card.card_entertainment', ['value' => $tvShowData])->render();
            }

            $hasMore = $tvshows->hasMorePages();

            return response()->json([
                'status' => true,
                'html' => $html,
                'message' => __('movie.tvshow_list'),
                'hasMore' => $hasMore,
            ], 200);
        }


        return response()->json([
            'status' => true,
            'data' => $responseData,
            'message' => __('movie.tvshow_list'),
        ], 200);
    }

    public function tvshowDetails(Request $request)
    {

        $tvshow_id = $request->tvshow_id;

        $cacheKey = 'tvshow_' . $tvshow_id . '_' . $request->profile_id;

        $responseData = Cache::get($cacheKey);

        if (!$responseData) {

            $tvshow = Entertainment::where('id', $tvshow_id)->with('entertainmentGenerMappings', 'plan', 'entertainmentReviews', 'entertainmentTalentMappings', 'season', 'episode')->first();
            $tvshow['reviews'] = $tvshow->entertainmentReviews ?? null;

            if ($request->has('user_id')) {
                $user_id = $request->user_id;
                $tvshow['user_id'] = $user_id;
                $tvshow['is_watch_list'] = WatchList::where('entertainment_id', $request->tvshow_id)->where('user_id', $user_id)->where('profile_id', $request->profile_id)->exists();
                $tvshow['is_likes'] = Like::where('entertainment_id', $request->tvshow_id)->where('user_id', $user_id)->where('profile_id', $request->profile_id)->where('is_like', 1)->exists();
                $tvshow['your_review'] =  $tvshow->entertainmentReviews ? $tvshow->entertainmentReviews->where('user_id', $user_id)->first() :null;

                if ($tvshow['your_review']) {
                    $tvshow['reviews'] = $tvshow['reviews']->where('user_id', '!=', $user_id);
                }
            }

            $responseData = new TvshowDetailResource($tvshow);
            Cache::put($cacheKey, $responseData);
        }

        return response()->json([
            'status' => true,
            'data' => $responseData,
            'message' => __('movie.tvshow_details'),
        ], 200);
    }

    public function saveDownload(Request $request)
    {
        $user = auth()->user();
        $download_data = $request->all();
        $download_data['user_id'] = $user->id;

        $download = EntertainmentDownload::where('entertainment_id', $request->entertainment_id)->where('user_id', $user->id)->where('entertainment_type', $request->entertainment_type)->first();

        if (!$download) {
            $result = EntertainmentDownload::create($download_data);

            if ($request->entertainment_type == 'movie') {

                Cache::flush();

            } else if ($request->entertainment_type == 'episode') {
                Cache::flush();

            }

            return response()->json(['status' => true, 'message' => __('movie.movie_download')]);
        } else {
            return response()->json(['status' => true, 'message' => __('movie.already_download')]);
        }
    }

    public function episodeList(Request $request)
    {

        $perPage = $request->input('per_page', 10);
        $user_id = $request->user_id;
        $episodeList = Episode::where('status', 1)->with('entertainmentdata', 'plan', 'EpisodeStreamContentMapping', 'episodeDownloadMappings');

        if ($request->has('tvshow_id')) {
            $episodeList = $episodeList->where('entertainment_id', $request->tvshow_id);
        }
        if ($request->has('season_id')) {
            $episodeList = $episodeList->where('season_id', $request->season_id);
        }

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $episodeList->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', "%{$searchTerm}%");
            });
        }

        $episodes = $episodeList->orderBy('id', 'asc')->paginate($perPage);

        $responseData = EpisodeResource::collection(
            $episodes->map(function ($episode) use ($user_id) {
                return new EpisodeResource($episode, $user_id);
            })
        );


        if ($request->has('is_ajax') && $request->is_ajax == 1) {
            $html = '';

            foreach ($responseData->toArray($request) as $index => $value) {
                $html .= view('frontend::components.card.card_episode', [
                    'data' => $value,
                    'index' => $index
                ])->render();
            }

            $hasMore = $episodes->hasMorePages();

            return response()->json([
                'status' => true,
                'html' => $html,
                'message' => __('movie.episode_list'),
                'hasMore' => $hasMore,
            ], 200);
        }


        return response()->json([
            'status' => true,
            'data' => $responseData,
            'message' => __('movie.episode_list'),
        ], 200);
    }

    public function episodeDetails(Request $request)
    {
        $user_id = $request->user_id;
        $episode_id = $request->episode_id;

        $cacheKey = 'episode_' . $episode_id .'_'.$request->profile_id;

        $responseData = Cache::get($cacheKey);

        if (!$responseData) {
            $episode = Episode::where('id', $episode_id)->with('entertainmentdata', 'plan', 'EpisodeStreamContentMapping', 'episodeDownloadMappings')->first();

            if ($request->has('user_id')) {
                $continueWatch = ContinueWatch::where('entertainment_id', $episode->id)->where('user_id', $user_id)->where('profile_id', $request->profile_id)->where('entertainment_type', 'episode')->first();
                $episode['continue_watch'] = $continueWatch;

                $episode['is_download'] = EntertainmentDownload::where('entertainment_id', $episode->id)->where('user_id',  $user_id)->where('entertainment_type', 'episode')->where('is_download', 1)->exists();

                $genre_ids = $episode->entertainmentData->entertainmentGenerMappings->pluck('genre_id');

                $episode['moreItems'] = Entertainment::where('type', 'tvshow')
                    ->whereHas('entertainmentGenerMappings', function ($query) use ($genre_ids) {
                        $query->whereIn('genre_id', $genre_ids);
                    })
                    ->where('id', '!=', $episode->id)
                    ->orderBy('id', 'desc')
                    ->get();

                $episode['genre_data'] = Genres::whereIn('id', $genre_ids)->get();
            }

            $responseData = new EpisodeDetailResource($episode);
            Cache::put($cacheKey, $responseData);
        }

        return response()->json([
            'status' => true,
            'data' => $responseData,
            'message' => __('movie.episode_details'),
        ], 200);
    }

    public function searchList(Request $request)
    {

        $perPage = $request->input('per_page', 10);
        $movieList = Entertainment::query()->with('entertainmentGenerMappings', 'plan', 'entertainmentReviews', 'entertainmentTalentMappings', 'entertainmentStreamContentMappings')->where('type', 'movie');

        $movieList = $movieList->where('status', 1);

        $movies = $movieList->orderBy('updated_at', 'desc');
        $movies = $movies->paginate($perPage);

        $responseData = new SearchResource($movies);
        if(isenablemodule('movie') == 1){
            $responseData = $responseData;

        }else{
            $responseData = [];
        }

        return response()->json([
            'status' => true,
            'data' => $responseData,
            'message' => __('movie.search_list'),
        ], 200);
    }

    public function getSearch(Request $request)
    {

        $movieList = Entertainment::query()->whereDate('release_date', '<=', Carbon::now())->with('entertainmentGenerMappings', 'plan', 'entertainmentReviews', 'entertainmentTalentMappings', 'entertainmentStreamContentMappings')->where('type', 'movie')->where('status', 1);

        if ($request->has('search') && $request->search !='') {

            $searchTerm = $request->search;

            if (strtolower($searchTerm) == 'movie' || strtolower($searchTerm) == 'movies') {
                $movieList->where('type', 'movie');
            } else {

                $movieList->where('name', 'like', "%{$searchTerm}%")
                    ->orWhereHas('entertainmentGenerMappings.genre', function ($query) use ($searchTerm) {
                        $query->where('name', '=', "%{$searchTerm}%");
                    });
            }

        }

        $movieList = $movieList->orderBy('updated_at', 'desc')->get();


        $movieData = (isenablemodule('movie') == 1) ? MoviesResource::collection($movieList) : [];
        $tvshowList = Entertainment::where('status', 1)->where('type', 'tvshow')->whereDate('release_date', '<=', Carbon::now())->with('entertainmentGenerMappings', 'plan', 'entertainmentReviews', 'entertainmentTalentMappings', 'season', 'episode')->whereHas('episode');

        if ($request->has('search') && $request->search !='') {

            $searchTerm = $request->search;
            $tvshowList->where('name', 'like', "%{$searchTerm}%")
            ->orWhereHas('entertainmentGenerMappings.genre', function ($query) use ($searchTerm) {
                $query->where('name', '=', "%{$searchTerm}%");
            });
        }

        $tvshowList = $tvshowList->orderBy('updated_at', 'desc')->where('type', 'tvshow')->get();
        $tvshowData = (isenablemodule('tvshow') == 1) ? TvshowResource::collection($tvshowList) : [];


        $videoList = Video::query()->whereDate('release_date', '<=', Carbon::now())->with('VideoStreamContentMappings', 'plan');

        if ($request->has('search') && $request->search !='') {

            $searchTerm = $request->search;
            $videoList->where('name', 'like', "%{$searchTerm}%");
        }

        $videoList = $videoList->where('status', 1)->orderBy('updated_at', 'desc')->take(6)->get();
        $videoData = (isenablemodule('video') == 1) ? VideoResource::collection($videoList) : [];


        $seasonList = Season::query()->with('episodes');

        if ($request->has('search') && $request->search !='') {

            $searchTerm = $request->search;
            $seasonList->where('name', 'like', "%{$searchTerm}%");
        }

        $seasonList = $seasonList->where('status', 1)->orderBy('updated_at', 'desc')->get();
        $seasonData = (isenablemodule('tvshow') == 1) ? SeasonResource::collection($seasonList) : [];


        $episodeList = Episode::query()->whereDate('release_date', '<=', Carbon::now())->with('seasondata');

        if ($request->has('search') && $request->search !='') {

            $searchTerm = $request->search;
            $episodeList->where('name', 'like', "%{$searchTerm}%");
        }

        $episodeList = $episodeList->where('status', 1)->orderBy('updated_at', 'desc')->get();
        $episodeData = (isenablemodule('tvshow') == 1) ? EpisodeResource::collection($episodeList) : [];


        $actorList = CastCrew::query()->where('type', 'actor')->with('entertainmentTalentMappings');

        if ($request->has('search') && $request->search !='') {

            $searchTerm = $request->search;
            $actorList->where('name', 'like', "%{$searchTerm}%");
        }

        $actorList = $actorList->orderBy('updated_at', 'desc')->get();
        $actorData = CastCrewListResource::collection($actorList);


        $directorList = CastCrew::query()->where('type', 'director')->with('entertainmentTalentMappings');

        if ($request->has('search') && $request->search !='') {

            $searchTerm = $request->search;
            $directorList->where('name', 'like', "%{$searchTerm}%");
        }

        $directorList = $directorList->orderBy('updated_at', 'desc')->take(6)->get();
        $directorData = CastCrewListResource::collection($directorList);



        if ($request->has('is_ajax') && $request->is_ajax == 1) {

            $html = '';

            if($movieData && $movieData->isNotEmpty()) {

                foreach ($movieData->toArray($request) as $index => $value) {

                    $html .= view('frontend::components.card.card_entertainment', [
                        'value' => $value,
                        'index' => $index,
                        'is_search'=>1,
                    ])->render();
                }
            }
            if ($tvshowData && $tvshowData->isNotEmpty()) {

                foreach ($tvshowData->toArray($request) as $index => $value) {
                    $html .= view('frontend::components.card.card_entertainment', [
                        'value' => $value,
                        'index' => $index,
                        'is_search'=>1,
                    ])->render();
                }
            }
            if ($videoData && $videoData->isNotEmpty()) {

                foreach ($videoData->toArray($request) as $index => $value) {
                    $html .= view('frontend::components.card.card_video', [
                        'data' => $value,
                        'index' => $index,
                        'is_search'=>1,
                    ])->render();
                }
            }
            if ($seasonData && $seasonData->isNotEmpty()) {

                foreach ($seasonData->toArray($request) as $index => $value) {
                    $html .= view('frontend::components.card.card_season', [
                        'value' => $value,
                        'index' => $index,
                        'is_search'=>1,
                    ])->render();
                }
            }
            if ($episodeData && $episodeData->isNotEmpty()) {

                foreach ($episodeData->toArray($request) as $index => $value) {
                    $html .= view('frontend::components.card.card_season', [
                        'value' => $value,
                        'index' => $index,
                        'is_search'=>1,
                    ])->render();
                }
            }
            if ($actorData && $actorData->isNotEmpty()) {

                foreach ($actorData->toArray($request) as $index => $value) {
                    $html .= view('frontend::components.card.card_castcrew', [
                        'data' => $value,
                        'index' => $index,
                        'is_search'=>1,
                    ])->render();
                }
            }
            if ($directorData && $directorData->isNotEmpty()) {

                foreach ($directorData->toArray($request) as $index => $value) {
                    $html .= view('frontend::components.card.card_castcrew', [
                        'data' => $value,
                        'index' => $index,
                        'is_search'=>1,
                    ])->render();
                }
            }

            if (empty($movieData) && empty($tvshowData) && empty($videoData) && empty($seasonData) && empty($episodeData) && empty($actorData) && empty($directorData)) {
                $html .= '';
            }


            return response()->json([
                'status' => true,
                'html' => $html,
                'message' => __('movie.search_list'),

            ], 200);
        }

        return response()->json([
            'status' => true,
            'movieList' => $movieData,
            'tvshowList' => $tvshowData,
            'videoList' => $videoData,
            'seasonList' => $seasonData,
            'message' => __('movie.search_list'),
        ], 200);
    }


    public function comingSoon(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $todayDate = Carbon::today()->toDateString();

        $entertainmentList = Entertainment::where('release_date', '>', $todayDate)->where('status', 1)
        ->with([
            'UserReminder' => function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
            },
            'entertainmentGenerMappings',
            'plan',
            'entertainmentReviews',
            'entertainmentTalentMappings',
            'entertainmentStreamContentMappings',
            'season'

        ]);

        $entertainment = $entertainmentList->paginate($perPage);

        $responseData = ComingSoonResource::collection($entertainment);

        if ($request->has('is_ajax') && $request->is_ajax == 1) {
            $html = '';

            $entertainmentList->when(Auth::check(), function ($query) {
                $query->with(['UserRemind' => function ($query) {
                    $query->where('user_id', Auth::id());
                }]);
            })->get();
            $entertainment = $entertainmentList->paginate($perPage);
            $responseData = ComingSoonResource::collection($entertainment);

            foreach ($responseData->toArray($request) as $comingSoonData) {

               if(isenablemodule( $comingSoonData['type'])==1){

                $html .= view('frontend::components.card.card_comingsoon', ['data' => $comingSoonData])->render();

               }

            }

            $hasMore = $entertainment->hasMorePages();

            return response()->json([
                'status' => true,
                'html' => $html,
                'message' => __('movie.coming_soon_list'),
                'hasMore' => $hasMore,
            ], 200);
        }

        return response()->json([
            'status' => true,
            'data' => $responseData,
            'message' => __('movie.coming_soon_list'),
        ], 200);
    }

    public function saveReminder(Request $request)
    {
        $user = auth()->user();
        $reminderData = $request->all();
        $reminderData['user_id'] = $user->id;

        $profile_id=$request->has('profile_id') && $request->profile_id
        ? $request->profile_id
        : getCurrentProfile($user->id, $request);

        $reminderData['profile_id'] = $profile_id;



        $entertainment = $request->entertainment_id ? Entertainment::where('id', $request->entertainment_id)->first() : null;
        if($entertainment != null){
            $reminderData['release_date'] = $request->release_date ?? $entertainment->release_date;
        }


        $reminders = UserReminder::updateOrCreate(
            ['entertainment_id' => $request->entertainment_id, 'user_id' => $user->id, 'profile_id'=>$profile_id],
            $reminderData
        );

        Cache::flush();

        $message = $reminders->wasRecentlyCreated ? __('movie.reminder_add') : __('movie.reminder_update');
        $result = $reminders;

        return response()->json(['status' => true, 'message' => $message]);
    }

    public function saveEntertainmentViews(Request $request)
    {
        $user = auth()->user();
        $data = $request->all();
        $data['user_id'] = $user->id;
        $viewData = EntertainmentView::where('entertainment_id', $request->entertainment_id)->where('user_id', $user->id)->first();

        Cache::flush();

        if (!$viewData) {
            $views = EntertainmentView::create($data);
            $message = __('movie.view_add');
        } else {
            $message = __('movie.already_added');
        }

        return response()->json(['status' => true, 'message' => $message]);
    }
    public function deleteReminder(Request $request)
    {
        $user = auth()->user();

        $ids = $request->is_ajax == 1 ? $request->id : explode(',', $request->id);

        $entertainment = Entertainment::whereIn('id',$ids)->get();

        $reminders = UserReminder::whereIn('entertainment_id', $ids)->where('user_id', $user->id)->forceDelete();

        Cache::flush();

        if ($reminders == null) {

            $message = __('movie.reminder_add');

            return response()->json(['status' => false, 'message' => $message]);
        }

        $message = __('movie.reminder_remove');


        return response()->json(['status' => true, 'message' => $message]);
    }
    public function deleteDownload(Request $request)
    {
        $user = auth()->user();

        $ids = explode(',', $request->id);

        $download = EntertainmentDownload::whereIn('id', $ids)->forceDelete();

        Cache::flush();

        if ($download == null) {

            $message = __('movie.download');

            return response()->json(['status' => false, 'message' => $message]);
        }

        $message = __('movie.download');


        return response()->json(['status' => true, 'message' => $message]);
    }
}
