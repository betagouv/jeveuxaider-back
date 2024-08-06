<?php

namespace App\Http\Requests;

use App\Rules\UniqueInsensitive;
use Illuminate\Foundation\Http\FormRequest;

class RegisterVolontaireRequest extends FormRequest
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
            'email' => ['required', 'email', new UniqueInsensitive('users')],
            'password' => 'required|min:8',
            'first_name' => 'required|min:3',
            'last_name' => 'required',
            'mobile' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'birthday' => 'required|date|before:-16 years|after:-100 years',
            'zip' => 'required|postal_code:FR',
            'country' => 'required|in:FR',
            'city' => '',
            'longitude' => '',
            'latitude' => '',
            'department' => '',
            'service_civique' => 'boolean',
            'type' => '',
            'utm_source' => '',
            'utm_campaign' => '',
            'utm_medium' => '',
            'service_civique_completion_date' => '',
            'cej' => '',
            'cej_email_adviser' => 'required_if:cej,true',
            'ft' => '',
            'ft_email_adviser' => 'required_if:ft,true',
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
            'first_name.min' => 'Votre prénom doit contenir au moins :min lettres',
            'last_name.required' => 'Un nom est requis',
            'email.required' => 'Un email est requis',
            'email.unique' => 'Cet email est déjà pris. Merci de vous connecter avec vos identifiants.',
            'email.email' => 'Cet email est mal formaté',
            'password.required' => 'Un mot de passe est requis',
            'password.min' => 'Votre mot de passe doit contenir au moins :min caractères',
            'birthday.required' => 'La date de naissance est requise',
            'birthday.date' => 'La date d\'anniversaire saisie n\'est pas correcte.',
            'birthday.after' => 'JeVeuxAider.gouv.fr est ouvert aux personnes de moins de 100 ans',
            'birthday.before' => 'JeVeuxAider.gouv.fr est ouvert aux personnes de plus de 16 ans',
            'postal_code' => 'Le code postal n\'est pas valide',
            'country.required' => 'Le pays de résidence est requis',
            'country.in' => 'Vous devez résider régulièrement en France pour vous inscrire',
            'zip.required' => 'Le code postal est requis',
            'mobile.required' => 'Le numéro de téléphone est requis',
            'mobile' => 'Le numéro de téléphone n\'est pas valide',
        ];
    }
}
