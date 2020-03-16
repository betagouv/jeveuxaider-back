<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\YoungRequest;
use Illuminate\Validation\Rule;

class YoungUpdateRequest extends YoungRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $young = request()->route('young');

        return $this->user()->can('update', $young);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $young = request()->route('young');

        return [
            'email' => [
                'nullable',
                'email',
                Rule::unique('youngs')->ignore($young->id),
            ],
            'first_name' => 'sometimes|required|min:3',
            'last_name' => 'sometimes|required|min:2',
            'phone' => '',
            'department' => '',
            'address' => '',
            'zip' => '',
            'city' => '',
            'regular_city' => '',
            'regular_latitude' => '',
            'regular_longitude' => '',
            'latitude' => '',
            'longitude' => '',
            'engaged' => '',
            'engaged_structure' => '',
            'interet_defense' => '',
            'interet_defense_type' => '',
            'interet_defense_domaine' => '',
            'interet_defense_motivation' => '',
            'interet_securite' => '',
            'interet_securite_domaine' => '',
            'interet_solidarite' => '',
            'interet_sante' => '',
            'interet_education' => '',
            'interet_culture' => '',
            'interet_sport' => '',
            'interet_environnement' => '',
            'interet_citoyennete' => '',
            'mission_format' => '',
            'mission_autonome_projet' => '',
            'mission_autonome_structure' => '',
            'contraintes' => '',
            'situation' => '',
            'genre' => '',
            'notes' => '',
            'mission_id' => '',
            'state' => '',
        ];
    }
}
