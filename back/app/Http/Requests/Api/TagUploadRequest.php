<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ThematiqueRequest;

class TagUploadRequest extends ThematiqueRequest
{
    public function authorize()
    {
        return $this->user()->can('update', request()->route('tag'));
    }

    public function rules()
    {
        return [
            'image' => 'required|file|image',
        ];
    }
}
