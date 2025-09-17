<?php

namespace Modules\Filemanager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilemanagerRequest extends FormRequest
{
    public function rules()
    {
        return [
            'file_url.*' => 'required|file|mimes:jpeg,jpg,png,gif,svg,webp|max:5120',
            // এখন webp ও allow হবে
        ];
    }

    public function messages()
    {
        return [
            'file_url.*.required' => 'File is required.',
            'file_url.*.file' => 'Each file must be a valid file.',
            'file_url.*.mimes' => 'Only image files are allowed: jpeg, jpg, png, gif, svg, webp.',
            'file_url.*.max' => 'Image size must not exceed 5MB.',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
