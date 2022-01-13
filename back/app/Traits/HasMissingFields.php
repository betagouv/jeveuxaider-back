<?php

namespace App\Traits;

trait HasMissingFields
{

    public function saveMissingFields()
    {

        if(!$this->checkFields){
            return;
        }

        $missingFields = [];

        foreach ($this->checkFields as $field) {
            if ($this->{$field}) {
                if(is_array($this->{$field}) && count($this->{$field}) == 0){
                    $missingFields[] = $field;
                }
            } else {
                $missingFields[] = $field;
            }
        }

        $this->missing_fields = count($missingFields) ? $missingFields : null;

    }

}