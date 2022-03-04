<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ReseauRequest;

class ReseauUploadRequest extends ReseauRequest
{
    public function authorize()
    {
        return $this->user()->can('update', $this->route('reseau'));
    }

    public function rules()
    {
        return [
            'image' => 'file|image',
        ];
    }
}
