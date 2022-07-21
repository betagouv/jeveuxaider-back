<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersTemplatesWithReseau implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {
            $query->whereNull('reseau_id');
            if ($value != 'empty') {
                if (is_array($value)) {
                    $query->orWhereIn('reseau_id', $value);
                } else {
                    $query->orWhere('reseau_id', $value);
                }
            }
        });
    }
}
