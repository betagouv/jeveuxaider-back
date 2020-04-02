<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\CollectivityRequest;

class CollectivityUpdateRequest extends CollectivityRequest
{

    public function authorize()
    {
        return $this->user()->can('update', request()->route('collectivity'));
    }
    
}
