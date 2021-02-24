<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\MissionTemplateRequest;

class MissionTemplateUploadRequest extends MissionTemplateRequest
{
    public function authorize()
    {
        return $this->user()->can('update', request()->route('missionTemplate'));
    }

    public function rules()
    {
        return [
            'image' => 'required|file|image',
        ];
    }
}
