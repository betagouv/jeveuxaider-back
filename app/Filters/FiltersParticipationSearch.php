<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersParticipationSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query
            ->whereHas('mission', function (Builder $query) use ($value) {
                $query
                    ->where('name', 'LIKE', '%' . $value . '%')
                    ->orWhereHas('structure', function (Builder $query) use ($value) {
                        $query->where('name', 'LIKE', '%' . $value . '%');
                    });
            })
            ->orWhereHas('profile', function (Builder $query) use ($value) {
                $query
                    ->where('first_name', 'LIKE', '%' . $value . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $value . '%')
                    ->orWhere('email', 'LIKE', '%' . $value . '%');
            });
        ;
    }
}
