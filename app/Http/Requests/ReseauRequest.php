<?php

namespace App\Http\Requests;

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
                function ($attribute, $department, $fail) {
                    $datas = $this->validator->getData();
                    if (! empty($datas['zip'])) {
                        $zip = str_replace(' ', '', $datas['zip']);

                        if (substr($zip, 0, strlen($department)) != $department) {
                            // Exeptions.
                            if (in_array($department, ['2A', '2B']) && substr($zip, 0, 2) == '20') {
                                return;
                            }

                            $fail("L'adresse et le département ne correspondent pas !");
                        }
                    }
                },
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
