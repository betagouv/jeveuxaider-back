<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersProfileZips implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return is_array($value) ? $query->whereIn('zip', $value) : $query->where('zip', $value);
    }
}
