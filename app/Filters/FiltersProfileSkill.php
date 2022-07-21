<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersProfileSkill implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->whereHas('tags', function (Builder $query) use ($value) {
            $query->whereJsonContains('name->fr', $value);
        });
    }
}
