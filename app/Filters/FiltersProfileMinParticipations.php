<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersProfileMinParticipations implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value, $property) {
            $query->has('participations', '>=', $value);
            $query->whereHas('participations', function (Builder $query) {
                $query->where('state', 'Validée');
            }, '>=', $value);
        });
    }
}
