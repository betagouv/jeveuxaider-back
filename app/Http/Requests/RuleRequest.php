<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RuleRequest extends FormRequest
{
    public function authorize()
    {
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
            'events' => 'array|required',
            'conditions' => 'array|required',
            'actions' => 'array|required',
            'is_active' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Le nom est requis',
            'name.min' => 'Le nom doit contenir au moins :min lettres',
            'name.max' => 'Le nom peut contenir au plus :min lettres',
        ];
    }
}
