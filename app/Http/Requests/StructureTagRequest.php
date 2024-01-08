<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StructureTagRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('manageTags', $this->route('structure'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => [
                'required',
                'unique:structures_tags,name,NULL,id,structure_id,' . $this->route('structure')->id,
            ]
        ];

        return $rules;
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Un nom est requis',
            'name.unique' => 'Ce tag existe déjà',
        ];
    }
}
