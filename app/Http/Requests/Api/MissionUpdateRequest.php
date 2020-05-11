<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\MissionRequest;

class MissionUpdateRequest extends MissionRequest
{
    public function authorize()
    {
        return $this->user()->can('update', request()->route('mission'));
    }
}
