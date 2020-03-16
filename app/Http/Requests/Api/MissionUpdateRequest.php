<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\MissionRequest;

class MissionUpdateRequest extends MissionRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', request()->route('mission'));
    }
}
