<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\DomaineRequest;
use App\Models\Domaine;

class DomaineCreateRequest extends DomaineRequest
{
    public function authorize()
    {
        return $this->user()->can('create', Domaine::class);
    }
}
