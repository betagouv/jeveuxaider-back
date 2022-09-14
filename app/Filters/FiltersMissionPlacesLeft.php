<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersMissionPlacesLeft implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $value ? $query->hasPlacesLeft() : $query->complete();
    }
}
