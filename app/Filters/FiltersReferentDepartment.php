<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersReferentDepartment implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->whereHas('user.departmentsAsReferent', function ($query) use ($value) {
            $query->where('number', $value);
        });
    }
}
