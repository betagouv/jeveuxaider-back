<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersMissionDomaine implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value, $property) {
            $query
                ->where('domaine_id', $value)
                ->orWhereHas('template', function (Builder $query) use ($value) {
                    $query->where('domaine_id', $value);
                });
        });
    }
}
