<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\DomaineRequest;

class DomaineCreateRequest extends DomaineRequest
{
    public function authorize()
    {
        return $this->user()->isAdmin();
    }
}
