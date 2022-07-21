<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersConversationExclude implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where('id', '<>', $value);
    }
}
