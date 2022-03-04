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
}
