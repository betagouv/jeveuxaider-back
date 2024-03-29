<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ProfileRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends ProfileRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $profile = request()->route('profile') ?: request()->user()->profile;

        return $this->user()->can('update', $profile);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $profile = request()->route('profile') ?: request()->user()->profile;

        $rules = [
            'email' => [
                'sometimes',
                'email',
                'required',
                Rule::unique('profiles')->ignore($profile->id),
            ],
            'first_name' => 'sometimes|required',
            'last_name' => 'sometimes|required',
            'mobile' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'zip' => 'nullable|postal_code:FR',
            'birthday' => 'nullable|date|before:-16 years|after:-100 years',
            'phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'is_visible' => 'boolean',
            'service_civique' => 'boolean',
            'disponibilities' => '',
            'description' => '',
            'domaines' => '',
            'skills' => '',
            'tags' => '',
            'type' => '',
            'commitment__duration' => '',
            'commitment__time_period' => '',
            'service_civique_completion_date' => '',
            'cej' => '',
            'cej_email_adviser' => 'required_if:cej,true',
            'notification__responsable_frequency' => 'nullable|in:realtime,summary',
            'notification__responsable_bilan' => 'boolean',
            'notification__referent_frequency' => 'nullable|in:realtime,summary',
            'notification__referent_bilan' => 'boolean',
        ];

        if (request()->user()->isAdmin()) {
            $rules['can_export_profiles'] = 'boolean';
        }

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
            'email.unique' => 'Cet email est déjà pris',
            'first_name.required' => 'Un prénom est requis',
            'first_name.min' => 'Votre prénom doit contenir au moins :min lettres',
            'last_name.required' => 'Un nom est requis',
            'birthday.required' => 'La date de naissance est requise',
            'birthday.date' => 'La date d\'anniversaire saisie n\'est pas correcte.',
            'birthday.after' => 'JeVeuxAider.gouv.fr est ouvert aux personnes de moins de 100 ans',
            'birthday.before' => 'JeVeuxAider.gouv.fr est ouvert aux personnes de plus de 16 ans',
            'postal_code' => 'Le code postal n\'est pas valide',
            'zip.required' => 'Le code postal est requis',
            'mobile.required' => 'Le numéro de téléphone est requis',
            'mobile' => 'Le numéro de téléphone n\'est pas valide',
            'mobile.min' => 'Le numéro de téléphone n\'est pas valide',
            'mobile.regex' => 'Le numéro de téléphone n\'est pas valide',
            'phone' => 'Le numéro de téléphone n\'est pas valide',
            'phone.min' => 'Le numéro de téléphone n\'est pas valide',
            'phone.regex' => 'Le numéro de téléphone n\'est pas valide',
        ];
    }
}
