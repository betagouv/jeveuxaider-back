<?php

namespace App\Http\Requests;

use App\Models\Invitation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', [Invitation::class, $this]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'email' => [
                'email',
                'required',
                Rule::unique('invitations')->where(function ($query) {
                    return $query->where('role', request()->input('role'))
                        ->where('email', request()->input('email'))
                        ->where('invitable_type', request()->input('invitable_type'))
                        ->where('invitable_id', request()->input('invitable_id'));
                }),
            ],
            'role' => 'required',
            'invitable_id' => 'required_if:role,responsable_organisation,responsable_territoire,responsable_reseau,responsable_antenne',
            'invitable_type' => '',
            'properties' => 'required_if:role,referent_regional,referent_departemental,responsable_antenne',
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
