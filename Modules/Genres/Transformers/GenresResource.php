<?php

namespace Modules\Genres\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class GenresResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id ?? null,
            'name' => $this->name ?? null,
            // 'description' => $this->description ?? null,
            'genre_image' => !empty($this->file_url) ? setBaseUrlWithFileName($this->file_url) : null,
            'status' => $this->status ?? null,
        ];
    }
}
