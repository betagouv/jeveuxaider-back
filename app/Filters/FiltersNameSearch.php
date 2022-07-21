<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersNameSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {
            $terms = explode(' ', $value);
            foreach ($terms as $term) {
                $query->whereRaw('CONCAT(name) ILIKE ?', ['%'.$term.'%']);
            }
        });
    }
}
