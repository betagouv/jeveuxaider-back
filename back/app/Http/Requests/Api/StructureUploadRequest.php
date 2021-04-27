<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\StructureRequest;

class StructureUploadRequest extends StructureRequest
{
    public function authorize()
    {
        return $this->user()->can('update', $this->route('structure'));
    }

    public function rules()
    {
        return [
            'image' => 'file|image',
        ];
    }
}
