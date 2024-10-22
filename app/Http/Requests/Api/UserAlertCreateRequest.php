<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\UserAlertRequest;
use App\Models\UserAlert;

class UserAlertCreateRequest extends UserAlertRequest
{
    public function authorize()
    {
        return $this->user()->can('create', UserAlert::class);
    }
}
