<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageTemplateRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->isAdmin();
    }

    public function rules()
    {
        return [
            'user_id' => 'required',
            'name' => 'required',
            'message' => 'required',
            'is_shared' => 'boolean',
        ];
    }
}
