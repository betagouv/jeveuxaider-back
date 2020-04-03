<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CollectivityRequest extends FormRequest
{
    public function authorize()
    {
        if (backpack_auth()->check()) {
            return true;
        }
    }

    public function rules()
    {
        return [
            'name' => 'required|min:3|max:255',
            'type' => 'required',
            'description' => '',
            'zips' => 'required',
            'instagram' => '',
            'twitter' => '',
            'facebook' => '',
        ];
    }
}
