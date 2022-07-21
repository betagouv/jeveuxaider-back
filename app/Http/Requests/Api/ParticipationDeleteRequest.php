<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ParticipationRequest;

class ParticipationDeleteRequest extends ParticipationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $participation = request()->route('participation');

        return $this->user()->can('delete', $participation);
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
