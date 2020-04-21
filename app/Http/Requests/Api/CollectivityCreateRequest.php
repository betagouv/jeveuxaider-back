<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\CollectivityRequest;
use App\Models\Collectivity;

class CollectivityCreateRequest extends CollectivityRequest
{
    public function authorize()
    {
        return $this->user()->can('create', Collectivity::class);
    }
}
