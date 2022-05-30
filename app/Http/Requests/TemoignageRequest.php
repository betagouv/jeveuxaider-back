<?php

namespace App\Http\Requests;

use App\Models\Temoignage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class TemoignageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'participation_id' => 'required',
            'grade' => 'required',
            'testimony' => '',
            'is_published' => 'boolean'
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'participation_id' => "L'identifiant de participation est requis.",
            'grade' => 'La note est requise.',
        ];
    }
}
