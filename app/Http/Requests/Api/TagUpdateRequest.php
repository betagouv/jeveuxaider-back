<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\TagRequest;

class TagUpdateRequest extends TagRequest
{
    public function authorize()
    {
        return $this->user()->can('update', request()->route('tag'));
    }
}
