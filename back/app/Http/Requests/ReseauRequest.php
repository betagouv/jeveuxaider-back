<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ReseauRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'sometimes|required|min:3|max:255',
            'domaines' => '',
            'publics_beneficiaires' => '',
            'description' => 'required',
            'phone' => '',
            'email' => '',
            'address' => '',
            'latitude' => '',
            'longitude' => '',
            'zip' => '',
            'city' => '',
            'department' => [
                function ($attribute, $value, $fail) {
                    $datas = $this->validator->getData();
                    if (!empty($datas['zip'])) {
                        if (substr($datas['zip'], 0, strlen($value)) != $value) {
                            // Exeptions.
                            if (in_array($value, ['2A', '2B']) && substr($datas['zip'], 0, 2) == '20') {
                                return;
                            }

                            $fail("L'adresse et le département ne correspondent pas !");
                        }
                    }
                }
            ],
            'country' => '',
            'website' => 'max:255',
            'facebook' => 'max:255',
            'twitter' => 'max:255',
            'instagram' => 'max:255',
            'donation' => 'max:255',
            'logo' => '',
            'image_1' => '',
            'image_2' => '',
            'color' => '',
            'is_published' => '',
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
            'name.required' => 'Le nom du réseau est requis',
            'name.min' => 'Le nom du réseau doit contenir au moins :min lettres',
            'name.max' => 'Le nom du réseau peut contenir au plus :min lettres',
        ];
    }
}
