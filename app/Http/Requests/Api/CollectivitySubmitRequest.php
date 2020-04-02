<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\CollectivityRequest;

class CollectivitySubmitRequest extends CollectivityRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|min:3|max:255',
            'type' => 'required',
            'description' => 'required',
            'zips' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'email|required',
            'mobile' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        ];
    }
}
