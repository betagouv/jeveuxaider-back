<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersTrashSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property) : Builder
    {
        return $query
            ->where(function ($query) use ($value, $property) {
                if ($property == 'Volontaires') {
                    $query
                    ->where('first_name', 'LIKE', '%' . $value . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $value . '%');
                } else {
                    $query->where('name', 'LIKE', '%' . $value . '%');
                }
            })
        ;
    }
}
