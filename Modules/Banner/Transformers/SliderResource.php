<?php

namespace Modules\Banner\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Entertainment\Models\Entertainment;
use Modules\LiveTV\Models\LiveTvChannel;
use Modules\Entertainment\Transformers\MoviesResource;
use Modules\Entertainment\Transformers\TvshowResource;
use Modules\LiveTV\Transformers\LiveTvChannelResource;
use Modules\Entertainment\Models\Watchlist;
use Illuminate\Support\Facades\Crypt;
class SliderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function __construct($resource, $userId = null)
    {
        parent::__construct($resource);
        $this->userId = $userId;
    }

    public function toArray($request): array
    {
        $entertainment = null;
        $data = null;

        switch ($this->type) {
            case 'movie':
            case 'tvshow':
                $entertainment = Entertainment::find($this->type_id);
                if ($entertainment) {
                    $entertainment['is_watch_list'] = WatchList::where('entertainment_id', $this->type_id)
                        ->where('user_id', $this->userId)
                        ->where('profile_id',$request->profile_id)
                        ->exists();
                    $data = $this->type === 'movie' ? new MoviesResource($entertainment) : new TvshowResource($entertainment);
                }
                break;

            case 'livetv':

                $livetv = LiveTvChannel::find($this->type_id);
                if ($livetv) {
                    $data = new LiveTvChannelResource($livetv);
                }
                break;
        }

         // Encrypt the trailer_url if needed
        //  if ($data && $data->trailer_url) {
        //     $data['trailer_url'] = Crypt::encryptString($data['trailer_url']);
        // }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'poster_url' => setBaseUrlWithFileName($this->poster_url),
            'file_url' => setBaseUrlWithFileName($this->file_url),
            'type' => $this->type,
            'data' => $data,
        ];
    }
}
