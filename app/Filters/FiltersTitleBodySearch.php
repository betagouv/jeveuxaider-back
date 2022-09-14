<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersTitleBodySearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $query
                ->where('title', 'ILIKE', '%'.$value.'%')
                ->orWhere('description', 'ILIKE', '%'.$value.'%');
        });
    }
}
