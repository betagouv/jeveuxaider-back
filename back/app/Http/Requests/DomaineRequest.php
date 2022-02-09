<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DomaineRequest extends FormRequest
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
                'unique:domaines',
                'min:3',
                'max:255',
            ],
            'published' => 'boolean',
            'description' => 'nullable',
            'title' => 'required',
        ];
    }
}
