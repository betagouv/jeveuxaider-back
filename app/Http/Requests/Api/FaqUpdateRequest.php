<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\FaqRequest;

class FaqUpdateRequest extends FaqRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->route('faq'));
    }
}
