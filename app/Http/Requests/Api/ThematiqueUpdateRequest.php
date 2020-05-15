<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ThematiqueRequest;
use Illuminate\Validation\Rule;

class ThematiqueUpdateRequest extends ThematiqueRequest
{
    public function authorize()
    {
        return $this->user()->can('update', request()->route('thematique'));
    }


    public function rules()
    {
        $thematique = request()->route('thematique');

        return [
            'title' => [
                'required',
                Rule::unique('thematiques')->ignore($thematique->id),
                'min:3',
                'max:255',
            ],
            'published' => 'boolean',
        ];
    }
}
