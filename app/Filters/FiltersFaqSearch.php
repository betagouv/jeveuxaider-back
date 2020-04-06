<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersFaqSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value, $property) {
            $query
                ->where('title', 'LIKE', '%' . $value . '%')
                ->orWhere('description', 'LIKE', '%' . $value . '%');
        })
        ;
    }
}
