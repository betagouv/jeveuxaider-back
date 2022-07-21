<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersMissionSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {
            if (is_numeric($value)) {
                $query
                    ->where('id', $value);
            } else {
                if (is_array($value)) {
                    $value = implode(',', $value);
                }
                $query->where('name', 'ILIKE', '%'.$value.'%')
                    ->orWhereHas('template', function (Builder $query) use ($value) {
                        $query->where('title', 'ILIKE', '%'.$value.'%');
                    })
                    ->orWhereHas('structure', function (Builder $query) use ($value) {
                        $query->where('name', 'ILIKE', '%'.$value.'%');
                    });
            }
        });
    }
}
