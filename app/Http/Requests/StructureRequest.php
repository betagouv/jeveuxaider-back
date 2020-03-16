<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StructureRequest extends FormRequest
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
            'name' => 'sometimes|required|min:3|max:255',
            'user_id' => 'sometimes|required',
            'logo' => '',
            'siret' => '',
            'statut_juridique' => 'sometimes|required',
            'association_types' => '',
            'structure_publique_type' => '',
            'structure_publique_etat_type' => '',
            'structure_privee_type' => '',
            'description' => '',
            'address' => '',
            'latitude' => '',
            'longitude' => '',
            'zip' => '',
            'city' => '',
            'department' => '',
            'country' => '',
            'website' => '',
            'facebook' => '',
            'twitter' => '',
            'instagram' => '',
            'reseau_id' => '',
            'is_reseau' => '',
            'association_types' => '',
            'structure_publique_type' => '',
            'structure_publique_etat_type' => '',
            'structure_privee_type' => '',
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
            'user_id.required' => 'La structure doit appartenir à un utilisateur',
            'name.required' => 'Le nom de la structure est requis',
            'name.min' => 'Le nom de la structure doit contenir au moins :min lettres',
            'name.max' => 'Le nom de la structure peut contenir au plus :min lettres',
            'statut_juridique.required' => 'Le statut juridique est requis',
        ];
    }
}
