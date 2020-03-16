<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\MissionRequest;

class MissionCreateRequest extends MissionRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('view', request()->route('structure'));
    }
}
