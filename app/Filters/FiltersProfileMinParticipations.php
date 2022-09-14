<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersProfileMinParticipations implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {
            $query->has('participations', '>=', $value);
            $query->whereHas('participations', function (Builder $query) {
                $query->where('state', 'Validée');
            }, '>=', $value);
        });
    }
}
