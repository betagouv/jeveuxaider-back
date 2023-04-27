<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\MissionRequest;

class MissionCreateRequest extends MissionRequest
{
    public function authorize()
    {
        return $this->user()->can('view', request()->route('structure')) && !empty($this->user()->profile->mobile) && $this->user()->profile->doesntHave('missionsInactive');
    }
}
