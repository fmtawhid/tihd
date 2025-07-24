<?php

namespace Modules\Video\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Subscriptions\Transformers\PlanResource;
use Modules\Subscriptions\Models\Plan;
use Modules\Video\Models\Video;
use Modules\Video\Transformers\VideoResource;
use Modules\Entertainment\Models\EntertainmentDownload;
use Modules\Subscriptions\Models\Subscription;

class VideoDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        $plans = [];
        $plan = $this->plan;
        if($plan){
            $plans = Plan::where('level', '<=', $plan->level)->get();
        }

        $more_items = Video::where('status',1)->take(6)->get()->except($this->id);
        $downloadMappings = $this->entertainmentDownloadMappings ? $this->entertainmentDownloadMappings->toArray() : [];

        if ($this->download_status == 1) {

            if($this->download_type != null &&  $this->download_url !=null){

            $downloadData = [
                'type' => $this->download_type,
                'url' => $this->download_url,
                'quality' => 'default',
            ];
            $downloadMappings[] = $downloadData;
         }
        }
        $download = EntertainmentDownload::where('entertainment_id', $this->entertainment_id)->where('user_id',  $this->user_id)->where('entertainment_type', 'episode')->where('is_download', 1)->first();
        $getDeviceTypeData = Subscription::checkPlanSupportDevice($request->user_id);
        $deviceTypeResponse = json_decode($getDeviceTypeData->getContent(), true); // Decode to associative array
        return [
            'id' => $this->id,
            'name' => $this->name,
            'trailer_url_type' => $this->trailer_url_type,
            'trailer_url' => $this->trailer_url_type=='Local' ? setBaseUrlWithFileName($this->trailer_url) : $this->trailer_url,
            'access' => $this->access,
            'plan_id' => $this->plan_id,
            'plan_level' => $this->plan->level ?? 0,
            'imdb_rating' => $this->IMDb_rating,
            'content_rating' => $this->content_rating,
            'watched_time' => optional($this->continue_watch)->watched_time ?? null,
            'is_watch_list' => $this->is_watch_list ?? false,
            'is_likes' => $this->is_likes ?? false,
            'duration' => $this->duration,
            'release_date' => $this->release_date,
            'is_restricted' => $this->is_restricted,
            'short_desc' => $this->short_desc,
            'description' => strip_tags($this->description),
            'enable_quality' => $this->enable_quality,
            'video_upload_type' => $this->video_upload_type,
            'video_url_input' => $this->video_upload_type=='Local' ? setBaseUrlWithFileName($this->video_url_input) : $this->video_url_input,
            'download_status' => $this->download_status,
            'download_url' => $this->download_url,
            'poster_image' => setBaseUrlWithFileName($this->poster_url),
            'video_links' => $this->VideoStreamContentMappings ?? null,
            'plans' => PlanResource::collection($plans),
            'more_items' => VideoResource::collection($more_items),
            'status' => $this->status,
            'is_likes' => $this->is_likes ?? false,
            'is_download' => $this->is_download ?? false,
            'download_quality' => $downloadMappings,
            'download_id' => !empty($download) ? $download->id: null,
            'is_device_supported' => $deviceTypeResponse['isDeviceSupported']


        ];
    }
}
