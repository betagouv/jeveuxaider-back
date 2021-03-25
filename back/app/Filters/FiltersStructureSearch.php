<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersStructureSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value, $property) {
            if (is_numeric($value)) {
                $query
                ->where('name', 'ILIKE', '%' . $value . '%')
                ->orWhere('id', $value);
            } else {
                if (is_array($value)) {
                    $value = implode(',', $value);
                }
                $query
                ->where('name', 'ILIKE', '%' . $value . '%');
            }
        });
    }
}
