<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersMissionIsTemplate implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value, $property) {
            if ($value) {
                $query->whereNotNull('template_id');
            } else {
                $query->whereNull('template_id');
            }
        });
    }
}
