<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', request()->route('mission'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'sometimes|required',
            'name' => 'required_without:template_id|min:3|max:255',
            'responsable_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'structure_id' => '',
            'information' => '',
            'objectif' => 'required_without:template_id',
            'description' => 'required_without:template_id',
            'address' => 'requiredIf:type,Mission en présentiel',
            'latitude' => 'requiredIf:type,Mission en présentiel',
            'longitude' => 'requiredIf:type,Mission en présentiel',
            'zip' => 'requiredIf:type,Mission en présentiel',
            'city' => 'requiredIf:type,Mission en présentiel',
            'department' => [
                'requiredIf:type,Mission en présentiel',
                function ($attribute, $department, $fail) {
                    $datas = $this->validator->getData();
                    if (!empty($datas['zip'])) {
                        $zip = str_replace(' ', '', $datas['zip']);

                        if (substr($zip, 0, strlen($department)) != $department) {
                            // Exeptions.
                            if (in_array($department, ['2A', '2B']) && substr($zip, 0, 2) == '20') {
                                return;
                            }

                            $fail("L'adresse et le département ne correspondent pas !");
                        }
                    }
                }
            ],
            'participations_max'=> 'integer',
            'dates_infos'=> '',
            'state' => [
                function ($attribute, $value, $fail) {
                    if ($this->mission && $this->mission->state !== $value) { // State will  change
                        if (! $this->user()->can('changeState', [$this->mission, $value])) {
                            $fail('Vous n\'êtes pas autorisé à changer le statut de cette mission.');
                        }
                    }
                }
            ],
            'periodicite' => '',
            'publics_volontaires' => '',
            'publics_beneficiaires' => '',
            'type' => '',
            'domaine_id' => 'required_without:template_id',
            'template_id' => '',
            'tags' => '',
            'thumbnail' => '',
            'skills' => '',
            'domaine_secondaire' => '',
            'commitment__duration' => 'required',
            'commitment__time_period' => '',
            'is_priority' => '',
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
            'user_id.required' => 'La mission doit appartenir à un utilisateur',
            'structure_id.required' => 'La mission doit avoir une structure',
            'name.required' => 'Le nom de la mission est requis',
            'name.min' => 'Le nom de la mission doit contenir au moins :min lettres',
            'name.max' => 'Le nom de la mission peut contenir au plus :min lettres',
            'start_date.date' => 'La date de début doit être au format YYYY-MM-DD H:i:s',
            'start_date.date_format' => 'La date de début doit être au format YYYY-MM-DD H:i:s',
            'end_date.date' => 'La date de fin doit être au format YYYY-MM-DD H:i:s',
            'end_date.date_format' => 'La date de fin doit être au format YYYY-MM-DD H:i:s',
            'end_date.after' => 'La date de fin doit être supérieur à celle de début',
            'name.required_without' => 'Le nom de la mission est requis',
            'responsable_id.required' => 'Sélectionnez le contact principal de la mission'
        ];
    }
}
