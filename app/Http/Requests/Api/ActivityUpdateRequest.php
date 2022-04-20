<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ActivityRequest;
use Illuminate\Validation\Rule;

class ActivityUpdateRequest extends ActivityRequest
{
    public function authorize()
    {
        return $this->user()->isAdmin();
    }


    public function rules()
    {
        $activity = request()->route('activity');

        return [
            'name' => [
                'required',
                Rule::unique('activities')->ignore($activity->id),
                'min:3',
                'max:255',
            ],
            'description' => '',
            'is_published' => 'boolean',
            'seo_recruit_title' => '',
            'seo_recruit_description' => '',
            'seo_engage_title' => '',
            'seo_engage_paragraphs' => '',
            'promoted_organisations' => '',
            'domaines' => 'required',
            'promoted_organisations' => ''
        ];
    }
}
