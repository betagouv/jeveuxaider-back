<?php

namespace App\Http\Requests\Api;

use App\Models\Temoignage;
use Illuminate\Foundation\Http\FormRequest;

class TemoignageCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        // return $this->authorize('create', Temoignage::class);
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
