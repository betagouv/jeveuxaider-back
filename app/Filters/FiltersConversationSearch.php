<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersConversationSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property) : Builder
    {
        return $query->whereHas('users', function (Builder $subquery) use ($value) {
            $subquery->whereHas('profile', function ($q) use ($value) {
                $q->whereRaw("concat(first_name, ' ', last_name) ilike '%$value%' ");
            });
        });
    }
}
