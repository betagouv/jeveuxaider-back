<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TerritoireRequest extends FormRequest
{
    public function authorize()
    {
        // @TODO: territoire policy
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'min:2',
                'max:255',
            ],
            'suffix_title' => 'required',
            'type' => 'required',
            'description' => '',
            'department' => 'required',
            'zips' => '',
            'tags' => '',
            'is_published' => 'boolean',
            'state' => '',
            'seo_recruit_title' => '',
            'seo_recruit_description' => '',
            'seo_engage_title' => '',
            'seo_engage_paragraphs' => '',
            'promoted_organisations' => ''
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Le nom est requis',
            'name.min' => 'Le nom doit contenir au moins :min lettres',
            'name.max' => 'Le nom peut contenir au plus :min lettres',
            'suffix_title.required' => 'Le suffix est requis',
            'type.required' => 'Le type est requis',
            'department.required' => 'Le département est requis',
        ];
    }
}
