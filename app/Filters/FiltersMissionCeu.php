<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersMissionCeu implements Filter
{
    public function __invoke(Builder $query, $value, string $property) : Builder
    {
        return $query->where(function ($query) use ($value, $property) {
            $query->whereHas('structure', function (Builder $query) use ($value) {
                $query->ceu($value);
            });
        });
    }
}
