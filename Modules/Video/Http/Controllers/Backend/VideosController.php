<?php

namespace Modules\Video\Http\Controllers\Backend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Modules\Video\Models\Video;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Modules\Video\Http\Requests\VideoRequest;
use App\Trait\ModuleTrait;
use Modules\Constant\Models\Constant;
use Modules\Genres\Models\Genres;
use Modules\Subscriptions\Models\Plan;
use Modules\Video\Models\VideoStreamContentMapping;
use App\Services\StreamContentService;
use Modules\Video\Services\VideoService;
use App\Services\ChatGTPService;

class VideosController extends Controller
{
    protected string $exportClass = '\App\Exports\VideoExport';
    protected $videoService;
    protected $chatGTPService;

    protected $streamContentService;
    use ModuleTrait {
        initializeModuleTrait as private traitInitializeModuleTrait;
    }

    public function __construct(VideoService $videoService,ChatGTPService $chatGTPService)
    {
        $this->videoService = $videoService;
        $this->chatGTPService=$chatGTPService;
        $this->traitInitializeModuleTrait(
            'video.title', // module title
            'videos', // module name
            'fa-solid fa-clipboard-list' // module icon
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
                'text' => __('video.singular_title') . ' ' . __('movie.lbl_movie_access'),
            ],

            [
                'value' => 'duration',
                'text' => __('movie.lbl_duration'),
            ],

            [
                'value' => 'release_date',
                'text' => __('movie.lbl_release_date'),
            ],


            [
                'value' => 'is_restricted',
                'text' => __('movie.lbl_age_restricted'),
            ],

            [
                'value' => 'status',
                'text' => __('plan.lbl_status'),
            ]
        ];
        $export_url = route('backend.videos.export');

        $plan=Plan::where('status',1)->get();

