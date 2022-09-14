<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\TemoignageRequest;

class TemoignageUpdateRequest extends TemoignageRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', request()->route('temoignage'));
    }
}
