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
        if (backpack_auth()->check()) {
            return true;
        }

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
            'name' => 'sometimes|required|min:3|max:255',
            'tuteur_id' => '',
            'start_date' => 'nullable|date_format:Y-m-d H:i:s',
            'end_date' => 'nullable|date_format:Y-m-d H:i:s|after:start_date',
            'structure_id' => '',
            'domaines' => '',
            'format' => 'sometimes|required',
            'description' => '',
            'address' => '',
            'latitude' => '',
            'longitude' => '',
            'zip' => '',
            'city' => '',
            'department' => '',
            'country' => '',
            'participations_max'=> 'integer',
            'dates_infos'=> '',
            'periodes'=> '',
            'frequence'=> '',
            'planning'=> '',
            'actions'=> '',
            'justifications'=> '',
            'contraintes'=> '',
            'handicap'=> '',
            'state' => '',
            'periodicite' => '',
            'publics_volontaires' => '',
            'publics_beneficiaires' => '',
            'domaine' => '',
            'type' => ''
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
        ];
    }
}
