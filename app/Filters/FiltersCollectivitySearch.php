<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersCollectivitySearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query
            ->where('name', 'LIKE', '%' . $value . '%');
    }
}