        return view('video::backend.video.index', compact('module_action', 'filter', 'export_import', 'export_columns', 'export_url','plan'));
    }

    public function bulk_action(Request $request)
    {
        $ids = explode(',', $request->rowIds);
        $actionType = $request->action_type;
        $moduleName = 'Video'; // Adjust as necessary for dynamic use

        return $this->performBulkAction(Video::class, $ids, $actionType, $moduleName);
    }

    public function index_data(Datatables $datatable, Request $request)
    {
        $filter = $request->filter;
        return $this->videoService->getDataTable($datatable, $filter);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */

    public function create()
    {

        $constants = Constant::whereIn('type', ['upload_type', 'movie_language', 'video_quality'])->get()->groupBy('type');

        $upload_url_type = $constants->get('upload_type', collect());
        $video_quality = $constants->get('video_quality', collect());
        $plan = Plan::where('status', 1)->get();
        $module_title = __('video.add_title');
        $mediaUrls = getMediaUrls();
        $assets = ['textarea'];
        return view('video::backend.video.create', compact('upload_url_type','assets', 'plan', 'video_quality', 'module_title', 'mediaUrls'));


    }

    public function store(VideoRequest $request)
    {
        $data = $request->all();

        $data['poster_url'] = extractFileNameFromUrl($data['poster_url']);

        // $data['trailer_url'] = ($data['trailer_url_type'] == 'Local') ? $data['trailer_video'] : $data['trailer_url'];
        $data['video_url_input'] = ($data['video_upload_type'] == 'Local') ? extractFileNameFromUrl($data['video_file_input']) : $data['video_url_input'];

        $data['type'] = 'video';
        $video = Video::create($data);


        // $this->streamContentService->handleQualityVideoUrlInput($request, new VideoStreamContentMapping(), 'video_id');

        if ($request->has('enable_quality') && $request->enable_quality == 1) {

            $qualityVideoUrl = $request->quality_video_url_input;
            $videoQuality = $request->video_quality;
            $videoQualityType = $request->video_quality_type;
            $qualityVideoFile = $request->quality_video;

            if(!empty($videoQuality) && (!empty($qualityVideoUrl) || !empty($qualityVideoFile)) && !empty($videoQualityType)){

                foreach ($videoQuality as $index => $quality) {
                    if ($quality != '' && ($qualityVideoUrl[$index] != '' || $qualityVideoFile[$index] != '') && $videoQualityType[$index] != '' ) {
                        VideoStreamContentMapping::create([
                            'video_id' => $video->id,
                            'url' => $qualityVideoUrl[$index] ?? extractFileNameFromUrl($qualityVideoFile[$index]),
                            'type' => $videoQualityType[$index],
                            'quality' => $quality,
                        ]);
                    }
                }
            }
        }
        $notification_data = [
            'id' => $video->id,
            'name' => $video->name,
            'poster_url' => $video->poster_url ?? null,
            'type' => 'Video',
            'release_date' => $video->release_date ?? null,
            'description' => $video->description ?? null,
        ];
        sendNotifications($notification_data);
        $message = trans('messages.create_form', ['type' => 'Viedo']);

        return redirect()->route('backend.videos.index')->with('success', $message);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data = Video::where('id', $id)->with('VideoStreamContentMappings')->first();
        $data->poster_url= setBaseUrlWithFileName($data->poster_url);

        if($data->trailer_url_type =='Local'){
            $data->trailer_url_type = setBaseUrlWithFileName($data->trailer_url);
        }

        if($data->video_upload_type =='Local'){
            $data->video_url_input = setBaseUrlWithFileName($data->video_url_input);
        }

        $upload_url_type=Constant::where('type','upload_type')->get();
        $plan = Plan::where('status', 1)->get();

        $numberOptions = collect(range(1, 10))->mapWithKeys(function ($number) {
            return [$number => $number];
        });

        $video_quality = Constant::where('type', 'video_quality')->get();
        $mediaUrls = getMediaUrls();
        $assets = ['textarea'];
        $module_title = __('video.edit_title');

        return view('video::backend.video.edit', compact('data','assets','upload_url_type', 'plan', 'numberOptions', 'video_quality', 'module_title', 'mediaUrls'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(VideoRequest $request, $id)
    {
        $requestData = $request->all();
        $requestData['poster_url'] = extractFileNameFromUrl($requestData['poster_url']);


        $data = Video::where('id', $id)->first();
        if ($requestData['access'] == 'free') {

            $requestData['plan_id'] = null;
        }
       // $requestData['trailer_url'] = ($requestData['trailer_url_type'] == 'Local') ? extractFileNameFromUrl($requestData['trailer_video']) : $requestData['trailer_url'];
        $requestData['video_url_input'] = ($requestData['video_upload_type'] == 'Local') ? extractFileNameFromUrl($requestData['video_file_input']) : $requestData['video_url_input'];
        $data->update($requestData);


        if (isset($requestData['enable_quality']) && $requestData['enable_quality'] == 1) {

            $qualityVideoUrlInput = $requestData['quality_video_url_input'] ?? [];
            $videoQuality = $requestData['video_quality'] ?? [];
            $videoQualityType = $requestData['video_quality_type'] ?? [];
            $qualityVideo = $requestData['quality_video'] ?? [];

            if (!empty($videoQuality) && (!empty($qualityVideoUrlInput) || !empty($qualityVideo)) && !empty($videoQualityType)) {
                VideoStreamContentMapping::where('video_id', $data->id)->forceDelete();
                foreach ($videoQuality as $index => $videoquality) {

                    if ($videoquality != '' && (isset($qualityVideoUrlInput[$index]) && $qualityVideoUrlInput[$index] != '' || isset($qualityVideo[$index]) && $qualityVideo[$index] != '') && isset($videoQualityType[$index])) {

                        $url = $qualityVideoUrlInput[$index] ??  extractFileNameFromUrl($qualityVideo[$index]);
                        $type = $videoQualityType[$index] ?? null;
                        $quality = $videoquality;

                        VideoStreamContentMapping::create(['video_id' => $data->id, 'url' => $url, 'type' => $type, 'quality' => $quality]);
                    }
                }
            }
        }
        $message = trans('messages.update_form', ['type' => 'Video']);

        return redirect()->route('backend.videos.index')->with('success', $message);
    }

    public function update_status(Request $request, Video $id)
    {
        $id->update(['status' => $request->status]);
        return response()->json(['status' => true, 'message' => __('messages.status_updated')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */

    public function destroy($id)
    {
        $data = Video::where('id', $id)->first();
        $data->delete();
        $message = trans('messages.delete_form', ['form' => 'Video']);
        return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function restore($id)
    {
        $data = Video::withTrashed()->where('id', $id)->first();
        $data->restore();
        $message = trans('messages.restore_form', ['form' => 'Video']);
        return response()->json(['message' => $message, 'status' => true], 200);
    }

    public function forceDelete($id)
    {
        $data = Video::withTrashed()->where('id', $id)->first();
        $data->forceDelete();
        $message = trans('messages.permanent_delete_form', ['form' => 'Video']);
        return response()->json(['message' => $message, 'status' => true], 200);
    }


    public function downloadOption(Request $request, $id)
    {
        $data = Video::where('id', $id)->with('videoDownloadMappings')->find($id);

        // if (!$data) {
        //     return redirect()->route('backend.video.index')->with('error', 'Video not found.');
        // }

        $module_title =  __('messages.download') . ' ' .  __('video.singular_title');

        $upload_url_type=Constant::where('type','upload_type')->get();
        $video_quality=Constant::where('type','video_quality')->get();

        return view('video::backend.video.download', compact('data', 'module_title', 'upload_url_type', 'video_quality'));
    }

    public function storeDownloads(Request $request, $id)
    {
        $data = $request->all();
        $this->videoService->storeDownloads($data, $id);
        $message = trans('messages.set_download_url');

        return redirect()->route('backend.videos.index')->with('success', $message);
    }

    public function generateDescription(Request $request)
    {
        $name = $request->input('name');
        $description = $request->input('description');
        $type=$request->input('type');

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



}




