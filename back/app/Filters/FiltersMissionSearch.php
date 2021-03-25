<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersMissionSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value, $property) {
            if (is_numeric($value)) {
                $query
                    ->where('id', $value);
            } else {
                if (is_array($value)) {
                    $value = implode(',', $value);
                }
                $query
                ->where('name', 'ILIKE', '%' . $value . '%')
                ->orWhereHas('structure', function (Builder $query) use ($value) {
                    $query->where('name', 'ILIKE', '%' . $value . '%');
                });
            }
        });
    }
}
