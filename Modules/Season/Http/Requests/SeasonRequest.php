<?php

namespace Modules\Season\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeasonRequest extends FormRequest
{
   public function rules()
    {

        return [
            'name' => ['required'],
            'entertainment_id'=> ['required'],
            'access'=> ['required'],
            'plan_id' => 'required_if:access,paid',
            'description' => 'required|string',
            
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'entertainment_id.required' => 'TV Show is required.',
            'access.required' => 'Access is required.',
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
