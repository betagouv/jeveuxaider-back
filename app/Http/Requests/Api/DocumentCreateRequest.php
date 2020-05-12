<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\DocumentRequest;
use App\Models\Document;

class DocumentCreateRequest extends DocumentRequest
{
    public function authorize()
    {
        return $this->user()->can('create', Document::class);
    }
}
