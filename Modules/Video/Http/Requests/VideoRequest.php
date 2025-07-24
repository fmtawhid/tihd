<?php

namespace Modules\Video\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VideoRequest extends FormRequest
{
   public function rules()
    {

        return [
            'name' => ['required'],
            'duration'=> ['required'],
            'access' => 'required',
            'plan_id' => 'required_if:access,paid',
            // 'release_date' => ['required'],
            'description' => 'required|string',
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'Title is required.',
            'duration.required' => 'Duration is required.',
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
