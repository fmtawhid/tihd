<?php

namespace Modules\Season\Http\Controllers\Backend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Modules\Season\Models\Season;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Modules\Season\Http\Requests\SeasonRequest;
use App\Trait\ModuleTrait;
use Modules\Constant\Models\Constant;
use Modules\Entertainment\Models\Entertainment;
use Modules\Season\Services\SeasonService;
use Modules\Subscriptions\Models\Plan;
use App\Services\ChatGTPService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class SeasonsController extends Controller
{
    protected string $exportClass = '\App\Exports\SeasonExport';

    protected $seasonService;
    protected $chatGTPService;

    use ModuleTrait {
        initializeModuleTrait as private traitInitializeModuleTrait;
        }


    public function __construct(SeasonService $seasonService,ChatGTPService $chatGTPService)
    {
        $this->seasonService = $seasonService;
        $this->chatGTPService=$chatGTPService;

        $this->traitInitializeModuleTrait(
            'season.title',
            'seasons',
            'fa-solid fa-clipboard-list'
        );
    }



    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

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
                'text' => __('messages.name'),
            ],
            [
                'value' => 'access',
                'text' => __('episode.lbl_season') . ' ' . __('movie.lbl_movie_access'),
            ],


            [
                'value' => 'plan_id',
                'text' => __('movie.plan'),
            ],

            [
                'value' => 'entertainment_id',
                'text' => __('movie.lbl_tv_show'),
            ],


            [
                'value' => 'status',
                'text' => __('plan.lbl_status'),
            ]
        ];
        $export_url = route('backend.seasons.export');

        $plan=Plan::where('status',1)->get();

        $tvshows = Entertainment::where('type','tvshow')->get();

        return view('season::backend.season.index', compact('module_action', 'filter', 'export_import', 'export_columns', 'export_url','plan','tvshows'));
    }

    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);
        $actionType = $request->action_type;
        $moduleName = 'Season'; // Adjust as necessary for dynamic use
        Cache::flush();


        return $this->performBulkAction(Season::class, $ids, $actionType, $moduleName);
    }

    public function index_data(Datatables $datatable, Request $request)
    {
        $filter = $request->filter;
        return $this->seasonService->getDataTable($datatable, $filter);
    }


    public function index_list(Request $request)
    {
        $term = trim($request->q);

        $query_data = Season::query();

        if ($request->filled('entertainment_id')) {
            $query_data->where('entertainment_id', $request->entertainment_id);
        }

        $query_data = $query_data->where('status', 1)->get();

        $data = $query_data->map(function ($row) {
            return [
                'id' => $row->id,
                'name' => $row->name,
            ];
        });

        return response()->json($data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */

      public function create()
    {

        $upload_url_type=Constant::where('type','upload_type')->get();

        $plan=Plan::where('status',1)->get();

        $tvshows=Entertainment::Where('type','tvshow')->where('status', 1)->orderBy('id','desc')->get();

        $imported_tvshow = Entertainment::where('type', 'tvshow')
        ->where('status', 1)
        ->whereNotNull('tmdb_id')
        ->get();

        $assets = ['textarea'];
        $seasons=null;

        $module_title = __('season.new_title');
        $mediaUrls =  getMediaUrls();

        return view('season::backend.season.create', compact('upload_url_type','assets','plan','tvshows','module_title','mediaUrls','imported_tvshow','seasons'));

    }

    public function store(SeasonRequest $request)
    {
        $data = $request->all();
        $data['poster_url']= !empty( $data['tmdb_id']) ?  $data['poster_url'] : extractFileNameFromUrl($data['poster_url']);
        // $data['poster_url'] = extractFileNameFromUrl($data['poster_url']);
        $data['trailer_video'] = extractFileNameFromUrl($data['trailer_video']);

        $season = $this->seasonService->create($data);
        $notification_data = [
            'id' => $season->id,
            'name' => $season->name,
            'poster_url' => $season->poster_url ?? null,
            'type' => 'season',
            'release_date' => optional($season->entertainmentdata)->release_date ?? null,
            'description' => $season->description ?? null,
        ];
        sendNotifications($notification_data);
        $message = __('messages.create_form', ['form' => 'Season']);

        return redirect()->route('backend.seasons.index')->with('success', $message);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data = Season::findOrFail($id);
        $tmdb_id = $data->tmdb_id;
        $data->poster_url = setBaseUrlWithFileName($data->poster_url);


        if($data->trailer_url_type =='Local'){

            $data->trailer_url = setBaseUrlWithFileName($data->trailer_url);
        }

        $upload_url_type=Constant::where('type','upload_type')->get();

        $plan=Plan::where('status',1)->get();
        $assets = ['textarea'];

        $tvshows=Entertainment::Where('type','tvshow')->where('status',1)->orderBy('id','desc')->get();
        $module_title = __('season.edit_title');

        $mediaUrls =  getMediaUrls();

        return view('season::backend.season.edit', compact('data','tmdb_id','upload_url_type','plan','tvshows','module_title','mediaUrls','assets'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(SeasonRequest $request, $id)
    {
        $requestData = $request->all();

        $requestData['poster_url'] = !empty($requestData['tmdb_id']) ? $requestData['poster_url'] : extractFileNameFromUrl($requestData['poster_url']);
        // $requestData['poster_url'] = extractFileNameFromUrl($requestData['poster_url']);
        $requestData['trailer_video'] = extractFileNameFromUrl($requestData['trailer_video']);


        // if ($requestData['access'] == 'free') {
        //     $requestData['plan_id'] = null;
        // }

        $this->seasonService->update($id, $requestData);

        $message = __('messages.update_form', ['form' => 'Season']);
        return redirect()->route('backend.seasons.index')->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */

    public function destroy($id)
    {

        $this->seasonService->delete($id);

        $message = __('messages.delete_form', ['form' => 'Season']);
        return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function restore($id)
    {
        $this->seasonService->restore($id);
        $message = __('messages.restore_form', ['form' => 'Season']);
        return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function forceDelete($id)
    {
        $this->seasonService->forceDelete($id);

        $message = __('messages.permanent_delete_form', ['form' => 'Season']);
        return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function update_status(Request $request, Season $id)
    {
        $id->update(['status' => $request->status]);
        Cache::flush();


        return response()->json(['status' => true, 'message' => __('messages.status_updated')]);
    }

    public function ImportSeasonlist(Request $request){

        $tv_show_id=$request->tmdb_id;

        $tvshowjson = $this->seasonService->getSeasonsList($tv_show_id);
        $tvshowDetails = json_decode($tvshowjson, true);

        while($tvshowDetails === null) {

            $tvshowjson = $this->seasonService->getSeasonsList($tv_show_id);
           $tvshowDetails = json_decode($tvshowjson, true);

        }

        if (isset($seasons['success']) && $seasons['success'] === false) {
            return response()->json([
                'success' => false,
                'message' => $seasons['status_message']
            ], 400);
        }

        $seasonsData= [];

        if(isset($tvshowDetails['seasons']) && is_array($tvshowDetails['seasons'])) {

            foreach ($tvshowDetails['seasons'] as $season) {
                $seasonlist = [
                    'name' => $season['name'],
                    'season_number'=>$season['season_number'],
                ];

                $seasonsData[] = $seasonlist;
            }
         }
        return response()->json($seasonsData);
     }

     public function ImportSeasonDetails(Request $request){

        $tvshow_id=$request->tvshow_id;
        $season_id=$request->season_id;

        $season=Season::where('tmdb_id', $tvshow_id)->where('season_index',$season_id)->first();

        if(!empty($season)){

            $message = __('season.already_added_season');

            return response()->json([
                'success' => false,
                'message' => $message,
            ], 400);

        }

        $configuration =$this->seasonService->getConfiguration();
        $configurationData = json_decode($configuration, true);

        while($configurationData === null) {

            $configuration =$this->seasonService->getConfiguration();
            $configurationData = json_decode($configuration, true);
        }

        if(isset($configurationData['success']) && $configurationData['success'] === false) {
            return response()->json([
                'success' => false,
                'message' => $configurationData['status_message']
            ], 400);
        }

        $seasonData = $this->seasonService->getSeasonsDetails($tvshow_id,$season_id);
        $seasonDetails = json_decode($seasonData, true);

        while($seasonDetails === null) {

            $seasonData = $this->seasonService->getSeasonsDetails($tvshow_id,$season_id );
            $seasonDetails = json_decode($seasonData, true);

        }

        if (isset($seasonDetails['success']) && $seasonDetails['success'] === false) {
            return response()->json([
                'success' => false,
                'message' => $seasonDetails['status_message']
            ], 400);
        }

        $seasonvideos = $this->seasonService->getSeasonVideos($tvshow_id,$season_id);
        $seasonvideo = json_decode($seasonvideos, true);

        while ($seasonvideo === null) {

             $seasonvideos = $this->seasonService->getSeasonVideos($tvshow_id,$season_id);
             $seasonvideo = json_decode($seasonvideos, true);
        }

        if (isset($seasonvideo['success']) && $seasonvideo['success'] === false) {
            return response()->json([
                'success' => false,
                'message' => $seasonvideo['status_message']
            ], 400);
        }

        $trailer_url_type=null;
        $trailer_url=null;

        if(isset($seasonvideo['results']) && is_array($seasonvideo['results'])) {

            foreach($seasonvideo['results'] as $video) {

                if($video['type'] == 'Trailer'){

                    $trailer_url_type= $video['site'];
                    $trailer_url='https://www.youtube.com/watch?v='.$video['key'];

                }
            }
        }

        $tvshows = Entertainment::where('tmdb_id',$tvshow_id)->first();

        $data = [

            'poster_url' => $configurationData['images']['secure_base_url'] . 'original' . $seasonDetails['poster_path'],
            'trailer_url_type'=>$trailer_url_type,
            'trailer_url'=>$trailer_url,
            'name' => $seasonDetails['name'],
            'description' => $seasonDetails['overview'],
            'entertainment_id'=>$tvshows->id,
            'access'=>'free',
            'season_index'=>$season_id,
            'tvshow_id'=>$tvshow_id,

        ];

             return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);

     }

     public function generateDescription(Request $request)
     {
         $name = $request->input('name');
         $description = $request->input('description');
         $tvshow=$request->input('tvshow');
         $type=$request->input('type');

         $tvshows=Entertainment::Where('id',$tvshow)->first();

         if( $tvshows){

            $name= $name.'of'.$tvshows->name;
         }

         $result = $this->chatGTPService->GenerateDescription($name, $description, $type);

         $result =json_decode( $result, true);

         if (isset($result['error'])) {
             return response()->json([
                 'success' => false,
                 'message' => $result['error']['message'],
             ], 400);
         }

         return response()->json([

             'success' => true,
             'data' => isset($result['choices'][0]['message']['content']) ? $result['choices'][0]['message']['content'] : null,
         ], 200);
     }

     public function details($id)
    {
        $data = Season::with([
            'entertainmentdata',
            'episodes',
            'plan',

        ])->findOrFail($id);

        $data->poster_url = setBaseUrlWithFileName($data->poster_url);
        $data->formatted_release_date = Carbon::parse($data->release_date)->format('d M, Y');
        $module_title = __('season.title');
        $show_name = $data->name;
        $route = 'backend.seasons.index';
        return view('season::backend.season.details', compact('data','module_title','show_name','route'));
    }

}
