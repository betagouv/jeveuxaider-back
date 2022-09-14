<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ParticipationRequest;

class ParticipationDeclineRequest extends ParticipationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', request()->route('participation'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'reason' => 'required',
            'content' => '',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'reason.required' => 'Merci de sélectionner une raison',
        ];
    }
}
