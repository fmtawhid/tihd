<?php

namespace Modules\Episode\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EpisodeRequest extends FormRequest
{
   public function rules()
    {
        return [
            'name' => ['required'],
            'entertainment_id'=> ['required'],
            'content_rating'=>'required|string',
            'description' => 'required|string',
            'access' => 'required',
            'IMDb_rating' => 'required|numeric|min:1|max:10',
            'plan_id' => 'required_if:access,paid',
            'season_id'=> ['required'],
            'duration'=> ['required'],
            'release_date'=> ['required'],
        
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'entertainment_id.required' => 'TV Show is required.',
            'season_id.required' => 'Season is required.',
            'duration.required' => 'Duration is required.',
            'IMDb_rating.required' => 'IMDb rating is required.', 
            'IMDb_rating.numeric' => 'IMDb rating must be a number.', 
            'IMDb_rating.min' => 'IMDb rating must be at least 1.', 
            'IMDb_rating.max' => 'IMDb rating cannot be more than 10.',
            'release_date.required' => 'Release Date is required.',
        ];
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
