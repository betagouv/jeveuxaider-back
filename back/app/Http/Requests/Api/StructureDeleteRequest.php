<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\StructureRequest;

class StructureDeleteRequest extends StructureRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $structure = request()->route('structure');

        return $this->user()->can('delete', $structure);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
