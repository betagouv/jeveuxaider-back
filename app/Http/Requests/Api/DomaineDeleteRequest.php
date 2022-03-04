<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\DomaineRequest;

class DomaineDeleteRequest extends DomaineRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('delete', request()->route('domaine'));
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
