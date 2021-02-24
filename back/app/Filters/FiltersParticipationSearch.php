<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersParticipationSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query
            ->where(function ($query) use ($value, $property) {
                $query->whereHas('mission', function (Builder $query) use ($value) {
                    $query
                        ->where('name', 'ILIKE', '%' . $value . '%')
                        ->orWhereHas('structure', function (Builder $query) use ($value) {
                            $query->where('name', 'ILIKE', '%' . $value . '%');
                        });
                })
                ->orWhereHas('profile', function (Builder $query) use ($value) {
                    $query
                        ->where('first_name', 'ILIKE', '%' . $value . '%')
                        ->orWhere('last_name', 'ILIKE', '%' . $value . '%')
                        ->orWhere('email', 'ILIKE', '%' . $value . '%');
                });
            })
        ;
    }
}
