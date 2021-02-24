<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ThematiqueRequest;

class ThematiqueUploadRequest extends ThematiqueRequest
{
    public function authorize()
    {
        return $this->user()->can('update', request()->route('thematique'));
    }

    public function rules()
    {
        return [
            'image' => 'required|file|image',
        ];
    }
}
