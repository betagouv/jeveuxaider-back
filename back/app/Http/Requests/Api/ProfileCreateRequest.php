<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ProfileRequest;

class ProfileCreateRequest extends ProfileRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|unique:profiles',
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:2',
            'role' => 'required|in:referent,analyste,referent_regional',
            'mobile' => '',
            'phone' => '',
            'avatar' => '',
            'referent_department' => '',
            'referent_region' => '',
            'tete_de_reseau_id' => '',
            'is_analyste' => 'boolean'
        ];
    }
}
