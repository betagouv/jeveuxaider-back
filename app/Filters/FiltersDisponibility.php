<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersDisponibility implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->whereJsonContains('disponibilities', $value);
    }
}
