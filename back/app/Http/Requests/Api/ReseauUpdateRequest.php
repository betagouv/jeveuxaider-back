<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ReseauRequest;

class ReseauUpdateRequest extends ReseauRequest
{
    public function authorize()
    {
        return $this->user()->can('update', $this->route('reseau'));
    }
}
