<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\UserAlertRequest;

class UserAlertUpdateRequest extends UserAlertRequest
{
    public function authorize()
    {
        return $this->user()->can('update', request()->route('alert'));
    }
}
