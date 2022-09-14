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
            'user_id' => 'sometimes',
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
            'department' => [
                function ($attribute, $department, $fail) {
                    $datas = $this->validator->getData();
                    if (! empty($datas['zip'])) {
                        $zip = str_replace(' ', '', $datas['zip']);

                        if (substr($zip, 0, strlen($department)) != $department) {
                            // Exeptions.
                            if (in_array($department, ['2A', '2B']) && substr($zip, 0, 2) == '20') {
                                return;
                            }

                            $fail("L'adresse et le département ne correspondent pas !");
                        }
                    }
                },
            ],
            'country' => '',
            'website' => 'max:255',
            'facebook' => 'max:255',
            'twitter' => 'max:255',
            'instagram' => 'max:255',
            'donation' => 'max:255',
            'state' => '',
            'publics_beneficiaires' => '',
            'domaines' => '',
            'image_1' => '',
            'image_2' => '',
            'rna' => '',
            'api_id' => '',
            'phone' => '',
            'email' => '',
            'color' => '',
            'send_volunteer_coordonates' => '',
            'tete_de_reseau_id' => '',
            'reseaux' => '',
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
            'website.max' => 'Le lien de votre site doit contenir au plus :max caractères',
            'facebook.max' => 'Le lien facebook doit contenir au plus :max caractères',
            'twitter.max' => 'Le lien twitter doit contenir au plus :max caractères',
            'instagram.max' => 'Le lien instagram doit contenir au plus :max caractères',
            'donation.max' => 'Le lien de donation doit contenir au plus :max caractères',
        ];
    }
}
