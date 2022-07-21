<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersMissionIsTemplate implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {
            if ($value) {
                $query->whereNotNull('template_id');
            } else {
                $query->whereNull('template_id');
            }
        });
    }
}
