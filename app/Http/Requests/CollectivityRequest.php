<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CollectivityRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->isAdmin();
    }

    public function rules()
    {
        return [
            'title' => [
                'required',
                'unique:collectivities',
                'min:3',
                'max:255',
            ],
            'type' => 'required',
            'description' => '',
            'department' => '',
            'zips' => '',
        ];
    }
}
