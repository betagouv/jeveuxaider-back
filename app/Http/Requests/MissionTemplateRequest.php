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
            'description' => 'required',
            'objectif' => 'required',
            'thematique_id' => 'nullable',
            'published' => 'boolean'
        ];
    }
}
