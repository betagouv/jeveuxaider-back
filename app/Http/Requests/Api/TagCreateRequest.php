<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\TagRequest;
use Spatie\Tags\Tag;

class TagCreateRequest extends TagRequest
{
    public function authorize()
    {
        return $this->user()->can('create', Tag::class);
    }
}
