<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersStructureCeu implements Filter
{
    public function __invoke(Builder $query, $value, string $property) : Builder
    {
        return $query->ceu($value);
    }
}
