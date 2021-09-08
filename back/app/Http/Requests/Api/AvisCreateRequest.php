<?php

namespace App\Http\Requests\Api;

use App\Models\Avis;
use Illuminate\Foundation\Http\FormRequest;

class AvisCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        // return $this->authorize('create', Avis::class);
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
