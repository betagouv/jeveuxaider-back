<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersProfileSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value, $property) {
            $query->where('first_name', 'ILIKE', '%' . $value . '%')
                ->orWhere('last_name', 'ILIKE', '%' . $value . '%')
                ->orWhere('email', 'ILIKE', '%' . $value . '%');
        })
        ;
    }
}
