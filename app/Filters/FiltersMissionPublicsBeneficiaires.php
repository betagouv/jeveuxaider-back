<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersMissionPublicsBeneficiaires implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {
            if (is_array($value)) {
                foreach ($value as $v) {
                    $query->orWhereJsonContains('publics_beneficiaires', $v);
                }
            } else {
                $query->whereJsonContains('publics_beneficiaires', $value);
            }
        });
    }
}
