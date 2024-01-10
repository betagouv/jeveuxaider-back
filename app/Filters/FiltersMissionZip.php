<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersMissionZip implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {
            return $query->where('zip', $value)
                ->orWhereJsonContains('autonomy_zips', [['zip' => $value]]);
        });
    }
}
