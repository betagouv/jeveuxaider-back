<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\MissionTemplateRequest;
use App\Models\MissionTemplate;

class MissionTemplateCreateRequest extends MissionTemplateRequest
{
    public function authorize()
    {
        return $this->user()->can('create', MissionTemplate::class);
    }
}
