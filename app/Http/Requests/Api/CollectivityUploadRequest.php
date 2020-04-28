<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\CollectivityRequest;

class CollectivityUploadRequest extends CollectivityRequest
{
    public function authorize()
    {
        return $this->user()->can('update', request()->route('collectivity'));
    }

    public function rules()
    {
        return [
            'image' => 'required|file|image',
        ];
    }
}
