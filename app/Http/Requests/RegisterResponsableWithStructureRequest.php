<?php

namespace App\Http\Requests;

use App\Rules\UniqueInsensitive;
use Illuminate\Foundation\Http\FormRequest;

class RegisterResponsableWithStructureRequest extends FormRequest
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
            'first_name' => 'required|min:3|regex:/^[a-zA-Z\'\’\-\s]+$/',
            'last_name' => 'required|regex:/^[a-zA-Z\'\’\-\s]+$/',
            'mobile' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'birthday' => 'required|date|before:-16 years|after:-100 years',
            'zip' => 'required|postal_code:FR',
            'city' => '',
            'longitude' => '',
            'latitude' => '',
            'department' => '',
            'structure_name' => 'required|min:3',
            'utm_source' => '',
            'utm_campaign' => '',
            'utm_medium' => '',
            'structure_api' => '',
            'statut_juridique' => '',
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
            'first_name.regex' => 'Votre prénom doit contenir uniquement des lettres',
            'last_name.required' => 'Un nom est requis',
            'last_name.regex' => 'Votre nom doit contenir uniquement des lettres',
            'email.required' => 'Un email est requis',
            'email.unique' => 'Cet email est déjà pris. Merci de vous connecter avec vos identifiants.',
            'email.email' => 'Cet email est mal formaté',
            'password.required' => 'Un mot de passe est requis',
            'password.min' => 'Votre mot de passe doit contenir au moins :min caractères',
            'structure_name.required' => 'Le nom de votre organisation est requis',
            'structure_name.min' => 'Votre organisation doit contenir au moins :min caractères',
            'birthday.required' => 'La date de naissance est requise',
            'birthday.date' => 'La date d\'anniversaire saisie n\'est pas correcte.',
            'birthday.after' => 'JeVeuxAider.gouv.fr est ouvert aux personnes de moins de 100 ans',
            'birthday.before' => 'JeVeuxAider.gouv.fr est ouvert aux personnes de plus de 16 ans',
            'postal_code' => 'Le code postal n\'est pas valide',
            'zip.required' => 'Le code postal est requis',
            'mobile.required' => 'Le numéro de téléphone est requis',
            'mobile' => 'Le numéro de téléphone n\'est pas valide',
        ];
    }
}
