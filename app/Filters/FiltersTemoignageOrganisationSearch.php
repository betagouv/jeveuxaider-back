<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FiltersTemoignageOrganisationSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {
            $query->whereHas('participation.mission.structure', function (Builder $query) use ($value) {
                $query->whereRaw("CONCAT(structures.name, ' ', structures.city, ' ', structures.rna) ILIKE ?", ['%'.$value.'%']);
            });
        });
    }
}
