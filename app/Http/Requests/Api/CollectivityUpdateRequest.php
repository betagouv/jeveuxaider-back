<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\CollectivityRequest;
use Illuminate\Validation\Rule;

class CollectivityUpdateRequest extends CollectivityRequest
{
    public function authorize()
    {
        return $this->user()->can('update', request()->route('collectivity'));
    }


    public function rules()
    {
        $collectivity = request()->route('collectivity');

        return [
            'title' => [
                'required',
                Rule::unique('collectivities')->ignore($collectivity->id),
                'min:3',
                'max:255',
            ],
            'type' => 'required',
            'description' => '',
            'department' => '',
            'zips' => '',
            'published' => 'boolean',
        ];
    }
}
