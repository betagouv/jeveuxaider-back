<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersStructureIsCollectivity implements Filter
{
    public function __invoke(Builder $query, $value, string $property) : Builder
    {
        return $value ? $query->where('statut_juridique', 'Collectivité') : $query->where('statut_juridique', '!=', 'Collectivité');
    }
}
