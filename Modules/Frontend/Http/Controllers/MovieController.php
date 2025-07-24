<?php

namespace Modules\Frontend\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Entertainment\Models\ContinueWatch;
use Modules\Entertainment\Models\Entertainment;
use Modules\Entertainment\Transformers\MovieDetailResource;
use Modules\Entertainment\Models\Watchlist;
use Modules\Entertainment\Models\Like;
use Illuminate\Support\Facades\Cache;
use Modules\Entertainment\Models\EntertainmentDownload;
use App\Models\UserWatchHistory;
use Modules\LiveTV\Models\LiveTvCategory;
use Modules\LiveTV\Models\LiveTvChannel;
use Modules\LiveTV\Transformers\LiveTvCategoryResource;
use Modules\LiveTV\Transformers\LiveTvChannelResource;
use Modules\Entertainment\Transformers\ComingSoonResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Modules\Subscriptions\Models\Subscription;
use Modules\Genres\Models\Genres;
use Modules\Entertainment\Models\EntertainmentGenerMapping;
use Modules\Entertainment\Transformers\MoviesResource;

use App\Models\UserSearchHistory;
class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function movieList($language=null)
    {
        $movies = Entertainment::where('language', $language)->get();
        return view('frontend::movie', compact('movies', 'language'));
    }

    public function moviesListByGenre($genre_id)
    {
        $movies = Entertainment::whereHas('entertainmentGenerMappings', function ($query) use ($genre_id) {
            $query->where('genre_id', $genre_id);
        })->get();

        $genre = Genres::where('id',$genre_id)->first();

        return view('frontend::movie', compact('movies', 'genre_id','genre'));
    }
    public function livetvList()
    {
        $channelData = LiveTvChannel::with('TvCategory','plan','TvChannelStreamContentMappings')->where('status',1)->orderBy('updated_at', 'desc')->take(6)->get();
        $categoryData = LiveTvCategory::with('tvChannels')->where('status',1)->orderBy('updated_at', 'desc')->get();

        $responseData['slider'] = LiveTvChannelResource::collection($channelData)->toArray(request());
        $responseData['category_data'] = LiveTvCategoryResource::collection($categoryData)->toArray(request());

        return view('frontend::livetv',compact('responseData'));
    }


    public function movieDetails(Request $request, $id)
    {
        $movieId = $id;
        $userId = auth()->id();
        $cacheKey = 'movie_' . $movieId;

        // Retrieve cached data
        $data = Cache::get($cacheKey);

        if (!$data) {
            $movie = Entertainment::where('id', $movieId)
                ->with([
                    'entertainmentGenerMappings',
                    'plan',
                    'entertainmentReviews.user',
                    'entertainmentTalentMappings',
                    'entertainmentStreamContentMappings',
                    'entertainmentDownloadMappings'
                ])
                ->first();

            $reviews = $movie->entertainmentReviews ?? collect();

            // Encrypt the trailer URL
            if (!empty($movie->trailer_url) && $movie->trailer_url_type != 'Local') {
                $movie['trailer_url'] = Crypt::encryptString($movie->trailer_url);
            }

            if (!empty($movie->video_url_input) && $movie->video_upload_type != 'Local') {
                $movie['video_url_input'] = Crypt::encryptString($movie->video_url_input);
            }

            if ($userId) {
                $movie['is_watch_list'] = WatchList::where('entertainment_id', $movieId)
                    ->where('user_id', $userId)
                    ->exists();

                $movie['is_likes'] = Like::where('entertainment_id', $movieId)
                    ->where('user_id', $userId)
                    ->where('is_like', 1)
                    ->exists();

                $movie['is_download'] = EntertainmentDownload::where('entertainment_id', $movieId)
                    ->where('user_id', $userId)
                    ->where('entertainment_type', 'movie')
                    ->where('is_download', 1)
                    ->exists();

                $yourReview = $reviews->where('user_id', $userId)->first();

                $movie['your_review'] = $yourReview;
                $movie['reviews'] = $yourReview ? $reviews->where('user_id', '!=', $userId) : $reviews;

                $movie['total_review'] = $movie->entertainmentReviews->count();

                $continueWatch = ContinueWatch::where('entertainment_id', $movieId)
                    ->where('user_id', $userId)
                    ->where('entertainment_type', 'movie')
                    ->first();

                $movie['continue_watch'] = $continueWatch;
            } else {
                $movie['reviews'] = $reviews;
            }

            $genres = $movie->entertainmentGenerMappings;

            $genre_ids = $genres->pluck('genre_id')->toArray();
            $entertainment_ids = EntertainmentGenerMapping::whereIn('genre_id', $genre_ids)
                ->pluck('entertainment_id')
                ->toArray();
            $more_items = Entertainment::whereIn('id', $entertainment_ids)
                ->where('type', 'movie')
                ->where('status', 1)
                ->limit(7)
                ->get()
                ->except($id);

            $data = new MovieDetailResource($movie);
            $data['more_items'] = MoviesResource::collection($more_items);

            // Cache the base data
            Cache::put($cacheKey, $data);
        }

        // Convert data to array for manipulation
        $data = $data->toArray($request);

        // Dynamically append more_items (non-cached)
        $movie = Entertainment::where('id', $movieId)
            ->with('entertainmentGenerMappings')
            ->first();

        $genres = $movie->entertainmentGenerMappings;
        $genre_ids = $genres->pluck('genre_id')->toArray();
        $entertainment_ids = EntertainmentGenerMapping::whereIn('genre_id', $genre_ids)
            ->pluck('entertainment_id')
            ->toArray();
        $more_items = Entertainment::whereIn('id', $entertainment_ids)
            ->where('type', 'movie')
            ->where('status', 1)
            ->limit(7)
            ->get()
            ->except($id);

        $data['more_items'] = MoviesResource::collection($more_items);

        if ($request->has('is_search') && $request->is_search == 1) {
            $user_id = auth()->user()->id ?? $request->user_id;

            if ($user_id) {
                $currentProfile = GetCurrentProfile($user_id, $request);

                if ($currentProfile) {
                    $existingSearch = UserSearchHistory::where('user_id', $user_id)
                        ->where('profile_id', $currentProfile)
                        ->where('search_query', $data['name'])
                        ->first();

                    if (!$existingSearch) {
                        UserSearchHistory::create([
                            'user_id' => $user_id,
                            'profile_id' => $currentProfile,
                            'search_query' => $data['name'],
                            'search_id' => $data['id'],
                            'type' => $data['type']
                        ]);
                    }
                }
            }
        }

        return view('frontend::movieDetail', compact('data'));
    }



    public function liveTvDetails($id)
    {
        $livetvId = $id;
        $userId = auth()->id();

            $livetv = LiveTvChannel::where('id',$livetvId)->with('TvCategory','plan','TvChannelStreamContentMappings')->  first();
            $suggestions = LiveTvChannel::where('category_id', $livetv->category_id)
            ->where('id', '!=', $livetvId) // Exclude the current channel
            ->with('TvCategory') // Eager load the category
            ->get();

            $suggestions = LiveTvChannelResource::collection($suggestions)->toArray(request());

            if (!$livetv) {
                return abort(404, 'TV show not found.');
            }


          $data = new LiveTvChannelResource($livetv);


        $data=$data->toArray(request());

        // Encrypt the trailer URL
        if (!empty($livetv->TvChannelStreamContentMappings['server_url'])) {
            $data['server_url'] = Crypt::encryptString($livetv->TvChannelStreamContentMappings['server_url']);
        }

        // Get suggestions based on TV category

        return view('frontend::livetvDetail', compact('data', 'suggestions'));
    }

    public function livetvChannelsList(Request $request, $id)
    {
        $tvcategory_id = $id;
        $data = LiveTvChannel::where('category_id', $tvcategory_id)->where('status',1)->get();

        $data = LiveTvChannelResource::collection($data)->toArray(request());

        return view('frontend::tvchannelList', compact('data'));
    }


    public function comingSoonList($language=null)
    {
      $watch = UserWatchHistory::where('user_id', auth()->id())
    ->with('entertainment') // Eager loading Entertainment model
    ->get();
	return view('frontend::watchhistory', compact('watch'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('frontend::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('frontend::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('frontend::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
