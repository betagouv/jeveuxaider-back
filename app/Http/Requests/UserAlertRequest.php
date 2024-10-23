<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserAlertRequest extends FormRequest
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
        $rules = [
            'type_missions' => 'required|in:presentiel,distance,all',
            'activities' => 'required',
            'commitment' => 'required',
            'zip' => 'required_if:type_missions,presentiel,all',
            'city' => 'required_if:type_missions,presentiel,all',
            'radius' => 'required_if:type_missions,presentiel,all',
            'latitude' => 'required_if:type_missions,presentiel,all',
            'longitude' => 'required_if:type_missions,presentiel,all',
        ];

        return $rules;
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => 'Un email est requis',
            'email.unique' => 'Une invitation a déjà été envoyée à cet email',
            'email.email' => 'Cet email est mal formaté',
            'role' => 'Un rôle est requis',
            'invitable_id.required_if' => 'Merci de saisir une entité à laquelle rattacher cet email',
            'properties.required_if' => 'Merci de saisir une entité à laquelle rattacher cet email',
        ];
    }
}
