<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class AddressIsNeeded implements ValidationRule, DataAwareRule
{
    protected $data = [];

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->data['is_autonomy'] === true) {
            return;
        }

        // Hack - Dom Tom (Nouvelle Calédonie et Polynésie française)
        if (in_array($this->data['department'], ['987', '988'])) {
            return;
        }

        if ($this->data['type'] !== 'Mission en présentiel') {
           return;
        }

        if(!$value){
            $fail('Le champs :attribute est obligatoire pour une mission en présentiel');
        }

    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
