<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class AddressesInSameDepartment implements ValidationRule, DataAwareRule
{
    protected $data = [];

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $firstDepartment = $value[0]['department'];
            foreach ($value as $address) {
                if($address['department'] !== $firstDepartment) {
                    $fail('Toutes les adresses doivent être dans le même département.');
                    return;
                }
            }
        } catch (\Exception $e) {
            $fail('Une erreur est survenue ', $e->getMessage());
        }
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
