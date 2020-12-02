<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersMissionSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property) : Builder
    {
        return $query->where(function ($query) use ($value, $property) {
            $query
                ->where('name', 'ILIKE', '%' . $value . '%')
                ->orWhere('id', $value)
                ->orWhereHas('structure', function (Builder $query) use ($value) {
                    $query->where('name', 'ILIKE', '%' . $value . '%');
                });
        })
            ;
    }
}
