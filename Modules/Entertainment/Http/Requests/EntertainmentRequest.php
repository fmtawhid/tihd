<?php

namespace Modules\Entertainment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class EntertainmentRequest extends FormRequest
{
   public function rules()
    {


        return [
            'name' => ['required'],
            'movie_access' => 'required',
            'plan_id' => 'required_if:movie_access,paid',
            'language'=> ['required'],
            'genres'=> ['required'],
            'content_rating'=>'required|string',
            'actors'=> ['required'],
            'directors'=> ['required'],
            'IMDb_rating' => 'required|numeric|min:1|max:10',
            'duration'=> ['required'],
            'release_date' => ['required'],
            'description' => ['required', 'string'],

        ];


        if ($this->input('type') == 'movie') {
            $rules['duration'] = 'required';
            $rules['video_upload_type']='required';
        }

    }




    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'language.required' => 'Language is required.',
            'genres.required' => 'Genres is required.',
            'actors.required' => 'Actors is required.',
            'directors.required' => 'Directors is required.',
            'duration.required' => 'Duration is required.',
            'video_upload_type.required'=>'Video Type is required.',
            'release_date.required' => 'Release Date is required.',
            'description.required' => 'Description is required.',
            'IMDb_rating.required' => 'IMDb rating is required.',
            'IMDb_rating.numeric' => 'IMDb rating must be a number.',
            'IMDb_rating.min' => 'IMDb rating must be at least 1.',
            'IMDb_rating.max' => 'IMDb rating cannot be more than 10.',
            'description.string' => 'Description must be a string.',
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
