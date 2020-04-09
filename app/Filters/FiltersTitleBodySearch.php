<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersTitleBodySearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value, $property) {
            $query
                ->where('title', 'ILIKE', '%' . $value . '%')
                ->orWhere('description', 'ILIKE', '%' . $value . '%');
        })
        ;
    }
}
