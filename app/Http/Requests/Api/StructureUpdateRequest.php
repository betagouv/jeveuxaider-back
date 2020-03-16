<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\StructureRequest;

class StructureUpdateRequest extends StructureRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->route('structure'));
    }
}
