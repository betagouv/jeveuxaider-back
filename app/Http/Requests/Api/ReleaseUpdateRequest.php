<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ReleaseRequest;

class ReleaseUpdateRequest extends ReleaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->route('release'));
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'date' => 'required',
            'description' => 'required',
        ];
    }
}
