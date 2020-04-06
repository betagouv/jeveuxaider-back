<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersMissionSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property) : Builder
    {
        return $query->where(function ($query) use ($value, $property) {
            $query->where('name', 'LIKE', '%' . $value . '%')
                    ->orWhereHas('structure', function (Builder $query) use ($value) {
                        $query->where('name', 'LIKE', '%' . $value . '%');
                    });
        })
            ;
    }
}
