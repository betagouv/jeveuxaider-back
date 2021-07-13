<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\TerritoireRequest;
use Illuminate\Validation\Rule;

class TerritoireUpdateRequest extends TerritoireRequest
{
    public function authorize()
    {
        return $this->user()->can('update', request()->route('territoire'));
    }
}
