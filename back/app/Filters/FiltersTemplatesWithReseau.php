<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersTemplatesWithReseau implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value, $property) {
            if ($value == 'empty') {
                $query->whereNull('reseau_id');
            } else {
                if (is_array($value)) {
                    $query->orWhereIn('reseau_id', $value);
                } else {
                    $query->orWhere('reseau_id', $value);
                }
            }
        });
    }
}
