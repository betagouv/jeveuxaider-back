<?php

namespace App\Http\Requests;

use App\Models\MissionTemplate;
use App\Models\Structure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', request()->route('mission'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'state' => [
                function ($attribute, $value, $fail) {
                    $datas = $this->validator->getData();

                    // Creation
                    if (!$this->mission) {
                        if ($value === 'Validée' && (empty($datas['template_id']) || empty($datas['template']))) {
                            $fail('Une erreur est survenue.');
                        }
                    }

                    // Edit
                    if ($this->mission && $this->mission->state !== $value) { // State will  change
                        if ($value == 'Validée' && $this->mission->structure->state != 'Validée') {
                            $fail('Vous devez valider l\'organisation au préalable.');
                        }
                        if (!$this->user()->can('changeState', [$this->mission, $value])) {
                            $fail('Vous n\'êtes pas autorisé à changer le statut de cette mission.');
                        }
                    }
                },
            ],
            'tags' => '',
            'is_registration_open' => '',
            'is_online' => '',
        ];
    }
}
