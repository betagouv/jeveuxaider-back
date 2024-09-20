<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ParticipationRequest;

class ParticipationCreateRequest extends ParticipationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'mission_id' => 'required',
            'state' => '',
            'utm_source' => '',
            'utm_campaign' => '',
            'utm_medium' => '',
            'slots' => '',
            'date' => '',
        ];
    }
}
