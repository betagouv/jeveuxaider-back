<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivityRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->isAdmin();
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'unique:activities',
                'min:2',
                'max:255',
            ],
            'description' => '',
            'is_published' => 'boolean',
            'seo_recruit_title' => '',
            'seo_recruit_description' => '',
            'seo_engage_title' => '',
            'seo_engage_paragraphs' => '',
            'promoted_organisations' => '',
            'domaines' => 'required',
            'promoted_organisations' => '',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Le nom est requis',
            'name.unique' => 'Cette activité existe déjà',
            'name.min' => 'Le nom doit contenir au moins :min lettres',
            'name.max' => 'Le nom peut contenir au plus :min lettres',
        ];
    }
}
