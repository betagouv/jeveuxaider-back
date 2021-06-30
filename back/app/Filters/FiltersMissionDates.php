<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersMissionDates implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        if ($value == 'incoming') {
            return $query->incoming();
        }
        if ($value == 'current') {
            return $query->current();
        }
        if ($value == 'outdated') {
            return $query->outdated();
        }
        return $query;
    }
}
