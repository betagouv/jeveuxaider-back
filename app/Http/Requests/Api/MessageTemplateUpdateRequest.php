<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\MessageTemplateRequest;

class MessageTemplateUpdateRequest extends MessageTemplateRequest
{
    public function authorize()
    {
        return $this->user()->can('update', request()->route('messageTemplate'));
    }
}
