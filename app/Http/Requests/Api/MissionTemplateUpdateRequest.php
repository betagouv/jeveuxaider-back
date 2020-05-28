<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\MissionTemplateRequest;

class MissionTemplateUpdateRequest extends MissionTemplateRequest
{
    public function authorize()
    {
        return $this->user()->can('update', request()->route('missionTemplate'));
    }
}
