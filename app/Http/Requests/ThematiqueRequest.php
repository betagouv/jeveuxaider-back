<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThematiqueRequest extends FormRequest
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
                'unique:thematiques',
                'min:3',
                'max:255',
            ],
            'published' => 'boolean',
            'domaine_id' => 'nullable',
            'description' => 'nullable',
            'title' => 'required',
            'color' => 'required'
        ];
    }
}
