<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\StructureRequest;
use App\Models\Structure;

class StructureCreateRequest extends StructureRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Structure::class);
    }
}
