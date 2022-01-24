<?php

namespace App\Filters;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersProfileRole implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        switch ($value) {
            case 'admin':
                return $query->whereHas('user', function (Builder $query) use ($value) {
                    $query->where('is_admin', true);
                });
                break;
            case 'analyste':
                return $query->where('is_analyste', true);
                break;
            case 'referent':
                return $query->whereNotNull('referent_department');
                break;
            case 'referent_regional':
                return $query->whereNotNull('referent_region');
                break;
            case 'tete_de_reseau':
                return $query->whereNotNull('tete_de_reseau_id');
                break;
            case 'responsable':
                return $query->whereHas('structures');
                break;
            case 'responsable_territoire':
                return $query->whereHas('territoires');
                break;
            default:
                return $query;
                break;
        }
    }
}
