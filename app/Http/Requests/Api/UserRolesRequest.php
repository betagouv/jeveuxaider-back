<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UserRolesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasRole(['admin', 'referent', 'referent_regional', 'responsable', 'tete_de_reseau', 'responsable_territoire']);
    }

    public function rules()
    {
        return [];
    }
}
