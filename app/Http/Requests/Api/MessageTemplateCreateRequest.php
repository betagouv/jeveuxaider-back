<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\MessageTemplateRequest;
use App\Models\MessageTemplate;

class MessageTemplateCreateRequest extends MessageTemplateRequest
{
    public function authorize()
    {
        return $this->user()->can('create', MessageTemplate::class);
    }
}
