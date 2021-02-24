<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ThematiqueRequest;
use App\Models\Thematique;

class ThematiqueCreateRequest extends ThematiqueRequest
{
    public function authorize()
    {
        return $this->user()->can('create', Thematique::class);
    }
}
