<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;

trait HasMissingFields
{
    public function getMissingFieldsAttribute()
    {
        if (! $this->checkFields) {
            return [];
        }

        $missingFields = [];

        foreach ($this->checkFields as $field) {
            if ($this->{$field}) {
                if (is_array($this->{$field}) || $this->{$field} instanceof Collection) {
                    if (count($this->{$field}) == 0) {
                        $missingFields[] = $field;
                    }
                }
            } else {
                $missingFields[] = $field;
            }
        }

        return count($missingFields) ? $missingFields : [];
    }

    public function getCompletionRateAttribute()
    {
        $missingFields = $this->getMissingFieldsAttribute();

        return count($this->checkFields) > 0 ? 100 - round(count($missingFields) * 100 / count($this->checkFields)) : 100;
    }
}
