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
            'responsable_id' => '',
            'start_date' => 'nullable|date_format:Y-m-d H:i:s',
            'end_date' => 'nullable|date_format:Y-m-d H:i:s|after:start_date',
            'structure_id' => '',
            'format' => 'sometimes|required',
            'information' => '',
            'objectif' => 'required_without:template_id',
            'description' => 'required_without:template_id',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'zip' => 'required',
            'city' => 'required',
            'department' => [
                'required',
                function ($attribute, $value, $fail) {
                    $datas = $this->validator->getData();
                    if (!empty($datas['zip'])) {
                        if (!str_contains($datas['zip'], $value)) {
                            // Exeptions.
                            if (in_array($value, ['2A', '2B']) && str_contains($datas['zip'], '20')) {
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
        ];
    }
}
