<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class AddressesInDepartment implements ValidationRule, DataAwareRule
{
    protected $data = [];

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $department = $this->data['department'];

        if($this->data['is_autonomy'] === false) {
            return;
        }

        foreach ($value as $address) {
            if (substr($address['zip'], 0, strlen($department)) != $department) {
                // Exeptions.
                if (in_array($department, ['2A', '2B']) && substr($address['zip'], 0, 2) == '20') {
                    continue;
                }
                $fail('Les codes postaux et le département ne correspondent pas !');

                return;
            }
        }
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
