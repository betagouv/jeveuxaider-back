<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TerritoireRequest extends FormRequest
{
    public function authorize()
    {
        // @TODO: territoire policy
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
            'suffix_title' => 'required',
            'type' => 'required',
            'description' => '',
            'department' => 'required',
            'zips' => '',
            'tags' => '',
            'is_published' => 'boolean',
            'state' => '',

            'seo_recruit_title' => '',
            'seo_recruit_description' => '',
            'seo_engage_title' => '',
            'seo_engage_paragraphs' => '',
        ];
    }
}
