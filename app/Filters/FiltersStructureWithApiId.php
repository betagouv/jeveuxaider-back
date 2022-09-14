<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersStructureWithApiId implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {
            if ($value == 'empty') {
                $query->whereNull('api_id')->orWhere('api_id', '');
            } elseif ($value == 'na') {
                $query->where('api_id', 'N/A');
            } elseif ($value == 'not_found_api_engagement') {
                $query->where('api_id', 'NOT_FOUND_API_ENGAGEMENT');
            } elseif ($value == 'filled') {
                $query->whereNotNull('api_id')->where('api_id', '!=', '');
            }
        });
    }
}
