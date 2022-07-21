<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContextualMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('addMessage', request()->route('conversation'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'contextual_state' => 'required',
            'contextual_reason' => '',
            'content' => '',
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
            'contextual_state.required' => 'Le nouveau statut est requis',
        ];
    }
}
