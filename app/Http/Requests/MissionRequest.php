<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'structure_id' => '',
            'information' => '',
            'objectif' => 'required_without:template_id',
            'description' => 'required_without:template_id',
            'address' => '',
            'latitude' => [
                Rule::requiredIf(function () {
                    if ($this->request->get('is_autonomy') === true) {
                        return false;
                    }
                    // Hack - Dom Tom (Nouvelle Calédonie et Polynésie française)
                    if (in_array($this->request->get('department'), ['987', '988'])) {
                        return false;
                    }
                    if ($this->request->get('type') == 'Mission en présentiel') {
                        return true;
                    }
                }),
            ],
            'longitude' => [
                Rule::requiredIf(function () {
                    if ($this->request->get('is_autonomy') === true) {
                        return false;
                    }
                    // Hack - Dom Tom (Nouvelle Calédonie et Polynésie française)
                    if (in_array($this->request->get('department'), ['987', '988'])) {
                        return false;
                    }
                    if ($this->request->get('type') == 'Mission en présentiel') {
                        return true;
                    }
                }),
            ],
            'zip' => [
                Rule::requiredIf(function () {
                    if ($this->request->get('type') === 'Mission en présentiel' && $this->request->get('is_autonomy') === false) {
                        return true;
                    }
                }),
            ],
            'city' => [
                Rule::requiredIf(function () {
                    if ($this->request->get('type') === 'Mission en présentiel' && $this->request->get('is_autonomy') === false) {
                        return true;
                    }
                }),
            ],
            'department' => [
                'requiredIf:type,Mission en présentiel',
                function ($attribute, $department, $fail) {
                    $datas = $this->validator->getData();
                    if (empty($datas['is_autonomy']) && ! empty($datas['zip'])) {
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
            'participations_max' => 'integer',
            'dates_infos' => '',
            'state' => [
                function ($attribute, $value, $fail) {
                    if ($this->mission && $this->mission->state !== $value) { // State will  change
                        if ($value == 'Validée' && $this->mission->structure->state != 'Validée') {
                            $fail('Vous devez valider l\'organisation au préalable.');
                        }
                        if (! $this->user()->can('changeState', [$this->mission, $value])) {
                            $fail('Vous n\'êtes pas autorisé à changer le statut de cette mission.');
                        }
                    }
                },
            ],
            'periodicite' => '',
            'publics_volontaires' => '',
            'publics_beneficiaires' => '',
            'type' => '',
            'domaine_id' => 'required_without:template_id',
            'domaine_secondary_id' => '',
            'template_id' => '',
            'thumbnail' => '',
            'skills' => '',
            'commitment__duration' => 'required',
            'commitment__time_period' => '',
            'is_priority' => '',
            'is_snu_mig_compatible' => '',
            'snu_mig_places' => 'required_if:is_snu_mig_compatible,true',
            'activity_id' => '',
            'is_autonomy' => '',
            'autonomy_zips' => [
                'required_if:is_autonomy,true',
                function ($attribute, $autonomy_zips, $fail) {
                    $datas = $this->validator->getData();
                    if (! empty($autonomy_zips) && ! empty($datas['department'])) {
                        $department = $datas['department'];
                        foreach ($autonomy_zips as $item) {
                            if (substr($item['zip'], 0, strlen($department)) != $department) {
                                // Exeptions.
                                if (in_array($department, ['2A', '2B']) && substr($item['zip'], 0, 2) == '20') {
                                    continue;
                                }
                                $fail('Les codes postaux et le département ne correspondent pas !');

                                return;
                            }
                        }
                    }
                },
            ],
            'autonomy_precisions' => '',
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
            'responsable_id.required' => 'Sélectionnez le contact principal de la mission',
            'snu_mig_places.required_if' => 'Merci d\'indiquer le nombre de places pour les jeunes du SNU',
        ];
    }
}
