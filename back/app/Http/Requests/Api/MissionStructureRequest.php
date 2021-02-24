<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class MissionStructureRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('update', request()->route('mission'));
    }

    public function rules()
    {
        return [];
    }
}
