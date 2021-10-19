<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersStructureAntenne implements Filter
{
    public function __invoke(Builder $query, $value, string $property) : Builder
    {
        return $value ? $query->whereHas('reseaux') : $query->whereDoesntHave('reseaux');
    }
}
