<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersProfileSkill implements Filter
{
    public function __invoke(Builder $query, $value, string $property) : Builder
    {
        return $query->whereHas('tags', function (Builder $query) use ($value) {
            $query->whereJsonContains('name->fr', $value);
        });
    }
}
