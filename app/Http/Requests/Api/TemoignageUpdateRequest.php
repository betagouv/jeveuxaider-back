<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\TemoignageRequest;
use App\Models\Temoignage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class TemoignageUpdateRequest extends TemoignageRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', request()->route('temoignage'));
    }

}
