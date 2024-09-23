<?php

namespace App\Http\Requests;

use App\Rules\UniqueInsensitive;
use Illuminate\Foundation\Http\FormRequest;

class RegisterInvitationRequest extends FormRequest
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
        ];
    }
}
