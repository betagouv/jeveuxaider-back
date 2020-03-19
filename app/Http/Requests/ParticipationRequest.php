<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParticipationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (backpack_auth()->check()) {
            return true;
        }

        return $this->user()->can('view', request()->route('participation'));
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
            'profile_id' => 'required',
            'state' => 'required'
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
            'mission_id.required' => 'La mission est requise',
            'profile_id.required' => 'Le profil est requis',
            'state.required' => 'Un statut est requis'
        ];
    }
}
