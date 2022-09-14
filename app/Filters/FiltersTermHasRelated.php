<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersTermHasRelated implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {
            if ($value == 'yes') {
                $query->whereHas('related');
            }
            if ($value == 'no') {
                $query->whereDoesntHave('related');
            }
        });
    }
}
