<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ProfileRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends ProfileRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $profile = request()->route('profile') ?: request()->user()->profile;

        return $this->user()->can('update', $profile);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $profile = request()->route('profile') ?: request()->user()->profile;

        return [
            'email' => [
                'sometimes',
                'email',
                'required',
                Rule::unique('profiles')->ignore($profile->id),
            ],
            'first_name' => 'sometimes|required|min:3',
            'last_name' => 'sometimes|required|min:2',
            'mobile' => '',
            'phone' => '',
            'avatar' => '',
            'referent_department' => '',
            'reseau_id' => '',
        ];
    }
}
