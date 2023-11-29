<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\NoteRequest;
use Illuminate\Foundation\Http\FormRequest;

class NoteUpdateRequest extends NoteRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', request()->route('note'));
    }

}
