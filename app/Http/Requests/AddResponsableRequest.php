<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddResponsableRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->isAdmin();
    }

    public function rules()
    {
        return [
            'email' => 'required|email|exists:profiles,email',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Un email est requis',
            'email.email' => 'Le format de l\'email est invalide',
            'email.exists' => 'Cet email n\'existe pas',
        ];
    }
}
