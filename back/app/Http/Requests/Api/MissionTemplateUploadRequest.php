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
            'image' => 'file|image',
            'photo' => 'file|image',
        ];
    }
}
