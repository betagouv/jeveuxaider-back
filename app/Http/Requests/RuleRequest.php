<?php

namespace App\Http\Requests;

use App\Models\Term;
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
            'event' => 'required',
            'conditions' => 'array|required',
            'action_key' => 'required',
            'action_value' => [
                'required',
                function ($attribute, $value, $fail) {
                    $datas = $this->validator->getData();
                    if(!$this->isActionValueCorrect($datas, $value)) {
                        $fail("La valeur $value n'est pas valide");
                    }
                },
            ],
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

    protected function isActionValueCorrect($datas, $value) {

        switch($datas['action_key']){
            case 'mission_attach_tag':
                return Term::where('id', $value)->exists();
            case 'mission_detach_tag':
                return Term::where('id', $value)->exists();
        }

        return false;
    }
}
