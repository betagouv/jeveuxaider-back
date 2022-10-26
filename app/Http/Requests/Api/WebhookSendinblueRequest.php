<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class WebhookSendinblueRequest extends FormRequest
{
    public function rules()
    {
        return [
            'event' => 'required|in:hard_bounce',
            'email' => 'required|email'
        ];
    }
}
