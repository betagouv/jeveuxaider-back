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
            'first_name' => 'required|regex:/^[a-zA-Z\'\’\-\s]+$/',
            'last_name' => 'required|min:2|regex:/^[a-zA-Z\'\’\-\s]+$/',
            'mobile' => '',
            'phone' => '',
            'avatar' => '',
        ];
    }
}
