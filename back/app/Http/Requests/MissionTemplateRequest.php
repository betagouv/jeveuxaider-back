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
            'reseau_id' => '',
            'published' => 'boolean',
            'priority' => 'boolean',
            // 'state' => 'required'
        ];
    }
}
