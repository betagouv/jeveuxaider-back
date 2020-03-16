<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\YoungRequest;

class YoungDeleteRequest extends YoungRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $young = request()->route('young');

        return $this->user()->can('delete', $young);
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
