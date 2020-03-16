<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersYoungContext implements Filter
{
    public function __invoke(Builder $query, $value, string $property) : Builder
    {
        switch ($value) {
            case 'Email incorrect':
                $query->hasNotValidEmail();
                break;
            case 'Non géolocalisé':
                $query->hasNotValidGeolocalisation();
                break;
        }
        return $query;
    }
}
