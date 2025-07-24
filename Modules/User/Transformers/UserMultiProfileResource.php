<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class UserMultiProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'avatar' =>setBaseUrlWithFileName($this->avatar),
            'is_active' => $this->activeprofile !== null ? 1 : 0,
        ];
    }
}
