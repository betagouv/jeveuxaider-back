<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\TerritoireRequest;

class TerritoireDeleteRequest extends TerritoireRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('delete', request()->route('territoire'));
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
