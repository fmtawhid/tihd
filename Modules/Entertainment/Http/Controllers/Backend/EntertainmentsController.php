<?php

namespace Modules\Entertainment\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Modules\Entertainment\Models\Entertainment;
use Illuminate\Http\Request;
use Modules\Entertainment\Http\Requests\EntertainmentRequest;
use App\Trait\ModuleTrait;
use Modules\Constant\Models\Constant;
use Modules\Subscriptions\Models\Plan;
use Modules\Genres\Models\Genres;
use Modules\CastCrew\Models\CastCrew;
use Modules\Entertainment\Services\EntertainmentService;
use Modules\World\Models\Country;
use Illuminate\Support\Facades\Cache;

class EntertainmentsController extends Controller
{
    protected string $exportClass = '\App\Exports\EntertainmentExport';


    use ModuleTrait {
        initializeModuleTrait as private traitInitializeModuleTrait;
        }

        protected $entertainmentService;

        public function __construct(EntertainmentService $entertainmentService)
        {
            $this->entertainmentService = $entertainmentService;

            $this->traitInitializeModuleTrait(
                'castcrew.castcrew_title',
                'castcrew',

                'fa-solid fa-clipboard-list'
            );
        }


    public function index(Request $request)
    {
        $filter = [
            'status' => $request->status,
        ];

        $module_action = 'List';

        $export_import = true;
        $export_columns = [
            [
                'value' => 'name',
                'text' => ' Name',
            ]
        ];
        $export_url = route('backend.entertainments.export');

        return view('entertainment::backend.entertainment.index', compact('module_action', 'filter', 'export_import', 'export_columns', 'export_url'));
    }

    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);
        $actionType = $request->action_type;
        $moduleName = 'Entertainment'; // Adjust as necessary for dynamic use

        Cache::flush();


        return $this->performBulkAction(Entertainment::class, $ids, $actionType, $moduleName);
    }

    public function store(EntertainmentRequest $request)
     {

        $data = $request->all();
        $data['thumbnail_url'] = !empty($data['tmdb_id']) ? $data['thumbnail_url'] :extractFileNameFromUrl($data['thumbnail_url']);
        $data['poster_url']= !empty( $data['tmdb_id']) ?  $data['poster_url'] : extractFileNameFromUrl($data['poster_url']);


        if (isset($data['IMDb_rating'])) {
            // Round the IMDb rating to 1 decimal place
            $data['IMDb_rating'] = round($data['IMDb_rating'], 1);
        }

        if($request->trailer_url_type == 'Local'){
            $data['trailer_video'] = extractFileNameFromUrl($data['trailer_video']);
        }
        if($request->video_upload_type == 'Local'){
            $data['video_file_input'] = extractFileNameFromUrl($data['video_file_input']);
        }

        $entertainment = $this->entertainmentService->create($data);
        $type = $entertainment->type;
        $message = trans('messages.create_form', ['type' =>ucfirst($type)]);


        Cache::flush();

        if($type=='movie'){

            return redirect()->route('backend.movies.index')->with('success', $message);

        }else{

            return redirect()->route('backend.tvshows.index')->with('success', $message);
        }
    }

    public function update_status(Request $request, Entertainment $id)
    {
        $id->update(['status' => $request->status]);

        Cache::flush();

        return response()->json(['status' => true, 'message' => __('messages.status_updated')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {

        $data = Entertainment::where('id', $id)
            ->with([
                'entertainmentGenerMappings',
                'entertainmentCountryMappings',
                'entertainmentStreamContentMappings',
                'entertainmentTalentMappings'
            ])
            ->first();

        $tmdb_id = $data->tmdb_id;
        $data->thumbnail_url = setBaseUrlWithFileName($data->thumbnail_url);
        $data->poster_url =setBaseUrlWithFileName($data->poster_url);

        if($data->trailer_url_type =='Local'){

            $data->trailer_url = setBaseUrlWithFileName($data->trailer_url);
        }

        if($data->video_upload_type =='Local'){

            $data->video_url_input = setBaseUrlWithFileName($data->video_url_input);
        }


        $constants = Constant::whereIn('type', ['upload_type', 'movie_language', 'video_quality'])->get();
        $upload_url_type = $constants->where('type', 'upload_type');
        $movie_language = $constants->where('type', 'movie_language');
        $video_quality = $constants->where('type', 'video_quality');

        $plan = Plan::where('status', 1)->get();
        $genres = Genres::where('status', 1)->get();
        $actors = CastCrew::where('type', 'actor')->get();
        $directors = CastCrew::where('type', 'director')->get();
        $countries = Country::where('status', 1)->get();
        $mediaUrls = getMediaUrls();
        $assets = ['textarea'];

        if ($data->type === 'tvshow') {
            $module_title = __('tvshow.edit_title');
        } else {
            $module_title = __('movie.edit_title');
        }


        $numberOptions = collect(range(1, 10))->mapWithKeys(function ($number) {
            return [$number => $number];
        });

        $data['genres'] = $data->entertainmentGenerMappings->pluck('genre_id')->toArray();
        $data['countries'] = $data->entertainmentCountryMappings->pluck('country_id')->toArray();
        $data['actors'] = $data->entertainmentTalentMappings->pluck('talent_id')->toArray();
        $data['directors'] = $data->entertainmentTalentMappings->pluck('talent_id')->toArray();

        return view('entertainment::backend.entertainment.edit', compact(
            'data',
            'tmdb_id',
            'upload_url_type',
            'plan',
            'movie_language',
            'genres',
            'numberOptions',
            'actors',
            'directors',
            'countries',
            'video_quality',
            'mediaUrls',
            'assets',
            'module_title'

        ));
    }


    public function update(EntertainmentRequest $request, $id)
    {

        Cache::flush();
        $request_data=$request->all();
        $request_data['thumbnail_url'] = !empty($request_data['tmdb_id']) ? $request_data['thumbnail_url'] :extractFileNameFromUrl($request_data['thumbnail_url']);
        $request_data['poster_url'] = !empty($request_data['tmdb_id']) ? $request_data['poster_url'] : extractFileNameFromUrl($request_data['poster_url']);
        $request_data['trailer_video'] = extractFileNameFromUrl($request_data['trailer_video']);
        $request_data['video_file_input'] = isset($request_data['video_file_input'])  ? extractFileNameFromUrl($request_data['video_file_input']) : null;

        if (isset($request_data['IMDb_rating'])) {
            // Round the IMDb rating to 1 decimal place
            $request_data['IMDb_rating'] = round($request_data['IMDb_rating'], 1);
        }

        $entertainment = $this->entertainmentService->getById($id);

        // Handle Poster Image
        if ($request->input('remove_image') == 1) {
            $requestData['poster_url'] = setDefaultImage($request_data['poster_url']);


        } elseif ($request->hasFile('poster_url')) {
            $file = $request->file('poster_url');
            StoreMediaFile($entertainment, $file, 'poster_url');
            $requestData['poster_url'] = $entertainment->getFirstMediaUrl('poster_url');
        } else {
            $requestData['poster_url'] = $entertainment->poster_url;
        }

        // Handle Thumbnail Image
        if ($request->input('remove_image_thumbnail') == 1) {
            $requestData['thumbnail_url'] = setDefaultImage($request_data['thumbnail_url']);
        } elseif ($request->hasFile('thumbnail_url')) {
            $file = $request->file('thumbnail_url');
            StoreMediaFile($entertainment, $file, 'thumbnail_url');
            $requestData['thumbnail_url'] = $entertainment->getFirstMediaUrl('thumbnail_url');
        } else {
            $requestData['thumbnail_url'] = $entertainment->thumbnail_url;
        }
        $data = $this->entertainmentService->update($id, $request_data);

        Cache::flush();


        $type = $entertainment->type;
        $message = trans('messages.update_form', ['Form' =>ucfirst($type)]);

        if ($type == 'movie') {
            return redirect()->route('backend.movies.index')
                ->with('success', $message);
        } else if ($type == 'tvshow') {
            return redirect()->route('backend.tvshows.index')
                ->with('success', $message);
        }
    }


    public function destroy($id)
    {
       $entertainment = $this->entertainmentService->getById($id);
       $type=$entertainment->type;
       $entertainment->delete();
       $message = trans('messages.delete_form', ['form' => $type]);
       Cache::flush();
       return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function restore($id)
    {
        $entertainment = $this->entertainmentService->getById($id);
        $type=$entertainment->type;
        $entertainment->restore();
        $message = trans('messages.restore_form', ['form' =>$type]);
        Cache::flush();
        return response()->json(['message' => $message, 'status' => true], 200);

    }

    public function forceDelete($id)
    {
        $entertainment = $this->entertainmentService->getById($id);
        $type=$entertainment->type;
        $entertainment->forceDelete();
        $message = trans('messages.permanent_delete_form', ['form' =>$type]);
        Cache::flush();
        return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function downloadOption(Request $request, $id){

        $data = Entertainment::where('id',$id)->with('entertainmentDownloadMappings')->first();

        $module_title =__('messages.download_movie');

        $upload_url_type=Constant::where('type','upload_type')->get();
        $video_quality=Constant::where('type','video_quality')->get();
        Cache::flush();

        return view('entertainment::backend.entertainment.download', compact('data','module_title','upload_url_type','video_quality'));

    }


   public function storeDownloads(Request $request, $id)
    {
        $data = $request->all();
        $this->entertainmentService->storeDownloads($data, $id);
        $message = trans('messages.set_download_url');
        Cache::flush();

        return redirect()->route('backend.movies.index')->with('success', $message);
    }


    public function details($id)
    {
        $data = Entertainment::with([
            'entertainmentGenerMappings',
            'entertainmentStreamContentMappings',
            'entertainmentTalentMappings',
            'entertainmentReviews',
            'season',

        ])->findOrFail($id);


       foreach ($data->entertainmentTalentMappings as $talentMapping) {
    $talentProfile = $talentMapping->talentprofile;

    if ($talentProfile) {
        if (in_array($talentProfile->type, ['actor', 'director'])) {
            $talentProfile->file_url =  setBaseUrlWithFileName($talentProfile->file_url);
        }
    }
}
        $data->poster_url =setBaseUrlWithFileName($data->poster_url);

        $data->formatted_release_date = Carbon::parse($data->release_date)->format('d M, Y');
        if($data->type == "movie"){
            $module_title = __('movie.title');
            $show_name = $data->name;
            $route = 'backend.movies.index';
        }else{
            $module_title = __('tvshow.title');
            $show_name = $data->name;
            $route = 'backend.tvshows.index';
        }

        return view('entertainment::backend.entertainment.details', compact('data','module_title','show_name','route'));
    }



}
