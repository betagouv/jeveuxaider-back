<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class YoungRequest extends FormRequest
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

        return $this->user()->can('view', request()->route('young'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'sometimes|required|min:3',
            'last_name' => 'sometimes|required|min:2',
            'state' => 'sometimes|required'
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
            'first_name.required' => 'Un prénom est requis',
            'first_name.min' => 'Le prénom doit contenir au moins :min lettres',
            'last_name.required' => 'Un nom est requis',
            'last_name.min' => 'Le nom doit contenir au moins :min lettres',
            'state.required' => 'Le statut est requis',
            'email.unique' => 'Une fiche jeune appartient déjà à cet email'
        ];
    }
}
