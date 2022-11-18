<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersDoesntHaveTags implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->whereDoesntHave('tags', function (Builder $query) use ($value) {
            $query->where('id', $value);
        });
    }
}
