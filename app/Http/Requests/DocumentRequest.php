<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->isAdmin();
    }

    public function rules()
    {
        return [
            'title' => 'required',
            'link' => 'required_if:type,link',
            'description' => '',
            'roles' => '',
            'type' => '',
            'is_published' => 'boolean',
        ];
    }
}
