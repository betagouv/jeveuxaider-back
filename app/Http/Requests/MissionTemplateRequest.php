<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MissionTemplateRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->isAdmin();
    }

    public function rules()
    {
        return [
            'title' => 'required',
            'subtitle' => 'required',
            'description' => 'required',
            'objectif' => 'required',
            'domaine_id' => 'required',
            'domaine_secondary_id' => '',
            'reseau_id' => '',
            'activity_id' => '',
            'activity_secondary_id' => '',
            'published' => 'boolean',
            'priority' => 'boolean',
            'state' => '',
            'tags' => '',
            'recommendation_date_type' => '',
            'recommendation_with_dates' => 'boolean|nullable',
            'recommendation_type' => '',
        ];
    }
}
