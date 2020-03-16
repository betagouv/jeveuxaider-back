<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\MissionRequest;

class MissionDeleteRequest extends MissionRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $mission = request()->route('mission');

        return $this->user()->can('delete', $mission);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
