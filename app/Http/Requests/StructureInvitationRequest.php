<?php

namespace App\Http\Requests;

use App\Models\Profile;
use Illuminate\Foundation\Http\FormRequest;

class StructureInvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('view', request()->route('structure'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => [
                'required',
                function ($attribute, $value, $fail) {
                    $profile = Profile::where('email', 'ILIKE', $value)->first();
                    if ($profile && $profile->structures->count() > 0) {
                        $fail('Cet email appartient déjà à une organisation.');
                    }
                }
            ],
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:2',
            'role' => 'required|in:responsable'
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
            'email.required' => 'Un email est requis',
            'first_name.required' => 'Un prénom est requis',
            'first_name.min' => 'Votre prénom doit contenir au moins :min lettres',
            'last_name.required' => 'Un nom est requis',
            'last_name.min' => 'Votre nom doit contenir au moins :min lettres',
        ];
    }
}
