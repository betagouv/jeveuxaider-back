<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersProfileZips implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return is_array($value) ? $query->whereIn('zip', $value) : $query->where('zip', $value);
    }
}
