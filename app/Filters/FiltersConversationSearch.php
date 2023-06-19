<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersConversationSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {

        return $query->whereHas('users', function (Builder $subquery) use ($value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $subquery->whereHas('profile', function ($q) use ($value) {
                $q->whereRaw("concat(first_name, ' ', last_name, ' ', email) ilike '%$value%' ");
            });
        });
    }
}
