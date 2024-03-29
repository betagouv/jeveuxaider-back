<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\NoteRequest;
use App\Models\Note;
use Illuminate\Foundation\Http\FormRequest;

class NoteCreateRequest extends NoteRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Note::class);
    }
}
