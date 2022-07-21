<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersTagName implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        if (is_array($value)) {
            $value = implode(',', $value);
        }

        return $query->where(function ($query) use ($value) {
            $query->where('name->fr', 'LIKE', '%'.$value.'%')
                ->orWhere('group', 'LIKE', '%'.$value.'%');
        });
    }
}
