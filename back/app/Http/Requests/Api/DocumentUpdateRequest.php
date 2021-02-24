<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\DocumentRequest;

class DocumentUpdateRequest extends DocumentRequest
{
    public function authorize()
    {
        return $this->user()->can('update', request()->route('document'));
    }
}
