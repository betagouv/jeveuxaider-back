<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\MissionRequest;

class MissionDuplicateRequest extends MissionRequest
{
    public function authorize()
    {
        return $this->user()->can('duplicate', request()->route('mission'));
    }

    public function rules()
    {
        return [];
    }
}
