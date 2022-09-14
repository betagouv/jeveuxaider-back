<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\DomaineRequest;
use Illuminate\Validation\Rule;

class DomaineUpdateRequest extends DomaineRequest
{
    public function authorize()
    {
        return $this->user()->isAdmin();
    }

    public function rules()
    {
        $domaine = request()->route('domaine');

        return [
            'name' => [
                'required',
                Rule::unique('domaines')->ignore($domaine->id),
                'min:3',
                'max:255',
            ],
            'published' => 'boolean',
            'description' => 'nullable',
            'title' => 'required',
        ];
    }
}
