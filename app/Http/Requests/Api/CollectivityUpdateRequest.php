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
            'name' => [
                'required',
                Rule::unique('collectivities')->ignore($collectivity->id),
                'min:2',
                'max:255',
            ],
            'title' => '',
            'type' => 'required',
            'description' => '',
            'department' => '',
            'zips' => '',
            'state' => '',
            'published' => 'boolean',
        ];
    }
}
