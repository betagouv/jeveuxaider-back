<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\MissionRequest;

class MissionCloneRequest extends MissionRequest
{
    public function authorize()
    {
        return $this->user()->can('update', request()->route('mission'));
    }

    public function rules()
    {
        return [];
    }
}
